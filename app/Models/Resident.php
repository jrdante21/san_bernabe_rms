<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;
    public $table = 'resident';
    protected $guarded = [];
    protected $appends = ['name', 'address', 'age'];
    protected $with = ['education', 'children', 'business', 'work', 'assets'];

    public function getNameAttribute() {
        return "{$this->fname} {$this->mname} {$this->lname}";
    }

    public function getAddressAttribute() {
        $home_num = (!empty($this->home_num)) ? $this->home_num.', ' : '';
        return "{$home_num}Purok {$this->purok}, {$this->brgy}, {$this->muni}, {$this->prov}";
    }

    public function getAgeAttribute () {
        $diff = date_diff(date_create("now"), date_create($this->bday));
        return $diff->y;
    }

    public function education () {
        return $this->hasMany(ResidentEducation::class);
    }

    public function children () {
        return $this->hasMany(ResidentChildren::class);
    }

    public function business () {
        return $this->hasMany(ResidentBusiness::class);
    }

    public function work () {
        return $this->hasMany(ResidentWork::class);
    }

    public function assets () {
        return $this->hasMany(ResidentAssets::class);
    }
}
