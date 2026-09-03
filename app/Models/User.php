<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;


    protected $guard_name = 'api';


    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'image',
        'fcm_token',
        'failed_login_attempts',
        'locked_at',
        'is_locked',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];


    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'published_at' => 'datetime',

            'password' => 'hashed',

            'verified_at' => 'datetime',

            'locked_at' => 'datetime',

            'is_locked' => 'boolean',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Profile Image URL
    |--------------------------------------------------------------------------
    */

    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }


        if (
            is_string($value) &&
            (
                str_starts_with($value, 'http://') ||
                str_starts_with($value, 'https://') ||
                str_starts_with($value, '/storage/')
            )
        ) {
            return $value;
        }


        return Storage::disk('public')
            ->url($value);
    }


    /*
    |--------------------------------------------------------------------------
    | Complaints
    |--------------------------------------------------------------------------
    */

    public function complaints()
    {
        return $this->hasMany(
            Complaint::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Complaint Audit Logs
    |--------------------------------------------------------------------------
    */

    public function complaintAuditLogs()
    {
        return $this->morphMany(
            ComplaintAuditLog::class,
            'auditable'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    */

    public function attachments()
    {
        return $this->hasMany(
            Attachment::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    public function notifications()
    {
        return $this->hasMany(
            Notification::class
        );
    }
}
