@extends('layouts.base')

@section('title')
    Posss
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Library</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>


    <div class="page-heading">
        {{-- <h3>Halo , {{ auth::user()->username }}</h3> --}}
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah Produk Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{ $jmlBarang[0]->jumlah }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah Bahan</h6>
                                        @foreach ($jmlBahan as $bhn)
                                            <h6 class="font-extrabold mb-0">{{ $bhn->jumlah }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah bahan olah</h6>
                                        @foreach ($jmlBahanOlah as $bhn)
                                            <h6 class="font-extrabold mb-0">{{ $bhn->jumlah }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h4>10 Produk terlaris</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="dt_topbarang">
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <h4>Grafik Penjualan Produk : <span id="txtProduk" class="badge bg-success"></span>
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="selectBarang" name="selectBarang">
                                                        @foreach ($listBarang as $x)
                                                            <option value="{{ $x->brgid }}">{{ $x->brgnama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small>cari produk</small>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="chart_tgldari"
                                                    placeholder="Cari produk...">
                                                <small>tanggal dari</small>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="chart_tglsampai"
                                                    placeholder="Cari produk...">
                                                <small>tanggal sampai</small>
                                            </div>
                                        </div>

                                        <button class="btn btn-success mt-3 btn-block" id="prosesChart">Proses</button>
                                </div>
                            </div>
                            <div id="chart"></div>
                        </div>
                    </div>




                </div>
                <div class="row">

                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Riwayat Aktivitas Stock Opname</h4>
                            </div>
                            <div class="card-body" style="max-height: 500px; overflow-y:auto;">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Tanggal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($catSO as $x)
                                                <tr>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <p class="font-bold ms-3 mb-0">{{ $x->sopcuser }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <p class="font-bold ms-3 mb-0">{{ $x->sopctgl }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class=" mb-0">{{ $x->sopcket }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                {{-- <div class="card">
                    <div class="card-body py-4 px-5">

                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Face 1">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">John Duck</h5>
                                <h6 class="text-muted mb-0">@johnducky</h6>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-header">
                        <h4>20 Pembelian Terbaru ke gudang besar</h4>
                    </div>

                    <div class="card-content pb-4" style="max-height: 500px; overflow-y:auto;">
                        @foreach ($pembelianTerbaru as $datas)
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="name ms-4">
                                    <h5 class="mb-1">{{ $datas->pmbdbrgn }} - {{ $datas->pmbdjumlah }}</h5>
                                    <h6 class="text-muted mb-0">
                                        {{ \Carbon\Carbon::parse($datas->created_at)->translatedFormat('j F Y - H:i') }}
                                    </h6>
                                </div>
                            </div>
                        @endforeach


                    </div>
                    <div class="card-footer">
                        <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Lihat Selengkapnya
                        </button>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header">
                        <h4>Visitors Profile</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-visitors-profile"></div>
                    </div>
                </div> --}}
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            getTopBarang();
        });

        function getTopBarang() {
            $('#dt_topbarang').DataTable({
                scrollCollapse: true,
                paging: false,
                processing: true,
                retrieve: true,
                destroy: true,
                searching: false,
                info: false,
                // serverSide: true,
                // autoWidth: true,
                ajax: {
                    url: "{{ route('dashboard', ['cabang' => '__cabang__']) }}".replace(
                        '__cabang__', cabang),
                },
                columns: [{
                        data: 'brgnama',
                        name: 'brgnama',
                        title: 'Barang',

                    },
                    {
                        data: 'brjnama',
                        name: 'brjnama',
                        title: 'Kelompok',

                    },
                    {
                        data: 'jumlahs',
                        name: 'jumlahs',
                        title: 'Jumlah terjual',

                    },

                ],
            });
        };


        function chartTrenPenjualan() {
            $.ajax({
                url: "{{ route('chartTrenPenjualan') }}",
                data: {
                    brgid: $('#selectBarang').val(),
                    tgldari: $('#chart_tgldari').val(),
                    tglsampai: $('#chart_tglsampai').val()
                },
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#txtProduk').html($('#selectBarang :selected').text())
                    Toast.fire({
                        icon: "success",
                        title: "Berhasil generate grafik"
                    });
                    var options = {
                        series: [{
                            name: "Jumlah Penjualan",
                            data: data.series
                        }],
                        chart: {
                            type: 'line',
                            height: 350
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        title: {
                            text: 'Grafik Penjualan',
                            align: 'left'
                        },
                        xaxis: {
                            categories: data.labels, // Gunakan tanggal dari API
                            type: 'datetime'
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah'
                            }
                        }
                    };

                    // Hapus chart lama jika sudah ada
                    $("#chart").html("");

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                },
                error: function(xhr, status, error) {
                    console.error("Gagal mengambil data. Status: " + status + ", Error: " + error);
                }

            });
        }


        $('#prosesChart').click(function(e) {

            chartTrenPenjualan();
        });
    </script>
@endpush
