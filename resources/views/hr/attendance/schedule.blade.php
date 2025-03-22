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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance Schedule</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Schedule</a></li>
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
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-add">
                  Add
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Schedule ID</th>
                    <th>Schedule Name</th>
                    <th>Normal In 1</th>
                    <th>Normal Out 1</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($scheduleList as $r)
                  <tr>
                    <td>{{ $r->scheduleid }}</td>
                    <td>{{ $r->schedulename }}</td>
                    <td>{{ $r->normalin1 }}</td>
                    <td>{{ $r->normalout1 }}</td>
                    <td>
                      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-edit{{$r->scheduleid}}"><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete" onclick="setDeleteUrl('{{url('/hr/attendance/schedule/delete')}}/{{ $r->scheduleid }}')">
                        <i class="far fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </table>
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
<div class="modal fade" id="modal-add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/schedule/add')}}" method="post">
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
                  <div class="form-group">
                    <label>Schedule Name</label>
                    <input type="text" class="form-control"  name="frm_schedulename" required>
                  </div>
                  <div class="form-group">
                    <label>Normal In 1</label>
                    <input type="time" class="form-control"  name="frm_normalin1" required>
                  </div>
                  <div class="form-group">
                    <label>Normal Out 1</label>
                    <input type="time" class="form-control"  name="frm_normalout1" required>
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

@foreach($scheduleList as $r)
<div class="modal fade" id="modal-edit{{$r->scheduleid}}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form start -->
      <form action="{{url('/hr/attendance/schedule/update')}}" method="post">
        <div class="modal-body">
        <!-- general form elements -->
          <div class="card card-primary">
              {{ csrf_field() }}
              <div class="card-body">                
                <div class="card-header">
                  <h3 class="card-title">
                    General
                  </h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Schedule ID</label>
                    <input type="text" class="form-control"  name="frme_schid" value="{{ $r->scheduleid }}" readonly>
                  </div>
                  <div class="form-group">
                    <label>Schedule Name</label>
                    <input type="text" class="form-control"  name="frme_schedulename" value="{{ $r->schedulename }}">
                  </div>
                  <div class="form-group">
                    <label>Normal In 1</label>
                    <input type="time" class="form-control"  name="frme_normalin1" value="{{ $r->normalin1 }}">
                  </div>
                  <div class="form-group">
                    <label>Normal Out 1</label>
                    <input type="time" class="form-control"  name="frme_normalout1" value="{{ $r->normalout1 }}">
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
@endforeach

<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation Delete Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this data??
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <!-- Formulir penghapusan yang akan dikirim -->
        <form id="delete-form" method="get" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Menyimpan URL delete dalam variabel global
  let deleteUrl = '';

  // Menetapkan URL delete pada tombol yang diklik
  function setDeleteUrl(url) {
    // Menetapkan URL penghapusan ke action formulir delete
    document.getElementById('delete-form').action = url;
  }
</script>

<script>
  document.getElementById('deptSelect').addEventListener('change', function() {
    var levelName = this.selectedOptions[0].getAttribute('data-levelname');
    document.getElementById('deptNameInput').value = levelName;
  });
</script>

@endsection