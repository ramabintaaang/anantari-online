@extends('layouts.base')

@section('title')
    Transaksi
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
            <li class="breadcrumb-item active" aria-current="page">Kasir</li>
        </ol>
    </nav>

    <div class="page-content">
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-md-12 col-xs-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4 class="card-title">Multiple Column</h4>
                        </div> --}}
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" id="formTransaksi">
                                    @csrf
                                    <div class="row">
                                        <h4>Transaksi Kasir: <span id="titleDetail" class="badge bg-primary"></span>
                                        </h4>
                                        <hr>
                                        <div class="row">
                                            <h5>filter</h5>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">tanggal dari</label>

                                                    <input type="date" id="ipt_tgldari" class="form-control frmbarang"
                                                        name="ipt_tgldari">

                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">tanggal sampai</label>

                                                    <input type="date" id="ipt_tglsampai" class="form-control frmbarang"
                                                        name="ipt_tglsampai">

                                                </div>
                                            </div>
                                            {{-- <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Nama Pemesan</label>
                                                    <input type="text" id="ipt_nama" class="form-control frmbarang"
                                                        name="ipt_nama">
                                                    <span class="text-danger error ipt_brgid_error "></span>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column"></label>
                                                    <button class="btn btn-success form-control" type="button"
                                                        id="btnSearchTransaksi"><i class="bi bi-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                                <div class="divider">
                                    <div class="divider-text">List Transaksi </div>
                                </div>
                                <form class="form" id="formInputTransaksi">
                                    @csrf
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonTransaksi">
                                        <button id="btnTambahTransaksid" class="btn btn-primary col-md-2 col-xs-1"
                                            title="Tambah/Update" type="submit"><i class="bi bi-plus-circle"></i></button>
                                        <button id="btnResetTransaksi" class="btn btn-secondary col-md-2 col-xs-1"
                                            title="Reset form" type="button" style="margin-left: 10px"><i
                                                class="bi bi-bootstrap-reboot"></i></button>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6>Nama<span class="text-danger " style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="text" class="form-control frmtransaksi"
                                                    placeholder="Nama Pemesan" id="tnsnama" name="tnsnama">
                                                <span class="text-danger error tnsnama_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <h6>Jenis
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <select class="form-select " id="tnsjenis" name="tnsjenis">
                                                    <option value="1">
                                                        Dine in
                                                    </option>
                                                    <option value="2">
                                                        Take Away
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                {{--  --}}
                                <div class="col-md-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_transaksi"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama</th>
                                                                <th>Tanggal</th>
                                                                <th>Total</th>
                                                                <th>User</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider">
                                    <div class="divider-text">Detail Transaksi </div>
                                </div>
                                <div class="col-md-7 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-center">
                                                <h4>Barang yang dipesan</h4>
                                            </div>
                                            <form class="form" id="formTransaksid">
                                                @csrf
                                                <div class="row col-xs-12" style="margin-bottom: 10px"
                                                    id="listButtonTransaksid">
                                                    <button id="btnTambahTransaksid"
                                                        class="col-md-2 col-xs-1 btn btn-success"
                                                        title="Tambah/Update Bahan" type="submit"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                    <button id="btnTambahAddOn" class="col-md-4 col-xs-1 btn btn-primary"
                                                        title="Tambah/Update Bahan" type="button"
                                                        style="margin-left: 10px">Tambah add on</button>
                                                    {{-- <button id="btnHapusTransaksid" class="col-md-2 col-xs-1"
                                                        title="Hapus Bahan"><i class="bi bi-trash"></i></button>
                                                    <button id="btnResetTransaksid" class="col-md-2 col-xs-1"
                                                        title="Reset form" type="button"><i
                                                            class="bi bi-bootstrap-reboot"></i></button> --}}

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6>Nama Barang<span class="text-danger "
                                                                style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdparent" placeholder="transaksi" name="tnsdparent"
                                                                readonly style="display: none">
                                                            <input type="text" class="form-control" id="tnsdgenerate"
                                                                placeholder="generate" name="tnsdgenerate" readonly
                                                                style="display: none">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdbarang" name="tnsdbarang" readonly
                                                                style="display: none">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                placeholder="Klik produk dari list barang / produk"
                                                                id="tnsdbarangn" name="tnsdbarangn" readonly>

                                                            </span>
                                                            <span class="text-danger error tnsdbarang_error"></span>
                                                            {{-- <div class="form-control-icon">
                                                                <i class="bi bi-search" id="iconSearchBarang"></i>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>jumlah<span class="text-danger " style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control  nomor"
                                                                id="tnsdjumlah" name="tnsdjumlah">

                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdhargaasli" name="tnsdhargaasli" readonly
                                                                style="display: none">

                                                            <span class="text-danger error tnsdjumlah_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>Total
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdtotal" name="tnsdtotal" readonly>

                                                            <span class="text-danger error tnsdtotal_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>Keterangan
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdketerangan" name="tnsdketerangan">

                                                            <span class="text-danger error tnsdketerangan_error"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="text" class="form-control frmtransaksid" id="tnsdgudang"
                                                    name="tnsdgudang" readonly style="display: none;">
                                                <div class="col-md-8">
                                                    <div id="listVarian"></div>
                                                    <input type="text" id="tempVarian" style="display: none">
                                                </div>
                                            </form>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_transaksid"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Jumlah</th>
                                                                <th>Jenis</th>
                                                                <th>Total</th>
                                                                <th>Keterangan</th>
                                                                {{-- <th>User</th> --}}
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-center">
                                                <h4>Input barang / produk <span id="titleBahand"
                                                        class="badge bg-primary"></span></h4>
                                                <div class="row mt-5">
                                                    <div class="col-sm-4">
                                                        <h6>Jenis Barang</h6>
                                                        <select class="form-select" id="barangJenis" name="barangJenis">
                                                            <option value="All">All
                                                            </option>
                                                            @foreach ($jenis as $datas)
                                                                <option value="{{ $datas->brjid }}">{{ $datas->brjnama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <h6>Cari Barang</h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="namaBarang" name="namaBarang">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="layoutBarang" style="overflow-x: hidden; height:500px;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </section>
    </div>
    <!-- // Basic multiple Column Form section end -->
    </div>

    {{-- MODAL --}}
    <!--Basic Modal -->
    <div class="modal fade text-left" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalBarang"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_modalBarang" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>harga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    {{-- TEMPLATE --}}
    @include('layouts.modal')
@endsection

@push('js')
    @include('transaksi.kasir-js')
@endpush
