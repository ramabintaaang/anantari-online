<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelMutasi()
        klikTabelMutasi()
        let processedData = [];
        let processedDataVarian = [];

        $('#mutaket').val('')
        $('#mutdbahann').val('')
        $('#mutdjumlah').val('')
        $('#mutdjumlahgudang').val('')
        $('#mutdparent').val('')










        $('#tnsdjumlah').val('');

    });

    function getTabelMutasi() {
        $('#dt_mutasi').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getMutasi', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'jenisz',
                    name: 'jenisz'
                },
                {
                    data: 'mutaket',
                    name: 'mutaket'
                },
                {
                    data: 'asalGudang',
                    name: 'asalGudang'
                },
                {
                    data: 'user_created',
                    name: 'user_created'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
        });
    };


    function getTabelRiwayatMutasi(id) {
        $('#dt_mutasi_riwayat').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getMutasiRiwayat', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.bahan = id
                }
            },
            columns: [{
                    data: 'bhnnama',
                    name: 'bhnnama'
                },
                {
                    data: 'mutdjumlah',
                    name: 'mutdjumlah'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'jenisz',
                    name: 'jenisz'
                },
                {
                    data: 'gudangn',
                    name: 'gudangn'
                },

            ],
        });
    };

    function getBahanFromGudang(id) {
        var table = $('#dt_bahan_from_gudang').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            retrieve: true,
            ajax: {
                url: "{{ route('getBahanFromGudang', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.gudang = $('#mutagudang').val()
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                },
                {
                    data: 'bhnnama',
                    name: 'bhnnama'
                },
                {
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'satnama',
                    name: 'satnama'
                },

            ],

            displayLength: 25,

        });

    }

    function getBahanFromGudangTujuan(id) {
        var table = $('#dt_bahan_from_gudang_tujuan').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            retrieve: true,
            ajax: {
                url: "{{ route('getBahanFromGudangTujuan', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.gudang = $('#mutastore').val()
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                },
                {
                    data: 'bhnnama',
                    name: 'bhnnama'
                },
                {
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'satnama',
                    name: 'satnama'
                },

            ],

            displayLength: 25,

        });

    }

    function getTabelMutasiDetail(id) {
        // var groupColumn = 1; // Kolom ke-2 yang akan digunakan untuk pengelompokan
        var table = $('#dt_pembeliand').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            ajax: {
                url: "{{ route('getMutasiDetail', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.transaksi = id
                    d.cabangTujuan = $('#mutastore').val()
                }
            },
            columns: [

                {
                    data: 'bhnnama',
                    name: 'bhnnama'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'user_created',
                    name: 'user'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],

            displayLength: 25,

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




    $(function() {

        $("#formInputMutasi").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#mutaket').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Keterangan wajib diisi"
                });
            } else if ($('#mutatgl').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tanggal wajib diisi"
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addMutasi', ['cabang' => '__cabang__']) }}"
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
                        } else {

                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Mutasi"
                            });
                            $('#mutaket').val('')
                            $('#dt_mutasi').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });
            }


        });
    });


    $(function() {

        $("#formInputMutasid").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#mutdparent').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih pembelian dahulu di list pembelian !"
                });
            } else if ($('#mutdbahan').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tentukan bahan yang akan ditambah !"
                });
            } else if (parseInt($('#mutdjumlah').val()) > parseInt($('#mutdjumlahgudang').val())) {
                Toast.fire({
                    icon: "error",
                    title: "Jumlah tidak dapat melebihi dari saldo gudang ya !"
                });


            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addMutasiDetail', ['cabang' => '__cabang__']) }}"
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
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menambah Bahan yang dimutasi keluar"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_pembeliand').DataTable().ajax.reload();
                            $('#dt_mutasi_riwayat').DataTable().ajax.reload();
                            $('.frmpembeliand').val('')
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


    function klikTabelBahanFromGudang() {

        var table = $('#dt_bahan_from_gudang').DataTable();
        table.on('click', 'tbody tr', function() {


            if (table.row(this).data().bhnsaldo == null || table.row(this).data().bhnsaldo == 0) {
                Toast.fire({
                    icon: "error",
                    title: "Bahan habis, tidak dapat dimutasi"
                });
            } else {
                $('#mutdbahann').val(table.row(this).data().bhnnama);
                $('#mutdbahan').val(table.row(this).data().bhnid);
                $('#mutdposisi').val(table.row(this).data().bhnsaldo);
                $('#mutdjumlahgudang').val(table.row(this).data().bhnsaldo)
                $('#modalBahan').modal("hide")
                $('#mutdjumlah').val(1)
                $('#mutdjumlah').focus()

                getTabelRiwayatMutasi(table.row(this).data().bhnid);
                $('#titleRiwayatMutasi').html(table.row(this).data().bhnnama)
            }



        });
    }


    function klikTabelBahanFromGudangTujuan() {

        var table = $('#dt_bahan_from_gudang_tujuan').DataTable();
        table.on('click', 'tbody tr', function() {

            $('#mutdbahan_tujuan').val(table.row(this).data().bhnid);
            $('#mutdbahann_tujuan').val(table.row(this).data().bhnnama);

            $('#modalBahanTujuan').modal("hide")

        });
    }



    // --------------------------------------------------------untuk click2 button


    function klikTabelMutasi() {

        var table = $('#dt_mutasi').DataTable();
        table.on('click', 'tbody tr', function() {

            // Jika baris yang diklik sudah memiliki kelas 'selected', maka hilangkan kelas tersebut
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                // Jika baris yang diklik belum memiliki kelas 'selected', tambahkan kelas tersebut
                // Hapus kelas 'selected' dari semua baris lainnya
                $('#dt_mutasi tbody tr').removeClass('selected');
                $(this).addClass('selected');
            }


            $('#mutaket').val(table.row(this).data().mutaket)
            $('#mutaid').val(table.row(this).data().mutaid)
            $('#mutatgl').val(table.row(this).data().mutatgl)
            $('#mutagudang').val(table.row(this).data().mutagudang)
            $('#mutastore').val(table.row(this).data().mutastore)
            $('#mutajenis_temp').val(table.row(this).data().mutajenis)


            if (table.row(this).data().mutajenis == 'JMU001') {
                $('#mutajenis').val(table.row(this).data().mutajenis)
                $('#mutadjenis').val(table.row(this).data().mutajenis)


                $('#mutastorehead').css("display", "")
                $('#frmTujuan').css("display", "")


            } else {
                $('#mutajenis').val(table.row(this).data().mutajenis)
                $('#mutadjenis').val(table.row(this).data().mutajenis)
                $('#frmTujuan').css("display", "none")

            }

            if (table.row(this).data().mutastore != null || table.row(this).data().mutastore == '') {
                $('#mutastore').val(table.row(this).data().mutastore)
                $('#mutdstore').val(table.row(this).data().mutastore)

            } else {
                $('#mutastore').val(null)
                $('#mutdstore').val(null)

            }

            $('#mutdparent').val(table.row(this).data().mutaid)
            $('#mutdgudang').val(table.row(this).data().mutagudang)


            $('#titleDetail').html(table.row(this).data().jmunama + '-' + table.row(this).data()
                .mutaket)
            $('.error').html('')
            getTabelMutasiDetail(table.row(this).data().mutaid)

            Toast.fire({
                icon: "success",
                title: "Sedang memilih pengeluaran : " + table.row(this).data()
                    .mutaid
            });
        });
    }


    function klikTabelModalBahan() {

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
        $('#dt_mutasi').DataTable().ajax.reload();


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
        if ($('#mutaid').val() == '') {
            Toast.fire({
                icon: "error",
                title: "Pilih pengeluaran terlebih dahulu"
            });
        } else {
            getBahanFromGudang()
            klikTabelBahanFromGudang()
            $('#modalBahan').modal("show")
            $('#titleModalBahan').html("List Bahans")
        }
    });

    $('#iconSearchBahanTujuan').click(function(e) {
        if ($('#mutaid').val() == '') {
            Toast.fire({
                icon: "error",
                title: "Pilih pengeluaran terlebih dahulu"
            });
        } else {
            getBahanFromGudangTujuan()
            klikTabelBahanFromGudangTujuan()
            $('#modalBahanTujuan').modal("show")
            $('#titleModalBahanTujuan').html("List Bahan Store Tujuan - Gudang Besar")
        }
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





    $(document).on('click', '#delete-btn-pmbd', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');
        var barang = $(this).data('barang');
        var barangn = $(this).data('barangn');
        var jumlah = $(this).data('jumlah');

        []; // Mengubah menjadi array jika ada data, jika tidak kosong



        if ($('#tempDivisi').val() != 'admin') {
            Toast.fire({
                icon: "error",
                title: "Anda tidak mempunyai akses untuk menghapus"
            });
        } else {
            Swal.fire({
                title: "Yakin untuk menghapus " + barangn + " ?",
                text: "jika setuju maka saldo gudang akan berkurang",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    deletePembeliand(id, jumlah, barang)
                }
            });
        }

    });

    $(document).on('click', '#edit-btn-pmbd', function(e) {
        e.preventDefault();

        // Ambil data dari atribut data-*
        var id = $(this).data('id');
        var barang = $(this).data('barang');
        var barangn = $(this).data('barangn');
        var jumlah = $(this).data('jumlah');

        []; // Mengubah menjadi array jika ada data, jika tidak kosong



        if ($('#tempDivisi').val() != 'admin') {
            Toast.fire({
                icon: "error",
                title: "Anda tidak mempunyai akses untuk mengedit"
            });
        } else {
            Swal.fire({
                title: "Yakin untuk menghapus " + barangn + " ?",
                text: "jika setuju maka saldo gudang akan berkurang",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    deletePembeliand(id, jumlah, barang)
                }
            });
        }

    });



    $(document).on('change', '#mutajenis', function() {
        if (this.value == 'JMU001') {
            $('#mutastorehead').css('display', '');
        } else {
            $('#mutastorehead').css('display', 'none');
        }
    });
</script>
