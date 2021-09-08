<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    public $table = 'documents';
    protected $guarded = [];
    protected $appends = ['amount_format'];
    protected $with = ['resident', 'business', 'assets', 'summons', 'assignatory.admin_officials'];

    public function getAmountFormatAttribute () {
        return number_format($this->amount);
    }

    public function assignatory () {
        return $this->hasMany(DocumentsAssignatory::class, 'type', 'type');
    }

    public function resident () {
        return $this->belongsTo(Resident::class);
    }

    public function business () {
        return $this->hasOne(DocumentsBusiness::class);
    }

    public function assets () {
        return $this->hasOne(DocumentsAssets::class);
    }

    public function summons () {
        return $this->hasOne(DocumentsSummons::class);
    }
}
