@extends('layouts.base')

@section('title')
    Master Bahan
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Master Bahan</li>
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
                                <form class="form" id="formbahan">
                                    @csrf
                                    <div class="row">
                                        <h4>Detail bahan : <span id="titleDetail" class="badge bg-success"></span>
                                        </h4>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Kode bahan</label>
                                                <input type="text" id="bhnid" class="form-control frmbahan"
                                                    name="bhnid" placeholder="Kode bahan akan terisi secara otomatis"
                                                    readonly>
                                                <span class="text-danger error bhnid_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Nama bahan<span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhnnama" id="bhnnama">
                                                <span class="text-danger error bhnnama_error "></span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Harga Jual<span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span><span class="text-primary small" style="margin-left: 10px"
                                                        href="#" id="btnRiwayatUpdate">riwayat update
                                                    </span></label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="ipt_brgharga" id="ipt_brgharga">
                                                <span class="text-danger error ipt_brgharga_error"></span>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Satuan <span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span></label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="bhnsatuan" name="bhnsatuan">
                                                        @foreach ($satuan as $x)
                                                            <option value="{{ $x->satid }}">{{ $x->satnama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error bhnsatuan_error"></span>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Saldo Saat ini</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhnsaldo" id="bhnsaldo" readonly>
                                                <span class="text-danger bhnsaldo_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Minimal Saldo</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhnmin" id="bhnmin">
                                                <span class="text-danger bhnmin_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Maksimal Saldo</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhnmax" id="bhnmax">
                                                <span class="text-danger bhnmax_error"></span>
                                            </div>
                                        </div>


                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1"
                                                id="btnResetbahan">Reset</button>
                                        </div>
                                </form>

                                <hr class="mt-3">
                                {{-- <form class="form" id="formBahanDigunakan">
                                    @csrf
                                    <h4>Bahan yang digunakan</h4>
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonBahan">
                                        <button id="btnTambahBahanbahan" class="col-md-2 col-xs-1"
                                            title="Tambah/Update Bahan" type="submit"><i
                                                class="bi bi-plus-circle"></i></button>
                                        <button id="" class="col-md-2 col-xs-1" title="Hapus Bahan"><i
                                                class="bi bi-trash"></i></button>
                                        <button id="btnResetFormBahan" class="col-md-2 col-xs-1" title="Reset form"
                                            type="button"><i class="bi bi-bootstrap-reboot"></i></button>

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

                                                <input type="text" class="form-control frmbahan" name="bhanbahan"
                                                    id="bhanbahan" style="display: none" readonly>

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
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4>List Bahan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_AllBahan">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Satuan</th>
                                            <th>batas min</th>
                                            <th>Saldo</th>

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
    </div>
    </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $(".frmbahan").val('')
            $(".frmbarang").val('')


            dtAllBahan()
            klikDtAllBahan()
            crud = 'c'


        });

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
                url: "{{ route('getAllBahan', ['cabang' => '__cabang__']) }}".replace('__cabang__', cabang),

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    }, {
                        data: 'bhnnama',
                        name: 'nama'
                    },
                    {
                        data: 'bhnsatuan',
                        name: 'satuan'
                    },

                ],
            });
        };

        function dtAllBahan() {
            $('#dt_AllBahan').DataTable({

                scrollCollapse: true,
                paging: true,
                processing: true,
                // destroy: true,
                // info: false,
                // serverSide: true,
                autoWidth: true,
                ajax: "{{ route('dtAllBahan', ['cabang' => '__cabang__']) }}".replace('__cabang__', cabang),
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
                        name: 'satnama'
                    },
                    {
                        data: 'bhnmin',
                        name: 'bhnmin'
                    },
                    {
                        data: 'bhnsaldo',
                        name: 'bhnsaldo'
                    },

                ],
            });
        };

        function getBahanBarang(id) {
            $('#dt_barang_bahan').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                destroy: true,
                info: false,
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
                        data: 'bhnnama',
                        name: 'nama'
                    },
                    {
                        data: 'bhankuantiti',
                        name: 'kuantiti'
                    },
                    {
                        data: 'bhansatuan',
                        name: 'satuan'
                    },
                    // {
                    //     data: 'bhan',
                    //     name: 'status'
                    // },

                ],
            });
        };

        $(function() {

            $("#formbahan").on('submit', function(e) {
                e.preventDefault();
                var a = new FormData(this);

                if ($('#bhnid').val() == '') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('addBahan', ['cabang' => '__cabang__']) }}".replace(
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
                                    title: "Gagal !"
                                });
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Bahan Berhasil ditambah !"
                                });

                                $('#dt_AllBahan').DataTable().ajax.reload();
                                $('.frmbhana').val('')
                                $('#bhnnama').val('')

                            }
                        },
                        error: function(xhr) {}
                    });
                } else if ($('#bhnid').val() != '') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updateBahan', ['cabang' => '__cabang__']) }}".replace(
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
                                $('#dt_AllBahan').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {}
                    });
                } else {
                    alert('Error , silahkan refresh')
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
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Bahan"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_bahan').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });

            });
        });

        function klikDtAllBahan() {



            crud = 'u'

            var table = $('#dt_AllBahan').DataTable();
            table.on('click', 'tbody tr', function() {

                $('#dt_AllBahan tbody tr').removeClass('selected');
                $(this).addClass('selected');

                $('#bhnid').val(table.row(this).data().bhnid);
                $('#bhnnama').val(table.row(this).data().bhnnama);
                $('#bhnsatuan').val(table.row(this).data().bhnsatuan);
                $('#bhnsaldo').val(table.row(this).data().bhnsaldo);
                $('#bhnmin').val(table.row(this).data().bhnmin);
                $('#bhnmax').val(table.row(this).data().bhnmax);

                if (table.row(this).data().brgstatus == '1') {
                    $("#ipt_brgstatus").prop('checked', true)
                } else {
                    $("#ipt_brgstatus").prop('checked', false)
                }

                $('#bhanbarang').val(table.row(this).data().brgid)

                getBahanBarang(table.row(this).data().brgid)

                $('#titleDetail').html(table.row(this).data().brgnama)
                $('.error').html('')
                // $('#dt_barang_bahan').DataTable().ajax.reload();



            });
        }

        function klikTabelBarangBahan() {

            var table = $('#dt_bahan').DataTable();
            table.on('click', 'tbody tr', function() {
                $('#bhnid').val(table.row(this).data().bhnid);
                $('#bhannama').val(table.row(this).data().bhnnama);
                $('#bhansatuan').val(table.row(this).data().bhansatuan);


                $('#modalBahanBarang').modal("hide")
                $('#bhankuantiti').focus()

                crud = 'u'


            });
        }


        // untuk click2 button
        $('#btnRiwayatUpdate').click(function(e) {
            alert("cooming soon")

        });

        $('#btnResetFormBahan').click(function(e) {
            $(".frmbahan").val('')
            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

        });

        $('#btnResetbahan').click(function(e) {
            $(".frmbarang").val('')
            $(".frmbahan").val('')
            $('#titleDetail').html('')

            // Menghapus kelas 'selected' dari semua baris
            $('#dt_AllBahan tbody tr').removeClass('selected');

            // Menghilangkan kelas 'selected' dari tombol reset juga
            $(this).removeClass('selected');

            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

        });

        $('#iconSearchBahan').click(function(e) {
            getAllBahan()
            klikTabelBarangBahan()
            $('#modalBahanBarang').modal("show")
            $('#titleModal').html("Bahan untuk " + $("#ipt_brgnama").val())
        });
    </script>
@endpush
