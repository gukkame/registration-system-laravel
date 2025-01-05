<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'subscription_type',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['days_till_end_of_sub'];

    /**
     * The attributes that should be cast to native types.
     *  Naming Convention: The method name follows a specific naming convention: get + AttributeName + Attribute. This tells Laravel that this method is an accessor for a custom attribute.
     * @var array<string, string>
     */
    public function getDaysTillEndOfSubAttribute()
    {

        $endDate = \Carbon\Carbon::parse($this->end_date);
        $currentDate = \Carbon\Carbon::now();
        $daysTillEnd = $endDate->diffInDays($currentDate, true, true);
        return floor($daysTillEnd + 1);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
