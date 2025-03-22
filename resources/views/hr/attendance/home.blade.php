<!-- Menghubungkan dengan view template master -->
@extends('main')
 
<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title')
<title>Admin | Dashboard</title>
@endsection

@section('navi')
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance')}}" class="nav-link"><i class="nav-icon fas fa-home"></i></a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/record')}}" class="nav-link">Record</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/leave')}}" class="nav-link">Leave</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/overtime')}}" class="nav-link">Overtime</a>
  </li>
  <li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuration</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
      <li><a href="{{url('/hr/attendance/schedule')}}" class="dropdown-item">Schedule </a></li>
      <li><a href="{{url('/hr/attendance/holiday')}}" class="dropdown-item">Holiday</a></li>
    </ul>
  </li>
@endsection

<!-- isi bagian konten -->
<!-- cara penulisan isi section yang panjang -->
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Index</a></li>
              <li class="breadcrumb-item active">Home</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Title</h5>
                <p class="card-text">
                  Sample Text
                </p>
              </div>
            </div>
            <!-- /.card -->
          </div>
          
          <div class="col-lg-3">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Period Generated</h3>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Year</th>
                    <th>Month</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($generated as $r)
                    <tr>
                      <td>{{ $r->year }}</td>
                      <td>{{ \Carbon\Carbon::createFromFormat('m', $r->month)->monthName }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection