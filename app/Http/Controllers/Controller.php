<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $transactions = [
        ['id'=>1, 'name'=>'comm_tax', 'title'=>'Community Tax'],
        ['id'=>2, 'name'=>'brgy_certificate', 'title'=>'Barangay Certificate'],
        ['id'=>3, 'name'=>'brgy_clearance', 'title'=>'Barangay Clearance'],
        ['id'=>4, 'name'=>'brgy_indigency', 'title'=>'Barangay Indigency'],
        ['id'=>5, 'name'=>'buss_permit', 'title'=>'Business Permit'],
        ['id'=>6, 'name'=>'buss_clearance', 'title'=>'Business Clearance'],
        ['id'=>7, 'name'=>'purok_clearance', 'title'=>'Purok Clearance'],
        ['id'=>8, 'name'=>'certification', 'title'=>'Certification'],
        ['id'=>9, 'name'=>'summons', 'title'=>'Summons'],
        ['id'=>10, 'name'=>'borrowed_materials', 'title'=>'Borrowed Materials'],
    ];

    protected function user ()
    {
        $user1 = auth()->guard('admin')->check();
        $user2 = auth()->guard('official')->check();
        if ($user1 || $user2) {
            return ($user1) ? auth()->guard('admin')->user() : auth()->guard('official')->user();
        } else {
            return null;
        }
    }

}
