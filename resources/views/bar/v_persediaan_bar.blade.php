@extends('layouts.base')

@section('title')
    Persediaan - Bar
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Bar</li>
            <li class="breadcrumb-item active" aria-current="page">Persediaan</li>

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
                                        <p>Bahan akan berwarna merah apabila saldo kurang dari minimal saldo (dapat di
                                            setting di master bahan) , <b>defaultnya adalah 10</b> </p>
                                        <h4>Persediaan bahan di gudang bar: <span id="titleDetail"
                                                class="badge bg-primary"></span>
                                        </h4>
                                        <hr>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0 " id="dt_bahan_bar"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Kode</th>
                                                                        <th>Nama</th>
                                                                        <th>Saldo</th>

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
                                </form>
                                <div class="divider">
                                    <div class="divider-text"> </div>
                                </div>
                                <div class="row">
                                    <p>Bahan akan berwarna merah apabila saldo kurang dari minimal saldo (dapat di
                                        setting di master bahan olah) , <b>defaultnya adalah 10</b> </p>
                                    <h4>Persediaan bahan olah di gudang bar: <span id="titleDetail"
                                            class="badge bg-primary"></span>
                                    </h4>
                                    <hr>
                                    <div class="row">
                                        <form class="form" id="formBahanOlah">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6>Bahan olah yang ingin dibuat<span class="text-danger "
                                                            style="margin-left: 5px">*
                                                    </h6>
                                                    <div class="form-group position-relative has-icon-right">
                                                        <input type="text" class="form-control" id="bhoid"
                                                            placeholder="transaksi" name="bhoid" readonly
                                                            style="display: none">
                                                        <input type="text" class="form-control" id="bhogudang"
                                                            placeholder="transaksi" name="bhogudang" readonly
                                                            style="display: none">
                                                        <input type="text" class="form-control" id="bhosaldo"
                                                            placeholder="transaksi" name="bhosaldo" readonly
                                                            style="display: none">
                                                        <input type="text" class="form-control" id="bhosatuan"
                                                            placeholder="transaksi" name="bhosatuan" readonly
                                                            style="display: none">
                                                        <input type="text" class="form-control" id="bhosatuans"
                                                            placeholder="transaksi" name="bhosatuans" readonly
                                                            style="display: none">
                                                        <input type="text" class="form-control frmpembeliand"
                                                            placeholder="Klik icon untuk cari bahan" id="bhonama"
                                                            name="bhonama" readonly>
                                                        <span class="text-danger error bhoid_error"></span>


                                                        <div class="form-control-icon">
                                                            <i class="bi bi-search" id="iconSearchBahan"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6">
                                                    <h6>Base yang dihasilkan <span class="text-danger "
                                                            style="margin-left: 5px">*
                                                    </h6>
                                                    <div class="col-md-3">
                                                        <div class="d-flex align-items-center">
                                                            <input type="number" class="form-control nomor me-2"
                                                                id="bhototal" name="bhototal" style="width: 100px;">
                                                            <span class="badge bg-primary" id="titleHasil"></span>
                                                        </div>
                                                        <span class="text-danger error bhokuantiti_error"></span>
                                                    </div>




                                                    {{-- <span>
                                                        <h1 id="titleHasil">
                                                            <button type="button" class="btn btn-primary" id="btnGanti"
                                                                name="btnGanti">Ganti</button>
                                                        </h1>
                                                    </span> --}}
                                                </div>
                                                <div class="col-sm-6">
                                                    <h6>jumlah<span class="text-danger " style="margin-left: 5px">*
                                                    </h6>
                                                    <div class="form-group position-relative has-icon-right">
                                                        <input type="number" class="form-control  nomor" id="bhokuantiti"
                                                            name="bhokuantiti">


                                                        <input type="text" class="form-control frmtransaksid"
                                                            id="bhohasil" name="bhohasil" readonly
                                                            style="display:none;">

                                                        <span class="text-danger error pmbdjumlah_error"></span>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <button class="btn btn-primary col-md-3" type="submit">buat
                                                            bahan
                                                            olah</button>
                                                        <button class="btn btn-secondary col-md-3"
                                                            type="reset">Reset</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0 " id="dt_bahan_olah"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Nama</th>
                                                            <th>Saldo</th>
                                                            <th>Asal Gudang</th>
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
                        <div class="col-md-5 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title text-center">
                                        <h4>Riwayat Permintaan : <span id="titleBahand" class="badge bg-primary"></span>
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0 " id="dt_bahand" style="width:100%">
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

    <div class="modal fade text-left" id="modalBahanBarang" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal"></h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahanOlah" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
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

    {{-- TEMPLATE --}}
    @include('layouts.modal')
@endsection

@push('js')
    @include('bar.persediaan_bar-js')
@endpush
