<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentBusiness extends Model
{
    use HasFactory;
    public $table = 'resident_business';
    protected $guarded = [];
}
