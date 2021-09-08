<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowed extends Model
{
    use HasFactory;
    public $table = 'borrowed';
    protected $guarded = [];
    protected $appends = ['amount_format', 'remarks', 'type'];
    protected $with = ['resident'];

    public function getAmountFormatAttribute () {
        return number_format($this->amount);
    }

    public function getTypeAttribute () {
        return 10;
    }

    public function getRemarksAttribute () {
        $stat = 3;
        $text = "Not yet returned";

        switch ($this->status) {
            default:
            case 'good':
                if (!empty($this->returned_at)) {
                    $stat = 1; $text = "Returned";
                } else {
                    $stat = 2; $text = "Not yet returned";
                }
                break;

            case 'damaged':
                if (!empty($this->paid_at) && !empty($this->returned_at)) {
                    $stat = 1; $text = "Paid & Returned";
                } else {
                    $stat = 3; $text = "Unpaid / Unreturned";
                }

                break;

            case 'lost':
                if (!empty($this->paid_at)) {
                    $stat = 1; $text = "Paid";
                } else {
                    $stat = 3; $text = "Unpaid";
                }
                
                break;
        }

        return [
            'stat' => $stat,
            'text' => $text
        ];
    }

    public function resident () {
        return $this->belongsTo(Resident::class);
    }

}
