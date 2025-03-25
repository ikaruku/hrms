<?php
 
namespace App\Http\Controllers\hr\employee;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class workerController extends Controller
{
    // method view from table
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
    	// get data
        $worker = DB::table('hr_empltable')->where('status', 'Active')->orWhere('leavedate', '>=', today())->count();
        $pastworker = DB::table('hr_empltable')->whereNot('status','Active')->count();
    	// sent to view
		return view('hr/employee/home',compact('menus','worker','pastworker'));
	}

	public function indexworker()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
    	// get data
		$empllist = DB::table('hr_empltable')->where('status', 'Active')->orWhere('leavedate', '>=', today())->get();
    	// sent to view
		return view('hr/employee/worker',compact('empllist','menus'));
	}

	public function indexpast()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
    	// get data
		$empllist = DB::table('hr_empltable')->whereNot('status','Active')->get();
    	// sent to view
		return view('hr/employee/pastworker',compact('empllist','menus'));
	}

    
    public function exportexcel()
    {
        // Ambil data dari database menggunakan DB Query
        $users = DB::table('hr_empltable')->get();

        // Membuat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->mergeCells('C1:D1');
        $sheet->setCellValue('C1', 'Email');

        // Menambahkan data dari query ke spreadsheet
        $row = 2; // Mulai dari baris kedua karena baris pertama adalah header
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->emplid);
            $sheet->setCellValue('B' . $row, $user->emplname);
            $row++;
        }

        // Mengatur format untuk kolom tertentu (misalnya angka atau tanggal)
        //$sheet->getStyle('D2:D' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

        // Menyiapkan writer dan mengirimkan file Excel ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'users_' . now()->format('YmdHis') . '.xlsx';

        // Mengirimkan file ke browser sebagai unduhan
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    public function add(Request $request)
    {
        // insert data to table
        DB::table('hr_empltable')->insert([
            'emplid' => $request->frm_emplid,
            'emplname' => $request->frm_emplname,
            'address' => $request->frm_address,
            'joindate' => $request->frm_joindate,
            'emplpicture' => 'dist/img/avatar6.png',
            'status' => 'Active',
        ]);
        // redirect to home
        return redirect('/hr/employee/worker');
    }

    public function detail($emplid)
    {
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$deptlist = DB::table('hr_depttable')->get();
		$levellist = DB::table('hr_leveltable')->get();
		$positionlist = DB::table('hr_positiontable')->get();
		$attschedulelist = DB::table('hr_attendanceschedule')->get();
        // get data
		$workeredit = DB::table('hr_empltable')->where('emplid',$emplid)->get();
		$familylist = DB::table('hr_emplfamily')->where('emplid',$emplid)->get()->sortBy('familybirthdate');
		$deptlevelpostrans = DB::table('hr_deptlevelpostrans')->where('emplid',$emplid)->get()->sortBy('startdate');
    	// sent to view
		return view('hr/employee/workeredit',compact('menus','workeredit','deptlist','levellist','positionlist','familylist','attschedulelist','deptlevelpostrans'));
	}

	public function update(Request $request)
    {
        /*
        echo '<pre>';
        print_r($request->emplid);
        echo '</pre>';
        */
        $request->validate([
            'photo' => 'image|mimes:jpeg,png|max:2048',
        ], [
            'photo.image' => 'File yang diupload harus berupa gambar.',
            'photo.mimes' => 'Hanya file dengan ekstensi jpg dan png yang diperbolehkan.',
            'photo.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        $workercheckfile = DB::table('hr_empltable')->where('emplid',$request->emplid)->first();
        //diubah kalo ada, kalo ga ada tetep
        if($request->photo){
            if($request->photo_prev){
                Storage::disk('public')->delete($workercheckfile->emplpicture);
            }
            $path = $request->file('photo')->store('photos/emplpicture', 'public');
        }
        else{
            $path = $request->photo_prev;
        }

        DB::table('hr_empltable')->where('emplid',$request->emplid)->update([
            'emplpicture' => $path,

            'ktp' => $request->frm_ktp,
            'status' => $request->frm_status,
            'emplname' => $request->frm_emplname,
            'placeofbirth' => $request->frm_placeofbirth,
            'birthdate' => $request->frm_birthdate,
            'gender' => $request->frm_gender,
            'address' => $request->frm_address,
            'email' => $request->frm_email,
            'phonenumber' => $request->frm_phonenumber,
            'joindate' => $request->frm_joindate,
            'permanentdate' => $request->frm_permanentdate,
            'leavedate' => $request->frm_leavedate,

            'taxid' => $request->frm_taxid,
            'taxtype' => $request->frm_taxtype,

            'weekdayid' => $request->frm_weekday,
            'saturdayid' => $request->frm_saturday,
        ]);
        
        return redirect()->action(
            [workerController::class, 'detail'], ['id' => $request->emplid]
        );
    }

	public function delete($emplid)
    {
        DB::table('hr_empltable')->where('emplid',$emplid)->delete();
        return redirect('/hr/employee/worker');
    }
    
    public function addfamily(Request $request)
    {
        // insert data to table
        DB::table('hr_emplfamily')->insert([
            'emplid' => $request->emplid,
            'familyname' => $request->frm_familyname,
            'familybirthdate' => $request->frm_familybirthdate,
            'familyrelation' => $request->frm_familyrelation,
        ]);
        // redirect to home
        //return redirect('/hr/worker');
        return redirect()->action(
            [workerController::class, 'detail'], ['id' => $request->emplid]
        );
    }
    
	public function deletefamily($id)
    {
        $empl = DB::table('hr_emplfamily')->where('id',$id)->first();
        DB::table('hr_emplfamily')->where('id',$id)->delete();
        return redirect()->action(
            [workerController::class, 'detail'], ['id' => $empl->emplid]
        );
    }

    public function addorganization(Request $request)
    {
        // insert data to table
        DB::table('hr_deptlevelpostrans')->insert([
            'emplid' => $request->emplid,
            'startdate' => $request->frm_startdate,
            'deptid' => $request->frm_deptid,
            'deptname' => $request->frm_deptname,
            'levelid' => $request->frm_levelid,
            'levelname' => $request->frm_levelname,
            'posid' => $request->frm_positionid,
            'posname' => $request->frm_positionname,
        ]);

        //update to worker detail
        DB::table('hr_empltable')->where('emplid',$request->emplid)->update([
            'deptid' => $request->frm_deptid,
            'deptname' => $request->frm_deptname,
            'levelid' => $request->frm_levelid,
            'levelname' => $request->frm_levelname,
            'positionid' => $request->frm_positionid,
            'positionname' => $request->frm_positionname,
        ]);

        return redirect()->action(
            [workerController::class, 'detail'], ['id' => $request->emplid]
        );
    }

    public function deleteorganization($id)
    {
        $empl = DB::table('hr_deptlevelpostrans')->where('id',$id)->first();
        DB::table('hr_deptlevelpostrans')->where('id',$id)->delete();
        return redirect()->action(
            [workerController::class, 'detail'], ['id' => $empl->emplid]
        );
    }
}