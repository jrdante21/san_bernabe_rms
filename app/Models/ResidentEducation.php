<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentEducation extends Model
{
    use HasFactory;
    public $table = 'resident_education';
    protected $guarded = [];
    protected $appends = ['level_word'];

    public function getLevelWordAttribute () {
        $levels = [
            1 => 'Primary',
            2 => 'Secondary',
            3 => 'Tertiary',
            4 => 'Vocational',
        ];

        return $levels[$this->level];
    }
}
