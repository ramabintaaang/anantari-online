@extends('layouts.base')

@section('title')
    Stock Opname
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Utility</li>
            <li class="breadcrumb-item active" aria-current="page">Stock Opname</li>
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
                                        <h4>Stock Opname: <span id="titleDetail" class="badge bg-primary"></span>
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
                                                        id="btnSearchSO"><i class="bi bi-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                                <div class="divider">
                                    <div class="divider-text">Daftar List Stock Opaname </div>
                                </div>
                                <form class="form" id="formInputSO">
                                    @csrf
                                    <div class="row col-xs-12" style="margin-bottom: 10px" id="listButtonTransaksi">
                                        <button id="btnTambahSOP" class="col-md-2 col-xs-1 btn btn-primary" title="Tambah"
                                            type="submit"><i class="bi bi-plus-circle">Tambah</i></button>
                                        <button id="btnEditSOP" class="col-md-2 col-xs-1 btn btn-warning" title="Reset form"
                                            type="button" style="display:none;"><i class="bi bi-bootstrap-reboot" >Edit Transaksi</i></button>
                                        <button id="btnResetSOP" class="col-md-2 col-xs-1 btn btn-info" title="Reset form"
                                            type="button" style="display:none;"><i class="bi bi-bootstrap-reboot" >Reset Form</i></button>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6>Keterangan<span class="text-danger " style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="text" class="form-control frmtransaksi"
                                                    placeholder="Keterangan Stock Opname" id="sopnama" name="sopnama">
                                                <span class="text-danger error sopnama_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <h6>Tanggal Stock Opname<span class="text-danger "
                                                    style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <input type="date" class="form-control frmtransaksi"
                                                    placeholder="Keterangan Pembelian" id="soptgl" name="soptgl">
                                                <span class="text-danger error soptgl_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <h6>Gudang<span class="text-danger " style="margin-left: 5px">*</span>
                                            </h6>
                                            <div class="form-group position-relative has-icon-right">
                                                <select class="form-select " id="sopgudang" name="sopgudang">
                                                    @foreach ($gudang as $datas)
                                                        <option value={{ $datas->gudangid }}>
                                                            {{ $datas->gudangn }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- <input type="text" class="form-control frmtransaksi"
                                                    placeholder="Keterangan Pembelian" id="sopgudang" name="sopgudang"
                                                    value="{{ $gudang[0]->gudangid }}" readonly style="display: none">
                                                <input type="text" class="form-control "
                                                    placeholder="Keterangan Pembelian" id="sopgudangn" name="sopgudangn"
                                                    value="{{ $gudang[0]->gudangn }}" readonly> --}}
                                                <span class="text-danger error sopgudang_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                {{--  --}}
                                <div class="col-md-12 col-xs-12">
                                    <div class="card ">
                                        <div class="card-header">
                                            <div class="card-body bg-light">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_so"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>keterangan</th>
                                                                <th>Tanggal StockOpname</th>
                                                                <th>User</th>
                                                                <th>Gudang</th>
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
                                    <div class="divider-text">Detail Stock Opname </div>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-center">
                                                <h4>Bahan yang di stock opname</h4>
                                            </div>
                                            <form class="form" id="formInputSOD">
                                                @csrf
                                                <div class="row col-xs-12" style="margin-bottom: 10px"
                                                    id="listButtonPembeliand">
                                                    <button id="btnTambahSOPD" class="btn btn-primary col-md-2 col-xs-1"
                                                        title="Tambah" type="submit"><i
                                                            class="bi bi-plus-circle">Tambah</i></button>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6>Nama Bahan<span class="text-danger "
                                                                style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmsopd"
                                                                id="sopdbahan" placeholder="transaksi" name="sopdbahan"
                                                                readonly style="display: none">
                                                            <input type="text" class="form-control" id="sopdparent"
                                                                placeholder="generate" name="sopdparent" readonly
                                                                style="display: none">
                                                            <div class="input-group mb-3">
							  <input type="text" class="form-control" placeholder="Cari barang" aria-label="Recipient's username" aria-describedby="button-addon2" name="sopdbahann" id="sopdbahann" readonly>
							  <button class="btn btn-outline-primary" type="button" id="iconSearchBahan">Cari</button>
							</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>jumlah fisik<span class="text-danger "
                                                                style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control  nomor "
                                                                id="tempGudangUtama" name="tempGudangUtama"
                                                                style="display: none">
                                                            <input type="text" class="form-control  nomor "
                                                                id="sopdjumlah" name="sopdjumlah">
                                                            <input type="text" class="form-control frmsopd"
                                                                id="sopdposisi" name="sopdposisi" readonly>
                                                            <small>jumlah yang ada di sistem</small>

                                                            <span class="text-danger error sopdjumlah_error"></span>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>Keterangan
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="sopdket" name="sopdket">

                                                            <span class="text-danger error sopdket_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="card-body">
                                                <div class="table-responsive" style="overflow-x: hidden; height:500px;">
                                                    <table class="table table-hover mb-0 " id="dt_sopd"
                                                         >
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama</th>
                                                                <th>Jenis</th>
                                                                <th>Jumlah</th>
                                                                <th>User</th>
                                                                <th>Tgl Input</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-center">
                                                <h4>100 Stock Opname Terakhir : <span id="titleBahand"
                                                        class="badge bg-primary"></span>
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_stockopname_riwayat"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Jumlah</th>
                                                                <th>Tanggal</th>
                                                                <th>Gudang</th>
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
    <!-- // Basic multiple Column Form section end -->
    </div>

    @include('layouts.modal')

    {{-- MODAL --}}
    <!--Basic Modal -->
    <div class="modal fade text-left" id="modalBahan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalBahan"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_modalBahan" style="width:100%">
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
@endsection

@push('js')
    @include('utility.stockOpname.stockOpname-js')
@endpush
