@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card">
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
                    <span>{{ $counseling->elderlyCounselee->has_fallen ? 'YA' : 'Tidak' }}</span>
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
                    <label for="health_problems" class="form-label">Kesehatan Lansia</label>
                    <textarea id="health_problems" class="form-control" disabled>{{ $counseling->elderlyCounselee->health_problems ?? '-' }}</textarea>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Hasil Skrining</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Risiko Jatuh</th>
                        <th>Perberdayaan Keluarga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($screenings as $index => $screening)
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada hasil skrining
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Hasil Evaluasi</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">Sesi</th>
                        <th>Tanggal</th>
                        <th>Topik</th>
                        <th>Skor</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evaluations as $evaluation)
                        <tr>
                            <td>
                                Sesi {{ $sessionNumbers[$evaluation->counseling_session_id] ?? '-' }}
                            </td>

                            <td>
                                {{ $evaluation->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $evaluation->topic->name ?? '-' }}
                            </td>

                            <td>
                                {{ $evaluation->score ?? '-' }}
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada hasil evaluasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
