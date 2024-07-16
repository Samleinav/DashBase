<?php

namespace Botble\Dashplugin\Models;

use Botble\ACL\Traits\PermissionTrait;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Models\BaseModel;
use Botble\Base\Models\Concerns\HasSlug;
use Botble\Base\Supports\Helper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerRole extends BaseModel
{
    use HasSlug;
    use PermissionTrait;

    protected $table = 'dash_roles';

    protected $fillable = [
        'name',
        'slug',
        'permissions',
        'description',
        'is_default',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'permissions' => 'json',
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'is_default' => 'bool',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $model) {
            $model->slug = self::createSlug($model->slug ?: $model->name, $model->getKey());
        });

        self::deleted(function (self $model) {
            $model->users()->detach();

            Helper::clearCache();
        });
    }

    public function delete(): ?bool
    {
        if ($this->exists) {
            $this->users()->detach();
        }

        return parent::delete();
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(Customer::class, 'dash_customer_roles', 'role_id', 'customer_id')
            ->withTimestamps();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'created_by')->withDefault();
    }

    public function getAvailablePermissions(): array
    {
        $permissions = [];
       
                $configuration = config('plugins.dashplugin.front-permissions');
                if (! empty($configuration)) {
                    foreach ($configuration as $config) {
                        $permissions[$config['flag']] = $config;
                    }
                }

        return $permissions;
    }
}
