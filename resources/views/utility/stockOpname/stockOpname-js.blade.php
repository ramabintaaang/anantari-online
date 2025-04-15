<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#sopdparent').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelSO()
        klikTabelSO()
        getAllBarang()
        let processedData = [];
        let processedDataVarian = [];

        let gudangPick = ''
        let statusModal = ''
        
        $('.frmtransaksi').val('')





        $('#tnsdjumlah').val('');

    });

    function getTabelSO() {
        $('#dt_so').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getStockOpname', ['cabang' => '__cabang__']) }}"
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
                    data: 'ket',
                    name: 'ket'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'user_created',
                    name: 'user_created'
                },
                {
                    data: 'gudang',
                    name: 'gudang'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
        });
    };

    function getTabelStockOpnamedRiwayat(id) {
    
    // Hancurkan DataTable sebelumnya jika ada
    if ($.fn.dataTable.isDataTable('#dt_stockopname_riwayat')) {
        $('#dt_stockopname_riwayat').DataTable().clear().destroy();
    }
    
        $('#dt_stockopname_riwayat').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getStockOpnamedRiwayat', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.riwayatBarang = id
                }
            },
            columns: [{
                    data: 'sopdbahann',
                    name: 'sopdbahann'
                },
                {
                    data: 'sopdjumlah',
                    name: 'sopdjumlah'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'gudangn',
                    name: 'gudangn'
                },

            ],
        });
    };

    function getTabelSOPD(id) {
        // var groupColumn = 1; // Kolom ke-2 yang akan digunakan untuk pengelompokan
        var table = $('#dt_sopd').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            ajax: {
                url: "{{ route('getStockOpnameD', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
                data: function(d) {
                    d.transaksi = id
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },

                {
                    data: 'sopdbahann',
                    name: 'sopdbahann'
                },
                {
                    data: 'jenisz',
                    name: 'jenisz'
                },
                {
                    data: 'sopdjumlah',
                    name: 'sopdjumlah'
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
           
            displayLength: 15,
            
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
            destroy: true,
            ajax: {
                url: "{{ route('getTransaksiDetailBahan', ['cabang' => '__cabang__']) }}"
                    .replace(
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



    function getPickerBahan() {
        var tableHTML = `<table class="table table-hover mb-0" id="dt_modalBahan2" style="width:100%"></table>`;
        $('#tableContainerBesar').html(tableHTML);
        $('#dt_modalBahan2').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: "{{ route('getAllSaldoGudang', ['cabang' => '__cabang__']) }}"
                .replace(
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
                    data: 'satuans',
                    name: 'satuans',
                    title: 'satuans',
                },
                {
                    data: 'jenisz',
                    name: 'jenisz',
                    title: 'jenis',
                },
                {
                    data: 'saldoBesar_custom',
                    name: 'saldoBesar',
                    title: 'saldoBesar',
                },
                {
                    data: 'saldoBar_custom',
                    name: 'saldoBar',
                    title: 'saldoBar',
                },
                {
                    data: 'saldoKitchen_custom',
                    name: 'saldoKitchen',
                    title: 'saldoKitchen',
                },
                {
                    data: 'saldoOlah',
                    name: 'saldoOlah',
                    title: 'saldoOlah',
                    visible: false
                },
                {
                    data: 'saldoBar',
                    name: 'saldoOlah',
                    title: 'saldoOlah',
                    visible: false
                },

                {
                    data: 'saldoKitchen',
                    name: 'saldoOlah',
                    title: 'saldoOlah',
                    visible: false
                },
                {
                    data: 'saldoBesar',
                    name: 'saldoOlah',
                    title: 'saldoOlah',
                    visible: false
                },



            ],
        });



        klikTabelSOPD()

    };


    function getAllBarang() {
        $('#dt_modalBarang').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            autoWidth: true,
            destroy: true,
            retrieve: true,
            ajax: "{{ route('getAllBarang', ['cabang' => '__cabang__']) }}"
                .replace(
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
                url: "{{ route('getBarangBahan', ['cabang' => '__cabang__']) }}"
                    .replace(
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

        $("#formInputSO").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#sopnama').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Keterangan wajib diisi"
                });
            } else if ($('#soptgl').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tanggal wajib diisi"
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addStockOpname', ['cabang' => '__cabang__']) }}"
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
                                title: "Berhasil Menambah StockOpname"
                            });
                            $('#sopnama').val('')
                            $('#dt_so').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });
            }


        });
    });


    $(function() {

        $("#formInputSOD").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);
            // a.append('gudangutama', gudangutama);

            if ($('#sopdparent').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih list dahulu di list stockopname !"
                });
            } else if ($('#sopdbahan').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Tentukan bahan yang akan ditambah !"
                });
            } else if ($("#sopdjumlah").val() != $('#sopdposisi').val()) {
                Swal.fire({
                    title: "Jumlah fisik ga sesuai saldo, yaqueen tetep input ?",
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                }).then((result) => {
                    if (result.isConfirmed) {

                        ///jika tetep ngotot simpen
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addStockOpnameD', ['cabang' => '__cabang__']) }}"
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
                                        $('span.' + prefix + '_error').text(
                                            val[0]);
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "success",
                                        title: "Berhasil update, stok diperbaharui mengikuti fisik"
                                    });
                                    // getBahanBarang($('#ipt_brgid'))
                                    $('#dt_sopd').DataTable().ajax.reload();
                                    $('#dt_stockopname_riwayat').DataTable().ajax
                                        .reload();




                                }
                            },
                            error: function(xhr) {}
                        });
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addStockOpnameD', ['cabang' => '__cabang__']) }}"
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
                                title: "Berhasil update, saldo dan fisik cocok lhurr . bobo gasix"
                            });
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_sopd').DataTable().ajax.reload();
                            $('#dt_stockopname_riwayat').DataTable().ajax
                                .reload();



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
            url: "{{ route('getBahanFromTransaksid', ['cabang' => '__cabang__']) }}"
                .replace(
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
            url: "{{ route('getVarianFromBarang', ['cabang' => '__cabang__']) }}"
                .replace(
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





    function getBahanFromVarian() {
        $.ajax({
            type: "get",
            url: "{{ route('getBahanFromVarian', ['cabang' => '__cabang__']) }}"
                .replace(
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

    function klikTabelSOPD() {

        var table = $('#dt_modalBahan2').DataTable();
        table.on('click', 'tbody tr', function() {

            getTabelStockOpnamedRiwayat(table.row(this).data().bhnid)
            $('#sopdbahan').val(table.row(this).data().bhnid);
            $('#sopdbahann').val(table.row(this).data().bhnnama);


            if (gudangPick == 1) {
                if (table.row(this).data().saldoBesar == null) {
                    Toast.fire({
                        icon: "error",
                        title: "Tidak pernah order barang, tidak dapat di stock opname"
                    });
                    statusModal = 0
                } else {
                    $('#sopdposisi').val(table.row(this).data().saldoBesar)
                    statusModal = 1
                }
            } else if (gudangPick == 2) {
                if (table.row(this).data().saldoBar == null) {
                    Toast.fire({
                        icon: "error",
                        title: "Tidak pernah order barang, tidak dapat di stock opname"
                    });
                } else {
                    $('#sopdposisi').val(table.row(this).data().saldoBar)
                    statusModal = 1
                }
            } else if (gudangPick == 3) {
                if (table.row(this).data().saldoKitchen == null) {
                    Toast.fire({
                        icon: "error",
                        title: "Tidak pernah order barang, tidak dapat di stock opname"
                    });
                    statusModal = 0
                } else {
                    $('#sopdposisi').val(table.row(this).data().saldoKitchen)
                    statusModal = 1
                }
            } else {
                alert("error , pilih yg lain")
            }

            if (statusModal == 0) {
                console.log('gagal milih')
            } else {
                $('#modalBesar').modal("hide")
                $('#sopdjumlah').val(1)
                $('#sopdjumlah').focus()
            }

        });
    }

    function klikTabelSO() {

        var table = $('#dt_so').DataTable();
        table.on('click', 'tbody tr', function() {
        
        let soptglz = table.row(this).data().soptgl;
	let formattedDate = new Date(soptglz).toISOString().split('T')[0];

            $('.frmsopd').val('')
            $('#sopdjumlah').val('')
            // Jika baris yang diklik sudah memiliki kelas 'selected', maka hilangkan kelas tersebut
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                // Jika baris yang diklik belum memiliki kelas 'selected', tambahkan kelas tersebut
                // Hapus kelas 'selected' dari semua baris lainnya
                $('#dt_so tbody tr').removeClass('selected');
                $(this).addClass('selected');
            }
            
            $('#btnTambahSOP').css('display','none')
            $('#btnEditSOP').css('display','')
	    $('#btnResetSOP').css('display','')


            
           $('#sopnama').val(table.row(this).data().sopnama)
           $('#soptgl').val(formattedDate)
           $('#sopgudang').val(table.row(this).data().sopgudang)

            $('#sopdparent').val(table.row(this).data().sopid)
            $('#titleDetail').html(table.row(this).data().sopid + ' - ' + table.row(this).data().sopnama)
            $('.error').html('')
            $('#tempGudangUtama').val(table.row(this).data().gudangutama);
            getTabelSOPD(table.row(this).data().sopid)
            gudangPick = (table.row(this).data().gudangutama)

            Toast.fire({
                icon: "success",
                title: "Sedang memilih Stock Opname : " + table.row(this).data()
                    .sopid
            });

            // alert(gudangPick)

        });
    }
    
     $('#btnResetSOP').click(function(e) {
            $('#btnTambahSOP').css('display','')
            $('#btnEditSOP').css('display','none')
	    $('#btnResetSOP').css('display','none')
	    $('#titleDetail').html('')
	    
	    $('.frmtransaksi').val('')
	    
             $('#dt_so tbody tr').removeClass('selected');

    });

    $('#btnRiwayatUpdate').click(function(e) {
        alert("cooming soon")

    });

    $('#btnSearchSO').click(function(e) {
        $('#dt_so').DataTable().ajax.reload();

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


    $('#iconSearchBahan').on('click', function() {

        var transaksi = $("#sopdparent").val()
        if (transaksi == '' || transaksi == null) {
            Toast.fire({
                icon: "error",
                title: "Pilih list stock opname terlebih dahulu..."
            });
        } else {
            getPickerBahan();
            $('#titleModalBesar').html("Pilih Bahan yang akan di STOCK OPNAME")
            $('#modalBesar').modal('show');
        }

    });
</script>
