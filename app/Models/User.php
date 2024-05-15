<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PhotoProfile;
use App\Models\Post;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'age',
        'email'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function name(): Attribute {
        return new Attribute (
          set: fn($value) => ucwords(strtolower($value))
        );
    }

    // Relación UNO a UNO
    public function photoProfile() {
      // $profile = PhotoProfile::where('user_id', $this->id)->first(); // Me devuelve la foto de perfil del usuario, esta es la manera más larga

      // return $this->hasOne(PhotoProfile::class, 'user_id', 'id'); 
      // Me devuelve la foto de perfil del usuario, esta es la manera más corta, el 'user_id' es el 'id' de la tabla 'photo_profile' y el 'id' de la tabla 'users', en caso de que se llamen de otra manera, puedes agregar ahí el nombre especíico, de lo contrario solo poner de la forma de abajo

      return $this->hasOne(PhotoProfile::class); // Me devuelve la foto de perfil del usuario, esta es la manera más corta
    }

    // Relación UNO a MUCHOS
    public function posts() {
      return $this->hasMany(Post::class);
    }

    // Relación MUCHOS a MUCHOS
    public function likes() {
      return $this->belongsToMany(Post::class, 'likes');
    }
}
