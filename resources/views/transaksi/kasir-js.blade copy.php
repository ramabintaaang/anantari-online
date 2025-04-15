<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelTransaksi()
        klikTabelTransaksi()

        let processedData;
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
                url: "{{ route('getTransaksi') }}",
                data: function(d) {
                    d.ipt_tgldari = $('#ipt_tgldari').val(),
                        d.ipt_tglsampai = $('#ipt_tglsampai').val(),
                        d.ipt_nama = $('#ipt_nama').val()
                }
            },
            columns: [{
                    data: 'tnsid',
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

    function getTabelTransaksiDetail(id) {
        var groupColumn = 2; // Kolom ke-2 yang akan digunakan untuk pengelompokan
        var table = $('#dt_transaksid').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            destroy: true,
            ajax: {
                url: "{{ route('getTransaksiDetail') }}",
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

        // Menambahkan event handler untuk baris dalam tabel detail
        $('#dt_transaksid').off('click').on('click', 'tbody tr', function() {
            var table = $('#dt_transaksid').DataTable();
            $('#titleBahand').html(table.row(this).data().tnsdjumlah + '-' + table.row(this).data().brgnama);
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
                url: "{{ route('getTransaksiDetailBahan') }}",
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


    function getBahanFromTransaksid() {
        $.ajax({
            type: "GET",
            url: "{{ route('getBahanFromTransaksid') }}",
            data: {
                'tnsdbarang': $('#tnsdbarang').val(),
                'transaksi': $('#tnsdparent').val(),

            },
            dataType: "json",
            success: function(res) {
                // Proses data setelah didapat
                processedData = processData(res.bahan);
                // sendDataToServer(processedData)
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

    // Fungsi untuk mengirim data ke server
    function sendDataToServer(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('addTransaksiDetailBahan') }}",
            data: JSON.stringify({
                bahan: data,
                transaksi: $('#tnsdgenerate').val(),

            }),
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log("Data berhasil dikirim:", transaksi);
                // $('#dt_transaksid').DataTable().ajax.reload();

            },
            error: function(xhr, status, error) {
                console.log("Error: ", error);
            }
        });

    }


    // function getTabelTransaksi() {
    //     $.ajax({
    //         type: "GET",
    //         url: "{{ route('getTransaksi') }}",
    //         data: {
    //             'tnsnama': $('#ipt_nama').val(),
    //             'ipt_tgldari': $('#ipt_tgldari').val(),
    //             'ipt_tglsampai': $('#ipt_tglsampai').val()
    //         },
    //         dataType: "json",
    //         success: function(res) {
    //             tabelTransaksi(res.data)
    //             Toast.fire({
    //                 icon: "success",
    //                 title: "Berhasil Mendapatkan Data"
    //             });
    //         }
    //     });
    // }

    function tabelTransaksi(data) {
        var tbody = $('#tb_transaksi');
        tbody.empty(); // Kosongkan tbody sebelum menambah data baru

        data.forEach(function(item) {
            var row = '<tr>' +
                '<td>' + item.tnsid + '</td>' +
                '<td>' + item.tnsnama + '</td>' +
                '<td>' + item.created_at + '</td>' +
                '<td>' + item.tnstotal + '</td>' +
                '<td>' + item.tnstatus + '</td>' +
                '<td><a href="#" title="Hapus"><i class="bi bi-trash"></i></a></td>'

            '</tr>';
            tbody.append(row);
        });
    }



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
            ajax: "{{ route('getAllBahan') }}",
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
            ajax: "{{ route('getAllBarang') }}",
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
                url: "{{ route('getBarangBahan') }}",
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
                    url: "{{ route('addTransaksi') }}",
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

        $("#formTransaksid").on('submit', function(e) {
            e.preventDefault();
            var a = new FormData(this);

            if ($('#tnsdparent').val() == '') {
                Toast.fire({
                    icon: "error",
                    title: "Pilih Transaksi dulu yuk..."
                });

            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('addTransaksiDetail') }}",
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
                                title: "Berhasil Menambah Transaksi detail"
                            });
                            $('.frmtransaksid').val('')
                            $('.error').html('')
                            $('#dt_transaksid').DataTable().ajax.reload();


                        }
                    },
                    error: function(xhr) {}
                });

                sendDataToServer(processedData);

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
                url: "{{ route('addBarangBahan') }}",
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

    function klikTabelTransaksi() {

        var table = $('#dt_transaksi').DataTable();
        table.on('click', 'tbody tr', function() {


            $('#tnsdparent').val(table.row(this).data().tnsid)
            $('#titleDetail').html(table.row(this).data().tnsid + ' - Atas nama : ' + table.row(this).data()
                .tnsnama)
            $('.error').html('')
            getTabelTransaksiDetail(table.row(this).data().tnsid)


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



        });
    }

    function generateTransaksid() {
        $.ajax({
            type: "get",
            url: "{{ route('generateIdTransaksiDetail') }}",
            data: "data",
            dataType: "json",
            success: function(res) {
                $('#tnsdgenerate').val(res.data)
            }
        });
    }


    // --------------------------------------------------------untuk click2 button
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
        getAllBarang()
        klikTabelModalTransaksid()
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

        // getBahanFromTransaksid()

        if (!isNaN(hargaasli) && !isNaN(jumlah)) {
            var nilai = hargaasli * jumlah;
            $('#tnsdtotal').val(nilai);
        }
    });
</script>
