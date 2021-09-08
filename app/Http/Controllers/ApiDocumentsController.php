<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminOfficials as Officials;
use App\Models\Borrowed;
use App\Models\Documents;
use App\Models\DocumentsAssignatory;
use App\Models\DocumentsBusiness;
use App\Models\DocumentsAssets;
use App\Models\DocumentsSummons;

use App\Models\Resident;

class ApiDocumentsController extends Controller
{
    public function documents (Request $req) 
    {
        $query = Documents::orderByDesc('updated_at');

        $data = $req->all();
        if (!empty($data['type'])) $query->where('type', $data['type']);
        if (!empty($data['res_id'])) $query->where('resident_id', $data['res_id']);
        if (!empty($data['search'])) {
            $resident = Resident::selectRaw('id as resID')->whereRaw("CONCAT(fname, ' ', mname, ' ', lname) LIKE ?", ['%'.$data['search'].'%']);
            $query->joinSub($resident, 'resident', function($join){
                $join->on('documents.resident_id', '=', 'resident.resID');
            });
        }

        return $query->paginate(10);
    }

    public function modify_docs (Request $req)
    {
        $id = $req->input('id');
        $data = $req->input('data');

        if (empty($id)) {
            $id = Documents::create($data)->id;
        } else {
            Documents::find($id)->update($data);
        }

        $business = $req->input('business');
        if (!empty($business)) {
            DocumentsBusiness::where('documents_id', $id)->delete();

            $business['documents_id'] = $id;
            DocumentsBusiness::insert($business);
            Documents::find($id)->touch();
        }
        
        $assets = $req->input('assets');
        if (!empty($assets)) {
            DocumentsAssets::where('documents_id', $id)->delete();

            $assets['documents_id'] = $id;
            $assets['assets'] = json_encode($assets['assets']);
            DocumentsAssets::insert($assets);
            Documents::find($id)->touch();
        }

        $summons = $req->input('summons');
        if (!empty($summons)) {
            DocumentsSummons::where('documents_id', $id)->delete();

            $summons['documents_id'] = $id;
            DocumentsSummons::insert($summons);
            Documents::find($id)->touch();
        }
    }

    public function borrowed (Request $req) 
    {
        $query = Borrowed::orderByDesc('created_at');

        $data = $req->all();
        if (!empty($data['res_id'])) $query->where('resident_id', $data['res_id']);
        if (!empty($data['search'])) {
            $resident = Resident::selectRaw('id as resID')->whereRaw("CONCAT(fname, ' ', mname, ' ', lname) LIKE ?", ['%'.$data['search'].'%']);
            $query->joinSub($resident, 'resident', function($join){
                $join->on('borrowed.resident_id', '=', 'resident.resID');
            });
        }

        return $query->orderByDesc('updated_at')->paginate(10);
    }

    public function modify_borrowed (Request $req)
    {
        $id = $req->input('id');
        $data = $req->input('data');

        if (empty($id)) {
            $id = Borrowed::create($data)->id;
        } else {
            Borrowed::find($id)->update($data);
        }
    }
}
