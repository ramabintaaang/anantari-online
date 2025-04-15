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
                url: "{{ route('getPembelian') }}",
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
                    data: 'pmbjenis',
                    name: 'pmbjenis'
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
                url: "{{ route('getPembelianDetail') }}",
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
            ajax: "{{ route('getAllBahanOnly') }}",
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
                    url: "{{ route('addPembelian') }}",
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
                    url: "{{ route('addPembeliand') }}",
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
            url: "{{ route('getBahanFromTransaksid') }}",
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
            url: "{{ route('getVarianFromBarang') }}",
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
            url: "{{ route('generateIdTransaksiDetail') }}",
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
            url: "{{ route('getBahanFromVarian') }}",
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


    function klikTabelPembelian() {

        var table = $('#dt_pembelian').DataTable();
        table.on('click', 'tbody tr', function() {


            $('#pmbdparent').val(table.row(this).data().pmbid)
            $('#pmbdgudang').val(table.row(this).data().pmbgudang)

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
                confirmButtonText: "Yes, Hapus saja!"
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



        if ($('#tempDivisi').val() != 'admin' || $('#tempDivisi').val() == 'purchasing') {
            Toast.fire({
                icon: "error",
                title: "Anda tidak mempunyai akses untuk mengedit"
            });
        } else {
            Swal.fire({
                title: "Yakin untuk menghapus " + barangn + " ?",
                text: "jika setuju maka saldo gudang akan terkakulasi",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, hapus saja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    deletePembeliand(id, jumlah, barang)
                }
            });
        }

    });

    function deletePembeliand(id, jumlah, barang) {
        $.ajax({
            type: "POST",
            url: "{{ route('deletePembeliand') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                'pmbdid': id,
                'pmbdjumlah': jumlah,
                'pmbdbrg': barang,
            },
            dataType: "json",
            success: function(response) {
                Toast.fire({
                    icon: "success",
                    title: "Bahan berhasil dihapus dari pembelian"
                });
                $('#dt_pembeliand').DataTable().ajax.reload();

            }
        });
    }
</script>
