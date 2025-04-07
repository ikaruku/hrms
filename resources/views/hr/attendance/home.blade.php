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

@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance Dashboard</h1>
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
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$enroll}}</h3>
                <p>Enrolled Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6"> 
            <div class="small-box bg-success">
              <div class="inner">
              <h3>{{$present}}</h3>
                <p>Present Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6"> 
            <div class="small-box bg-danger">
              <div class="inner">
              <h3>{{$absent->count()}}</h3>
                <p>Absent Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="" data-toggle="modal" data-target="#modal-absent" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6"> 
            <div class="small-box bg-warning">
              <div class="inner">
              <h3>{{$late}}</h3>
                <p>Late Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
                    
          <div class="col-lg-3">
            <div class="card">
              <div class="card-header">
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
          
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 7 Days Attendance</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
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
<script src="{{url('/plugins/jquery/jquery.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{url('/plugins/chart.js/Chart.min.js')}}"></script>
<script>
  $(function () {
      var lineChartCanvas = $('#lineChart').get(0).getContext('2d');
      
      var lineChartData = {!! json_encode($dataAbsensi) !!};
      
      lineChartData.datasets.forEach(function(dataset) {
          dataset.tension = 0;  // Menonaktifkan interpolasi spline (garis lurus)
      });

      var lineChartOptions = {
          maintainAspectRatio : false,
          responsive : true,
          legend: {
              display: true
          },
          scales: {
              xAxes: [{
                  gridLines : {
                      display : false,
                  }
              }],
              yAxes: [{
                  gridLines : {
                      display : false,
                  }
              }]
          }
      };

      new Chart(lineChartCanvas, {
          type: 'line',
          data: lineChartData,
          options: lineChartOptions
      });
  });
</script>


<div class="modal fade" id="modal-absent">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Absent Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card card-primary">
        <div class="modal-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Status</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              @foreach($absent as $r)
              <tr>
                <td>{{ $r->emplid }}</td>
                <td>{{ $r->emplname }}</td>
                <td>{{ $r->attstatus }}</td>
                <td>{{ $r->notes }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection