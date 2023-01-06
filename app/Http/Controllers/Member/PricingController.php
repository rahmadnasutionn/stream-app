<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PricingController extends Controller
{
    
    public function index()
    {
        $packageStandard = Package::where('name', 'standard')->first();
        $goldPackage = Package::where('name', 'gold')->first();

        return view('member.pricing', [
            'standard' => $packageStandard,
            'gold' => $goldPackage,
        ]);
    }
}
