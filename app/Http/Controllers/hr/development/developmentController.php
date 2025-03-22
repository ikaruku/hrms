<?php
 
namespace App\Http\Controllers\hr\development;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class developmentController extends Controller
{
    // TRAINING LIST
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		return view('hr/development/home',compact('menus'));
	}
}