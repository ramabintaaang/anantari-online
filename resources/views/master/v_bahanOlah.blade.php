@extends('layouts.base')

@section('title')
    Master Bahan Olah
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Master Bahan Olah</li>
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
                                <form class="form" id="formbahanolah">
                                    @csrf
                                    <div class="row">
                                        <h4>Detail bahan olah : <span id="titleDetail" class="badge bg-success"></span>
                                        </h4>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Kode bahan</label>
                                                <input type="text" id="bhoid" class="form-control frmbahan"
                                                    name="bhoid" placeholder="Kode bahan akan terisi secara otomatis"
                                                    readonly>
                                                <span class="text-danger error bhoid_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Nama bahan<span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhonama" id="bhonama">
                                                <span class="text-danger error bhonama_error "></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Satuan <span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span></label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="bhosatuan" name="bhosatuan">
                                                        @foreach ($satuans as $x)
                                                            <option value="{{ $x->satid }}">{{ $x->satnama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error bhosatuan_error"></span>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Saldo Saat ini</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhosaldo" id="bhosaldo" readonly>
                                                <span class="text-danger bhosaldo_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Minimal Saldo</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhomin" id="bhomin">
                                                <span class="text-danger bhomin_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Maksimal Saldo</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhomax" id="bhomax">
                                                <span class="text-danger bhomax_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Dalam 1x pembuatan, menghasilkan</label>
                                                <input type="text" class="form-control frmbahan" placeholder=""
                                                    name="bhohasil" id="bhohasil">
                                                <span class="text-danger bhohasil_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Buatkan dari gudang <span class="text-danger "
                                                        style="margin-left: 5px">*
                                                    </span></label>
                                                <fieldset class="form-group">
                                                    <select class="form-select " id="bhogudang" name="bhogudang">
                                                        @foreach ($gudang as $x)
                                                            <option value="{{ $x->gudangid }}">{{ $x->gudangn }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error bhogudang_error"></span>
                                                </fieldset>


                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                            <button type="button" class="btn btn-danger me-1 mb-1"
                                                id="btnHapus">Hapus</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1"
                                                id="btnResetbahan">Reset / buat bahan olah baru</button>
                                        </div>
                                </form>

                                <hr class="mt-3">
                                <form class="form" id="formBahanOlahUsed">
                                    @csrf
                                    <h4>Bahan yang digunakan</h4>
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonBahan">
                                        <button id="btnTambahBahanbahan" class="btn btn-primary col-md-2 col-xs-1"
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
                                    <table class="table table-hover mb-0" id="dt_bahan_used">
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
                            <h4>List Bahan yang diolah</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_AllBahanOlah">
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

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahanOlah" style="width:100%">
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

            $('#btnHapus').css("display", "none")

            dtAllBahanOlah()
            klikDtAllBahanOlah()




        });

        let crud = null;


        $('#btnHapus').click(function(e) {
            Swal.fire({
                title: "Yakin hapus barang olah?",
                showConfirmButton: false,
                showDenyButton: true,
                showCancelButton: true,
                denyButtonText: `Hapus`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    alert("nothing")

                } else if (result.isDenied) {
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('deleteBahanOlah', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__',
                            cabang),
                        data: {
                            'bhoid': $('#bhoid').val()
                        },
                        dataType: "json",
                        success: function(res) {
                            if (res.status == 200) {
                                Toast.fire({
                                    icon: "success",
                                    title: res.pesan
                                });
                                $('#bhoid').val('')
                                $('#bhonama').val('')
                                $('#bhosaldo').val('')
                                $('#bhohasil').val('')
                                $('#dt_AllBahanOlah').DataTable().ajax.reload();
                                crud = 'c'
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: res.pesan
                                });
                            }
                        }
                    });
                }
            });

        });
        var switchStatus = '1';
        $("#ipt_brgstatus").on('change', function() {
            if ($(this).is(':checked')) {
                switchStatus = '1';
            } else {
                switchStatus = '0';

            }
        });



        function dtAllBahanOlah() {
            $('#dt_AllBahanOlah').DataTable({

                scrollCollapse: true,
                paging: true,
                processing: true,
                // destroy: true,
                // info: false,
                // serverSide: true,
                autoWidth: true,
                ajax: "{{ route('dtAllBahanOlah', ['cabang' => '__cabang__']) }}".replace('__cabang__', cabang),

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    }, {
                        data: 'bhonama',
                        name: 'nama'
                    },
                    {
                        data: 'satuans',
                        name: 'satuans'
                    },
                    {
                        data: 'bhomin',
                        name: 'bhomin'
                    },
                    {
                        data: 'bhosaldo',
                        name: 'bhosaldo'
                    },

                ],
            });
        };

        function getBahanOlahUsed(id) {
            $('#dt_bahan_used').DataTable({
                scrollCollapse: true,
                paging: true,
                processing: true,
                destroy: true,
                info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: {
                    url: "{{ route('getBahanOlahUsed', ['cabang' => '__cabang__']) }}".replace('__cabang__',
                        cabang),
                    data: function(d) {
                        d.bhoid = id;
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
                        name: 'kuantitix'
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

            $("#formbahanolah").on('submit', function(e) {
                e.preventDefault();
                var a = new FormData(this);

                if ($('#bhoid').val() == '' || $('#bhoid').val() == null) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('addBahanOlah', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__',
                            cabang),
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
                                    title: "Bahan Berhasil ditambah !"
                                });

                                $('#dt_AllBahanOlah').DataTable().ajax.reload();
                                $('.frmbhana').val('')

                            }
                        },
                        error: function(xhr) {}
                    });
                } else if ($('#bhoid').val() != '' || $('#bhoid').val() != null) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updateBahanOlah', ['cabang' => '__cabang__']) }}".replace(
                            '__cabang__',
                            cabang),
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
                                    title: "Bahan berhasil di update!",
                                });
                                $('#iptNamaManual').val('')
                                $('#iptKetManual').val('')
                                $('#iptKtgManual').val('')
                                $('#tmid').val('')
                                $('#dt_AllBahanOlah').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {}
                    });
                }

            });
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
                ajax: "{{ route('getAllBahan', ['cabang' => '__cabang__']) }}".replace('__cabang__', cabang),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    }, {
                        data: 'bhnnama',
                        name: 'nama'
                    },
                    {
                        data: 'satuans',
                        name: 'satuan'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },

                ],
            });
        };



        $(function() {

            $("#formBahanOlahUsed").on('submit', function(e) {
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
                    url: "{{ route('addBahanOlahUsed', ['cabang' => '__cabang__']) }}".replace(
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
                                title: res.pesan
                            });
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Bahan"
                            });
                            $('#bhnid').val('')
                            $('#bhannama').val('')
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_bahan_used').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });

            });
        });

        function klikDtAllBahanOlah() {

            var table = $('#dt_AllBahanOlah').DataTable();
            table.on('click', 'tbody tr', function() {

                $('#dt_AllBahanOlah tbody tr').removeClass('selected');
                $(this).addClass('selected');

                crud = 'u';
                $('#bhoid').val(table.row(this).data().bhoid);
                $('#bhonama').val(table.row(this).data().bhonama);
                $('#bhosatuan').val(table.row(this).data().bhosatuan);
                $('#bhosaldo').val(table.row(this).data().bhosaldo);
                $('#bhomin').val(table.row(this).data().bhomin);
                $('#bhomax').val(table.row(this).data().bhomax);
                $('#bhohasil').val(table.row(this).data().bhohasil);
                $('#bhogudang').val(table.row(this).data().bhogudang);


                $('#bhanbahan').val(table.row(this).data().bhoid)

                getBahanOlahUsed(table.row(this).data().bhoid)

                $('#titleDetail').css("display", "")
                $('#titleDetail').html(table.row(this).data().bhonama)
                $('.error').html('')

                $('#btnHapus').css("display", "")

                // $('#dt_bahan_used').DataTable().ajax.reload();



            });
        }

        function klikTabelBarangBahan() {

            var table = $('#dt_bahan').DataTable();
            table.on('click', 'tbody tr', function() {
                $('#bhnid').val(table.row(this).data().bhnid);
                $('#bhannama').val(table.row(this).data().bhnnama);
                $('#bhansatuan').val(table.row(this).data().bhnsatuan);

                $('#modalBahanBarang').modal("hide")
                $('#bhankuantiti').focus()


            });
        }

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

            $('#btnHapus').css("display", "none")


        });

        $('#btnResetBarang').click(function(e) {
            $(".frmbarang").val('')
            $(".frmbahan").val('')
            $('#titleDetail').html('')

            Toast.fire({
                icon: "success",
                title: "Berhasil Reset form"
            });

        });

        $('#iconSearchBahan').click(function(e) {
            if ($('#bhoid').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih bahan olah dahulu !"
                });
            } else {
                getAllBahan()
                klikTabelBarangBahan()
                $('#modalBahanBarang').modal("show")
                $('#titleModal').html("Bahan untuk " + $("#bhonama").val())
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
                    $('#dt_bahan_used').DataTable().ajax.reload();
                }
            });
        });

        $('#btnResetbahan').click(function(e) {
            crud = 'c';
            // $('#bhoid').val()
            $('#titleDetail').css("display", "none")
            $('#btnHapus').css("display", "none")
            $('#dt_bahan_used').DataTable().ajax.reload();


        });
    </script>
@endpush
