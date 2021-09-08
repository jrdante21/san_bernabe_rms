<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminOfficials as Officials;
use App\Models\DocumentsAssignatory;

class ApiOfficialsController extends Controller
{
    public function officials (Request $req) 
    {
        $data = $req->all();
        $query = Officials::orderByDesc('updated_at');
        if (!empty($data['search'])) $query->whereRaw("CONCAT(fname, ' ', mname, ' ', lname) LIKE ?", ['%'.$data['search'].'%']);

        return $query->paginate(10);
    }

    public function modify_official (Request $req) 
    {   
        $id = $req->input('id');
        $data = $req->input('data');

        if (!empty($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        if (!empty($data['username'])) {
            $where[] = ['username', '=', $data['username']];
            if (!empty($id)) $where[] = ['id', '!=', $id];

            $query = Officials::where($where)->get();
            if (count($query) >= 1) {
                return response('Username already taken.', 406);
            }

        }

        if (empty($id)) {
            $id = Officials::create($data)->id;
        } else {
            $query = Officials::find($id)->update($data);
        }

    }

    public function assignatory (Request $req)
    {
        $query = Officials::with('assignatory')->orderByDesc('updated_at')->get();
        return $query;
    }

    public function set_assignatory (Request $req)
    {
        $type = $req->input('type');
        $ids = $req->input('ids');

        $data = [];
        foreach ($ids as $i) {
            $data[] = [
                'admin_officials_id' => $i,
                'type' => $type,
            ];
        }

        DocumentsAssignatory::where('type', $type)->delete();
        if (!empty($data)) DocumentsAssignatory::insert($data);
    }

}
