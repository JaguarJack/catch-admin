<?php

namespace Modules\User\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\User\Models\Traits\UserRelations;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;

/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $avatar
 * @property string $password
 * @property int $creator_id
 * @property int $status
 * @property string $login_ip
 * @property int $login_at
 * @property int $created_at
 * @property int $updated_at
 * @property string $remember_token
 */
class Users extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, UserRelations;

    protected $fillable = [
        'id', 'username', 'email', 'avatar', 'password', 'remember_token', 'creator_id', 'status', 'login_ip', 'login_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * @var array|string[]
     */
    public array $searchable = [
        'username' => 'like',
        'email' => 'like',
        'status' => '='
    ];

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array|string[]
     */
    protected array $form = ['username', 'email', 'password'];

    /**
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * password
     *
     * @return Attribute
     */
    protected function password(): Attribute
    {
        return new Attribute(
            // get: fn($value) => '',
            set: fn ($value) => bcrypt($value),
        );
    }

    /**
     * is super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->{$this->primaryKey} == config('catch.super_admin');
    }

    /**
     * update
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateBy($id, array $data): mixed
    {
        if (isset($data['password']) && ! $data['password']) {
            unset($data['password']);
        }

        return parent::updateBy($id, $data);
    }
}
