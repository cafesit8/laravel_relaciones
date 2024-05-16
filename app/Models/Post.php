<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [''];

    protected $hidden = ['user_id'];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function image(){
      return $this->hasMany(Image::class);
    }

    public function comments() {
      return $this->hasMany(Comment::class);
    }

    public function likes() {
      return $this->belongsToMany(User::class, 'likes');
    }
}
