<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    const SALT = "g";

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'key',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function setKeyAttribute($key)
    {
        $this->attributes['key'] = hash("sha256", $key . self::SALT);
    }

    static function getIdByKey($key)
    {
        return self::query()->where('key', $key)->select('id')->get()[0]->id;
    }
}
