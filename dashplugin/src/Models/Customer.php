<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Dashplugin\Enums\CustomerStatusEnum;
use Botble\Dashplugin\Notifications\ConfirmEmailNotification;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
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
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Botble\Dashplugin\Models\Message;
use Botble\Dashplugin\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Customer extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasApiTokens;
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
    ];

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

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'customer_id');
    }

    public function lastUnreadNotifications(): Builder
    {   
        return Notification::where(function ($query) {
                                $query->where('customer_id', $this->getKey())
                                    ->orWhereNull('customer_id');
                            })
                           ->Where('status', 'unread')
                           ->orderBy('created_at', 'desc')
                           ->limit(5);

    }


    public function countUnreadNotifications(): int
    {
        return $this->notifications()
                    ->where('status', 'unread')
                    ->count();
    }

    protected static function booted(): void
    {
        static::deleting(function (Customer $account) {
            $folder = Storage::path($account->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($account->upload_folder, '/' . $account->getKey())) {
                File::deleteDirectory($folder);
            }
        });
    }
}
