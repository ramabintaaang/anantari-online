<script>
    let divisi = null;

    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelTransaksi()
        klikTabelTransaksi()
        getAllBarang()
        klikTabelModalTransaksid()
        let processedData = [];
        let processedDataEdit = [];
        let processedDataTerpakai = [];

        let rawBahan = [];



        let processedDataVarian = [];
        let mode = null;

        $('#tnsdjumlah').val('');
        getMenuScroll('All')

        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Ambil CSRF token dari meta tag



    });

    function getTabelTransaksi() {
        $('#dt_transaksi').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getTransaksi', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.ipt_tgldari = $('#ipt_tgldari').val(),
                        d.ipt_tglsampai = $('#ipt_tglsampai').val(),
                        d.tnsdivisi = divisi
                }
            },
            columns: [{
                    data: 'id',
                    name: 'kode'
                },
                {
                    data: 'tnsnama',
                    name: 'satuan'
                },
                {
                    data: 'created_at',
                    name: 'created'
                },
                {
                    data: 'tnstotal',
                    name: 'satuan'
                },
                {
                    data: 'user_created',
                    name: 'user'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
        });
    };

    $('#barangJenis, #namaBarang').on('change input', function(event) {


        // Mendapatkan nilai terbaru dari #barangJenis
        var barangJenis = $('#barangJenis').val();
        // Mendapatkan nilai terbaru dari #namaBarang
        var namaBarang = $('#namaBarang').val();

        // Panggil fungsi getMenuScroll dengan parameter yang sesuai
        getMenuScroll(barangJenis, namaBarang);
    });

    function getMenuScroll(barangJenis, namaBarang) {
        $.ajax({
            type: "get",
            url: "{{ route('getAjaxBarang', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: {
                'barangJenis': barangJenis,
                'namaBarang': namaBarang
            },
            dataType: "json",
            success: function(res) {
                // Bersihkan terlebih dahulu konten yang ada di dalam div layoutBarang
                $('#layoutBarang').empty();

                // Variabel untuk menyimpan jenis barang terakhir
                var lastJenis = null;

                // Lakukan perulangan untuk setiap data dalam res.data
                res.data.forEach(function(data) {
                    var brgid = data.brgid; // Mengambil nilai brgid dari objek data

                    // Jika jenis barang berbeda dengan yang sebelumnya, tambahkan judul baru
                    if (data.brjnama !== lastJenis) {
                        var jenisBarangHtml = `
                        <div class="row mt-3">
                            <div class="col">
                                <h4>${data.brjnama}</h4>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-4 g-4" id="${data.brjnama.toLowerCase()}">
                    `;
                        $('#layoutBarang').append(jenisBarangHtml);
                        lastJenis = data.brjnama; // Perbarui jenis barang terakhir
                    }

                    // Buat elemen HTML untuk card barang
                    var cardHtml = `
                    <div class="col">
                        <div class="card border-primary position-relative" style="margin: 10px 0; padding: 0;" onclick="handleCardClick('${data.brgid}','${data.brgnama}','${data.brgharga}','${data.brggudang}')">
                            <div class="card-box d-flex justify-content-center align-items-center" style="width: 100px; height: 100px; background-color: #6c6c6c; border: 1px solid #0e0ae2; border-radius: 5px;">
                                <h5 class="card-title text-light text-center" style="font-size: 14px; margin: 0;">${data.brgnama}</h5>
                            </div>
                            <p class="text-success" style="margin: 0; text-align: center;">Rp.${data.brgharga}</p>
                        </div>
                    </div>
                `;

                    // Tambahkan elemen card ke dalam div layoutBarang sesuai dengan jenis barang
                    $(`#${data.brjnama.toLowerCase()}`).append(cardHtml);
                    // $('#layoutBarang').css('overflow-y', 'auto');
                });
            }
        });
    }



    function handleCardClick(brgid, brgnama, brgharga, brggudang) {
        mode = null
        $('#tnsdbarangn').val(brgnama)
        $('#tnsdbarang').val(brgid)
        $('#tnsdtotal').val(brgharga);
        $('#tnsdhargaasli').val(brgharga);
        $('#tnsdgudang').val(brggudang)
        $('#tnsdjumlah').val(1)
        $('#tnsdjumlah').focus()

        getBahanFromTransaksid()
        getVarianFromBarang()

        // Atau lakukan tindakan lain sesuai dengan kebutuhan aplikasi Anda
    }

    function getTabelTransaksiDetail(id) {
        var groupColumn = 2; // Kolom ke-2 yang akan digunakan untuk pengelompokan
        var table = $('#dt_transaksid').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            ajax: {
                url: "{{ route('getTransaksiDetail', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.transaksi = id
                }
            },
            columns: [{
                    data: 'brgnama',
                    name: 'nama'
                },

                {
                    data: 'tnsdjumlah',
                    name: 'created'
                },
                {
                    data: 'brjnama',
                    name: 'brjnama'
                },
                {
                    data: 'tnsdtotal',
                    name: 'satuan'
                },
                {
                    data: 'tnsdketerangan',
                    name: 'tnsdketerangan'
                },
                // {
                //     data: 'user_created',
                //     name: 'user'
                // },
                // {
                //     data: 'tnsbid',
                //     name: 'tnsbid'
                // },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
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
                            '<tr class="group bg-warning"><td colspan="7 ">' +
                            group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });

        // Menambahkan event handler untuk baris dalam tabel detail
        $('#dt_transaksid').off('click').on('click', 'tbody tr', function() {
            var table = $('#dt_transaksid').DataTable();
            if (table.row(this).data().tnsdketerangan == null) {
                $('#titleBahand').html(table.row(this).data().tnsdjumlah + '-' + table.row(this).data()
                    .brgnama);
            } else {
                $('#titleBahand').html(table.row(this).data().tnsdjumlah + '-' + table.row(this).data()
                    .brgnama +
                    '-' +
                    table.row(this).data().tnsdketerangan);
            }
            $('.error').html('');
            getTabelTransaksiDetailBahan(table.row(this).data().tnsdid);
        });
    }


    function getTabelTransaksiDetailBahan(id) {
        $('#dt_bahand').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            // retrieve: true,
            destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getTransaksiDetailBahan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.transaksi = id
                }
            },
            columns: [{
                    data: 'bhnnama',
                    name: 'nama'
                },
                {
                    data: 'tnsbjumlah',
                    name: 'tnsbjumlah'
                },
                {
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
        });
    };



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
            ajax: "{{ route('getAllBahan', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
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

    function getAllBarang() {
        $('#dt_modalBarang').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            autoWidth: true,
            destroy: true,
            retrieve: true,
            ajax: "{{ route('getAllBarang', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                }, {
                    data: 'brgnama',
                    name: 'nama'
                },
                {
                    data: 'brgharga',
                    name: 'harga'
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
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getBarangBahan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
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

        $("#formInputTransaksi").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#tnsnama').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Nama pemesan diisi dulu yuk..."
                });
                $('#tnsnama').focus()
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addTransaksi', ['cabang' => '__cabang__']) }}".replace(
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
                                title: "Berhasil Menambah Transaksi"
                            });
                            $('#tnsnama').val('')
                            $('#dt_transaksi').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
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

    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var parent = $(this).data('parent');
        var total = $(this).data('total');
        Swal.fire({
            title: "Hapus pesanan ini ?",
            showConfirmButton: false,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Hapus`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                $.ajax({
                    type: "post",
                    url: "{{ route('deleteTransaksiDetail', ['cabang' => '__cabang__']) }}"
                        .replace(
                            '__cabang__', cabang),
                    data: {
                        'tnsdid': id,
                        'tnsdparent': parent,
                        'tnstotal': total
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // Menambahkan token CSRF ke dalam header setiap permintaan
                        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
                    },
                    success: function(res) {
                        console.log(res.pesan)
                        if (res.status == 200) {
                            Toast.fire({
                                icon: "success",
                                title: res.pesan
                            });
                            $('#formTransaksid')[0].reset();
                            refreshSaldo();
                            $('#dt_transaksid').DataTable().ajax.reload();
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


    $(document).on('click', '.edit-btn', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');
        var barang = $(this).data('barang');
        var barangid = $(this).data('barangid');
        var jumlah = $(this).data('jumlah');
        var total = $(this).data('total');
        var ket = $(this).data('ket');
        var harga = $(this).data('harga');
        var parent = $(this).data('parent');
        var bahan = $(this).data('bahan');
        var tnsbparent = $(this).data('tnsbparent');
        var gudangx = $(this).data('gudang');

        // Mengambil data tnsbid dan mengubahnya menjadi array
        var tnsbidString = $(this).data('tnsbid');
        var tnsbid = tnsbidString ? tnsbidString.split(',') :
    []; // Mengubah menjadi array jika ada data, jika tidak kosong


        // Tampilkan modal
        $('#modalBesar').modal("show")
        $('#titleModalBesar').html("Edit : " + barang)
        initFormEdit(id, jumlah, total, ket, harga, parent, bahan, barangid, tnsbid, tnsbparent, gudangx);
        $('#modalBesar').modal('show');
    });






    function initFormEdit(id, jumlah, total, ket, harga, parent, bahan, barangid, tnsbid, tnsbparent, gudangx) {
        mode = 'edit';
        var tableHTML = `
     <form class="form" id="formTransaksid_edit">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" style="display:none;">
                    <label for="">ID tnsb</label>
                    <input type="text" class="form-control" id="tnsbid_edit" name="tnsbid_edit" value='${tnsbid}' >
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">parent tnsb</label>
                    <input type="text" class="form-control" id="tnsbparent_edit" name="tnsbparent_edit" value='${tnsbparent}' >
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">ID parent</label>
                    <input type="text" class="form-control" id="tnsdparent_edit" name="tnsdparent_edit" value='${parent}' >
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">barangid</label>
                    <input type="text" class="form-control" id="tnsdbarang_edit" name="tnsdbarang_edit" value='${barangid}' >
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">bahan ID</label>
                    <input type="text" class="form-control" id="tnsdbahan_edit" name="tnsdbahan_edit" value='${bahan}' >
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">ID</label>
                    <input type="text" class="form-control" id="tnsdid_edit" name="tnsdid_edit" value='${id}' >
                </div>
                <div class="form-group">
                    <label for="">Jumlah</label>
                    <input type="number" class="form-control" id="tnsdjumlah_edit" name="tnsdjumlah_edit" value='${jumlah}'>
                    <span class="text-danger error tnsdjumlah_edit_error"></span>

                </div>

                

                <div class="form-group" style="display:none;">
                    <label for="">Jumlah</label>
                    <input type="number" class="form-control" id="tnsdjumlah_edit_old" name="tnsdjumlah_edit_old" value='${jumlah}'>
                    <span class="text-danger error tnsdjumlah_edit_error"></span>

                </div>
                 <div class="form-group" style="display:none;">
                    <label for="">gudang</label>
                    <input type="text" class="form-control" id="tnsdgudang_edit" name="tnsdgudang_edit" value='${gudangx}'>

                </div>
                <div class="form-group">
                    <label for="">Keterangan</label>
                    <input type="text" class="form-control" id="tnsdketerangan_edit" name="tnsdketerangan_edit" value='${ket}'>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Total</label>
                    <input type="text" class="form-control" id="tnsdtotal_edit" name="tnsdtotal_edit" value='${total}' readonly>
                </div>
                <div class="form-group" style="display:none;">
                    <label for="">Harga</label>
                    <input type="text" class="form-control" id="brgharga_edit" name="brgharga_edit" value='${harga}' >
                </div>
            </div>
        </div>
        
        

        </form>
        <h4>Bahan yang dipakai</h4>
        <div>
            <table class="table table-hover mb-0" id="dt_bahanFromTransaksiDetail" style="width:100%"></table>
        </div>`;


        $('#tableContainerBesar').html(tableHTML);
        // $('#footerModalBesar').html(`<div class="col-sm-12 d-flex justify-content-end">
        //     <button type="submit" class="btn btn-primary me-1 mb-1" id="simpan_tnsdedit">Submit</button>
        // </div>`)
        $('#dt_bahanFromTransaksiDetail').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,

            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getTransaksiDetailBahan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                type: 'GET',
                data: function(d) {
                    d.transaksi = $('#tnsbparent_edit').val(); // Kirim data transaksi
                    d.gudang = $('#tnsdgudang_edit').val();
                }
            },
            columns: [{
                    data: 'bhnnama',
                    name: 'nama',
                    title: 'Nama',

                },
                {
                    data: 'satnama',
                    name: 'satuan',
                    title: 'Satuan',
                },
                {
                    data: 'tnsbjumlah',
                    name: 'jumlah',
                    title: 'jumlah',
                },
                {
                    data: 'bhnsaldo',
                    name: 'saldo gudang',
                    title: 'saldo gudang',
                },
                {
                    data: 'action',
                    name: 'action',
                    title: 'action',
                },

            ],
        });


        $('#tnsdjumlah_edit').on('input', function() {
            var hargaasli = parseFloat($('#brgharga_edit').val());
            var jumlah = parseFloat($(this).val());

            if (!isNaN(hargaasli) && !isNaN(jumlah)) {
                var nilai = hargaasli * jumlah;
                $('#tnsdtotal_edit').val(nilai); // Update total value
            }

            updateTransaksiDetail_new();
            refreshSaldo();
        });


        getBahanFromTransaksid()




        $(function() {
            // Handle form submission with Ajax
            $("#formTransaksid_edit").on('submit', function(e) {
                e.preventDefault(); // Prevent form from submitting normally



                var formData = new FormData(this);


                formData.append('bahan', JSON.stringify(processedData));
                formData.append('bahanTerpakai', JSON.stringify(processedDataTerpakai));
                formData.append('bahanVarian', JSON.stringify(processedDataVarian));

                if ($('#tnsdparent').val() == '') {
                    Toast.fire({
                        icon: "error",
                        title: "Pilih Transaksi dulu yuk..."
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updateTransaksiDetail', ['cabang' => '__cabang__']) }}"
                            .replace(
                                '__cabang__', cabang),
                        data: formData,
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
                            } else if (res.status == 400) {
                                Toast.fire({
                                    icon: "error",
                                    title: res.pesan
                                });
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Berhasil Edit Transaksi detail"
                                });
                                $('#dt_transaksi').DataTable().ajax.reload();
                                $('#dt_transaksid').DataTable().ajax.reload();
                                $('#modalBesar').modal(
                                    'hide'); // Close the modal after successful submission
                            }
                        },
                        error: function(xhr) {
                            console.log("Error: ", xhr);
                        }
                    });
                }

            });
        });

    }

    function updateTransaksiDetail_new() {
        mode = 'edit';

        var form = $('#formTransaksid_edit')[0];
        var formData = new FormData(form);

        formData.append('rawBahan', JSON.stringify(processRawBahan));
        formData.append('bahan', JSON.stringify(processedData));
        formData.append('bahanTerpakai', JSON.stringify(processedDataTerpakai));
        formData.append('bahanVarian', JSON.stringify(processedDataVarian));



        $.ajax({
            type: "POST",
            url: "{{ route('updateTransaksiDetail_new', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: formData,
            processData: false,
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
                    var old = $('#tnsdjumlah_edit').val()
                    $('#tnsdjumlah_edit_old').val(old)
                    getBahanFromTransaksid()
                    Toast.fire({
                        icon: "success",
                        title: "Berhasil edit"
                    });
                    $('#pmbket').val('')
                    $('#dt_transaksi').DataTable().ajax.reload();
                    $('#dt_transaksid').DataTable().ajax.reload();
                    $('#dt_bahanFromTransaksiDetail').DataTable().ajax.reload();



                }
            },
        });
    }

    function getBahanFromTransaksiDetail() {
        var tableHTML =
            `<table class="table table-hover mb-0" id="dt_bahanFromTransaksiDetail" style="width:100%"></table>`;
        $('#tableContainerBesar').html(tableHTML);
        $('#dt_bahanFromTransaksiDetail').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: "{{ route('getAllBahanBar', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: 'No',
                    orderable: false,
                }, {
                    data: 'bhnnama',
                    name: 'nama',
                    title: 'Nama',

                },
                {
                    data: 'bhnsatuan',
                    name: 'satuan',
                    title: 'Satuan',

                },
                {
                    data: 'bhnsaldo',
                    name: 'bhnsaldo',
                    title: 'Stok gudang besar',

                },
                {
                    data: 'saldoBar',
                    title: 'Stok gudang bar',

                },

            ],
        });
        klikTabelPembeliand()
    };

    function getBahanFromTransaksid() {
        if (mode == null) {
            $.ajax({
                type: "GET",
                url: "{{ route('getBahanFromTransaksid', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: {
                    'tnsdbarang': $('#tnsdbarang').val(),
                    'transaksi': $('#tnsdparent').val(),
                    'bvarid': $('#tempVarian').val(),
                    'bvarid': $('#tempVarian').val(),
                    'transaksiParentDetail': $('#tnsdparent_edit').val(),
                    'gudang': $('#tnsdgudang').val(),
                },
                dataType: "json",
                success: function(res) {
                    processedData = processData(res.bahan);
                    processedDataVarian = processDataVarian(res.bahanVarian);

                },
                error: function(xhr, status, error) {
                    console.log("Error: ", error);
                }
            });
        } else {
            $.ajax({
                type: "GET",
                url: "{{ route('getBahanFromTransaksid', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: {
                    'tnsdbarang': $('#tnsdbarang_edit').val(),
                    'transaksi': $('#tnsdparent_edit').val(),
                    'bvarid': $('#tempVarian').val(),
                    'transaksiParentDetail': $('#tnsbparent_edit').val(),
                    'gudang': $('#tnsdgudang').val(),
                },
                dataType: "json",
                success: function(res) {
                    processedData = processData(res.bahan);
                    processedDataVarian = processDataVarian(res.bahanVarian);
                    processedDataTerpakai = processDataTerpakai(res.bahanTerpakai);

                    processRawBahan = processRawBahan(res.bahan);



                    // alert(processedData)


                },
                error: function(xhr, status, error) {
                    console.log("Error: ", error);
                }
            });
        }

    }



    function getVarianFromBarang() {
        $.ajax({
            type: "GET",
            url: "{{ route('getVarianFromBarang', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: {
                'tnsdbarang': $('#tnsdbarang').val(),
            },
            dataType: "json",
            success: function(res) {


                // alert(res.bvarsaldo)


                var checkboxes = "";
                $.each(res.varian, function(index, varian) {
                    checkboxes +=
                        '<div><input name="cbVarian" class="form-check-input" type="checkbox" id="varian_' +
                        varian
                        .bvarid +
                        '" value="' + varian.bvarid + '">';
                    checkboxes += '<label for="varian_' + varian.bvarid + '">' + "  " + varian
                        .bvarnama +
                        '</label></div>';
                });

                $('#listVarian').html(checkboxes);


            },
            error: function(xhr, status, error) {
                console.log("Error: ", error);
            }
        });
    }

    function processData(data) {
        if (mode == null) {
            let multiplier = $('#tnsdjumlah').val(); // Contoh multiplier
            return data.map(item => {
                return {
                    ...item,
                    bhankuantiti: item.bhankuantiti * multiplier
                };
            });
        } else {
            let multiplier = $('#tnsdjumlah_edit').val(); // Contoh multiplier
            return data.map(item => {
                return {
                    ...item,
                    bhankuantiti: item.bhankuantiti * multiplier
                };
            });
        }

    }

    function processRawBahan(data) {
        return data.map(item => {
            return {
                ...item,
                bhankuantiti: item.bhankuantiti
            };
        });

    }

    function processDataTerpakai(data) {
        let multiplier = $('#tnsdjumlah_edit').val(); // Contoh multiplier
        return data.map(item => {
            return {
                ...item,
                bhankuantiti: item.bhankuantiti * multiplier
            };
        });

    }

    function processDataEdit(data) {
        // Ambil nilai multiplier dari input
        let multiplier = parseFloat($('#tnsbid_edit').val());

        // Jika multiplier tidak valid, set ke 1 (agar tidak mempengaruhi perhitungan)
        if (isNaN(multiplier)) {
            multiplier = 1;
        }

        // Proses setiap item dalam data array
        return data.map(item => {
            return {
                ...item,
                // Multiplikasi bhankuantiti dengan multiplier
                bhankuantiti: item.bhankuantiti * multiplier,
                // Menambahkan multiplier (tnsbid) ke dalam objek jika dibutuhkan
                tnsbid: multiplier // Menambahkan nilai tnsbid ke setiap item
            };
        });
    }


    function processDataVarian(data) {
        let multiplier = $('#tnsdjumlah').val(); // Contoh multiplier
        return data.map(item => {
            return {
                ...item,
                bvarsaldo: item.bvarsaldo * multiplier

            };
        });
    }

    $(function() {


        $("#formTransaksid").on('submit', function(e) {
            e.preventDefault();

            mode = null;


            // Panggil fungsi untuk mendapatkan bahan dari transaksi id
            // getBahanFromTransaksid();
            var a = new FormData(this);

            // Append processed data to the FormData object

            a.append('bahan', JSON.stringify(processedData));
            a.append('bahanVarian', JSON.stringify(processedDataVarian));
            a.append('bypassBahan', $('#tempBypass').val());

            if ($('#tnsdparent').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih Transaksi dulu yuk..."
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addTransaksiDetail', ['cabang' => '__cabang__']) }}"
                        .replace(
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
                        } else if (res.status == 400) {
                            Toast.fire({
                                icon: "error",
                                title: res.pesan
                            });
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Transaksi detail"
                            });
                            // $('#tnsdjumlah').val('')
                            // $('.frmtransaksid').val('');
                            $('.error').html('');
                            $('#formTransaksid')[0].reset();
                            refreshSaldo();
                            // $('#dt_transaksi').DataTable().ajax.reload();
                            $('#dt_transaksid').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr);
                    }
                });
            }
        });

    });


    function generateTransaksid() {
        $.ajax({
            type: "get",
            url: "{{ route('generateIdTransaksiDetail', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: "data",
            dataType: "json",
            success: function(res) {
                $('#tnsdgenerate').val(res.data)
            }
        });
    }



    function getBahanFromVarian() {
        $.ajax({
            type: "get",
            url: "{{ route('getBahanFromVarian', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: {
                'bvarid': $('#tempVarian').val()
            },
            dataType: "json",
            success: function(res) {
                console.log(res)
            }
        });
    }



    // --------------------------------------------------------untuk click2 button


    function klikTabelTransaksi() {

        var table = $('#dt_transaksi').DataTable();
        table.on('click', 'tbody tr', function() {

            $('#dt_transaksi tbody tr').removeClass('selected');
            $(this).addClass('selected');


            $('#tnsdparent').val(table.row(this).data().tnsid)
            $('#titleDetail').html(table.row(this).data().tnsid + ' - Atas nama : ' + table.row(this).data()
                .tnsnama)
            $('.error').html('')
            getTabelTransaksiDetail(table.row(this).data().tnsid)

            Toast.fire({
                icon: "success",
                title: "Sedang memilih transaksi : " + table.row(this).data()
                    .tnsnama
            });

        });
    }


    function klikTabelModalTransaksid() {

        var table = $('#dt_modalBarang').DataTable();
        table.on('click', 'tbody tr', function() {
            $('#tnsdbarang').val(table.row(this).data().brgid);
            $('#tnsdbarangn').val(table.row(this).data().brgnama);
            $('#tnsdhargaasli').val(table.row(this).data().brgharga);

            $('#modalBarang').modal("hide")
            $('#tnsdjumlah').focus()
            getBahanFromTransaksid()
            getVarianFromBarang()



        });
    }
    $('#btnRiwayatUpdate').click(function(e) {
        alert("cooming soon")

    });

    $('#btnSearchTransaksi').click(function(e) {
        $('#dt_transaksi').DataTable().ajax.reload();


    });

    $('#btnResetFormBahan').click(function(e) {
        $(".frmbahan").val('')
        Toast.fire({
            icon: "success",
            title: "Berhasil Reset form"
        });

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

    $('#iconSearchBarang').click(function(e) {

        generateTransaksid()

        // if ($('#tnsdgenerate').val() == '') {
        //     generateTransaksid()
        // }

        $('#modalBarang').modal("show")
        $('#titleModalBarang').html("Tambah barang untuk " + $('#titleDetail').html())
    });

    $('#tnsdjumlah').on('input', function() {
        var hargaasli = parseFloat($('#tnsdhargaasli').val());
        var jumlah = parseFloat($(this).val());

        getBahanFromTransaksid()

        if (!isNaN(hargaasli) && !isNaN(jumlah)) {
            var nilai = hargaasli * jumlah;
            $('#tnsdtotal').val(nilai);
        }
    });


    $('#listVarian').on('click', 'input[name="cbVarian"]', function() {
        if ($(this).is(":checked")) {
            if ($('#tnsdjumlah').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Masukkan jumlah terlebih dahulu"
                });
                $(this).prop('checked', false); // Uncheck the checkbox
            } else {
                // Uncheck all other checkboxes
                $('input[name="cbVarian"]').not(this).prop('checked', false);

                var value = $(this).val();
                var text = $('label[for="' + $(this).attr('id') + '"]').text().trim();
                console.log("Nilai: " + value + ", Teks: " + text);
                $('#tnsdketerangan').val(text);
                $('#tempVarian').val(value);
                // getBahanFromVarian()
                getBahanFromTransaksid();
            }
        } else {
            // When unchecking the already checked checkbox
            $('#tnsdketerangan').val('');
            $('#tempVarian').val('');
            getBahanFromTransaksid();
        }
    });

    // Menambahkan event handler untuk baris dalam tabel detail
    $('#dt_transaksid').off('click').on('click', 'tbody tr', function() {
        var table = $('#dt_transaksid').DataTable();
        if (table.row(this).data().tnsdketerangan == null) {
            $('#titleBahand').html(table.row(this).data().tnsdjumlah + '-' + table.row(this).data()
                .brgnama);
        } else {
            $('#titleBahand').html(table.row(this).data().tnsdjumlah + '-' + table.row(this).data()
                .brgnama +
                '-' +
                table.row(this).data().tnsdketerangan);
        }
        $('.error').html('');
        getTabelTransaksiDetailBahan(table.row(this).data().tnsdid);
    });
</script>
