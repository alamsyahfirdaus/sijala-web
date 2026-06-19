@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Lansia</h3>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item d-flex justify-content-between px-0 pt-0">
                    <span>Nama Lansia</span>
                    <span>{{ $counseling->elderlyCounselee->counselee->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Usia</span>
                    <span>{{ $counseling->elderlyCounselee->elderly_age }} Tahun</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Jenis Kelamin</span>
                    <span>{{ $counseling->elderlyCounselee->elderly_gender == 'L' ? 'Laki-Laki' : ($counseling->elderlyCounselee->elderly_gender == 'P' ? 'Perempuan' : '-') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Konseli</span>
                    <span><a href=""
                            class="text-decoration-none">{{ $counseling->elderlyCounselee->counselee->name }}</a></span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Lama Merawat</span>
                    <span>{{ $counseling->elderlyCounselee->care_duration_months }} Bulan</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Pernah Jatuh</span>
                    <span>{{ $counseling->elderlyCounselee->has_fallen ? 'Ya' : 'Tidak' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Konselor</span>
                    <span><a href="" class="text-decoration-none">{{ $counseling->counselor->name }}</a></span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between px-0 {{ $counseling->elderlyCounselee->health_problems ? 'border-bottom' : 'pb-0' }}">
                    <span>Wilayah</span>
                    <span>Puskesmas {{ $counseling->counselor->puskesmas->name }}</span>
                </li>
            </ul>
            @if ($counseling->elderlyCounselee->health_problems)
                <div class="form-group mt-2">
                    <label for="health_problems" class="form-label">Kondisi Kesehatan</label>
                    <textarea id="health_problems" class="form-control" disabled style="background-color: #fff;">{{ $counseling->elderlyCounselee->health_problems ?? '-' }}</textarea>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Hasil Skrining</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Risiko Jatuh</th>
                        <th>Perberdayaan Keluarga</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($screenings as $index => $screening)
                        <tr>
                            <td>
                                {{ $screening['session']->updated_at->format('d/m/Y') }}
                            </td>
                            <td>
                                @if ($screening['fallRisk'])
                                    {{ $screening['fallRisk']->total_score }}
                                    <span class="text-muted">
                                        ({{ $screening['fallRisk']->risk_level }})
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($screening['empowerment'])
                                    {{ $screening['empowerment']->total_score }}
                                    <span class="text-muted">
                                        ({{ $screening['empowerment']->empowerment_level }})
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="">Ubah Nilai</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Hasil Evaluasi</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th>Sesi</th>
                        <th>Tanggal</th>
                        <th>Topik</th>
                        <th>Skor</th>
                        <th>Kategori</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td>
                                Sesi {{ $sessionNumbers[$evaluation->counseling_session_id] ?? '-' }}
                            </td>

                            <td>
                                {{ $evaluation->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $evaluation->topic->topic ?? '-' }}
                            </td>

                            <td>
                              {{ $evaluation->total_score ?? '-' }}

                                @if ($evaluation->percentage)
                                    <span class="text-muted">
                                        ({{ number_format($evaluation->percentage, 1) }}%)
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $evaluation->category ?? '-' }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="">Ubah Nilai</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
