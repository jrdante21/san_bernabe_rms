<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsBusiness extends Model
{
    use HasFactory;
    public $table = 'documents_business';
    protected $guarded = [];
}
