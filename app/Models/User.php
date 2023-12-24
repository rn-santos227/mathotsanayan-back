<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;

use App\Traits\Auditable;

class User extends Authenticatable
{
    use Auditable, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'email',
        'password',
    ];

    protected $appends = [
        'type_name',
        'owner',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getTypeNameAttribute() {
        switch($this->type) {
            case 1:
                return 'Administrator';
            case 2:
                return 'Teacher';
            default:
                return 'Student';
        }
    }


    public function getOwnerAttribute() {
        switch($this->type) {
            case 1:
                $admin = Admin::where([
                    "user_id" => $this->id,
                ])->first();
                return $admin;
            case 2:
                $teacher = Teacher::where([
                    "user_id" => $this->id,
                ])->first();
                return $teacher;
            default:
                $student = Student::where([
                    "user_id" => $this->id,
                ])->first();
                return $student;
        }
    }

    public function validatePassword($password) {
        if (Hash::check($password, $this->password)) {
            return true;
        }
        else return false;
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    public function currentAccessToken() {
        return $this->tokens()->latest()->first();
    }

    public static function validate($request) {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
    }

    public function getToken($user, $account, $type) {
        $token = $user->createToken(env('APP_SALT'))->plainTextToken;
        $this->logLoginAudit($user);
        return response([
            $type => $account,
            'token' => $token
        ], 201);
    }
}
