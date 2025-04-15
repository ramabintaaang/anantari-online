<script>
    let divisi = 'bar';
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelTransaksi()
        klikTabelTransaksi()
        getAllBarang()
        klikTabelModalTransaksid()
        let processedData = [];
        let processedDataVarian = [];

        $('#barangJenis').val('All')

        getMenuScroll('All')


        $('#tnsdjumlah').val('');

    });

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
            url: "{{ route('getAjaxBarang') }}",
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
                        <div class="card border-primary position-relative" style="margin: 10px 0; padding: 0;" onclick="handleCardClick('${data.brgid}','${data.brgnama}','${data.brgharga}')">
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



    function handleCardClick(brgid, brgnama, brgharga) {
        $('#tnsdbarangn').val(brgnama)
        $('#tnsdbarang').val(brgid)
        $('#tnsdtotal').val(brgharga);
        $('#tnsdhargaasli').val(brgharga);
        $('#tnsdjumlah').val(1)
        $('#tnsdjumlah').focus()

        getBahanFromTransaksid()
        getVarianFromBarang()

        // Atau lakukan tindakan lain sesuai dengan kebutuhan aplikasi Anda
    }




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
                // {
                //     data: 'user_created',
                //     name: 'user'
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
            a.append('tnsdivisi', divisi);

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
            a.append('tnsddivisi', divisi);
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
                    url: "{{ route('addTransaksiDetailBar') }}",
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
                            $('#dt_transaksi').DataTable().ajax.reload();
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



    // --------------------------------------------------------untuk click2 button


    function klikTabelTransaksi() {

        var table = $('#dt_transaksi').DataTable();
        table.on('click', 'tbody tr', function() {


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
</script>
