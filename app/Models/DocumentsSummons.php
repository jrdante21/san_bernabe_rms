<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsSummons extends Model
{
    use HasFactory;
    public $table = 'documents_summons';
    protected $guarded = [];
}
