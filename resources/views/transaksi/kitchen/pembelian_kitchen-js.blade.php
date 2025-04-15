<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelPembelian()
        klikTabelPembelian()
        getAllBarang()
        klikTabelModalTransaksid()
        let processedData = [];
        let processedDataVarian = [];


        $('#tnsdjumlah').val('');

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
                url: "{{ route('getPembelian_kitchen', ['cabang' => '__cabang__']) }}".replace(
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
                    data: 'pmbtgl',
                    name: 'pmbtgl'
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
                url: "{{ route('getPembeliand_kitchen', ['cabang' => '__cabang__']) }}".replace(
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
                    data: 'pmbdbrgn',
                    name: 'pmbdbrgn'
                },
                {
                    data: 'pmbdjumlah',
                    name: 'pmbdjumlah'
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
                    url: "{{ route('addPembelian_kitchen', ['cabang' => '__cabang__']) }}"
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
                                title: "Berhasil Menambah Transaksi Pembelian"
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


    $(function() {

        $("#formInputPembeliand").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            var posisi = parseInt($('#pmbdposisi').val());
            var jumlah = parseInt($('#pmbdjumlah').val());

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
            } else if (jumlah > posisi) {
                Toast.fire({
                    icon: "error",
                    title: "Tidak cukup! Maksimal " + posisi
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addPembeliand_kitchen', ['cabang' => '__cabang__']) }}"
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
                                title: "Berhasil Menambah Bahan yang dibeli"
                            });
                            refreshSaldo()
                            // getBahanBarang($('#ipt_brgid'))
                            $('#dt_pembeliand').DataTable().ajax.reload();
                            $('.frmpembeliand').val('')
                            $('#titleModalBesar').html(
                                "Pilih Bahan yang akan diambil dari gudang besar")
                            getPickerBahan();
                            $('#modalBesar').modal('show');
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
                        .replace('__cabang__', cabang),
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
            url: "{{ route('generateIdTransaksiDetail', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang),
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
            url: "{{ route('getBahanFromVarian', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang),
            data: {
                'bvarid': $('#tempVarian').val()
            },
            dataType: "json",
            success: function(res) {
                console.log(res)
            }
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
            ajax: "{{ route('getAllBahanKitchen', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang),
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
                    name: 'satuan',
                    title: 'Satuan',

                },
                {
                    data: 'bhnsaldo',
                    name: 'bhnsaldo',
                    title: 'Stok gudang besar',

                },
                {
                    data: 'saldoKitchen',
                    title: 'Stok gudang kitchen',

                },

            ],
        });
        klikTabelPembeliand()
    };



    // --------------------------------------------------------untuk click2 button


    function klikTabelPembelian() {

        var table = $('#dt_pembelian').DataTable();
        table.on('click', 'tbody tr', function() {

            $('#dt_pembelian tbody tr').removeClass('selected');
            $(this).addClass('selected');


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


    $('#iconSearchBahan').on('click', function() {

        var transaksi = $("#pmbdparent").val()
        if (transaksi == '' || transaksi == null) {
            Toast.fire({
                icon: "error",
                title: "Pilih list order terlebih dahulu..."
            });
        } else {
            $('#titleModalBesar').html("Pilih Bahan yang akan diambil dari gudang besar")
            getPickerBahan();
            $('#modalBesar').modal('show');
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
</script>
