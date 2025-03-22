<!-- Menghubungkan dengan view template master -->
@extends('main')
 
<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title')
<title>Admin | Dashboard</title>
@endsection

@section('navi')
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/employee')}}" class="nav-link"><i class="nav-icon fas fa-home"></i></a>
</li>
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/employee/worker')}}" class="nav-link">Worker</a>
</li>
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/employee/pastworker')}}" class="nav-link">Past Worker</a>
</li>
<li class="nav-item dropdown">
  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuration</a>
  <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
    <li><a href="{{url('/hr/employee/department')}}" class="dropdown-item">Department </a></li>
    <li><a href="{{url('/hr/employee/level')}}" class="dropdown-item">Level</a></li>
    <li><a href="{{url('/hr/employee/position')}}" class="dropdown-item">Position</a></li>
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
            <h1 class="m-0">Employee</h1>
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
          <div class="col-lg-3 col-6"> 
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$worker}}</h3>
                <p>Worker</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="{{url('/hr/employee/worker')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6"> 
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$pastworker}}</h3>
                <p>Past Worker</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="{{url('/hr/employee/pastworker')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Title</h5>

                <p class="card-text">
                  Sample Text
                </p>

                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
              </div>
            </div>
            <!-- /.card -->
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