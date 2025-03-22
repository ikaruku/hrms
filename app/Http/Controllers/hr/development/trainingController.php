<?php
 
namespace App\Http\Controllers\hr\development;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class trainingController extends Controller
{
    // TRAINING LIST
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$trnlist = DB::table('hr_trainingtable')->get();
		return view('hr/development/training',compact('trnlist','menus'));
	}
    public function add(Request $request)
    {
        DB::table('hr_trainingtable')->insert([
            'trainingname' => $request->frm_trainingname,
            'trainer' => $request->frm_trainer,
        ]);
        return redirect('/hr/development/training');
    }
	public function update(Request $request)
    {
        DB::table('hr_trainingtable')->where('trainingid',$request->trainingid)->update([
            'trainingname' => $request->edit_trainingname,
            'trainer' => $request->edit_trainer,
        ]);
        return redirect('/hr/development/training');
    }
	public function delete($trainingid)
    {
        DB::table('hr_trainingtable')->where('trainingid',$trainingid)->delete();
        return redirect('/hr/development/training');
    }
    // TRAINING DETAIL
	public function indexdetail()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$scdlist = DB::table('hr_trainingschedule')->where('trainingid',request()->id)->get();
		$pcplist = DB::table('hr_traininguser')->where('trainingid',request()->id)->get();
		return view('hr/development/trainingdetail',compact('menus','scdlist','pcplist'));
	}
    public function addschedule(Request $request)
    {
        DB::table('hr_trainingschedule')->insert([
            'trainingid' => $request->frm_trainingid,
            'scheduleDate' => $request->frm_scheduledate,
            'startTime' => $request->frm_starttime,
            'endTime' => $request->frm_endtimme,
        ]); 
        return redirect()->action(
            [trainingController::class, 'indexdetail'], ['id' => $request->frm_trainingid]
        );
    }
	public function deleteschedule($id)
    {
        $trainingid = DB::table('hr_trainingschedule')->where('id',$id)->first()->trainingid;
        DB::table('hr_trainingschedule')->where('id',$id)->delete();
        return redirect()->action(
            [trainingController::class, 'indexdetail'], ['id' => $trainingid]
        );
    }
}