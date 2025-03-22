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

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Level</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Level</a></li>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">
                  Add
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Level ID</th>
                    <th>Level Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($levellist as $r)
                  <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->levelname }}</td>
                    <td>
                      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-edit{{$r->id}}"><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete" onclick="setDeleteUrl('{{url('/hr/employee/level/delete')}}/{{ $r->id }}')"><i class="far fa-trash-alt"></i></button>
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
      <form action="{{url('/hr/employee/level/add')}}" method="post">
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
                    <label>Level ID</label>
                    <input type="text" class="form-control"  name="frm_id" required>
                  </div>
                  <div class="form-group">
                    <label>Level Name</label>
                    <input type="text" class="form-control"  name="frm_levelname" required>
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

@foreach($levellist as $r)
<div class="modal fade" id="modal-edit{{$r->id}}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form start -->
      <form action="{{url('/hr/employee/level/update')}}" method="post">
        <div class="modal-body">
        <!-- general form elements -->
          <div class="card card-primary">
              {{ csrf_field() }}
              <input type="hidden" name="frm_id" value="{{ $r->id }}"> 
              <div class="card-body">                
                <div class="card-header">
                  <h3 class="card-title">
                    General
                  </h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Level Name</label>
                    <input type="text" class="form-control" name="edit_levelname" value="{{$r->levelname}}">
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

@endsection