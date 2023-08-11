<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $full_name
 * @property string $cpf_cnpj
 * @property string $email
 * @property string $type
 * @property string $password
 * @property int $created_at
 * @property int $updated_at
 */
class User extends Model
{
    protected $table = 'user';
    protected $fillable = ['full_name', 'cpf_cnpj', 'email', 'password', 'type'];
    protected $hidden = ['password', 'created_at', 'updated_at'];

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => md5($value),
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value === 'F' ? 'Fisíca' : 'Jurídica',
            set: fn (string $value) => $value === 'fisica' ? 'F' : 'J'
        );
    }
}
