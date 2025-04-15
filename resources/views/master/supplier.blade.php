@extends('layouts.base')

@section('title')
    Supplier
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Master Supplier</li>
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
                                <form class="form" id="formSupplier">
                                    @csrf
                                    <div class="row">
                                        <h4>Detail Supplier : <span id="titleDetail" class="badge bg-success"></span>
                                        </h4>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Kode Supplier</label>
                                                <input type="text" id="ipt_suppid" class="form-control frmbarang"
                                                    name="ipt_suppid" placeholder="Kode barang akan terisi secara otomatis"
                                                    readonly>
                                                <span class="text-danger error ipt_suppid_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Nama Supplier
                                                    <span class="text-danger " style="margin-left: 5px">*
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control frmbarang" placeholder=""
                                                    name="ipt_suppnama" id="ipt_suppnama">
                                                <span class="text-danger error ipt_suppnama_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Telepon</label>
                                                <input type="text" id="ipt_supptelp" class="form-control frmbarang"
                                                    name="ipt_supptelp">
                                                <span class="text-danger error ipt_supptelp_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Alamat</label>
                                                <input type="text" id="ipt_suppalamat" class="form-control frmbarang"
                                                    name="ipt_suppalamat">
                                                <span class="text-danger error ipt_suppalamat_error "></span>
                                            </div>
                                        </div>


                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1"
                                                id="btnResetBarang">Reset</button>
                                        </div>
                                </form>

                                <hr class="mt-3">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4>List Supplier</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_supplier">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Telp</th>
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
    </section>
    <!-- // Basic multiple Column Form section end -->
    </div>

    {{-- MODAL --}}
    <!--Basic Modal -->
    <div class="modal fade text-left" id="modalBahanBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahan" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahan" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Satuan</th>
                                    <th>Satuan</th>
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
            getAllSupplier()
            klikTabelSupplier()



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



        function getAllSupplier() {
            $('#dt_supplier').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                destroy: true,
                info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: {
                    url: "{{ route('getAllSupplier') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'suppnama',
                        name: 'kuantiti'
                    },

                    {
                        data: 'supptelp',
                        name: 'satuan'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
            });
        };



        $(function() {



            $("#formSupplier").on('submit', function(e) {
                e.preventDefault();
                var a = new FormData(this);

                if (crud == 'c') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('addSupplier') }}",
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
                                    title: "Berhasil Menambah Supplier"
                                });
                                $('#formSupplier')[0].reset();
                                $('#dt_supplier').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {}
                    });
                } else if (crud == 'u') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updateSupplier', ['cabang' => '__cabang__']) }}".replace(
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
                                $('#formSupplier')[0].reset();
                                $('#dt_supplier').DataTable().ajax.reload();
                                $('#titleDetail').html('')
                                crud = 'c'


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





        function klikTabelSupplier() {

            var table = $('#dt_supplier').DataTable();
            table.on('click', 'tbody tr', function() {

                $('#ipt_suppid').val(table.row(this).data().suppid);
                $('#ipt_suppnama').val(table.row(this).data().suppnama);
                $('#ipt_suppalamat').val(table.row(this).data().suppalamat);
                $('#ipt_supptelp').val(table.row(this).data().supptelp);





                $('#titleDetail').html(table.row(this).data().suppnama)
                $('.error').html('')

                crud = 'u'



            });
        }

        function klikTabelBarangBahan() {

            var table = $('#dt_bahan').DataTable();
            table.off('click', 'tbody tr');
            table.on('click', 'tbody tr', function() {
                $('#bhnid').val(table.row(this).data().bhnid);
                $('#bhannama').val(table.row(this).data().bhnnama);
                $('#bhansatuan').val(table.row(this).data().bhnsatuan);
                // alert(table.row(this).data().bhnsatuan)
                cekBarangBahanDouble($('#ipt_suppid').val(), table.row(this).data().bhnid);
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
            $('#ipt_suppid').val('')
            crud = 'c'
            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

            getBahanBarang('Kosongan')


        });
    </script>
@endpush
