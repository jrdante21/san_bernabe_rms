<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Borrowed;
use App\Models\Documents;

class HomeController extends Controller
{    
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function home (Request $req) 
    {
        $data['transactions'] = $this->transactions;
        $data['admin'] = $this->user();
        return view('admin', $data);
    }

    public function print_resident (Request $req)
    {
        $id = $req->input('id');
        if (empty($id)) return response('No resident ID found.', 404);

        $query = Resident::find($id);
        if (empty($query)) return response('No resident found.', 404);

        return view('printResident', $query);
    }

    public function print_residents (Request $req)
    {
        $data = $req->all();
        $query = Resident::orderByDesc('created_at')->get();
        $query = (!empty($data['per_purok'])) ? collect($query)->groupBy('purok') : [0 => $query];
    
        return view('printResidents', [
            'residents' => $query,
            'with_assets' => (!empty($data['with_assets'])) ? $data['with_assets'] : 0,
        ]);
    }

    public function print_report (Request $req)
    {
        $type = $req->input('type');

        $where = [];
        $where[] = ['issued_at', '<=', date('Y-m-d')];

        $resident = $req->input('resident');
        if (!empty($resident)) $resident = Resident::find($resident);
        if (!empty($resident)) $where[] = ['resident_id', '=', $resident['id']];

        if (empty($type)) {
            $query1 = Documents::where($where);
            $query2 = Borrowed::where($where);
            $query = collect($query1->get())->merge($query2->get());

        } else {
            $query = ($type <= 9 ) ? Documents::where('type', $type)->where($where) : Borrowed::where($where);
            $query = $query->get();
        }

        $query = collect($query)->groupBy('type');

        return view('printReport', [
            'documents' => $query,
            'transactions' => $this->transactions,
            'resident' => $resident,
        ]);
    }

    public function print_document (Request $req)
    {
        $id = $req->input('id');
        $query = Documents::find($id);
        if (empty($query)) return response('Document not found!', 404);

        return view('printDocument', $query);
    }

}
