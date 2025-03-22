<?php
 
namespace App\Http\Controllers\hr\attendance;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
class overtimeController extends Controller
{
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$overtime = DB::table('hr_overtime')->get();
		$empllist = DB::table('hr_empltable')->where('status','Active')->orderBy('emplname')->get();
		return view('hr/attendance/overtime',compact('menus','overtime','empllist'));
	}

    public function add(Request $request)
    {
        // Initiate
        $otdate = Carbon::parse($request->frm_overtimedate); 
        $timein = Carbon::parse($request->frm_timein);
        $timeout = Carbon::parse($request->frm_timeout);
        $index1 = 0;
        $index2 = 0;
        $index3 = 0;
        
        // Ambil data schedule asli
        $record = DB::table('hr_attendancerecord')->where('attdate', $otdate)->where('emplid', $request->frm_emplid)->first();

        // Hitung jika hari kerja
        if($record->normalin1){
            // Ubah format tanggal
            $normalin = Carbon::parse($record->normalin1);
            $normalout = Carbon::parse($record->normalout1);

            // Ambil perbedaan pagi
            $beforework = ($normalin->diffInMinutes($timein))/60;

            // Buat Carbon objek dengan tanggal untuk jam sore apakah beda hari
            $normalout = $otdate->copy()->setTimeFromTimeString($normalout->toTimeString());
            $timeout = $otdate->copy()->setTimeFromTimeString($timeout->toTimeString());

            // Jika beda hari maka tambah satu hari agar selisihnya diambil yang terdekat
            if ($timeout->isBefore($normalout)) {
                $timeout->addDay();
            }
            // Ambil perbedaan siang
            $afterwork = ($normalout->diffInMinutes($timeout))/60;

            // Total overtime
            $totalot = $beforework + $afterwork;
            $time = $beforework + $afterwork;
        }
        // Hitung jika hari libur
        else{
            $timein = $otdate->copy()->setTimeFromTimeString($timein->toTimeString());
            $timeout = $otdate->copy()->setTimeFromTimeString($timeout->toTimeString());

            $beforework = 0;
            $afterwork = 0;
            $totalot = ($timein->diffInMinutes($timeout))/60;

            $time = ($timein->diffInMinutes($timeout))/60;
        }

        $isHoliday = DB::table('hr_holiday')->whereDate('holidaydate', $otdate)->first();
        if($isHoliday || $otdate->isSunday()){
            if($time > 7){
                $time = $time - 7;
                $index1 = 7 * 2;
                if($time > 1){
                    $time = $time - 1;
                    $index2 = 1 * 3;
                    $index3 = $time * 4;
                }
                else{
                    $index2 = $time * 3;
                }
            }
            else{
                $index1 = $time * 2;
            }
        }
        else if($isHoliday && $otdate->isSaturday()){
            if($time > 5){
                $time = $time - 5;
                $index1 = 7 * 2;
                if($time > 1){
                    $time = $time - 1;
                    $index2 = 1 * 3;
                    $index3 = $time * 4;
                }
                else{
                    $index2 = $time * 3;
                }
            }
            else{
                $index1 = $time * 2;
            }
        }
        else{
            if($time > 1){
                $time = $time - 1;
                $index1 = 1 * 1.5;
                $index2 = $time * 2;	
            }
            else{
                $index1 = $time * 1.5;
            }
        }

        //ambil data employee
        $empllist = DB::table('hr_empltable')->where('emplid',$request->frm_emplid)->first();
        DB::table('hr_overtime')->insert([
            'overtimeid' => $this->generateCode(),
            'overtimedate' => $request->frm_overtimedate,
            'emplid' => $request->frm_emplid,
            'emplname' => $empllist->emplname,
            'timein' => $request->frm_timein,
            'timeout' => $request->frm_timeout,
            'beforework' => $beforework,
            'afterwork' => $afterwork,
            'overtimehour' => $totalot,
            'overtimeindex' => $index1 + $index2 + $index3,
            'notes' => $request->frm_notes,
        ]);
        return redirect('/hr/attendance/overtime');
        /*
        echo "<pre>";
        print_r($record->normalin1);
        */
    }

	public function delete($id)
    {
        DB::table('hr_overtime')->where('id',$id)->delete();
        return redirect('/hr/attendance/overtime');
    }

    function generateCode() {
        // Mendapatkan tahun dan bulan sekarang
        $year = date("Y");
        $month = date("m");

        // Tentukan nama file untuk menyimpan urutan
        $file = "counter_$year-$month.txt";

        // Jika file tidak ada, buat file baru dengan urutan 001
        if (!file_exists($file)) {
            file_put_contents($file, "001");
            $counter = 1;
        } else {
            // Baca counter yang ada di dalam file
            $counter = (int)file_get_contents($file);

            // Increment counter
            $counter++;
            
            // Simpan counter baru ke file
            file_put_contents($file, str_pad($counter, 3, "0", STR_PAD_LEFT));
        }

        // Format urutan menjadi tiga digit
        $counterFormatted = str_pad($counter, 3, "0", STR_PAD_LEFT);

        // Format output sesuai template
        $code = "OT.$year.$month$counterFormatted";

        return $code;
    }

}