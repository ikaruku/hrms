<!-- Menghubungkan dengan view template master -->
@extends('main')

@section('title')
<title>Admin | Dashboard</title>
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
            <h1 class="m-0">Worker Detail</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Worker Detail</a></li>
              <li class="breadcrumb-item active">HR</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    
    @foreach($workeredit as $r)
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <form action="{{url('/hr/employee/worker/update')}}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
        <button type="submit" class="btn btn-info"> Save </button>
        <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete" onclick="setDeleteUrl('{{url('/hr/employee/worker/delete')}}/{{ $r->emplid }}')"> Delete User </button>
        <br></br>
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                      src="{{url('/storage\/')}}{{ $r->emplpicture }}">
                </div>
                <h3 class="profile-username text-center">{{ $r->emplname }}</h3>
                <p class="text-muted text-center">{{ $r->birthdate }}</p>

                <input type="hidden" name="photo_prev" value="{{ $r->emplpicture }}"> 
                <input type="file" name="photo" accept="image/png, image/jpeg" >

              </div>
            </div>
              <!-- About Me Box -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Summary</h3>
                </div>
                <div class="card-body">
                  <strong>Department</strong>
                  <p class="text-muted">{{ $r->deptname }}</p>

                  <hr>
                  <strong>Level</strong>
                  <p class="text-muted">{{ $r->levelname }}</p>

                  <hr>
                  <strong>Position</strong>
                  <p class="text-muted">{{ $r->positionname }}</p>
                </div>
              </div>
            </div>

            <div class="col-md-9">
              <div class="card">
                <!-- HEADER UNTUK JUDUL TAB -->
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#worker" data-toggle="tab">Worker Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">Tax Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#family" data-toggle="tab">Family Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#attendance" data-toggle="tab">Attendance</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <!-- TAB UNTUK BASIC INFO -->
                    <div class="active tab-pane" id="basic">
                      <div class="form-group">
                        <label>Employee ID</label>
                        <input type="text" class="form-control" name="emplid" value="{{ $r->emplid }}" readonly>
                      </div>
                      <div class="form-group">
                        <label>Employee Name</label>
                        <input type="text" class="form-control" name="frm_emplname" value="{{ $r->emplname }}">
                      </div>
                      <div class="form-group">
                        <label>KTP ID</label>
                        <input type="text" class="form-control" name="frm_ktp" value="{{ $r->ktp }}">
                      </div>
                      <div class="form-group">
                        <label>Place of Birth</label>
                        <input type="text" class="form-control" name="frm_placeofbirth" value="{{ $r->placeofbirth }}">
                      </div>
                      <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" name="frm_birthdate" value="{{ $r->birthdate }}">
                      </div>
                      <div class="form-group">
                        <label>Gender</label>
                        <select name="frm_gender" class="form-control select2" >
                          <option value="" disabled selected>Select an option</option>
                          <option value="male" <?php if ($r->gender == 'male') echo 'selected'; ?>>Pria</option>
                          <option value="female" <?php if ($r->gender == 'female') echo 'selected'; ?>>Wanita</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" rows="3" name="frm_address">{{ $r->address }}</textarea>
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="frm_email" value="{{ $r->email }}">
                      </div>
                      <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" name="frm_phonenumber" value="{{ $r->phonenumber }}">
                      </div>
                    </div>

                    <!-- TAB UNTUK BASIC  -->
                    <div class="tab-pane" id="worker">
                      <div class="form-group">
                        <label>Status</label>
                        <select name="frm_status" class="form-control select2" >
                          <option value="Active" <?php if ($r->status == 'Active') echo 'selected'; ?>>Active</option>
                          <option value="Resigned" <?php if ($r->status == 'Resigned') echo 'selected'; ?>>Resigned</option>
                          <option value="Terminated" <?php if ($r->status == 'Terminated') echo 'selected'; ?>>Terminated</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Date of Join</label>
                        <input type="date" class="form-control" name="frm_joindate" value="{{ $r->joindate }}">
                      </div>
                      <div class="form-group">
                        <label>Date of Permanent</label>
                        <input type="date" class="form-control" name="frm_permanentdate" value="{{ $r->permanentdate }}">
                      </div>
                      <div class="form-group">
                        <label>Date of Leave</label>
                        <input type="date" class="form-control" name="frm_leavedate" value="{{ $r->leavedate }}">
                      </div>
                      
                      <br>
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-addpos">
                        Add Position
                      </button>
                      <br>

                      <table id="familytable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Level</th>
                            <th>Position</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($deptlevelpostrans as $p)
                          <tr>
                            <td>{{ $p->startdate }}</td>
                            <td>{{ $p->deptname }}</td>
                            <td>{{ $p->levelname }}</td>
                            <td>{{ $p->posname }}</td>
                            <td>
                              <a href="{{url('/hr/employee/worker/organization/delete\/')}}{{ $p->id }}">
                                <button type="button" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                              </a>
                            </td>
                          </tr>
                          @endforeach
                      </table>
                    </div>

                    <!-- TAB UNTUK TAX  -->
                    <div class="tab-pane" id="tax">
                      <div class="form-group">
                        <label>Tax ID</label>
                        <input type="text" class="form-control" name="frm_taxid" value="{{ $r->taxid }}">
                      </div>
                      <div class="form-group">
                        <label>Tax Type</label>
                        <select name="frm_taxtype" class="form-control select2" >
                          <option value="" disabled selected>Select an option</option>
                          <option value="TK0" <?php if ($r->taxtype == 'TK0') echo 'selected'; ?>>TK0</option>
                          <option value="TK1" <?php if ($r->taxtype == 'TK1') echo 'selected'; ?>>TK1</option>
                          <option value="TK2" <?php if ($r->taxtype == 'TK2') echo 'selected'; ?>>TK2</option>
                          <option value="TK3" <?php if ($r->taxtype == 'TK3') echo 'selected'; ?>>TK3</option>
                          <option value="K0" <?php if ($r->taxtype == 'K0') echo 'selected'; ?>>K0</option>
                          <option value="K1" <?php if ($r->taxtype == 'K1') echo 'selected'; ?>>K1</option>
                          <option value="K2" <?php if ($r->taxtype == 'K2') echo 'selected'; ?>>K2</option>
                          <option value="K3" <?php if ($r->taxtype == 'K3') echo 'selected'; ?>>K3</option>
                        </select>
                      </div>
                    </div>

                    <!-- TAB UNTUK FAMILY  -->
                    <div class="tab-pane" id="family">
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-add">
                        Add
                      </button>
                      <br></br>
                      <table id="familytable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Familiy Name</th>
                            <th>Family Birthday</th>
                            <th>Family Relation</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($familylist as $f)
                          <tr>
                            <td>{{ $f->familyname }}</td>
                            <td>{{ $f->familybirthdate }}</td>
                            <td>{{ $f->familyrelation }}</td>
                            <td>
                              <a href="{{url('/hr/worker/family/delete\/')}}{{ $f->id }}">
                                <button type="button" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                              </a>
                            </td>
                          </tr>
                          @endforeach
                      </table>
                    </div>

                    <!-- TAB UNTUK ATTENDANCE  -->
                    <div class="tab-pane" id="attendance">
                      <div class="form-group">
                        <label>Weekday Schedule</label>
                        <select class="form-control select2" style="width: 100%;" name="frm_weekday">
                          <option value="" disabled selected>Select an option</option>
                          @foreach($attschedulelist as $schedule)
                            <option value="{{ $schedule->scheduleid }}"
                              @if($schedule->scheduleid == $r->weekdayid) selected @endif>
                              {{ $schedule->scheduleid }} - {{ $schedule->schedulename }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Saturday Schedule</label>
                        <select class="form-control select2" style="width: 100%;" name="frm_saturday">
                          <option value="" disabled selected>Select an option</option>
                          @foreach($attschedulelist as $schedule)
                            <option value="{{ $schedule->scheduleid }}"
                              @if($schedule->scheduleid == $r->saturdayid) selected @endif>
                              {{ $schedule->scheduleid }} - {{ $schedule->schedulename }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
    @endforeach
    <!-- /.content -->
</div>

<!-- /.POP UP CONTENT -->
<div class="modal fade" id="modal-add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/employee/worker/family/add')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Add Data Family</h4>
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
                    <input type="text" class="form-control"  name="emplid" value="{{request()->id}}" hidden>
                  </div>
                  <div class="form-group">
                    <label>Family Name</label>
                    <input type="text" class="form-control"  name="frm_familyname">
                  </div>
                  <div class="form-group">
                    <label>Family Birthdate</label>
                    <input type="date" class="form-control"  name="frm_familybirthdate">
                  </div>
                  <div class="form-group">
                    <label>Family Relation</label>
                    <select name="frm_familyrelation" class="form-control select2" >
                      <option value="" disabled selected>Select an option</option>
                      <option value="Ayah" >Ayah</option>
                      <option value="Ibu" >Ibu</option>
                      <option value="Istri" >Istri</option>
                      <option value="Anak" >Anak</option>
                    </select>
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

<div class="modal fade" id="modal-addpos">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/employee/worker/organization/add')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Add Position</h4>
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
                    <input type="text" class="form-control"  name="emplid" value="{{request()->id}}" hidden>
                  </div>
                  <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="frm_startdate">
                  </div>
                  <div class="form-group">
                    <label>Department</label>
                    <select class="form-control select2" style="width: 100%;" name="frm_deptid" id="deptSelect" onchange="updateDeptName()" >
                      <option value="" disabled selected>Select an option</option>
                      @foreach($deptlist as $dept)
                        <option value="{{ $dept->deptid }}" data-deptname="{{ $dept->deptname }}" 
                          @if($dept->deptid == $r->deptid) selected @endif>
                          {{ $dept->deptid }} - {{ $dept->deptname }}
                        </option>
                      @endforeach
                    </select>
                    <input type="hidden" id="deptNameInput" name="frm_deptname" readonly />
                  </div>
                  <div class="form-group">
                    <label>Level</label>
                    <select class="form-control select2" style="width: 100%;" name="frm_levelid" id="levelSelect" onchange="updateLevelName()">
                    <option value="" disabled selected>Select an option</option>
                      @foreach($levellist as $level)
                        <option value="{{ $level->id }}" data-levelname="{{ $level->levelname }}"
                          @if($level->id == $r->levelid) selected @endif>
                          {{ $level->id }} - {{ $level->levelname }}
                        </option>
                      @endforeach
                    </select>
                    <input type="hidden" id="levelNameInput" name="frm_levelname" readonly />
                  </div>
                  <div class="form-group">
                    <label>Position</label>
                    <select class="form-control select2" style="width: 100%;" name="frm_positionid" id="positionSelect" onchange="updatePositionName()">
                    <option value="" disabled selected>Select an option</option>
                      @foreach($positionlist as $position)
                        <option value="{{ $position->posid }}" data-positionname="{{ $position->posname }}"
                          @if($position->posid == $r->positionid) selected @endif>
                          {{ $position->posid }} - {{ $position->posname }}
                        </option>
                      @endforeach
                    </select>
                    <input type="hidden" id="positionNameInput" name="frm_positionname" readonly />
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
  // DeptName
  function updateDeptName() {
      const select = document.getElementById('deptSelect');
      const selectedOption = select.options[select.selectedIndex];
      const deptName = selectedOption.getAttribute('data-deptname');

      document.getElementById('deptNameInput').value = deptName;
  }
  updateDeptName();
  // LevelName
  function updateLevelName() {
      const select = document.getElementById('levelSelect');
      const selectedOption = select.options[select.selectedIndex];
      const levelName = selectedOption.getAttribute('data-levelname');

      document.getElementById('levelNameInput').value = levelName;
  }
  updateLevelName();
  // Position
  function updatePositionName() {
      const select = document.getElementById('positionSelect');
      const selectedOption = select.options[select.selectedIndex];
      const positionName = selectedOption.getAttribute('data-positionname');

      document.getElementById('positionNameInput').value = positionName;
  }
  updatePositionName();

</script>
@endsection