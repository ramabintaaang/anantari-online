@extends('layouts.base')

@section('title')
    Pembelian - Order Kitchen
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Kitchen</li>
            <li class="breadcrumb-item active" aria-current="page">Order</li>

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
                                        <p>Di halaman ini, order bahan tergantung stok yang ada di gudang besar </p>
                                        <h4>Pembelian / Order: <span id="titleDetail" class="badge bg-primary"></span>
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
                                    <div class="divider-text">Daftar Pembelian </div>
                                </div>
                                <form class="form" id="formInputPembelian">
                                    @csrf
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonTransaksi">
                                        <button id="btnTambahTransaksid" class="col-md-2 col-xs-1" title="Tambah/Update"
                                            type="submit"><i class="bi bi-plus-circle"></i></button>
                                        <button id="btnResetTransaksi" class="col-md-2 col-xs-1" title="Reset form"
                                            type="button"><i class="bi bi-bootstrap-reboot"></i></button>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6>Keterangan<span class="text-danger " style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="text" class="form-control frmtransaksi"
                                                    placeholder="Keterangan Pembelian / Pengambilan barang" id="pmbket"
                                                    name="pmbket">
                                                <span class="text-danger error pmbket_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <h6>Tanggal Beli / Pengambilan barang<span class="text-danger "
                                                    style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="date" class="form-control frmtransaksi"
                                                    placeholder="Keterangan Pembelian" id="pmbtgl" name="pmbtgl">
                                                <span class="text-danger error pmbtgl_error"></span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-3">
                                            <h6>Jenis
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <select class="form-select " id="pmbjenis" name="pmbjenis">
                                                    <option value="cash">
                                                        Cash
                                                    </option>
                                                    <option value="kredit">
                                                        Kredit
                                                    </option>
                                                </select>
                                            </div>
                                        </div> --}}
                                    </div>
                                </form>
                                {{--  --}}
                                <div class="col-md-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_pembelian"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>keterangan</th>
                                                                <th>Tanggal Beli</th>
                                                                {{-- <th>Jenis</th> --}}
                                                                <th>User</th>
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
                                    <div class="divider-text">Detail Pembelian </div>
                                </div>
                                <div class="col-md-7 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-center">
                                                <h4>Bahan yang dibeli</h4>
                                            </div>
                                            <form class="form" id="formInputPembeliand">
                                                @csrf
                                                <div class="row col-xs-12" style="margin-bottom: 10px"
                                                    id="listButtonPembeliand">
                                                    <button id="btnTambahPembeliand" class="col-md-2 col-xs-1"
                                                        title="Tambah/Update Bahan" type="submit"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                    <button id="btnHapusPembeliand" class="col-md-2 col-xs-1"
                                                        title="Hapus Bahan"><i class="bi bi-trash"></i></button>
                                                    <button id="btnResetPembeliand" class="col-md-2 col-xs-1"
                                                        title="Reset form" type="button"><i
                                                            class="bi bi-bootstrap-reboot"></i></button>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6>Nama Bahan<span class="text-danger "
                                                                style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmpembeliand"
                                                                id="pmbdbrg" placeholder="transaksi" name="pmbdbrg"
                                                                readonly style="display: none">
                                                            <input type="text" class="form-control" id="pmbdparent"
                                                                placeholder="generate" name="pmbdparent" readonly
                                                                style="display: none">
                                                            <input type="text" class="form-control " id="tnsdbarang"
                                                                name="tnsdbarang" readonly style="display: none">
                                                            <input type="text" class="form-control " id="pmbdsatuan"
                                                                name="tnsdbarang" readonly style="display: none">
                                                            <input type="text" class="form-control frmpembeliand"
                                                                placeholder="Klik icon untuk cari bahan" id="pmbdbrgn"
                                                                name="pmbdbrgn" readonly>

                                                            </span>
                                                            <span class="text-danger error tnsdbarang_error"></span>
                                                            <div class="form-control-icon">
                                                                <i class="bi bi-search" id="iconSearchBahan"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>jumlah<span class="text-danger " style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control  nomor"
                                                                id="pmbdjumlah" name="pmbdjumlah">
                                                            <input type="text" class="form-control frmpembeliand"
                                                                id="pmbdposisi" name="pmbdposisi" readonly>
                                                            <span class="text-primary"> Jumlah stok di gudang
                                                                besar</span>

                                                            <input type="text" class="form-control frmpembeliand"
                                                                id="tnsdhargaasli" name="tnsdhargaasli" readonly
                                                                style="display: none">

                                                            <span class="text-danger error pmbdjumlah_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>Keterangan
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmpembeliand"
                                                                id="pmbdket" name="pmbdket">

                                                            <span class="text-danger error pmbdket_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div id="listVarian"></div>
                                                    <input type="text" id="tempVarian" style="display: none">
                                                </div>
                                            </form>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_pembeliand"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama</th>
                                                                <th>Jumlah</th>
                                                                <th>User</th>
                                                                <th>Tgl Input</th>
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
                                                <h4>Riwayat Permintaan : <span id="titleBahand"
                                                        class="badge bg-primary"></span>
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_bahand"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Terpakai</th>
                                                                <th>Sisa</th>
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
                            </div>
                        </div>
                    </div>
                </div>

        </section>
    </div>

    {{-- TEMPLATE --}}
    @include('layouts.modal')
@endsection

@push('js')
    @include('transaksi.kitchen.pembelian_kitchen-js')
@endpush
