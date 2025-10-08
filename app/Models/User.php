<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Document;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'bio',
        'business_info',
        'is_verified',
        'is_active',
        'last_active_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'business_info' => 'array',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'last_active_at' => 'datetime',
        ];
    }

    // Role helper methods
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships
    public function providedServices()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }

    public function customerBookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function providerBookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function customerPayments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    public function providerPayments()
    {
        return $this->hasMany(Payment::class, 'provider_id');
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function availabilities()
    {
        return $this->hasMany(ProviderAvailability::class, 'provider_id');
    }

    /**
     * Get the documents for the user (vendor).
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
