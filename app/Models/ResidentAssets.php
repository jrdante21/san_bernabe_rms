<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentAssets extends Model
{
    use HasFactory;
    public $table = 'resident_assets';
    protected $guarded = [];
    protected $appends = ['description'];

    public function getDescriptionAttribute () {
        switch ($this->type) {
            default:
            case 'vehicle':
                return [
                    ['name'=>'Brand', 'value'=>$this->v_brand],
                    ['name'=>'Model', 'value'=>$this->v_model],
                    ['name'=>'Plate No.', 'value'=>$this->v_plate],
                    ['name'=>'Color', 'value'=>$this->v_color],
                ];
                break;

            case 'animal':
                return [
                    ['name'=>'Type', 'value'=>$this->a_type],
                    ['name'=>'Sex', 'value'=>$this->a_sex],
                    ['name'=>'Color', 'value'=>$this->a_color],
                    ['name'=>'Age', 'value'=>$this->a_age],
                    ['name'=>'Certification', 'value'=>$this->a_cert],
                ];
                break;
            
            case 'land':
                return [
                    ['name'=>'Type', 'value'=>$this->l_type],
                    ['name'=>'Hectar', 'value'=>$this->l_hectar],
                    ['name'=>'Location', 'value'=>$this->l_loc],
                ];
                break;
        }
    }
}
