<script>
    $(document).ready(function() {
        $('.frmtransaksid').val('')
        $('#ipt_tgldari').val(tglsekarang)
        $('#ipt_tglsampai').val(tglsekarang)
        getTabelGudangBesar();
        getTabelGudangBar();
        getTabelGudangKitchen();

        let processedData = [];
        let processedDataVarian = [];


        $('#tnsdjumlah').val('');

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
                url: "{{ route('getTabelBahanBar', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            rowCallback: function(row, data) {
                if (data.bhnsaldo < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }

    function getTabelGudangBesar() {
        $('#dt_gudang_besar').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getGudangBesar', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'sbmasuk',
                    name: 'sbmasuk'
                },
                {
                    data: 'sbkeluar',
                    name: 'sbkeluar'
                },
                {
                    data: 'sbadjust',
                    name: 'sbadjust'
                },

            ],
            rowCallback: function(row, data) {
                if (data.bhnsaldo < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }

    function getTabelGudangBesar() {
        $('#dt_gudang_besar').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getGudangBesar', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'bhnmin',
                    name: 'bhnmin'
                },
                {
                    data: 'bhnmax',
                    name: 'bhnmax'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            rowCallback: function(row, data) {
                if (data.bhnsaldo < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
                // else if (data.bhnsaldo > data.bhnmin) {
                //     $(row).css('background-color',
                //         '#46CF76'); // Ganti warna latar belakang baris menjadi merah
                //     $(row).css('color',
                //         'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                // }
            }
        });
    }


    function getTabelGudangBar() {
        $('#dt_gudang_bar').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getGudangBar', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'bhnmin',
                    name: 'bhnmin'
                },
                {
                    data: 'bhnmax',
                    name: 'bhnmax'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            rowCallback: function(row, data) {
                if (data.bhnsaldo < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }


function getTabelGudangKitchen() {
        $('#dt_gudang_kitchen').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            destroy: true,
            ajax: {
                url: "{{ route('getGudangKitchen', ['cabang' => '__cabang__']) }}"
                    .replace(
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
                    data: 'bhnsaldo',
                    name: 'bhnsaldo'
                },
                {
                    data: 'bhnmin',
                    name: 'bhnmin'
                },
                {
                    data: 'bhnmax',
                    name: 'bhnmax'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            rowCallback: function(row, data) {
                if (data.bhnsaldo < data.bhnmin) { // Periksa apakah saldo kurang dari minimum
                    $(row).css('background-color',
                        '#ffa6a6'); // Ganti warna latar belakang baris menjadi merah
                    $(row).css('color',
                        'white'); // Opsional: Ganti warna teks menjadi putih agar terlihat jelas
                }
            }
        });
    }




    // --------------------------------------------------------untuk click2 button




    $(document).on('click', '.btn-riwayat', function(e) {
        e.preventDefault()

        var id = $(this).data('id');
        var bhnnama = $(this).data('bhnnama');
        // Tampilkan modal
        $('#modalBesar').modal("show")
        $('#titleModalBesar').html("Riwayat barang : " + bhnnama)
        initDT(id, bhnnama)
        $('#modalBesar').modal('show');
    });



    function initDT(id, bhnnama) {

        var url;

        // Mengambil nilai dari sgudangutama dan memeriksa nilainya
        if ($('#sgudangutama').val() == 2) {
            url = "{{ route('getRiwayatBahan', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang);
        } else if ($('#sgudangutama').val() == 3) {
            url = "{{ route('getRiwayatBahan', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang);
        } else {
            url = "{{ route('getRiwayatBahanGudangBesar', ['cabang' => '__cabang__']) }}"
                .replace('__cabang__', cabang);
        }

        var tableHTML = `
     
        <div>
            <table class="table table-hover mb-0" id="dt_riwayatBahan" style="width:100%"></table>
        </div>`;


        $('#tableContainerBesar').html(tableHTML);
        // $('#footerModalBesar').html(`<div class="col-sm-12 d-flex justify-content-end">
        //     <button type="submit" class="btn btn-primary me-1 mb-1" id="simpan_tnsdedit">Submit</button>
        // </div>`)
        $('#dt_riwayatBahan').DataTable({
            scrollCollapse: true,
            paging: true,
            processing: true,
            retrieve: true,
            ajax: {
                url: url,
                type: 'GET',
                data: function(d) {
                    d.bhnid = id
                    d.gudang = $('#sgudang').val()
                }
            },
            columns: [{
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
                    name: 'sbmasuk',
                    title: 'masuk',
                },
                {
                    data: 'sbkeluar',
                    name: 'sbkeluar',
                    title: 'keluar',
                },
                {
                    data: 'sbadjust',
                    name: 'sbadjust',
                    title: 'adjust',
                },


            ],
        });
    }

    $('#home-tab').click(function(e) {
        $.ajax({
            type: "GET",
            url: "{{ route('getMacamGudang', ['cabang' => '__cabang__']) }}"
                .replace(
                    '__cabang__', cabang),
            data: {
                'gudangutama': 1
            },
            dataType: "json",
            success: function(res) {
                $('#sgudang').val(res.data[0].gudangid)
                $('#sgudangn').val(res.data[0].gudangn)
                $('#sgudangutama').val(res.data[0].gudangutama)

            }
        });
    });


    $('#profile-tab').click(function(e) {
        $.ajax({
            type: "GET",
            url: "{{ route('getMacamGudang', ['cabang' => '__cabang__']) }}"
                .replace(
                    '__cabang__', cabang),
            data: {
                'gudangutama': 2
            },
            dataType: "json",
            success: function(res) {
                $('#sgudang').val(res.data[0].gudangid)
                $('#sgudangn').val(res.data[0].gudangn)
                $('#sgudangutama').val(res.data[0].gudangutama)

            }
        });
    });

    $('#contact-tab').click(function(e) {
        $.ajax({
            type: "GET",
            url: "{{ route('getMacamGudang', ['cabang' => '__cabang__']) }}"
                .replace(
                    '__cabang__', cabang),
            data: {
                'gudangutama': 3
            },
            dataType: "json",
            success: function(res) {
                $('#sgudang').val(res.data[0].gudangid)
                $('#sgudangn').val(res.data[0].gudangn)
                $('#sgudangutama').val(res.data[0].gudangutama)

            }
        });
    });
</script>
