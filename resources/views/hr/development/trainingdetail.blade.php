<!-- Menghubungkan dengan view template master -->
@extends('main')
 
<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title')
<title>Admin | Dashboard</title>
  <!-- DataTables -->
  <link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('navi')
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/development')}}" class="nav-link"><i class="nav-icon fas fa-home"></i></a>
</li>
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/development/training')}}" class="nav-link">Training</a>
</li>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Training Detail of {{request()->id}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Detail</a></li>
              <li class="breadcrumb-item"><a href="#">Training</a></li>
              <li class="breadcrumb-item active">HR</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#schedule" data-toggle="tab">Schedule</a></li>
                  <li class="nav-item"><a class="nav-link" href="#participant" data-toggle="tab">Participant</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <!-- TAB TANGGAL  -->
                  <div class="active tab-pane" id="schedule">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addschedule">
                      Add
                    </button>
                    <br></br>
                    <table id="filtertable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Schedule Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($scdlist as $r)
                      <tr>
                        <td>{{ $r->scheduleDate }}</td>
                        <td>{{ $r->startTime }}</td>
                        <td>{{ $r->endTime }}</td>
                        <td>
                          <a href="{{url('/hr/development/training/schedule/delete\/')}}{{ $r->id }}">
                            <button type="button" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </table>
                  </div>
                  <!-- TAB PARTICIPANT  -->
                  <div class="tab-pane" id="participant">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addparticipant">
                      Add
                    </button>
                    <br></br>
                    <table id="filtertable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pcplist as $r)
                      <tr>
                        <td>{{ $r->emplid }}</td>
                        <td>{{ $r->emplname }}</td>
                        <td>
                          <a href="{{url('/hr/development/training/schedule/delete\/')}}{{ $r->id }}">
                            <button type="button" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
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

<!-- /.POP UP CONTENT -->
<div class="modal fade" id="addschedule">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/development/training/addschedule')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Add Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- general form elements -->
          <div class="card card-primary">
            <!-- form start -->
              {{ csrf_field() }}
              <div class="card-body">
                <div class="card-header">
                  <h3 class="card-title">
                    General
                  </h3>
                </div>
                <div class="card-body">
                  <input type="hidden" class="form-control"  name="frm_trainingid" value="{{request()->id}}">
                  <div class="form-group">
                    <label>Schedule Date</label>
                    <input type="date" class="form-control"  name="frm_scheduledate">
                  </div>
                  <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" class="form-control"  name="frm_starttime">
                  </div>
                  <div class="form-group">
                    <label>End Time</label>
                    <input type="time" class="form-control"  name="frm_endtimme">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="addparticipant">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/development/training/addschedule')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Add Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- general form elements -->
          <div class="card card-primary">
            <!-- form start -->
              {{ csrf_field() }}
              <div class="card-body">
                <div class="card-header">
                  <h3 class="card-title">
                    General
                  </h3>
                </div>
                <div class="card-body">
                  <input type="hidden" class="form-control"  name="frm_trainingid" value="{{request()->id}}">
                  <div class="form-group">
                    <label>Employee ID</label>
                    <input type="text" class="form-control"  name="frm_scheduledate">
                  </div>
                  <div class="form-group">
                    <label>Employee Name</label>
                    <input type="text" class="form-control"  name="frm_starttime">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection