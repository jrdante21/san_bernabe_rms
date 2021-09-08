<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Resident;
use App\Models\ResidentEducation;
use App\Models\ResidentChildren;
use App\Models\ResidentBusiness;
use App\Models\ResidentWork;
use App\Models\ResidentAssets;

class ApiResidentController extends Controller
{
    public function residents (Request $req)
    {
        $query = Resident::orderByDesc('created_at');

        $search = $req->input('search');
        if (!empty($search)) $query->whereRaw("CONCAT(fname, ' ', mname, ' ', lname) LIKE ?", ['%'.$search.'%']);

        $query = $query->paginate(10);
        return $query;
    }

    public function resident (Request $req)
    {
        $id = $req->input('id');
        if (empty($id)) return response('No resident ID found.', 404);

        $query = Resident::find($id);
        return $query;
    }

    public function modify_resident (Request $req)
    {
        $id = $req->input('id');
        $info = $req->input('info');
        
        $img = $req->input('image'); // Base64 image only...
        if (!empty($img)) {
            $img = explode(';base64', $img);
            $img_type = explode('image/', $img[0])[1];
            $img_base64 = base64_decode($img[1]);
            $img_name = 'pfp_'.time().'.'.$img_type;
            file_put_contents('images/profile_pic/'.$img_name, $img_base64);

            $info['image'] = $img_name;
        }

        if (empty($id)) { // Add...
            $id = Resident::create($info)->id;
        } else { // Update...
            Resident::find($id)->update($info);
        }

        $otherInfo = [
            ['name'=>'education', 'main'=>'name', 'model'=>new ResidentEducation],
            ['name'=>'children', 'main'=>'name', 'model'=>new ResidentChildren],
            ['name'=>'business', 'main'=>'name', 'model'=>new ResidentBusiness],
            ['name'=>'work', 'main'=>'title', 'model'=>new ResidentWork],
            ['name'=>'assets', 'main'=>'type', 'model'=>new ResidentAssets],
        ];

        foreach ($otherInfo as $o) {
            $data = $req->input($o['name']);
            $newData = [];
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['resident_id'] = $id;
                    if (!empty($d[$o['main']])) $newData[] = $d;
                }
            }

            $model = $o['model'];
            $model::where('resident_id', $id)->delete();
            $model::insert($newData);
        }

    }
}
