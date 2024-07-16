<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Dashplugin\Enums\CustomerStatusEnum;
use Botble\Dashplugin\Notifications\ConfirmEmailNotification;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Dashplugin\Models\Message;
use Botble\Dashplugin\Models\Notification;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Botble\ACL\Traits\PermissionTrait;
use Botble\ACL\Contracts\HasPermissions as HasPermissionsContract;

class Customer extends BaseModel implements
    HasPermissionsContract,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasApiTokens;
    use PermissionTrait {
        PermissionTrait::hasPermission as traitHasPermission;
        PermissionTrait::hasAnyPermission as traitHasAnyPermission;
    }
    use Notifiable;


    protected $table = 'dash_customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'phone',
        'dob',
        'address',
        'zip',
        'city',
        'state',
        'country',
        'status',
        'super_user',
        'permissions',
        'manage_customers',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'first_name' => SafeContent::class,
        'last_name' => SafeContent::class,
        'email' => SafeContent::class,
        'phone' => SafeContent::class,
        'dob' => SafeContent::class,
        'address' => SafeContent::class,
        'zip' => SafeContent::class,
        'city' => SafeContent::class,
        'state' => SafeContent::class,
        'country' => SafeContent::class,
        'password' => 'hashed',
        'status' => CustomerStatusEnum::class,
        'permissions' => 'json',
        'password' => 'hashed',
    ];

    
    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(CustomerRole::class,'dash_customer_roles','customer_id', 'role_id');
    }

    public function inRole($role): bool
    {
        $roleId = null;
        if ($role instanceof CustomerRole) {
            $roleId = $role->getKey();
        }

        foreach ($this->roles as $instance) {
            if ($role instanceof CustomerRole) {
                if ($instance->getKey() === $roleId) {
                    return true;
                }
            } elseif ($instance->getKey() == $role || $instance->slug == $role) {
                return true;
            }
        }

        return false;
    }

    public function isSuperUser(): bool
    {
        return $this->super_user ;
    }

    public function hasPermission(string|array $permissions): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->traitHasPermission($permissions);
    }

    public function hasAnyPermission(string|array $permissions): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->traitHasAnyPermission($permissions);
    }


    protected function firstName(): Attribute
    {
        return Attribute::get(fn ($value) => ucfirst($value));
    }

    protected function lastName(): Attribute
    {
        return Attribute::get(fn ($value) => ucfirst($value));
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => trim($this->first_name . ' ' . $this->last_name));
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class)->withDefault();
    }


    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar) {
                    return RvMedia::getImageUrl($this->avatar, 'thumb');
                }

                try {
                    return (new Avatar())->create(Str::ucfirst($this->name))->toBase64();
                } catch (Exception) {
                    return RvMedia::getDefaultImage();
                }
            }
        );
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new ConfirmEmailNotification());
    }

    public function hasCredits(): bool
    {
        return true; 
    }



    protected function uploadFolder(): Attribute
    {
        return Attribute::make(
            get: function () {
                $folder = $this->getKey() ? 'customers/' . $this->getKey() : 'customers';

                return apply_filters('dash_account_upload_folder', $folder, $this);
            }
        )->shouldCache();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'customer_id');
    }

    public function lastUnreadMessages(): Builder
    {
        return Message::where(function ($query) {
                            $query->where('customer_id', $this->getKey());
                        })
                        ->Where('status', 'unread')
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
    }

    public function countUnreadMessages(): int
    {
        return $this->messages()
                    ->where('status', 'unread')
                    ->count();
    }

    public function notifications():Builder
    {
        $customerId = $this->getKey();

        // Obtener los IDs de los roles del cliente
        $roleIds = $this->roles()->pluck('dash_roles.id')->toArray();

        // Notificaciones donde customer_id es el cliente actual, es global, o coincide con algún rol del cliente
        $notifications = Notification::where(function ($query) use ($customerId, $roleIds) {
            $query->where('customer_id', $customerId)
                  ->orWhereNull('customer_id')
                  ->orWhereIn('roles', $roleIds);
        });

        return $notifications;
    }

    public function globalNotifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'dash_global_notifications', 'customer_id', 'notification_id')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }

    public function lastUnreadNotifications(): Builder
    {   
        $customerId = $this->getKey();

        // Obtener los IDs de los roles del cliente
        $roleIds = $this->roles()->pluck('dash_roles.id')->toArray();

        $directNotifications = Notification::where(function ($query) use ($customerId, $roleIds) {
                                $query->where('customer_id', $customerId)
                                    ->orWhereNull('customer_id')
                                    ->orWhereIn('roles', $roleIds);
                            })
                           ->Where('status', 'unread')
                           ->orderBy('created_at', 'desc');

        // Notificaciones globales no leídas por el cliente actual
        $globalNotifications = Notification::join('dash_global_notifications', 'dash_notifications.id', '=', 'dash_global_notifications.notification_id')
                                            ->where('dash_global_notifications.customer_id', $this->getKey())
                                            ->where('dash_global_notifications.is_read', false)
                                            ->orderBy('dash_notifications.created_at', 'desc')
                                            ->select('dash_notifications.*');

        return $directNotifications->union($globalNotifications)->limit(5);
    }


    public function countUnreadNotifications(): int
    {
        $customerId = $this->getKey();
        $roleIds = $this->roles()->pluck('dash_roles.id')->toArray();
        // Cuenta de notificaciones directas no leídas
        $directUnreadCount = Notification::where(function ($query) use ($customerId, $roleIds) {
            $query->where('customer_id', $customerId)
                  ->orWhereNull('customer_id')
                  ->orWhereIn('roles', $roleIds);
        })
        ->where('status', 'unread')
        ->count();

        // Cuenta de notificaciones globales no leídas por el cliente actual
        $globalUnreadCount = $this->globalNotifications()
                                  ->wherePivot('is_read', false)
                                  ->count();

        // Sumar ambas cuentas
        return $directUnreadCount + $globalUnreadCount;
    }

    protected static function booted(): void
    {
        static::deleting(function (Customer $account) {

            $account->roles()->detach();
            $folder = Storage::path($account->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($account->upload_folder, '/' . $account->getKey())) {
                File::deleteDirectory($folder);
            }
        });
    }
}
