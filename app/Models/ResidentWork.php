<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentWork extends Model
{
    use HasFactory;
    public $table = 'resident_work';
    protected $guarded = [];
}
