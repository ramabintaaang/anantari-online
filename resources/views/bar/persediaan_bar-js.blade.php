<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#bhonama').val('')
        $('#bhokuantiti').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        refreshSaldo()
        getTabelBahanBar()
        getTabelBahanOlah()
        klikTabelPembelian()
        getAllBarang()
        klikTabelModalTransaksid()
        let processedData = [];
        let processedDataVarian = [];


        $('#tnsdjumlah').val('');

    });


    $(function() {


        $("#formBahanOlah").on('submit', function(e) {
            e.preventDefault();

            var a = new FormData(this);

            // Append processed data to the FormData object


            if ($('#bhoid').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "bahan olah belum dipilih dengan benar"
                });
            } else if ($('#bhokuantiti').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "jumlah belum diset dengan benar !"
                });
            } else if ($('#bhototal').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "base yg dihasilkan belum diset dengan benar !"
                });
            } else {
                a.append('bahan', JSON.stringify(processedData));
                $.ajax({
                    type: "POST",
                    url: "{{ route('addBahanOlahUsedTrans', ['cabang' => '__cabang__']) }}"
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
                                title: "Berhasil Membuat Bahan Olah"
                            });
                            // $('#tnsdjumlah').val('')
                            // $('.frmtransaksid').val('');
                            $("#bhonama").val('')
                            $("#bhoid").val('')
                            $("#bhokuantiti").val('')
                            $("#bhototal").val('')


                            $('.error').html('');
                            $('#dt_bahan_olah').DataTable().ajax.reload();
                            $('#dt_bahan_bar').DataTable().ajax.reload();

                        }
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr);
                    }
                });
            }
        });

    });

    function getBahanFromBahanOlah() {
        $.ajax({
            type: "GET",
            url: "{{ route('getBahanFromBahanOlah', ['cabang' => '__cabang__']) }}"
                .replace(
                    '__cabang__', cabang),
            data: {
                'bhoid': $('#bhoid').val(),
                'gudang': $('#bhogudang').val(),
            },
            dataType: "json",
            success: function(res) {
                processedData = processData(res.bahan);
                console.log(processedData, res.bahan, parseInt($('#bhokuantiti').val()))
                // processedDataVarian = processDataVarian(res.bahanVarian);



            },
            error: function(xhr, status, error) {
                console.log("Error: ", error);
            }
        });

    }

    function processData(data) {
        let multiplier = $('#bhokuantiti').val(); // Contoh multiplier
        return data.map(item => {
            return {
                ...item,
                bhankuantiti: item.bhankuantiti * multiplier
            };
        });

    }

    function getTabelBahanBar() {
        $('#dt_bahan_bar').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getTabelBahanBar', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'bhnnama',
                    name: 'bhnnama'
                },
                {
                    data: 'jumlahSekarang',
                    name: 'jumlahSekarang'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            rowCallback: function(row, data) {
                if (data.jumlahSekarang < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }

    function getTabelBahanOlah() {
        $('#dt_bahan_olah').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {

                url: "{{ route('getTabelBahanOlah', ['cabang' => '__cabang__']) }}"
                    .replace(
                        '__cabang__', cabang),
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'bhonama',
                    name: 'bhonama'
                },
                {
                    data: 'bhosaldo',
                    name: 'bhosaldo'
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
            rowCallback: function(row, data) {
                if (data.bhosaldo < data.bhomin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }


    function klikTabelBarangBahanOlah() {

        var table = $('#dt_bahanOlah').DataTable();
        table.off('click', 'tbody tr');
        table.on('click', 'tbody tr', function() {
            $('#bhoid').val(table.row(this).data().bhoid);
            $('#bhonama').val(table.row(this).data().bhonama);
            $('#bhohasil').val(table.row(this).data().bhohasil);
            $('#bhosatuan').val(table.row(this).data().bhosatuan);
            $('#bhosatuans').val(table.row(this).data().satnama);
            $('#bhogudang').val(table.row(this).data().bhogudang);
            $('#bhosaldo').val(table.row(this).data().jumlahSekarang);
            $('#modalBahanBarang').modal("hide");
            $('#bhokuantiti').focus();

            getBahanFromBahanOlah()
        });
    }

    function cekBarangBahanDouble(bhanbarang, bhnid) {
        $.ajax({
            type: "GET",
            url: "{{ route('cekBarangBahanDouble', ['cabang' => '__cabang__']) }}"
                .replace(
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



    function getAllBahanOlah() {
        $('#dt_bahanOlah').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: "{{ route('getAllBahanOlah', ['cabang' => '__cabang__']) }}".replace(
                '__cabang__', cabang),
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                }, {
                    data: 'bhonama',
                    name: 'nama'
                },
                {
                    data: 'satnama',
                    name: 'satuan'
                },

            ],
        });
        klikTabelBarangBahanOlah()
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
                ajax: "{{ route('getBarangBahan', ['cabang' => '__cabang__']) }}".replace(
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



    function processDataVarian(data) {
        let multiplier = $('#tnsdjumlah').val(); // Contoh multiplier
        return data.map(item => {
            return {
                ...item,
                bvarsaldo: item.bvarsaldo * multiplier
            };
        });
    }




    function getPickerBahan() {
        var tableHTML = `<table class="table table-hover mb-0" id="dt_modalBahan2" style="width:100%"></table>`;
        $('#tableContainerBesar').html(tableHTML);
        $('#dt_modalBahan2').DataTable({
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


    function getRiwayatBahan_bar(bhnid) {
        var tableHTML = `<table class="table table-hover mb-0" id="dt_riwayatBahan" style="width:100%"></table>`;
        $('#tableContainerBesar').html(tableHTML);
        $('#dt_riwayatBahan').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getRiwayatBahan_bar_persediaan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.bhnid = bhnid;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: 'No',
                    orderable: false,
                }, {
                    data: 'created_at',
                    name: 'tanggal',
                    title: 'tanggal',

                },
                {
                    data: 'jenis',
                    name: 'jenis',
                    title: 'jenis',

                },
                {
                    data: 'sbmasuk',
                    name: 'masuk',
                    title: 'masuk',

                },
                {
                    data: 'sbkeluar',
                    name: 'keluar',
                    title: 'keluar',

                },
                {
                    data: 'sbadjust',
                    name: 'adjust',
                    title: 'adjust',

                },

            ],
        });
    };


    function getRiwayatBahanOlah_bar(bhnid) {
        var tableHTML = `<table class="table table-hover mb-0" id="dt_riwayatBahan" style="width:100%"></table>`;
        $('#tableContainerBesar').html(tableHTML);
        $('#dt_riwayatBahan').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            // destroy: true,
            // info: false,
            // serverSide: true,
            // autoWidth: true,
            ajax: {
                url: "{{ route('getRiwayatBahanOlah_bar_persediaan', ['cabang' => '__cabang__']) }}".replace(
                    '__cabang__', cabang),
                data: function(d) {
                    d.bhnid = bhnid;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: 'No',
                    orderable: false,
                }, {
                    data: 'created_at',
                    name: 'tanggal',
                    title: 'tanggal',

                },
                {
                    data: 'jenis',
                    name: 'jenis',
                    title: 'jenis',

                },
                {
                    data: 'sbmasuk',
                    name: 'masuk',
                    title: 'masuk',

                },
                {
                    data: 'sbkeluar',
                    name: 'keluar',
                    title: 'keluar',

                },
                {
                    data: 'sbadjust',
                    name: 'adjust',
                    title: 'adjust',

                },

            ],
        });
    };




    // --------------------------------------------------------untuk click2 button



    function klikTabelPembelian() {

        var table = $('#dt_pembelian').DataTable();
        table.on('click', 'tbody tr', function() {


            $('#pmbdparent').val(table.row(this).data().pmbid)
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

    $('#bhokuantiti').change(function(e) {
        getBahanFromBahanOlah()
        var hasil = $('#bhohasil').val()
        var jumlah = $('#bhokuantiti').val()
        var satuan = $('#bhosatuan').val()
        var satuans = $('#bhosatuans').val()

        var total = hasil * jumlah
        $('#titleHasil').html(satuans)

        $('#bhototal').val(total)


    });


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

    function klikTabelPembeliand() {

        var table = $('#dt_modalBahan2').DataTable();
        table.on('click', 'tbody tr', function() {
            if (table.row(this).data().bhnsaldo == 0) {
                Toast.fire({
                    icon: "error",
                    title: "Bahan di gudang habis, hubungi admin gudang"
                });
            } else {
                $('#pmbdbrg').val(table.row(this).data().bhnid);
                $('#pmbdbrgn').val(table.row(this).data().bhnnama);
                $('#pmbdsatuan').val(table.row(this).data().bhnsatuan)
                $('#pmbdposisi').val(table.row(this).data().bhnsaldo)
                $('#modalBesar').modal("hide")
                $('#pmbdjumlah').val(1)
                $('#pmbdjumlah').focus()
            }
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






    $('#addBtnBahanOlah').click(function(e) {
        e.preventDefault()
        $('#titleModalBesar').html("Buat bahan olah")
        $('#modalBesar').modal('show');
    });

    $(document).on('click', '#btn-riwayat', function(e) {
        e.preventDefault();

        var id = $(this).data('id')
        var bhnnama = $(this).data('bhnnama')
        $('#modalBesar').modal("show")
        $('#titleModalBesar').html("Riwayat barang : " + bhnnama + ", diurutkan dari yang terbaru")
        getRiwayatBahan_bar(id)
        $('#modalBesar').modal('show');
    });

    $(document).on('click', '#btn-riwayat-olah', function(e) {
        e.preventDefault();

        var id = $(this).data('id')
        var bhnnama = $(this).data('bhnnama')
        $('#modalBesar').modal("show")
        $('#titleModalBesar').html("Riwayat barang olah : " + bhnnama + ", diurutkan dari yang terbaru")
        getRiwayatBahanOlah_bar(id)
        $('#modalBesar').modal('show');
    });

    $('#iconSearchBahan').click(function(e) {
        getAllBahanOlah()

        $('#modalBahanBarang').modal("show")
    });
</script>
