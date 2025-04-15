@extends('layouts.base')

@section('title')
    Laporan Bahan
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="#">Master</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Laporan</li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Bahan</li>
        </ol>
    </nav>
    {{-- <a class="btn btn-warning float-end" href="{{ route('exportBahan') }}" id=""><i class="fa fa-download"></i> Export</a> --}}
    {{-- <button id="getCheckedValues">Get Checked Values</button> --}}
    <div class="page-content">
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-md-7 col-xs-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4 class="card-title">Multiple Column</h4>
                        </div> --}}
                        @php
                            $cabang = request()->route('cabang'); // Mendapatkan parameter cabang dari URL
                        @endphp
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" id="formBahan" method="GET"
                                    action="{{ route('exportBahan', ['cabang' => $cabang]) }}">

                                    @csrf
                                    <div class="row">
                                        <h4>Laporan Bahan : <span id="titleDetail" class="badge bg-success"></span>
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="first-name-column">tanggal dari</label>

                                                    <input type="date" id="tgldari" class="form-control"
                                                        name="tgldari">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="first-name-column">tanggal Sampai</label>

                                                    <input type="date" id="tglsampai" class="form-control"
                                                        name="tglsampai">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="first-name-column">Gudang</label>
                                                    <fieldset class="form-group">
                                                        <select class="form-select " id="gudang" name="gudang">
                                                            @foreach ($gudang as $x)
                                                                <option value="{{ $x->gudangid }}">{{ $x->gudangn }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            {{-- nav --}}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        {{-- <div class="card-header">
                                                            <h5 class="card-title">Horizontal Navs</h5>
                                                        </div> --}}
                                                        <div class="card-body">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active" id="home-tab"
                                                                        data-bs-toggle="tab" href="#bahanDasar"
                                                                        role="tab" aria-controls="home"
                                                                        aria-selected="true">Bahan
                                                                        Dasar</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="contact-tab"
                                                                        data-bs-toggle="tab" href="#bahanOlah"
                                                                        role="tab" aria-controls="contact"
                                                                        aria-selected="false">Bahan
                                                                        Olah</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="bahanDasar"
                                                                    role="tabpanel" aria-labelledby="home-tab">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <ul class="list-unstyled mb-0">
                                                                                        <div class="form-check">
                                                                                            <div class="checkbox">
                                                                                                <input type="checkbox"
                                                                                                    class="form-check-input selectAll_new"
                                                                                                    id="selectAll_bahan">
                                                                                                <label for="selectAll">Pilih
                                                                                                    Semua</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <hr>
                                                                                        @foreach ($bahan as $bahans)
                                                                                            <li
                                                                                                class="d-inline-block me-2 mb-1">
                                                                                                <div class="form-check">
                                                                                                    <div class="checkbox">
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            class="form-check-input"
                                                                                                            id="{{ $bahans->bhnid }}"
                                                                                                            name="listFormBahan[]"
                                                                                                            value="{{ $bahans->bhnid }}">
                                                                                                        <label
                                                                                                            for="checkbox2">{{ $bahans->bhnnama }}</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="bahanOlah" role="tabpanel"
                                                                    aria-labelledby="contact-tab">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <ul class="list-unstyled mb-0">
                                                                                        <div class="form-check">
                                                                                            <div class="checkbox">
                                                                                                <input type="checkbox"
                                                                                                    class="form-check-input selectAll_new"
                                                                                                    id="selectAll_bahanolah">
                                                                                                <label
                                                                                                    for="selectAll">Pilih
                                                                                                    Semua</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <hr>
                                                                                        @foreach ($bahanOlah as $x)
                                                                                            <li
                                                                                                class="d-inline-block me-2 mb-1">
                                                                                                <div class="form-check">
                                                                                                    <div class="checkbox">
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            class="form-check-input"
                                                                                                            id="{{ $x->bhoid }}"
                                                                                                            name="listFormBahan[]"
                                                                                                            value="{{ $x->bhoid }}">
                                                                                                        <label
                                                                                                            for="checkbox2">{{ $x->bhonama }}</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success me-1 mb-1">Export Excel</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1"
                                            id="btnResetbahan">Proses</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
    </div>

    {{-- MODAL --}}
    <!--Basic Modal -->
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
                    {{-- <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahan" style="width:100%">
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
                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dt_bahan" style="width:100%">
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
    </div>
    </div>
    </div>
@endsection

@push('js')
    @include('laporan.laporan_bahan.laporan_bahan_js')
@endpush
