<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminOfficials extends Authenticatable
{
    use HasFactory, Notifiable;
    public $table = 'admin_officials';
    protected $guarded = [];
    protected $appends = ['name', 'level', 'active'];
    protected $hidden = ['password', 'remember_token'];
    
    public function getNameAttribute () {
        return "{$this->title} {$this->fname} {$this->mname} {$this->lname}";
    }

    public function getLevelAttribute () {
        return 3;
    }

    public function getActiveAttribute () {
        return 1;
    }

    public function assignatory () {
        return $this->hasMany(DocumentsAssignatory::class);
    }

}
