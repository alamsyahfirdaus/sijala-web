@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card card-outline card-warning">
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
                    <span>{{ $counseling->elderlyCounselee->counselee->name }}</span>
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
                    <span>{{ $counseling->counselor->name }}</span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between px-0 {{ $counseling->elderlyCounselee->health_problems ? 'border-bottom' : 'pb-0' }}">
                    <span>Wilayah</span>
                    <span>
                        {{ $counseling->counselor?->puskesmas?->name ? 'Puskesmas ' . $counseling->counselor->puskesmas->name : '-' }}
                    </span>
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
        <div class="card-header border-bottom-0">
            @php
                $tabs = [
                    'skrining' => 'Hasil Skrining',
                    'evaluasi' => 'Hasil Evaluasi',
                    'resume' => 'Resume Konselor',
                    'tindak-lanjut' => 'Tindak Lanjut',
                ];
            @endphp

            <ul class="nav nav-tabs counseling-tabs" id="counselingTab" role="tablist">
                @foreach ($tabs as $id => $label)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $id }}-tab"
                            data-bs-toggle="tab" data-bs-target="#{{ $id }}" type="button" role="tab"
                            aria-controls="{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body pt-0">
            <div class="tab-content mt-3" id="counselingTabContent">

                @foreach ($tabs as $id => $label)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $id }}"
                        role="tabpanel" aria-labelledby="{{ $id }}-tab">

                        @switch($id)
                            @case('skrining')
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle datatable">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Risiko Jatuh</th>
                                                <th>Perberdayaan Keluarga</th>
                                                @if (Auth::user()->username == 'alamsyahfirdaus')
                                                    <th style="width: 5%;">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($screenings as $index => $screening)
                                                <tr>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($screening['session']->updated_at)->translatedFormat('d F Y') }}
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
                                                    <td>{{ $screening['empowerment'] ? $screening['empowerment']->total_score : '-' }}
                                                    </td>
                                                    @if (Auth::user()->username == 'alamsyahfirdaus')
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button"
                                                                    class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Aksi
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)"
                                                                            class="dropdown-item btn-edit-score"
                                                                            data-type="fall-risk"
                                                                            data-id="{{ $screening['fallRisk']->id }}"
                                                                            data-score="{{ $screening['fallRisk']->total_score ?? 0 }}">
                                                                            Ubah Skor Risiko Jatuh
                                                                        </a></li>
                                                                    <li>
                                                                        <hr class="dropdown-divider" />
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0)"
                                                                            class="dropdown-item btn-edit-score"
                                                                            data-type="empowerment"
                                                                            data-id="{{ $screening['empowerment']->id }}"
                                                                            data-score="{{ $screening['empowerment']->total_score ?? 0 }}">
                                                                            Ubah Skor Pemberdayaan
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @break

                            @case('evaluasi')
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle datatable">
                                        <thead>
                                            <tr>
                                                <th>Sesi</th>
                                                <th>Tanggal</th>
                                                <th>Topik</th>
                                                <th>Skor</th>
                                                <th>Kategori</th>
                                                @if (Auth::user()->username == 'alamsyahfirdaus')
                                                    <th style="width: 5%;">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluations as $evaluation)
                                                <tr>
                                                    <td>
                                                        Sesi {{ $sessionNumbers[$evaluation->counseling_session_id] ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($evaluation->created_at)->translatedFormat('d F Y') }}
                                                    </td>

                                                    <td>
                                                        {{ $evaluation->topic->topic ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ round($evaluation->percentage) }}
                                                    </td>
                                                    <td>
                                                        {{ $evaluation->category ?? '-' }}
                                                    </td>
                                                    @if (Auth::user()->username == 'alamsyahfirdaus')
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button"
                                                                    class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Aksi
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)"
                                                                            class="dropdown-item btn-edit-score"
                                                                            data-type="evaluation" data-id="{{ $evaluation->id }}"
                                                                            data-score="{{ $evaluation->total_score }}">
                                                                            Ubah Skor
                                                                        </a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @break

                            {{-- ========================================= --}}
                            {{-- RESUME KONSELOR --}}
                            {{-- ========================================= --}}
                            @case('resume')
                                RESUME KONSELOR
                            @break

                            {{-- ========================================= --}}
                            {{-- TINDAK LANJUT --}}
                            {{-- ========================================= --}}
                            @case('tindak-lanjut')
                                TINDAK LANJUT
                            @break
                        @endswitch

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('scores.update') }}" id="scoreForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scoreModalLabel">
                            Ubah Skor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="type" id="score_type">
                        <input type="hidden" name="id" id="score_id">

                        <div class="form-group">
                            <label for="score_value" class="form-label">Skor</label>
                            <input type="number" class="form-control" id="score_value" name="score" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .counseling-tabs {
            gap: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 12px;
        }

        .counseling-tabs .nav-link {
            border: none;
            border-radius: 12px;
            padding: 9px 18px;
            color: #6c757d;
            background: #f8f9fa;
            transition: .25s;
        }

        .counseling-tabs .nav-link i {
            font-size: 14px;
        }

        .counseling-tabs .nav-link:hover {
            background: #fff7df;
            color: #d98c00;
            transform: translateY(-2px);
        }

        .counseling-tabs .nav-link.active {
            background: #FFC107;
            color: #fff;
        }
    </style>

    <script>
        $(document).ready(function() {

            $('.btn-edit-score').on('click', function() {

                let type = $(this).data('type');
                let id = $(this).data('id');
                let score = $(this).data('score');
                let title = $(this).text().trim();

                $('#score_type').val(type);
                $('#score_id').val(id);
                $('#score_value').val(score);
                $('#scoreModalLabel').text(title);

                $('#score_value')
                    .removeClass('is-invalid')
                    .next('.invalid-feedback')
                    .text('');

                $('#scoreModal').modal('show');
            });

            $('#scoreForm').on('submit', function(e) {

                let score = $('#score_value').val().trim();

                let isValid = true;

                $('#score_value')
                    .removeClass('is-invalid')
                    .next('.invalid-feedback')
                    .text('');

                if (score === '') {
                    $('#score_value')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .text('Skor wajib diisi.');

                    isValid = false;
                } else if (isNaN(score)) {
                    $('#score_value')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .text('Skor harus berupa angka.');

                    isValid = false;
                } else if (parseFloat(score) < 0) {
                    $('#score_value')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .text('Skor tidak boleh kurang dari 0.');

                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
