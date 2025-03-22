<?php
 
namespace App\Http\Controllers\hr\attendance;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
 
class recordController extends Controller
{
    public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$empllist = DB::table('hr_empltable')->where('status','Active')->orderBy('emplname')->get();
		return view('hr/attendance/record',compact('empllist','menus'));
	}

    public function indexdetail($id)
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$attendanceList = DB::table('hr_attendancerecord')->where('emplid',$id)->get();
		return view('hr/attendance/attendancedetail',compact('attendanceList','menus'));
	}

	public function generateall(Request $request)
    {
        // Validasi input bulan dan tahun
        $request->validate([
            'frm_month' => 'required|integer|between:1,12',
            'frm_year' => 'required|integer|digits:4',
        ]);

        // Ambil input bulan dan tahun
        $month = $request->frm_month;
        $year = $request->frm_year;

        // Cek apakah sudah ada data dengan bulan dan tahun yang sama
        $existingRecord = DB::table('hr_attendancegenerated')
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        // Jika sudah ada, batalkan eksekusi dan beri pesan
        if ($existingRecord) {
            return redirect()->back()->with('error', 'Data untuk bulan dan tahun tersebut sudah di generate');
        }

        // Ambil semua karyawan yang memiliki schedule_id
        $employees = DB::table('hr_empltable')->where('status', 'Active')->get();

        // Tentukan hari pertama dan terakhir di bulan yang dipilih
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Daftar nama hari
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Generate records untuk setiap karyawan
        foreach ($employees as $employee) {
            // Loop untuk setiap hari dalam bulan tersebut
            $currentDate = $startOfMonth->copy();
            while ($currentDate <= $endOfMonth) {
                $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

                // Tentukan schedule yang sesuai
                $scheduleId = ($dayOfWeek == 6) ? $employee->saturdayid : $employee->weekdayid;
                $schedule = DB::table('hr_attendanceschedule')->where('scheduleid', $scheduleId)->first();

                // Tentukan status dan schedule untuk setiap hari
                $dayname = $dayNames[$dayOfWeek];

                // Cek apakah tanggal tersebut adalah hari libur
                $isHoliday = DB::table('hr_holiday')->whereDate('holidaydate', $currentDate)->first();
                if ($isHoliday || $dayOfWeek == 0) {
                    $attStatus = 'PH'; // Set status PH jika libur
                    $schedule = null;   // Set schedule menjadi null jika libur
                } else {
                    $attStatus = null;
                }

                // Insert ke tabel hr_attendancerecord
                DB::table('hr_attendancerecord')->insert([
                    'day' => $dayname,
                    'attdate' => $currentDate->format('Y-m-d'),
                    'emplid' => $employee->emplid,
                    'emplname' => $employee->emplname,
                    'scheduleid' => $schedule ? $schedule->scheduleid : null, // Set scheduleid atau null jika libur
                    'normalin1' => $schedule ? $schedule->normalin1 : null, // Set normalIn1 atau null jika libur
                    'normalout1' => $schedule ? $schedule->normalout1 : null, // Set normalOut1 atau null jika libur
                    'attstatus' => $attStatus,
                    'notes' => $isHoliday ? $isHoliday->holidayname : null,
                ]);

                // Pindah ke hari berikutnya
                $currentDate->addDay();
            }
        }

        // Setelah selesai, simpan entri ke tabel hr_generate
        DB::table('hr_attendancegenerated')->insert([
            'month' => $month,
            'year' => $year
        ]);

        return redirect('/hr/attendance/record');
    }

    public function import(Request $request)
    {   
        // Validasi file upload
        $request->validate([
            'frm_import' => 'required|mimes:xlsx,xls',
        ]);

        // Ambil file yang diupload langsung
        $file = $request->file('frm_import');

        // Baca file Excel menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($file->getRealPath());

        // Ambil sheet pertama
        $sheet = $spreadsheet->getActiveSheet();

        // Array untuk menyimpan data per tanggal dan employee_id
        $dataPerTanggalEmployee = [];

        // Loop untuk membaca data Excel dimulai dari baris kedua (melewatkan header)
        $rowIterator = $sheet->getRowIterator();
        $rowIterator->next(); // Melewatkan baris pertama (header)

        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Melanjutkan jika ada kolom kosong

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getFormattedValue();
            }

            // Ambil employee_id, tanggal dan time (waktu)
            $tanggal = isset($data[0]) ? $data[0] : null; // Tanggal
            $employee_id = isset($data[1]) ? $data[1] : null; // ID karyawan
            $waktu = isset($data[3]) ? $data[3] : null; // Waktu masuk atau pulang

            if ($employee_id && $waktu) {
                // Kelompokkan berdasarkan employee_id dan tanggal
                $dataPerTanggalEmployee[$employee_id][$tanggal][] = $waktu;
            }
        }

        // Proses data per employee_id dan tanggal
        foreach ($dataPerTanggalEmployee as $employee_id => $tanggalData) {
            foreach ($tanggalData as $tanggal => $times) {
                $timein = min($times); // Waktu tercepat
                $timeout = max($times); // Waktu paling lambat

                // Buka tabel dan bandingkan dengan timein timeout untuk memberikan label
                $attrecord = DB::table('hr_attendancerecord')->where('attdate', $tanggal)->where('emplid', $employee_id)->first();
                if ($attrecord) {
                    // Proses seperti sebelumnya jika data ditemukan
                    $timein = Carbon::parse($timein);
                    $timeout = Carbon::parse($timeout);
                    $normalIn1 = Carbon::parse($attrecord->normalin1);
                    $normalOut1 = Carbon::parse($attrecord->normalout1);

                    // Tentukan status berdasarkan waktu
                    if ($timein > $normalIn1) {
                        $attStatus = 'N';
                    } else if ($timeout < $normalOut1) {
                        $attStatus = 'N';
                    } else if ($timeout >= $normalOut1) {
                        $attStatus = 'N';
                    }

                    // Update data absensi yang sudah ada sesuai employee_id dan tanggal
                    DB::table('hr_attendancerecord')->where('attdate', $tanggal)->where('emplid', $employee_id)->update([
                        'actin1' => $timein,
                        'actout1' => $timeout,
                        'attstatus' => $attStatus,
                    ]);
                }
            }
        }

        // Cek tanggal-tanggal yang ada di hr_attendancerecord tetapi tidak ada di data excel
        $existingRecords = DB::table('hr_attendancerecord')->get();

        foreach ($existingRecords as $record) {
            $employee_id = $record->emplid;
            $tanggal = $record->attdate;

            // Cek jika tanggal tersebut tidak ada di dalam dataPerTanggalEmployee
            // Dan hanya update jika statusnya kosong (null atau '')
            if (!isset($dataPerTanggalEmployee[$employee_id][$tanggal])) {
                // Cek apakah status kosong, jika kosong, maka update status menjadi 'A'
                if (empty($record->attstatus)) {
                    DB::table('hr_attendancerecord')->where('attdate', $tanggal)->where('emplid', $employee_id)->update([
                        'attstatus' => 'A', // Status 'A' untuk absen
                    ]);
                }
            }
        }

        return redirect('/hr/attendance/record');
    }

    public function syncwithleave(Request $request)
    {
        // Ambil bulan dan tahun
        $month = $request->frml_month;
        $year = $request->frml_year;

        // Tentukan tanggal awal dan akhir bulan
        //$startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        //$endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Update data cuti karyawan
        DB::table('hr_leave')
            ->whereMonth('leavedate', $month)
            ->whereYear('leavedate', $year)
            ->get()
            ->each(function ($leave) {
                DB::table('hr_attendancerecord')
                    ->where('attdate', $leave->leavedate)
                    ->where('emplid', $leave->emplid)
                    ->update([
                        'normalin1' => null,
                        'normalout1' => null,
                        'actin1' => null,
                        'actout1' => null,
                        'attstatus' => $leave->leavetype,
                        'notes' => $leave->notes,
                    ]);
            });

        return redirect('/hr/attendance/record');
    }

    public function syncwithovertime(Request $request)
    {
        // Ambil bulan dan tahun
        $month = $request->frm_otsyncmonth;
        $year = $request->frm_otsyncyear;

        // Tentukan tanggal awal dan akhir bulan
        //$startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        //$endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Update data cuti karyawan
        DB::table('hr_overtime')
            ->whereMonth('overtimedate', $month)
            ->whereYear('overtimedate', $year)
            ->get()
            ->each(function ($overtime) {
                DB::table('hr_attendancerecord')
                    ->where('attdate', $overtime->overtimedate)
                    ->where('emplid', $overtime->emplid)
                    ->update([
                        'actin1' => $overtime->timein,
                        'actout1' => $overtime->timeout,
                        'attstatus' => 'OT',
                        'notes' => $overtime->notes,
                    ]);
            });

        return redirect('/hr/attendance/record');
    }

    public function export(Request $request)
    {
        $year = $request->frml_year;
        $month = $request->frml_month;
    
        // Ambil semua data karyawan
        $karyawan = DB::table('hr_empltable')->where('status','Active')->orderBy('emplname', 'asc')->get();
    
        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
    
        // Set default sheet index
        $spreadsheet->removeSheetByIndex(0); // Hapus sheet default pertama
        $sheetIndex = 0;
    
        foreach ($karyawan as $k) {
            // Tambahkan sheet baru untuk setiap karyawan
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($k->emplname); // Nama sheet adalah nama karyawan
    
            // Set header untuk sheet
            $sheet->setCellValue('A1', 'Tanggal')
                ->setCellValue('B1', 'Hari')
                ->setCellValue('C1', 'Status')
                ->setCellValue('D1', 'Normal In')
                ->setCellValue('E1', 'Normal Out')
                ->setCellValue('F1', 'Actual In')
                ->setCellValue('G1', 'Actual Out');
    
            // Ambil absensi karyawan menggunakan query builder
            $absensi = DB::table('hr_attendancerecord')
                        ->where('emplid', $k->emplid)
                        ->whereMonth('attdate', $month)
                        ->whereYear('attdate', $year)
                        ->get();
    
            $row = 2; // Mulai dari baris ke-2
            $statusCount = [
                'N' => 0,
                'Early' => 0,
                'Late' => 0,
                'SD' => 0,
                'UL' => 0,
                'AL' => 0,
                'ML' => 0,
                'PG' => 0,
                'MD' => 0,
                'HD' => 0,
                'A' => 0
            ];
    
            foreach ($absensi as $a) {
                // Cek apakah status absensi kosong, jika kosong set status ke 'Unknown'
                $status = $a->attstatus;
    
                // Isi data absensi
                $sheet->setCellValue('A' . $row, $a->attdate)
                    ->setCellValue('B' . $row, $a->day)
                    ->setCellValue('C' . $row, $status)
                    ->setCellValue('D' . $row, $a->normalin1)
                    ->setCellValue('E' . $row, $a->normalout1)
                    ->setCellValue('F' . $row, $a->actin1)
                    ->setCellValue('G' . $row, $a->actout1);
    
                // Hitung status absensi
                if (isset($statusCount[$status])) {
                    $statusCount[$status]++;
                }
    
                $color = '';
                // Tentukan warna berdasarkan status
                if ($status == 'N') {
                    $color = 'FAFAFA'; // Neutral (No Show)
                } elseif ($status == 'Late' || $status == 'Early') {
                    $color = 'B5828C'; // Purple
                } elseif ($status == 'PH') {
                    $color = 'E52020'; // Red
                } elseif ($status == 'SD') {
                    $color = '6A80B9'; // Blue
                } elseif ($status == 'UL') {
                    $color = 'EFB036'; // Yellow
                } elseif ($status == 'AL') {
                    $color = '543A14'; // Brown
                } elseif ($status == 'ML') {
                    $color = 'F2B28C'; // Pink
                } elseif ($status == 'PG') {
                    $color = 'FAD02E'; // Soft Yellow for Pregnant Leave (PG)
                } elseif ($status == 'PD') {
                    $color = 'F5CBA7'; // Light Beige for Period Leave (PD)
                } elseif ($status == 'HD') {
                    $color = 'A2D9CE'; // Light Teal for Half Day (HD)
                } elseif ($status == 'A') {
                    $color = '7D3C98'; // Dark Purple for Absent (A)
                }

                // Apply color styling if there is any
                if ($color) {
                    $sheet->getStyle('C' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                }
                $row++;
            }
    
            // Tambahkan summary di bawah data absensi (pindahkan ke kolom K dan L)
            $summaryRow = 1;
    
            // Set summary for each status
            foreach ($statusCount as $status => $count) {
                $sheet->setCellValue('K' . $summaryRow, 'Total ' . $status)   // Total status di kolom K
                    ->setCellValue('L' . $summaryRow, $count);
                $summaryRow++;
            }
    
            // Set sheet index
            $sheetIndex++;
        }
    
        // Set writer untuk format Excel
        $writer = new Xlsx($spreadsheet);
    
        // Simpan file Excel di server atau bisa dikirim langsung ke browser
        $filename = 'Absensi_Karyawan.xlsx';
        $path = storage_path('app/public/' . $filename);
    
        $writer->save($path);
    
        // Mengirim file untuk diunduh
        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function exportrecordovt(Request $request)
    {
        $emplid = $request->ovt_emplid;
        $year = $request->ovt_year;
        $month = $request->ovt_month;
    
        // Ambil semua data karyawan
        $karyawan = DB::table('hr_empltable')->where('status','Active')->orderBy('emplname', 'asc')->get();
    
        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
    
        // Set default sheet index
        $spreadsheet->removeSheetByIndex(0); // Hapus sheet default pertama
        $sheetIndex = 0;
    
        foreach ($karyawan as $k) {
            // Tambahkan sheet baru untuk setiap karyawan
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($k->emplname); // Nama sheet adalah nama karyawan
    
            // Set header untuk sheet
            $sheet->setCellValue('A1', 'Tanggal')
                ->setCellValue('B1', 'Hari')
                ->setCellValue('C1', 'Status')
                ->setCellValue('D1', 'Normal In')
                ->setCellValue('E1', 'Normal Out')
                ->setCellValue('F1', 'Actual In')
                ->setCellValue('G1', 'Actual Out')
                ->setCellValue('H1', 'Overtime Hour')
                ->setCellValue('I1', 'Overtime Index');
    
            // Ambil absensi karyawan menggunakan query builder
            $absensi = DB::table('hr_attendancerecord')
                        ->where('emplid', $k->emplid)
                        ->whereMonth('attdate', $month)
                        ->whereYear('attdate', $year)
                        ->get();
    
            $row = 2; // Mulai dari baris ke-2
            $statusCount = [
                'N' => 0,
                'Early' => 0,
                'Late' => 0,
                'SD' => 0,
                'UL' => 0,
                'AL' => 0,
                'ML' => 0,
                'PG' => 0,
                'MD' => 0,
                'HD' => 0,
                'A' => 0
            ];
    
            foreach ($absensi as $a) {
                // Cek apakah status absensi kosong, jika kosong set status ke 'Unknown'
                $status = isset($a->attstatus) ? $a->attstatus : 'A';
    
                // Isi data absensi
                $sheet->setCellValue('A' . $row, $a->attdate)
                    ->setCellValue('B' . $row, $a->day)
                    ->setCellValue('C' . $row, $status)
                    ->setCellValue('D' . $row, $a->normalin1)
                    ->setCellValue('E' . $row, $a->normalout1)
                    ->setCellValue('F' . $row, $a->actin1)
                    ->setCellValue('G' . $row, $a->actout1);
    
                // Hitung status absensi
                if (isset($statusCount[$status])) {
                    $statusCount[$status]++;
                }
    
                $color = '';
                // Tentukan warna berdasarkan status
                if ($status == 'N') {
                    $color = 'FAFAFA'; // Neutral (No Show)
                } elseif ($status == 'Late' || $status == 'Early') {
                    $color = 'B5828C'; // Purple
                } elseif ($status == 'PH') {
                    $color = 'E52020'; // Red
                } elseif ($status == 'SD') {
                    $color = '6A80B9'; // Blue
                } elseif ($status == 'UL') {
                    $color = 'EFB036'; // Yellow
                } elseif ($status == 'AL') {
                    $color = '543A14'; // Brown
                } elseif ($status == 'ML') {
                    $color = 'F2B28C'; // Pink
                } elseif ($status == 'PG') {
                    $color = 'FAD02E'; // Soft Yellow for Pregnant Leave (PG)
                } elseif ($status == 'PD') {
                    $color = 'F5CBA7'; // Light Beige for Period Leave (PD)
                } elseif ($status == 'HD') {
                    $color = 'A2D9CE'; // Light Teal for Half Day (HD)
                } elseif ($status == 'A') {
                    $color = '7D3C98'; // Dark Purple for Absent (A)
                }

                // Apply color styling if there is any
                if ($color) {
                    $sheet->getStyle('C' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                }
                $row++;
            }
    
            // Tambahkan summary di bawah data absensi (pindahkan ke kolom K dan L)
            $summaryRow = 1;
    
            // Set summary for each status
            foreach ($statusCount as $status => $count) {
                $sheet->setCellValue('K' . $summaryRow, 'Total ' . $status)   // Total status di kolom K
                    ->setCellValue('L' . $summaryRow, $count);
                $summaryRow++;
            }
    
            // Set sheet index
            $sheetIndex++;
        }
    
        // Set writer untuk format Excel
        $writer = new Xlsx($spreadsheet);
    
        // Simpan file Excel di server atau bisa dikirim langsung ke browser
        $filename = 'Absensi_Karyawan.xlsx';
        $path = storage_path('app/public/' . $filename);
    
        $writer->save($path);
    
        // Mengirim file untuk diunduh
        return response()->download($path)->deleteFileAfterSend(true);
    }

}
