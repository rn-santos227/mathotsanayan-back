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
        $typeNames = [
            1 => 'Administrator',
            2 => 'Teacher',
            3 => 'Student',
        ];
        return $typeNames[$this->type] ?? $typeNames['default'];
    }

    public function getOwnerAttribute() {
        $relationshipClasses = [
            Admin::class,
            Teacher::class,
            Student::class,
        ];
        $class = $relationshipClasses[$this->type - 1] ?? null;
        return $class ? $class::where('user_id', $this->id)->first() : null;
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    public static function findUserByName($name) {
        $userIds = [];
        
        $admins = Admin::where('name', $name)->orderBy('created_at', 'desc')->pluck('user_id')->toArray();
        $userIds = array_merge($userIds, $admins);
    
        $teachers = Teacher::where(function($query) use($name) {
            $search = '%' . $name . '%';  
            $query->where(function ($query) use ($search) {
                $query->where('last_name', 'LIKE', $search)
                    ->orWhere('first_name', 'LIKE', $search)
                    ->orWhere('suffix', 'LIKE', $search)
                    ->orWhereRaw("UPPER(SUBSTRING(middle_name, 1, 1)) LIKE ?", [strtoupper(substr($search, 1, 1))]);
            });
        })->orderBy('created_at', 'desc')->pluck('user_id')->toArray();
        $userIds = array_merge($userIds, $teachers);
    
        $students = Student::where(function($query) use($name) {
            $search = '%' . $name . '%';  
            $query->where(function ($query) use ($search) {
                $query->where('last_name', 'LIKE', $search)
                    ->orWhere('first_name', 'LIKE', $search)
                    ->orWhere('suffix', 'LIKE', $search)
                    ->orWhereRaw("UPPER(SUBSTRING(middle_name, 1, 1)) LIKE ?", [strtoupper(substr($search, 1, 1))]);
            });
        })->orderBy('created_at', 'desc')->pluck('user_id')->toArray();
        $userIds = array_merge($userIds, $students);
    
        return array_unique($userIds);
    }
    

    public function validatePassword($password) {
        if (Hash::check($password, $this->password)) {
            return true;
        }
        else return false;
    }

    public static function validate($request) {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
    }

    public function currentAccessToken() {
        return $this->tokens()->latest()->first();
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
