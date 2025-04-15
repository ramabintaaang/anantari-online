@extends('layouts.base2')

@section('title')
    POS by RBTG
@endsection

@section('content')
    <h3>Pilih Cabang</h3>
    <div class="page-heading">
        @if (Auth::user()->divisi === 'admin')
            <button class="btn btn-primary" id="btnTambahCabang">Tambah cabang</button>
        @endif

        {{-- <h3>Halo , {{ auth::user()->username }}</h3> --}}
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    @foreach ($setting as $settings)
                        <div class="col-6 col-lg-3 col-md-6">
                            <a href="{{ route('dashboard', ['cabang' => $settings->stgcabang]) }}" class="card-link">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldShow"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">{{ $settings->stgnama }}</h6>
                                                <h6 class="font-extrabold mb-0">{{ $settings->stgprefix }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>


            </div>
        </section>
        @if (Auth::user()->divisi === 'admin')
            <h3>User</h3>
            <button class="btn btn-primary" id="btnTambahUser">Tambah User</button>
            <div class="col-md-5 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_user">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>username</th>
                                            <th>divisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @endif


    <div class="modal fade modal-lg text-left" id="modalCabang" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Tambah Cabang</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form id="formCabang">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Info Cabang</h4>
                                    <div class="form-group">
                                        <label for="basicInput">Kode cabang</label>
                                        <small class="text-muted">terisi otomatis oleh sistem</small>
                                        <input type="text" class="form-control" id="stgcabang" name="stgcabang"
                                            placeholder="Kode cabang" readonly>
                                        <span class="text-danger error stgcabang_error"></span>

                                    </div>
                                    <div class="form-group">
                                        <label for="basicInput">Nama cabang</label>
                                        <input type="text" class="form-control" id="stgnama" name="stgnama"
                                            placeholder="Nama cabang">
                                        <span class="text-danger error stgnama_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="basicInput">Prefix / alias</label>
                                        <small class="text-danger">hanya input sekali & tidak dapat diubah (3 huruf)</small>
                                        <input type="text" class="form-control" id="stgprefix" name="stgprefix"
                                            placeholder="Prefix">
                                        <span class="text-danger error stgprefix_error"></span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Gudang</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Batal</span>
                        </button>
                        <button type="button" class="btn btn-primary ms-1" id="btnAddCabang">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tambah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade modal-lg text-left" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalUser"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form id="formUser">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Info User</h4>
                                    <div class="form-group">
                                        <label for="basicInput">Kode User</label>
                                        <small class="text-muted">terisi otomatis oleh sistem</small>
                                        <input type="text" class="form-control" id="iduser" name="iduser"
                                            readonly>
                                        <span class="text-danger error iduser_error"></span>

                                    </div>
                                    <div class="form-group">
                                        <label for="basicInput">Nama User</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                        <span class="text-danger error name_error"></span>
                                    </div>
                                    <div class="form-group" id="grupUsername">
                                        <label for="basicInput">username</label>
                                        <small class="text-danger">digunakan untuk login</small>
                                        <input type="text" class="form-control" id="username" name="username">
                                        <span class="text-danger error username_error"></span>

                                    </div>
                                    <div class="form-group" id="grupPassword">
                                        <label for="basicInput">password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <span class="text-danger error password_error"></span>

                                    </div>
                                    <div class="form-group position-relative has-icon-right">
                                        <label for="basicInput">Divisi</label>
                                        <select class="form-select " id="divisi" name="divisi">
                                            <option value="purchasing">purchasing</option>
                                            <option value="bar">bar</option>
                                            <option value="kitchen">kitchen</option>
                                            <option value="kasir">kasir</option>
                                            <option value="admin">admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Akses Cabang</h4>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($setting as $x)
                                            <li class="d-inline-block me-2 mb-1">
                                                <div class="form-check">
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="cabang[]" id="{{ $x->stgcabang }}"
                                                            class="form-check-input" value="{{ $x->stgcabang }}" checked>
                                                        <label for="{{ $x->stgcabang }}">{{ $x->stgnama }}</label>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary ms-1" id="btnAddUser">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tambah</span>
                        </button>
                        <button type="button" class="btn btn-warning ms-1" id="btnUpdateUser">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            getAllUser();
            klikTabelUser();
        });

        $('#btnTambahCabang').click(function(e) {
            $('#modalCabang').modal('show')
            getKodeCabang()
        });

        $('#btnTambahUser').click(function(e) {
            $('#modalUser').modal('show')
            $('#titleModalUser').html("Tambah User")
            $('#btnUpdateUser').css("display", "none")
            $('#name').val('')
            $('#username').val('')
            $('#grupPassword').css("display", '')
            getKodeUser()
        });

        function klikTabelUser() {

            var table = $('#dt_user').DataTable();
            table.on('click', 'tbody tr', function() {

                $('#titleModalUser').html("edit user")



                $('#modalUser').modal('show');
                $.ajax({
                    type: "get",
                    url: "{{ route('getUserDetail') }}",
                    data: {
                        'iduser': (table.row(this).data().id),
                        'username': (table.row(this).data().username),
                    },
                    dataType: "json",
                    success: function(res) {
                        $('#modalUser').modal("show")
                        $('#iduser').val(res.user[0].id)
                        $('#name').val(res.user[0].name)
                        $('#username').val(res.user[0].username)
                        $('#divisi').val(res.user[0].divisi)
                        $('#btnUpdateUser').css("display", "")
                        $('#grupPassword').css("display", "none")
                        $('#username').attr('readonly', true);

                        console.log(res.akses);

                        // Reset semua checkbox, hapus status "checked"
                        $('input[type="checkbox"]').prop('checked',
                            false); // Reset checkbox yang sudah tercentang

                        // Tandai checkbox berdasarkan data akses
                        res.akses.forEach(function(akses) {
                            // Pastikan idcabang sesuai dengan id checkbox
                            $('#' + akses.idcabang).prop('checked',
                                true); // Tandai checkbox dengan idcabang yang cocok
                        });
                    }
                });

            });
        }

        function getAllUser() {
            $('#dt_user').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                retrieve: true,
                // destroy: true,
                // info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: "{{ route('getUser') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'divisi',
                        name: 'divisi'
                    },

                ],
            });
        };

        function getKodeUser() {
            $.ajax({
                type: "get",
                url: "{{ route('getKodeUser') }}",
                dataType: "json",
                success: function(res) {
                    $('#iduser').val(res.iduser)
                }
            });
        }


        function getKodeCabang() {
            $.ajax({
                type: "get",
                url: "{{ route('getKodeCabang') }}",
                dataType: "json",
                success: function(res) {
                    $('#stgcabang').val(res.stgcabang)
                }
            });
        }

        $('#btnAddCabang').click(function(e) {
            e.preventDefault(); // Mencegah form submit secara default

            var form = $('#formCabang').serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('addCabang') }}",
                data: form,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Mengambil token CSRF dari meta tag
                },
                success: function(res) {
                    if (res.status == 500) {
                        $.each(res.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "berhasil buat cabang baru"
                        });
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        });

        $('#btnAddUser').click(function(e) {
            e.preventDefault(); // Mencegah form submit secara default

            var form = $('#formUser').serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('addUser') }}",
                data: form,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Mengambil token CSRF dari meta tag
                },
                success: function(res) {
                    if (res.status == 500) {
                        $.each(res.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "berhasil menambah user"
                        });
                        $('#dt_user').DataTable().ajax.reload();
                        $('#modalUser').modal('hide')

                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        });


        $('#btnUpdateUser').click(function(e) {
            e.preventDefault(); // Mencegah form submit secara default

            var form = $('#formUser').serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('updateUser') }}",
                data: form,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Mengambil token CSRF dari meta tag
                },
                success: function(res) {
                    if (res.status == 500) {
                        $.each(res.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else if (res.status == 200) {
                        Toast.fire({
                            icon: "success",
                            title: "berhasil update user"
                        });
                        $('#dt_user').DataTable().ajax.reload();

                        // $('#modalUser').modal('hide')

                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        });
    </script>
@endpush()
