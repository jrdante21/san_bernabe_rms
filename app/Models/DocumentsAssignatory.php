<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsAssignatory extends Model
{
    use HasFactory;
    public $table = 'documents_assignatory';
    protected $guarded = [];

    public function admin_officials () {
        return $this->belongsTo(AdminOfficials::class);
    }
}
