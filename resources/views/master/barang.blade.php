@extends('layouts.base')

@section('title')
    Barang
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Master Barang</li>
        </ol>
    </nav>

    <div class="page-content">
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-md-7 col-xs-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4 class="card-title">Multiple Column</h4>
                        </div> --}}
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" id="formBarang">
                                    @csrf
                                    <div class="row">
                                        <h4>Detail Barang : <span id="titleDetail" class="badge bg-success"></span>
                                        </h4>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Kode Barang</label>
                                                <input type="text" id="ipt_brgid" class="form-control frmbarang"
                                                    name="ipt_brgid" placeholder="Kode barang akan terisi secara otomatis"
                                                    readonly>
                                                <span class="text-danger error ipt_brgid_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Nama Barang
                                                    <span class="text-danger " style="margin-left: 5px">*
                                                    </span>
                                                    <span class="text-primary small" style="margin-left: 10px"
                                                        href="#" id="btnTambahVarian">Tambah Varian
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control frmbarang" placeholder=""
                                                    name="ipt_brgnama" id="ipt_brgnama">
                                                <span class="text-danger error ipt_brgnama_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Harga Jual<span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span><span class="text-primary small" style="margin-left: 10px"
                                                        href="#" id="btnRiwayatUpdate">riwayat update
                                                    </span></label>
                                                <input type="text" class="form-control frmbarang hitungan" placeholder=""
                                                    name="ipt_brghargatemp" id="ipt_brghargatemp">
                                                <input type="text" class="form-control frmbarang " placeholder=""
                                                    name="ipt_brgharga" id="ipt_brgharga" style="display: none">
                                                <span class="text-danger error ipt_brgharga_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Satuan <span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span></label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="ipt_brgsatuan" name="ipt_brgsatuan">
                                                        @foreach ($satuan as $datas)
                                                            <option value="{{ $datas->bsatid }}">{{ $datas->bsatnama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error ipt_brgsatuan_error"></span>
                                                </fieldset>


                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Supplier</label>
                                                <input type="text" class="form-control frmbarang" placeholder=""
                                                    name="ipt_brgsupp" id="ipt_brgsupp">
                                                <span class="text-danger error brgsupp_error"></span>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Jenis</label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="ipt_brgjenis" name="ipt_brgjenis">
                                                        @foreach ($jenis as $datas)
                                                            <option value="{{ $datas->brjid }}">{{ $datas->brjnama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error ipt_brgsatuan_error"></span>
                                                </fieldset>
                                                {{-- <input type="text" class="form-control frmbarang" placeholder=""
                                                    name="ipt_brgjenis" id="ipt_brgjenis"> --}}
                                                <span class="text-danger error brgjenis_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Sumber Bahan</label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="brggudang" name="brggudang">
                                                        @foreach ($gudang as $gudangs)
                                                            <option value="{{ $gudangs->gudangid }}">
                                                                {{ $gudangs->gudangn }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error ipt_brgsatuan_error"></span>
                                                </fieldset>
                                                {{-- <input type="text" class="form-control frmbarang" placeholder=""
                                                    name="ipt_brgjenis" id="ipt_brgjenis"> --}}
                                                <span class="text-danger error brgjenis_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 ">
                                            <div class="form-check form-switch ">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="ipt_brgstatus" id="ipt_brgstatus" checked>
                                                <label class="form-check-label" for="flexSwitchCheckChecked">
                                                    status</label>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1"
                                                id="btnSimpanBarang">Simpan</button>
                                            <button type="button" class="btn btn-warning me-1 mb-1"
                                                id="btnUpdateBarang">Update</button>
                                            <button type="button" class="btn btn-danger me-1 mb-1"
                                                id="btnDeleteBarang">Delete</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1"
                                                id="btnResetBarang">Reset</button>
                                        </div>
                                </form>

                                <hr class="mt-3">
                                <form class="form" id="formBahanDigunakan">
                                    @csrf
                                    <h4>Bahan yang digunakan</h4>
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonBahan">
                                        <button id="btnTambahBahanBarang" class="col-md-2 col-xs-1 btn btn-primary"
                                            title="Tambah/Update Bahan" type="submit"><i
                                                class="bi bi-plus-circle"></i></button>
                                        {{-- <button id="" class="col-md-2 col-xs-1" title="Hapus Bahan"><i
                                                class="bi bi-trash"></i></button>
                                        <button id="btnResetFormBahan" class="col-md-2 col-xs-1" title="Reset form"
                                            type="button"><i class="bi bi-bootstrap-reboot"></i></button> --}}

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h6>Nama Bahan</h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="text" class="form-control frmbahan" id="bhnid"
                                                    name="bhnid" placeholder="Klik icon untuk cari bahan" readonly
                                                    style="display: none">
                                                <input type="text" class="form-control frmbahan" id="bhannama"
                                                    name="bhannama" placeholder="Klik icon untuk cari bahan" readonly>
                                                <input type="text" class="form-control frmbahan" id="bhansatuan"
                                                    name="bhansatuan" placeholder="Klik icon untuk cari bahan" readonly
                                                    style="display: none">
                                                <input type="text" class="form-control frmbahan" id="bhohasil"
                                                    name="bhohasil" placeholder="Klik icon untuk cari bahan" readonly
                                                    style="display: none">

                                                <input type="text" class="form-control frmbahan" name="bhanbarang"
                                                    id="bhanbarang" style="display: none" readonly>

                                                <span class="text-danger error bhannama_error"></span>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-search" id="iconSearchBahan"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6>Kuantiti</h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="text" class="form-control frmbahan" id="bhankuantiti"
                                                    name="bhankuantiti">

                                                <span class="text-danger error bhankuantiti_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="dt_barang_bahan">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Kuantiti</th>
                                                <th>Satuan</th>
                                                <th>Jenis</th>
                                                <th>Action</th>
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
            <div class="col-md-5 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4>List Barang</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_barang">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Sumber bahan</th>
                                            <th>Status</th>
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
    </section>
    <!-- // Basic multiple Column Form section end -->
    </div>

    {{-- MODAL --}}
    <!--Basic Modal -->
    <div class="modal fade text-left" id="modalBahanBarang" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahan" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-left" id="modalVarian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalVarian"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="body-varian"></div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $(".frmbahan").val('')
            $(".frmbarang").val('')
            $('#btnUpdateBarang').css('display', 'none');
            $('#btnDeleteBarang').css('display', 'none');

            getAllBarang()
            klikTabelBarang()



        });

        let crud = 'c';

        var switchStatus = '1';
        $("#ipt_brgstatus").on('change', function() {
            if ($(this).is(':checked')) {
                switchStatus = '1';
            } else {
                switchStatus = '0';

            }
        });

        function getAllBahan() {
            $('#dt_bahan').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                retrieve: true,
                // destroy: true,
                // info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: "{{ url('{cabang}/master/getAllBahan') }}".replace('{cabang}', cabang),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    }, {
                        data: 'bhnnama',
                        name: 'nama'
                    },
                    {
                        data: 'satnama',
                        name: 'satuan'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },

                ],
            });
        };

        function getAllBarang() {
            var groupColumn = 4;
            var table = $('#dt_barang').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                scrollY: '400px',
                // destroy: true,
                // info: false,
                // serverSide: true,
                autoWidth: true,
                ajax: "{{ url('{cabang}/master/getAllBarang') }}".replace('{cabang}', cabang),
                columns: [{
                        data: 'brgnama',
                        name: 'nama'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                    {
                        data: 'gudangn',
                        name: 'gudangn'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'brjnama',
                        name: 'brjnama'
                    },

                ],
                columnDefs: [{
                    visible: false,
                    targets: groupColumn
                }],
                order: [
                    [groupColumn, 'asc']
                ],
                displayLength: 25,
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(groupColumn, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group bg-info text-white"><td colspan="7 ">' + group +
                                '</td></tr>'
                            );
                            last = group;
                        }
                    });
                }
            });
        };

        function getBahanBarang(id) {
            $('#dt_barang_bahan').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                destroy: true,
                info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: {
                    url: "{{ route('getBarangBahan', ['cabang' => '__cabang__']) }}".replace('__cabang__', cabang),
                    data: function(d) {
                        d.brgid = id;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'bhannama',
                        name: 'nama'
                    },
                    {
                        data: 'kuantitix',
                        name: 'kuantiti'
                    },
                    {
                        data: 'satuans',
                        name: 'satuans'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
            });
        };

        $(function() {



            $("#formBarang").on('submit', function(e) {
                e.preventDefault();
                var a = new FormData(this);
                a.append('cabang', cabang);

                if (crud == 'c') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('addBarang', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__', cabang),
                        data: a,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {
                            $(document).find('span.error').text('');
                        },
                        success: function(res) {
                            if (res.status == 500) {
                                $.each(res.error, function(prefix, val) {
                                    $('span.' + prefix + '_error').text(val[0]);
                                });
                            } else {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Berhasil Menambah Barang !',
                                    showConfirmButton: false,
                                    timer: 1500
                                })

                                $('#ipt_brghargatemp').val('')
                                $('#ipt_brgnama').val('')

                                $('#iptNamaManual').val('')
                                $('#iptKetManual').val('')
                                $('#iptKtgManual').val('')



                                $('#tmid').val('')
                                $('#dt_barang').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {}
                    });
                } else if (crud == 'u') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updateBarang', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__', cabang),
                        data: a,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {
                            $(document).find('span.error').text('');
                        },
                        success: function(res) {
                            if (res.status == 500) {
                                $.each(res.error, function(prefix, val) {
                                    $('span.' + prefix + '_error').text(val[0]);
                                });
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: res.pesan
                                });
                                $('#iptNamaManual').val('')
                                $('#iptKetManual').val('')
                                $('#iptKtgManual').val('')
                                $('#tmid').val('')
                                $('#dt_barang').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {}
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Perintah tidak dikenali !"
                    });
                }


            });
        });


        $(function() {

            $("#formBarang").on('click', '#btnUpdateBarang', function(e) {
                e.preventDefault();

                var form = $("#formBarang")[0]; // Mengacu pada elemen form HTML
                var a = new FormData(form);

                if ($('#ipt_brgid').val() == '') {
                    Toast.fire({
                        icon: "error",
                        title: "Tentukan barang yang akan diupdate !"
                    });
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('updateBarang', ['cabang' => '__cabang__']) }}".replace(
                        '__cabang__', cabang),
                    data: a,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    beforeSend: function() {
                        $(document).find('span.error').text('');
                    },
                    success: function(res) {
                        if (res.status == 500) {
                            $.each(res.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Update Barang"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_barang').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {}
                });

            });
        });


        $('#btnDeleteBarang').click(function(e) {
            e.preventDefault();

            var form = $("#formBarang")[0]; // Mengacu pada elemen form HTML
            var a = new FormData(form);


            Swal.fire({
                title: "Yakin menghapus barang ?",
                text: "barang yang dihapus tidak akan bisa dikembalikan !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yeaah, hapus saja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($('#ipt_brgid').val() == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Tentukan barang yang akan dihapus !"
                        });
                    }
                    $.ajax({
                        type: "POST",
                        url: "{{ route('deleteBarang', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__', cabang),
                        data: a,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {
                            $(document).find('span.error').text('');
                        },
                        success: function(res) {
                            if (res.status == 500) {
                                Toast.fire({
                                    icon: "error",
                                    title: "gagal menghapus Barang"
                                });
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Berhasil menghapus Barang"
                                });
                                // getBahanBarang($('#ipt_brgid'))
                                $('#dt_barang').DataTable().ajax.reload();
                                $('#dt_barang_bahan').DataTable().ajax.reload();

                            }
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: "error",
                                title: "gagal menghapus Barang"
                            });
                        }
                    });
                }


            });


        });



        $(function() {

            $("#formBahanDigunakan").on('submit', function(e) {
                e.preventDefault();
                var a = new FormData(this);

                if ($('#ipt_brgid').val() == '') {
                    Toast.fire({
                        icon: "error",
                        title: "Tentukan barang yang akan ditambah !"
                    });
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('addBarangBahan', ['cabang' => '__cabang__']) }}".replace(
                        '__cabang__', cabang),
                    data: a,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    beforeSend: function() {
                        $(document).find('span.error').text('');
                    },
                    success: function(res) {
                        if (res.status == 500) {
                            $.each(res.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });

                            Toast.fire({
                                icon: "error",
                                title: "gagal menambah, cek lagi"
                            });

                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Bahan"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_barang_bahan').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });

            });
        });

        function klikTabelBarang() {

            var table = $('#dt_barang').DataTable();
            table.on('click', 'tbody tr', function() {

                $('#btnUpdateBarang').css('display', '');
                $('#btnDeleteBarang').css('display', '');

                $('#btnSimpanBarang').css('display', 'none');

                $('#ipt_brgid').val(table.row(this).data().brgid);
                $('#ipt_brgnama').val(table.row(this).data().brgnama);
                $('#ipt_brgharga').val(table.row(this).data().brgharga);
                $('#ipt_brghargatemp').val(table.row(this).data().brgharga);

                $('#ipt_brgsupp').val(table.row(this).data().brgsupp);
                $('#ipt_brgsatuan').val(table.row(this).data().brgsatuan);
                $('#ipt_brgjenis').val(table.row(this).data().brgjenis);
                $('#brggudang').val(table.row(this).data().brggudang);


                if (table.row(this).data().brgstatus == '1') {
                    $("#ipt_brgstatus").prop('checked', true)
                } else {
                    $("#ipt_brgstatus").prop('checked', false)
                }

                $('#bhanbarang').val(table.row(this).data().brgid)

                getBahanBarang(table.row(this).data().brgid)

                $('#titleDetail').html(table.row(this).data().brgnama)
                $('.error').html('')

                crud = 'u'
                // $('#dt_barang_bahan').DataTable().ajax.reload();



            });
        }

        function klikTabelBarangBahan() {

            var table = $('#dt_bahan').DataTable();
            table.off('click', 'tbody tr');
            table.on('click', 'tbody tr', function() {
                $('#bhnid').val(table.row(this).data().bhnid);
                $('#bhannama').val(table.row(this).data().bhnnama);
                $('#bhansatuan').val(table.row(this).data().bhnsatuan);
                $('#bhohasil').val(table.row(this).data().bhohasil);

                cekBarangBahanDouble($('#ipt_brgid').val(), table.row(this).data().bhnid);
                $('#modalBahanBarang').modal("hide");
                $('#bhankuantiti').focus();
            });
        }

        function cekBarangBahanDouble(bhanbarang, bhnid) {
            $.ajax({
                type: "GET",
                url: "{{ route('cekBarangBahanDouble', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: {
                    'bhanbarang': bhanbarang,
                    'bhnid': bhnid
                },
                dataType: "json",
                success: function(res) {
                    if (res.data.length > 0) {
                        Toast.fire({
                            icon: "error",
                            title: "Bahan sudah ada , masukkan bahan yg lain"
                        });
                        $(".frmbahan").val('')

                    }
                }
            });
        }

        $('#ipt_brghargatemp').on('input', function() {
            let real = this.value.replace(/\./g, '')
            $('#ipt_brgharga').val(real)
        });


        // untuk click2 button
        $('#btnRiwayatUpdate').click(function(e) {
            Toast.fire({
                icon: "error",
                title: "Cooming Soon"
            });

        });

        $('#btnResetFormBahan').click(function(e) {
            e.preventDefault();
            $(".frmbahan").val('')

            $('#btnUpdateBarang').css('display', 'none');
            $('#btnSimpanBarang').css('display', '');

            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

            crud = 'c'


        });

        $('#btnResetBarang').click(function(e) {
            $(".frmbarang").val('')
            $(".frmbahan").val('')
            $('#titleDetail').html('')
            $('#ipt_brgid').val('')

            $('#btnUpdateBarang').css('display', 'none');
            $('#btnSimpanBarang').css('display', '');


            crud = 'c'
            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

            getBahanBarang('Kosongan')


        });

        $('#iconSearchBahan').click(function(e) {
            getAllBahan()
            klikTabelBarangBahan()
            $('#modalBahanBarang').modal("show")
            $('#titleModal').html("Bahan untuk " + $("#ipt_brgnama").val())
        });

        $('#btnTambahVarian').click(function(e) {
            if ($('#ipt_brgid').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih Barang terlebih dahulu"
                });
            } else {
                $('#titleModalVarian').html("Tambah Varian " + $('#ipt_brgnama').val())
                $('#modalVarian').modal("show")
            }
        });


        $(document).on('blur', '.bhankuantiti_edit', function() {
            if (this.value === '') {
                this.value = '1';
                Toast.fire({
                    icon: "error",
                    title: "Jumlah Minimal 1 "
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('updateBarangBahan', ['cabang' => '__cabang__']) }}".replace(
                        '__cabang__', cabang),
                    data: {
                        'bhanid': $(this).attr('id'),
                        'bhankuantiti': $(this).val(),
                    },
                    dataType: "json",
                    success: function(res) {
                        Toast.fire({
                            icon: "success",
                            title: "Kuantiti berhasil di update"
                        });
                    },
                    error: function(xhr) {
                        console.log("Status Code: " + xhr.status);
                        console.log("Status Text: " + xhr.statusText);
                        console.log("Response Text: " + xhr.responseText);
                    }
                });
            }
        });

        $(document).on('click', '.barangbahan_delete', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('deleteBarangBahan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: {
                    'bhanid': $(this).attr('id'),
                },
                dataType: "json",
                success: function(res) {
                    Toast.fire({
                        icon: "success",
                        title: "Barang Bahan berhasil di hapus"
                    });
                    $('#dt_barang_bahan').DataTable().ajax.reload();
                }
            });
        });
    </script>
@endpush
