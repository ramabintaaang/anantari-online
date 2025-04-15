@extends('layouts.base')

@section('title')
    Transaksi / Kasir Bar
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Transaksi / Bar </li>
            <li class="breadcrumb-item active" aria-current="page">Kasir Bar</li>
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
                                        <h4>Transaksi Kasir Bar: <span id="titleDetail" class="badge bg-primary"></span>
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
                                        <button id="btnTambahTransaksid" class="col-md-2 col-xs-1" title="Tambah/Update"
                                            type="submit"><i class="bi bi-plus-circle"></i></button>
                                        <button id="btnResetTransaksi" class="col-md-2 col-xs-1" title="Reset form"
                                            type="button"><i class="bi bi-bootstrap-reboot"></i></button>

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
                                        <div class="col-sm-3" style="display: none">
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
                                                    <button id="btnTambahTransaksid" class="col-md-2 col-xs-1"
                                                        title="Tambah/Update Bahan" type="submit"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                    <button id="btnHapusTransaksid" class="col-md-2 col-xs-1"
                                                        title="Hapus Bahan"><i class="bi bi-trash"></i></button>
                                                    <button id="btnResetTransaksid" class="col-md-2 col-xs-1"
                                                        title="Reset form" type="button"><i
                                                            class="bi bi-bootstrap-reboot"></i></button>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
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
                                                                placeholder="Klik icon untuk cari barang" id="tnsdbarangn"
                                                                name="tnsdbarangn" readonly>

                                                            </span>
                                                            <span class="text-danger error tnsdbarang_error"></span>
                                                            <div class="form-control-icon">
                                                                <i class="bi bi-search" id="iconSearchBarang"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h6>jumlah<span class="text-danger " style="margin-left: 5px">*
                                                        </h6>
                                                        <div class="form-group position-relative has-icon-right">
                                                            <input type="text" class="form-control  nomor"
                                                                id="tnsdjumlah" name="tnsdjumlah">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdtotal" name="tnsdtotal" readonly
                                                                style="display: none">
                                                            <input type="text" class="form-control frmtransaksid"
                                                                id="tnsdhargaasli" name="tnsdhargaasli" readonly
                                                                style="display: none">

                                                            <span class="text-danger error tnsdjumlah_error"></span>
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
                                                <div class="col-md-8">
                                                    <div id="listVarian"></div>
                                                    <input type="text" id="tempVarian">
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
                                                        class="badge bg-primary"></span>
                                                </h4>
                                            </div>
                                            <div id="layoutBarang">
                                                <div class="row row-cols-1 row-cols-md-3 g-4">
                                                    <div class="col">
                                                        <div class="card h-100 border-primary">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 1">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title 1</p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 2">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title 2</p>
                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 3">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title asasdasasdasdasdasdas3
                                                                </p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 3">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title asasdasasdasdasdasdas3
                                                                </p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 3">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title asasdasasdasdasdasdas3
                                                                </p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 3">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title asasdasasdasdasdasdas3
                                                                </p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <img src="https://via.placeholder.com/150"
                                                                class="card-img-top" alt="Card Image 3">
                                                            <div class="card-body">
                                                                <p class="card-title">Card title asasdasasdasdasdasdas3
                                                                </p>
                                                                <p class="card-title text-success">Card title
                                                                    asasdasasdasdasdasdas3
                                                                </p>

                                                                <a href="#" class="btn btn-primary mt-auto">Go
                                                                    somewhere</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        {{-- <div class="card-body">
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
                                            </div> --}}
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
@endsection

@push('js')
    @include('transaksi.bar.kasir_bar-js')
@endpush
