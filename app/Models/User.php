<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    /**
     * Summary of scopeAuthenticate
     * @param mixed $query
     * @param array $fields has the keys: username, password, account_type
     * @return boolean
     */
    public function scopeAuthenticate($query, $fields = [])
    {
        if(!Arr::has($fields, ['username', 'password', 'account_type'])) {
            throw Exception('Missing Fields');
        }

        $result = $query->where('username', $fields['username'])->where('password', bcrypt($fields['password']))->where('account_type', $fields['account_type']);
        if(!$result) {
            return false;
        }

        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'user_id',
        'account_type',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id', 'id');
    }

    public function specialist()
    {
        return $this->belongsTo(Specialist::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
    
}