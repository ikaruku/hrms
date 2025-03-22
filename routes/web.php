<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//SYSADMIN CONTROLLER
use App\Http\Controllers\sysadmin\MenuController;
use App\Http\Controllers\sysadmin\PermissionController;
use App\Http\Controllers\sysadmin\UserController;

//HR CONTROLLER
//ATTENDANCE CONTROLLER
use App\Http\Controllers\hr\attendance\attendanceController;
use App\Http\Controllers\hr\attendance\recordController;
use App\Http\Controllers\hr\attendance\scheduleController;
use App\Http\Controllers\hr\attendance\holidayController;
use App\Http\Controllers\hr\attendance\leaveController;
use App\Http\Controllers\hr\attendance\overtimeController;
//DEVELOPMENT CONTROLLER
use App\Http\Controllers\hr\development\developmentController;
use App\Http\Controllers\hr\development\trainingController;
//EMPLOYEE CONTROLLER
use App\Http\Controllers\hr\employee\workerController;
use App\Http\Controllers\hr\employee\organizationController;
use App\Http\Controllers\hr\employee\departmentController;
use App\Http\Controllers\hr\employee\levelController;
use App\Http\Controllers\hr\employee\positionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::group( ['middleware' => 'auth' ], function()
{
    //home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    //============================================================================================== 
    //SYSADMIN
    //menumanager
    Route::get('/sysadmin/menumanager', [MenuController::class, 'index'])->middleware('checkUserRole');
    Route::post('/sysadmin/menumanager/add', [MenuController::class, 'add'])->middleware('checkUserRole');
    Route::post('/sysadmin/menumanager/update',[MenuController::class, 'update'])->middleware('checkUserRole');
    Route::get('/sysadmin/menumanager/delete/{id}',[MenuController::class, 'delete'])->middleware('checkUserRole');
    //permissionmanager
    Route::get('/sysadmin/userpermission', [PermissionController::class, 'index'])->middleware('checkUserRole');
    Route::get('/sysadmin/userpermission/{id}',[PermissionController::class, 'permdetail'])->middleware('checkUserRole');
    Route::post('/sysadmin/save-permission', [PermissionController::class, 'savePermission'])->name('savePermission');

    //usermanager
    Route::get('/sysadmin/usermanager', [UserController::class, 'index'])->middleware('checkUserRole');
    Route::post('/sysadmin/usermanager/add', [UserController::class, 'add'])->middleware('checkUserRole');
    Route::post('/sysadmin/usermanager/update',[UserController::class, 'update'])->middleware('checkUserRole');
    Route::get('/sysadmin/usermanager/delete/{id}',[UserController::class, 'delete'])->middleware('checkUserRole');
    //==============================================================================================
    
    
    //==============================================================================================
    //HR
    //home
    Route::get('/hr/attendance/',[attendanceController::class, 'index']);
    //==============================================================================================
    //ATTENDANCE
    //record
    Route::get('/hr/attendance/record',[recordController::class, 'index']);
    Route::get('/hr/attendance/record/{id}',[recordController::class, 'indexdetail']);
    Route::post('/hr/attendance/record/generateall',[recordController::class, 'generateall']);
    Route::post('/hr/attendance/record/import',[recordController::class, 'import']);
    Route::post('/hr/attendance/record/syncwithleave',[recordController::class, 'syncwithleave']);
    Route::post('/hr/attendance/record/syncwithovertime',[recordController::class, 'syncwithovertime']);
    Route::get('/hr/attendance/exportrecord',[recordController::class, 'export']);
    Route::get('/hr/attendance/exportrecordovt',[recordController::class, 'exportrecordovt']);
    //schedule
    Route::get('/hr/attendance/schedule',[scheduleController::class, 'index']);
    Route::post('/hr/attendance/schedule/add',[scheduleController::class, 'add']);
    Route::post('/hr/attendance/schedule/update',[scheduleController::class, 'update']);
    Route::get('/hr/attendance/schedule/delete/{id}',[scheduleController::class, 'delete']);
    //holiday
    Route::get('/hr/attendance/holiday',[holidayController::class, 'index']);
    Route::post('/hr/attendance/holiday/add',[holidayController::class, 'add']);
    Route::post('/hr/attendance/holiday/update',[holidayController::class, 'update']);
    Route::get('/hr/attendance/holiday/delete/{id}',[holidayController::class, 'delete']);
    //leave
    Route::get('/hr/attendance/leave',[leaveController::class, 'index']);
    Route::post('/hr/attendance/leave/add',[leaveController::class, 'add']);
    Route::post('/hr/attendance/leave/addleave',[leaveController::class, 'addleave']);
    Route::post('/hr/attendance/leave/update',[leaveController::class, 'update']);
    Route::get('/hr/attendance/leave/delete/{id}',[leaveController::class, 'delete']);
    //overtime
    Route::get('/hr/attendance/overtime',[overtimeController::class, 'index']);
    Route::post('/hr/attendance/overtime/add',[overtimeController::class, 'add']);
    Route::post('/hr/attendance/overtime/update',[overtimeController::class, 'update']);
    Route::get('/hr/attendance/overtime/delete/{id}',[overtimeController::class, 'delete']);
    //==============================================================================================
    //DEVELOPMENT
    //home
    Route::get('/hr/development',[developmentController::class, 'index']);
    //training
    Route::get('/hr/development/training',[trainingController::class, 'index']);
    Route::post('/hr/development/training/add',[trainingController::class, 'add']);
    Route::post('/hr/development/training/update',[trainingController::class, 'update']);
    Route::get('/hr/development/training/delete/{id}',[trainingController::class, 'delete']);
    //training detail
    Route::get('/hr/development/training/{id}',[trainingController::class, 'indexdetail']);
    Route::post('/hr/development/training/addschedule',[trainingController::class, 'addschedule']);
    Route::get('/hr/development/training/schedule/delete/{id}',[trainingController::class, 'deleteschedule']);
    //==============================================================================================
    //EMPLOYEE
    //home
    Route::get('/hr/employee',[workerController::class, 'index']);
    //worker
    Route::get('/hr/employee/worker',[workerController::class, 'indexworker']);
    Route::get('/hr/employee/pastworker',[workerController::class, 'indexpast']);
    Route::post('/hr/employee/worker/add',[workerController::class, 'add']);
    Route::post('/hr/employee/worker/update',[workerController::class, 'update']);
    Route::get('/hr/employee/worker/detail/{id}', [workerController::class, 'detail']);
    Route::get('/hr/employee/worker/delete/{id}',[workerController::class, 'delete']);
    Route::get('/hr/employee/worker/exportexcel',[workerController::class, 'exportexcel']);
    //family detail
    Route::post('/hr/employee/worker/family/add',[workerController::class, 'addfamily']);
    Route::get('/hr/employee/worker/family/delete/{id}',[workerController::class, 'deletefamily']);
    //department level position
    Route::post('/hr/employee/worker/organization/add',[workerController::class, 'addorganization']);
    Route::get('/hr/employee/worker/organization/delete/{id}',[workerController::class, 'deleteorganization']);
    //department
    Route::get('/hr/employee/department',[departmentController::class, 'index']);
    Route::post('/hr/employee/department/add',[departmentController::class, 'add']);
    Route::post('/hr/employee/department/update',[departmentController::class, 'update']);
    Route::get('/hr/employee/department/delete/{id}',[departmentController::class, 'delete']);
    //level
    Route::get('/hr/employee/level',[levelController::class, 'index']);
    Route::post('/hr/employee/level/add',[levelController::class, 'add']);
    Route::post('/hr/employee/level/update',[levelController::class, 'update']);
    Route::get('/hr/employee/level/delete/{id}',[levelController::class, 'delete']);
    //position
    Route::get('/hr/employee/position',[positionController::class, 'index']);
    Route::post('/hr/employee/position/add',[positionController::class, 'add']);
    Route::post('/hr/employee/position/update',[positionController::class, 'update']);
    Route::get('/hr/employee/position/delete/{id}',[positionController::class, 'delete']);
    //==============================================================================================
    
});