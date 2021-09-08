<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminOfficials as Officials;

class ApiAdminController extends Controller
{
    public function loggedin_admin ()
    {
        return $this->user();
    }

    public function admins ()
    {
        $query = Admin::orderByDesc('updated_at')->where('level', '>=', 2)->get();
        return $query;
    }

    public function modify_admin (Request $req)
    {
        $id = $req->input('id');
        $data = $req->input('data');

        if (!empty($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        if (!empty($data['username'])) {
            $where[] = ['username', '=', $data['username']];
            if (!empty($id)) $where[] = ['id', '!=', $id];

            $query = Admin::where($where)->count();
            if ($query >= 1) return response('Username already taken.', 406);
        }

        if (empty($id)) {
            $count = Admin::count();
            if ($count >= 10) return response('Maximum of 10 admins only.', 406);

            Admin::create($data);
        } else {
            Admin::find($id)->update($data);
        }
        
    }

    public function edit_account (Request $req) {
        $data = $req->all();
        $user = $this->user();
        $model = ($user->level <= 2) ? new Admin : new Officials;

        if (!empty($data['username'])) {
            $query = $model::where([
                ['id', '!=', $user->id],
                ['username', '=', $data['username']]
            ])->count();
            if ($query >= 1) return response('Username already taken.', 406);
        }

        if (!empty($data['password'])) {
            $query = $model::selectRaw('password as pword')->find($user->id);
            if (!password_verify($data['old_password'], $query['pword'])) return response('Old password is incorrect.', 406);

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['old_password']);
        }

        $model::find($user->id)->update($data);
    }
}
