<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsAssets extends Model
{
    use HasFactory;
    public $table = 'documents_assets';
    protected $guarded = [];
    protected $appends = ['title'];

    public function getAssetsAttribute ($val) {
        return json_decode($val, true);
    }

    public function getTitleAttribute () {
        $assets = $this->assets;
        switch ($assets['type']) {
            default:
            case 'vehicle':
                $title = $assets['v_brand'].' '.$assets['v_model'];
                break;

            case 'animal':
                $title = $assets['a_type'];
                break;

            case 'land':
                $title = $assets['l_type'];
                break;
        }

        return $title;
    }
}
