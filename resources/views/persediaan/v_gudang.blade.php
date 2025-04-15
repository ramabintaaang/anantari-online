@extends('layouts.base')

@section('title')
    Persediaan gudang
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Persediaan</li>
            <li class="breadcrumb-item active" aria-current="page">Gudang</li>

        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p>Bahan akan berwarna merah apabila saldo kurang dari minimal saldo (dapat
                        di
                        setting di master bahan) , <b>defaultnya adalah 10</b> </p>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="first-name-column">Sumber Gudang</label>

                            <input type="text" id="sgudang" class="form-control frmbarang" name="sgudang"
                                value="" readonly style="display: none;">
                            <input type="text" id="sgudangutama" class="form-control frmbarang" name="sgudangutama"
                                value="" readonly style="display: none;">
                            <input type="text" id="sgudangn" class="form-control frmbarang" name="sgudang"
                                value="" readonly>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Gudang Besar</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Gudang Bar</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab"
                                aria-controls="contact" aria-selected="false">Gudang Kitchen</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        {{-- start home --}}
                        <div class="tab-pane fade show " id="home" role="tabpanel" aria-labelledby="home-tab">
                            <p class='my-2'>
                            <div class="col-md-12 col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0 " id="dt_gudang_besar"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Nama Bahan</th>
                                                            <th>Saldo</th>
                                                            <th>Min</th>
                                                            <th>Max</th>
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
                            </p>
                        </div>
                        {{-- end home atau gudang besar --}}
                        {{-- start bar --}}

                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <p class='my-2'>
                                <div class="col-md-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_gudang_bar"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama Bahan</th>
                                                                <th>Saldo</th>
                                                                <th>Min</th>
                                                                <th>Max</th>
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
                                </p>
                            </div>
                            {{-- end gudang bar --}}
                            {{-- start gudang kitchen --}}
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="tab-pane fade show " id="home" role="tabpanel" aria-labelledby="home-tab">
                                <p class='my-2'>
                                <div class="col-md-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0 " id="dt_gudang_kitchen"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama Bahan</th>
                                                                <th>Saldo</th>
                                                                <th>Min</th>
                                                                <th>Max</th>
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
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        </section>
    </div>

    <div class="modal fade text-left" id="modalBahanBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
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
    @include('persediaan.gudang-js')
@endpush
