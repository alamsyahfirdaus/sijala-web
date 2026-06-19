@extends('layouts.app')

@section('title', 'Laporan')

@section('page_title')
    {{ 'Laporan' }}
@endsection

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Laporan {{ $title }}</h3>
        </div>

        <div class="card-body table-responsive">

            <div class="row">

                <div class="col-md-8">

                    <form method="GET">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-4">
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                    Filter
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

                <div class="col-md-4 text-end">

                    <button class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        PDF
                    </button>

                    <button class="btn btn-success">
                        <i class="bi bi-file-earmark-excel-fill"></i>
                        Excel
                    </button>

                </div>

            </div>

            @if ($report == 'elderly')
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>NIK</th>
                            <th>Nama Lansia</th>
                            <th>Jenis Kelamin</th>
                            <th>Usia</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Data Lansia --}}
                    </tbody>

                </table>
            @elseif ($report == 'counselor')
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Konselor</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Total Konseling</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Data Konselor --}}
                    </tbody>

                </table>
            @elseif ($report == 'counseling')
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Konseli</th>
                            <th>Konselor</th>
                            <th>Sesi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Data Konseling --}}
                    </tbody>

                </table>
            @elseif ($report == 'screening')
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lansia</th>
                            <th>Tanggal Skrining</th>
                            <th>Skor</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Data Skrining --}}
                    </tbody>

                </table>
            @elseif ($report == 'evaluation')
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lansia</th>
                            <th>Tanggal Evaluasi</th>
                            <th>Hasil</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Data Evaluasi --}}
                    </tbody>

                </table>
            @endif

        </div>

    </div>

@endsection
