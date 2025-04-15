<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)

        $('#pmbdbrgn').val('')
        $('#pmbdjumlah').val('')
        $('#pmbdket').val('')


        getTabelPembelian()
        klikTabelPembelian()
        getAllBarang()
        klikTabelModalTransaksid()
        klikTabelPembeliand()
        let processedData = [];
        let processedDataVarian = [];


        $('#tnsdjumlah').val('');

        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Ambil CSRF token dari meta tag


    });

    function getTabelPembelian() {
        $('#dt_pembelian').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getPembelian', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.ipt_tgldari = $('#ipt_tgldari').val(),
                        d.ipt_tglsampai = $('#ipt_tglsampai').val(),
                        d.ipt_nama = $('#ipt_nama').val()
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'pmbket',
                    name: 'pmbket'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'gudangn',
                    name: 'gudangn'
                },
                {
                    data: 'user_created',
                    name: 'user'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
        });
    };

    function getTabelPembelianDetail(id) {
        // var groupColumn = 1; // Kolom ke-2 yang akan digunakan untuk pengelompokan
        var table = $('#dt_pembeliand').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            ajax: {
                url: "{{ route('getPembelianDetail', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.transaksi = id
                }
            },
            columns: [

                {
                    data: 'pmbdbrgn',
                    name: 'pmbdbrgn'
                },
                {
                    data: 'pmbdjumlah',
                    name: 'pmbdjumlah'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'user_created',
                    name: 'user'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            // columnDefs: [{
            //     visible: false,
            //     targets: groupColumn
            // }],
            // order: [
            //     [groupColumn, 'asc']
            // ],
            displayLength: 25,
            // drawCallback: function(settings) {
            //     var api = this.api();
            //     var rows = api.rows({
            //         page: 'current'
            //     }).nodes();
            //     var last = null;

            //     api.column(groupColumn, {
            //         page: 'current'
            //     }).data().each(function(group, i) {
            //         if (last !== group) {
            //             $(rows).eq(i).before(
            //                 '<tr class="group bg-info text-white"><td colspan="7 ">' + group +
            //                 '</td></tr>'
            //             );
            //             last = group;
            //         }
            //     });
            // }
        });

        // Menambahkan event handler untuk baris dalam tabel detail
        $('#dt_transaksid').off('click').on('click', 'tbody tr', function() {
            var table = $('#dt_transaksid').DataTable();
            // Hapus kelas 'selected' dari semua baris
            $('#dt_transaksid tbody tr').removeClass('selected');
            // Tambahkan kelas 'selected' ke baris yang diklik
            $(this).addClass('selected');



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
            destroy: true,
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
        $('#dt_modalBahan').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: "{{ route('getAllBahanOnly', ['cabang' => '__cabang__']) }}".replace(
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
                    data: 'satuans',
                    name: 'satuans'
                },

            ],
        });
    };

    function getAllSupplier() {
        $('#dt_modalBahan').DataTable({
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

            ],
        });
    };

    $(function() {

        $("#formInputPembelian").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#pmbket').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Keterangan wajib diisi"
                });
            } else if ($('#pmbtgl').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tanggal wajib diisi"
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addPembelian', ['cabang' => '__cabang__']) }}".replace(
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
                                title: "Berhasil Menambah Pembelian"
                            });
                            $('#pmbket').val('')
                            $('#dt_pembelian').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });
            }


        });
    });

    $('#btnEditTransaksi').click(function(e) {
        e.preventDefault(); // Mencegah refresh halaman

        let form = new FormData($('#formInputPembelian')[0]); // Ambil data dari form
        form.append('pmbid', $('#pmbdparent').val()); // Tambahkan field tambahan

        $.ajax({
            type: "POST",
            url: "{{ route('updatePembelian', ['cabang' => '__cabang__']) }}".replace('__cabang__',
                cabang),
            data: form,
            dataType: "json",
            processData: false, // Jangan proses data FormData
            contentType: false, // Jangan tetapkan Content-Type secara otomatis
            success: function(res) {
                Toast.fire({
                    icon: "success",
                    title: "Berhasil Edit Pembelian"
                });
                $('#dt_pembelian').DataTable().ajax.reload(); // Refresh tabel
                refreshSaldo(); // Panggil fungsi refresh saldo
            },
            error: function(err) {
                console.error(err);
                Toast.fire({
                    icon: "error",
                    title: "Gagal Edit Pembelian"
                });
            }
        });
    });



    $(function() {

        $("#formInputPembeliand").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#pmbdparent').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih pembelian dahulu di list pembelian !"
                });
            } else if ($('#pmbdbrg').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tentukan bahan yang akan ditambah !"
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addPembeliand', ['cabang' => '__cabang__']) }}".replace(
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
                                title: "Berhasil Menambah Bahan yang dibeli"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_pembeliand').DataTable().ajax.reload();
                            $('.frmpembeliand').val('')
                            refreshSaldo()
                            getAllBahan()
                            klikTabelBarangBahan()
                            $('#modalBahan').modal("show")
                            $('#titleModalBahan').html("List Bahan")
                            // $('#formInputPembeliand')[0].reset();



                        }
                    },
                    error: function(xhr) {}
                });
            }
        });
    });




    function getBahanFromTransaksid() {
        $.ajax({
            type: "GET",
            url: "{{ route('getBahanFromTransaksid', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            data: {
                'tnsdbarang': $('#tnsdbarang').val(),
                'transaksi': $('#tnsdparent').val(),
                'bvarid': $('#tempVarian').val()
            },
            dataType: "json",
            success: function(res) {
                processedData = processData(res.bahan);
                processedDataVarian = processDataVarian(res.bahanVarian);

                // alert(processedData)


            },
            error: function(xhr, status, error) {
                console.log("Error: ", error);
            }
        });
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


    // Fungsi untuk memproses data (contoh: mengalikan kuantiti dengan jumlah pesanan)
    function processData(data) {
        let multiplier = $('#tnsdjumlah').val(); // Contoh multiplier
        return data.map(item => {
            return {
                ...item,
                bhankuantiti: item.bhankuantiti * multiplier
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


            // Panggil fungsi untuk mendapatkan bahan dari transaksi id
            // getBahanFromTransaksid();
            var a = new FormData(this);

            // Append processed data to the FormData object
            a.append('bahan', JSON.stringify(processedData));
            a.append('bahanVarian', JSON.stringify(processedDataVarian));


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

    function klikTabelBarangBahan() {

        var table = $('#dt_modalBahan').DataTable();
        table.on('click', 'tbody tr', function() {
            $('#pmbdbrg').val(table.row(this).data().bhnid);
            $('#pmbdbrgn').val(table.row(this).data().bhnnama);
            $('#pmbdposisi').val(table.row(this).data().bhnsaldo)
            $('#modalBahan').modal("hide")
            $('#pmbdjumlah').val(1)
            $('#pmbdjumlah').focus()

        });
    }




    // --------------------------------------------------------untuk click2 button

    $('#btnResetTransaksi').click(function(e) {
        $('#btnTambahTransaksid').css('display', '')
        $('#btnEditTransaksi').css('display', 'none')
        $('#btnResetTransaksi').css('display', 'none')
        $('#titleDetail').html('')

        $('#pmbdparent').val('')
        $('#dt_pembelian tbody tr').removeClass('selected');

    });


    function klikTabelPembelian() {

        var table = $('#dt_pembelian').DataTable();
        table.on('click', 'tbody tr', function() {

            $('#dt_pembelian tbody tr').removeClass('selected');
            // Tambahkan kelas 'selected' ke baris yang diklik
            $(this).addClass('selected');

            $('#btnTambahTransaksid').css('display', 'none')
            $('#btnEditTransaksi').css('display', '')
            $('#btnResetTransaksi').css('display', '')

            $('#pmbdparent').val(table.row(this).data().pmbid)
            $('#pmbdgudang').val(table.row(this).data().pmbgudang)

            $('#pmbket').val(table.row(this).data().pmbket)
            $('#pmbtgl').val(table.row(this).data().pmbtgl)
            $('#pmbjenis').val(table.row(this).data().pmbjenis)
            $('#pmbgudang').val(table.row(this).data().pmbgudang)
            $('#pmbsupp').val(table.row(this).data().pmbsupp)


            $('#titleDetail').html(table.row(this).data().pmbid + '-' + table.row(this).data()
                .pmbket)
            $('.error').html('')
            getTabelPembelianDetail(table.row(this).data().pmbid)

            Toast.fire({
                icon: "success",
                title: "Sedang memilih pembelian : " + table.row(this).data()
                    .pmbid
            });

        });
    }

function klikTabelPembeliand() {

        var table = $('#dt_pembeliand').DataTable();
        table.on('click', 'tbody tr', function() {

            $('#dt_pembeliand tbody tr').removeClass('selected');
            // Tambahkan kelas 'selected' ke baris yang diklik
            $(this).addClass('selected');


            Toast.fire({
                icon: "success",
                title: "Sedang memilih pembelian : " + table.row(this).data()
                    .pmbid
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
        $('#dt_pembelian').DataTable().ajax.reload();


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

    $('#iconSearchBahan').click(function(e) {
        getAllBahan()
        klikTabelBarangBahan()
        $('#modalBahan').modal("show")
        $('#titleModalBahan').html("List Bahan")
    });


    $('#iconSearchSupplier').on('click', function() {

        var supp = $("#pmbdbrgn").val()
        if (supp == '' || supp == null) {
            Toast.fire({
                icon: "error",
                title: "Pilih barang terlebih dahulu..."
            });
        } else {
            $('#titleModalKecil').html("Pilih Supplier")
            getPickerSupplier();
            $('#modalKecil').modal('show');
        }

    });


    function getPickerSupplier() {
        var tableHTML = `<table class="table table-hover mb-0" id="dt_modalSupplier" style="width:100%"></table>`;
        $('#tableContainerKecil').html(tableHTML);
        $('#dt_modalSupplier').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: "{{ route('getAllSupplier') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: 'No',
                    orderable: false,
                },
                {
                    data: 'suppnama',
                    name: 'nama',
                    title: 'nama',

                },
                {
                    data: 'suppalamat',
                    name: 'alamat',
                    title: 'Alamat',

                },

            ],
        });
        klikTabelSupplier()
    };

    function klikTabelSupplier() {

        var table = $('#dt_modalSupplier').DataTable();
        table.on('click', 'tbody tr', function() {
            $('#pmbdsupp').val(table.row(this).data().suppid);
            $('#pmbdsuppn').val(table.row(this).data().suppnama);
            $('#modalKecil').modal("hide")
        });
    }
    // $('#tnsdjumlah').on('input', function() {

    // });

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


    $(document).on('click', '#delete-btn-pmbd', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');
        var barang = $(this).data('barang');
        var barangn = $(this).data('barangn');
        var jumlah = $(this).data('jumlah');

        []; // Mengubah menjadi array jika ada data, jika tidak kosong



        if ($('#tempDivisi').val() != 'admin' || $('#tempDivisi').val() == 'purchasing') {
            Toast.fire({
                icon: "error",
                title: "Anda tidak mempunyai akses untuk menghapus, hubungi admin/purchasing"
            });
        } else {
            Swal.fire({
                title: "Yakin untuk menghapus " + barangn + " ?",
                text: "jika setuju maka saldo gudang akan berkurang",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, hapus saja !"
            }).then((result) => {
                if (result.isConfirmed) {
                    deletePembeliand(id, jumlah, barang)
                }
            });
        }

    });

    $(document).on('click', '#btn-delete-pembelian', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');


        Swal.fire({
            title: "Yakin Hapus ? isi dari pembelian akan otomatis terhapus ?",
            showConfirmButton: false,
            showDenyButton: true,
            showCancelButton: true,
            denyButtonText: `Ya, Hapus`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                alert("perinah tidak dikenali")
            } else if (result.isDenied) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('deletePembelian', ['cabang' => '__cabang__']) }}"
                        .replace(
                            '__cabang__', cabang),

                    data: {
                        'pmbid': id,
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // Menambahkan token CSRF ke dalam header setiap permintaan
                        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
                    },
                    success: function(res) {
                        if (res.status == 200) {
                            Toast.fire({
                                icon: "success",
                                title: res.pesan
                            });
                            refreshSaldo()
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


    $(document).on('click', '#edit-btn-pmbd', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');
        var barang = $(this).data('barang');
        var barangn = $(this).data('barangn');
        var jumlah = $(this).data('jumlah');
        var bayar = $(this).data('bayar');

        [];
        $('#titleModalKecil').html('edit jumlah : ' + barangn)
        var tableHTML =
            `<form id="formEdit_pmbd">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <h6>Jumlah <span class="text-danger " style="margin-left: 5px">*</span>
                        </h6>
                        <div class="form-group position-relative has-icon-right">
                            <input type="text" class="nomor form-control"
                                id="pmbdid_edit"
                                name="pmbdid_edit" hidden>
                            <input type="text" class="nomor form-control"
                                id="pmbdjumlah_edit"
                                name="pmbdjumlah_edit">
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <h6>Bayar <span class="text-danger " style="margin-left: 5px">*</span>
                        </h6>
                        <div class="form-group position-relative has-icon-right">
                            <input type="text" class="nomor form-control"
                                id="pmbdbayar_edit"
                                name="pmbdbayar_edit">
                        </div>
                    </div>
                </div>
                <button class="btn btn-warning" type="submit">Update</button>
                </form>`;
        // var footer =
        //     `<button class="btn btn-warning" type="submit">Update</button>`;


        $('#tableContainerKecil').html(tableHTML);
        $('#pmbdid_edit').val(id);
        $('#pmbdjumlah_edit').val(jumlah);
        $('#pmbdbayar_edit').val(bayar);
        // $('#footerModalKecil').html(footer);
        $('#modalKecil').modal('show');

        updatePMBD()

    });

    function updatePMBD() {
        $(function() {

            $(document).on('submit', '#formEdit_pmbd', function(e) {
                e.preventDefault();
                
                var a = new FormData(this);
                a.append('pmbdparent',$('#pmbdparent').val())

                if ($('#pmbdjumlah_edit').val() == '') {
                    Toast.fire({
                        icon: "error",
                        title: "jumlah wajib diisi"
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('updatePembeliand', ['cabang' => '__cabang__']) }}"
                            .replace(
                                '__cabang__', cabang),
                        data: a,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function(xhr) {
                            // Menambahkan token CSRF ke dalam header setiap permintaan
                            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
                            $(document).find('span.error').text('');
                        },
                        success: function(res) {
                            if (res.status == 500) {
                                $.each(res.error, function(prefix, val) {
                                    $('span.' + prefix + '_error').text(val[
                                        0]);
                                });
                            } else {

                                Toast.fire({
                                    icon: "success",
                                    title: "Berhasil Update Pembelian detail"
                                });
                                refreshSaldo()
                                $('#pmbket').val('')
                                $('#dt_pembeliand').DataTable().ajax.reload();
                                $('#modalKecil').modal('hide');




                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("Error:", status, error);
                            Toast.fire({
                                icon: "error",
                                title: "Terjadi kesalahan, silakan coba lagi"
                            });
                        }
                    });
                }


            });
        });
    }





    function deletePembeliand(id, jumlah, barang) {
        $.ajax({
            type: "POST",
            url: "{{ route('deletePembeliand', ['cabang' => '__cabang__']) }}"
                .replace(
                    '__cabang__', cabang),
            data: {
                "_token": "{{ csrf_token() }}",
                'pmbdid': id,
                'pmbdjumlah': jumlah,
                'pmbdbrg': barang,
            },
            dataType: "json",
            success: function(response) {
                refreshSaldo()
                Toast.fire({
                    icon: "success",
                    title: "Bahan berhasil dihapus dari pembelian"
                });
                $('#dt_pembeliand').DataTable().ajax.reload();

            }
        });
    }
</script>
