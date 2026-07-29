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
                                @if (Auth::user()->username == 'alamsyahfirdaus')
                                    <div class="table-responsive">
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
                                                @foreach ([$screening['pre_test'], $screening['post_test']] as $index => $screening)
                                                    <tr>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($screening['session_date'])->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td>
                                                            @if ($screening['fall_risk'])
                                                                {{ $screening['fall_risk']['total_score'] }}
                                                                <span class="text-muted">
                                                                    ({{ $screening['fall_risk']['risk_level'] }})
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $screening['empowerment'] ? $screening['empowerment']['total_score'] : '-' }}
                                                        </td>
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
                                                                            data-id="{{ $screening['fall_risk']['id'] }}"
                                                                            data-score="{{ $screening['fall_risk']['total_score'] ?? 0 }}">
                                                                            Ubah Skor Risiko Jatuh
                                                                        </a></li>
                                                                    <li>
                                                                        <hr class="dropdown-divider" />
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0)"
                                                                            class="dropdown-item btn-edit-score"
                                                                            data-type="empowerment"
                                                                            data-id="{{ $screening['empowerment']['id'] }}"
                                                                            data-score="{{ $screening['empowerment']['total_score'] ?? 0 }}">
                                                                            Ubah Skor Pemberdayaan
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="timeline">

                                        @if ($screening)
                                            <div class="time-label">
                                                <span class="badge bg-warning px-3 py-2 shadow-sm">
                                                    {{ $screening['pre_test']['session_date']->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="timeline-item shadow-sm border-0 rounded-3">
                                                    <div class="timeline-header bg-primary text-white">
                                                        <strong>Pre Test</strong>
                                                        <span class="float-end" style="font-size: 14px;">Sesi
                                                            {{ $screening['pre_test']['session_number'] }}</span>
                                                    </div>
                                                    <div class="timeline-body">
                                                        <div class="card border-0">
                                                            <div class="card-body">
                                                                <div class="row g-4">
                                                                    <div class="col-md-6">
                                                                        <small class="text-muted d-block mb-2">
                                                                            Risiko Jatuh
                                                                        </small>

                                                                        @if ($screening['pre_test']['fall_risk'])
                                                                            @php
                                                                                $risk =
                                                                                    $screening['pre_test']['fall_risk'];

                                                                                $riskColor = match ($risk->risk_level) {
                                                                                    'Rendah' => 'success',
                                                                                    'Sedang' => 'warning',
                                                                                    'Tinggi' => 'danger',
                                                                                    default => 'secondary',
                                                                                };
                                                                            @endphp

                                                                            <h2 class="fw-bold mb-2">
                                                                                {{ $risk->total_score }}
                                                                            </h2>

                                                                            <span class="badge bg-{{ $riskColor }}">
                                                                                {{ $risk->risk_level }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">
                                                                                Belum ada data
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <small class="text-muted d-block mb-2">
                                                                            Kemandirian Kesehatan Keluarga
                                                                        </small>

                                                                        @if ($screening['pre_test']['empowerment'])
                                                                            @php
                                                                                $empowerment =
                                                                                    $screening['pre_test'][
                                                                                        'empowerment'
                                                                                    ];

                                                                                $empowermentColor = match (
                                                                                    $empowerment->empowerment_level
                                                                                ) {
                                                                                    'Tinggi' => 'success',
                                                                                    'Sedang' => 'warning',
                                                                                    'Rendah' => 'danger',
                                                                                    default => 'secondary',
                                                                                };
                                                                            @endphp

                                                                            <h2 class="fw-bold mb-2">
                                                                                {{ $empowerment->total_score }}
                                                                            </h2>

                                                                            <span class="badge bg-{{ $empowermentColor }}">
                                                                                {{ $empowerment->empowerment_level }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">
                                                                                Belum ada data
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($screening['post_test'])
                                                <div>
                                                    <div class="timeline-item border-0 shadow-sm">
                                                        <div class="timeline-body text-center">
                                                            <h5 class="fw-bold mb-2">Proses Konseling</h5>
                                                            <p class="text-muted mb-3">
                                                                Konseling berlangsung dari
                                                                <strong>Sesi
                                                                    {{ $screening['pre_test']['session_number'] }}</strong>
                                                                hingga

                                                                <strong>Sesi
                                                                    {{ $screening['post_test']['session_number'] }}</strong>
                                                            </p>

                                                            <span class="badge bg-secondary px-3 py-2">
                                                                {{ $screening['post_test']['session_number'] - $screening['pre_test']['session_number'] + 1 }}
                                                                Sesi Konseling
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="time-label">
                                                    <span
                                                        class="badge bg-warning px-3 py-2 shadow-sm">{{ $screening['post_test']['session_date']->translatedFormat('d F Y') }}</span>
                                                </div>
                                                <div>
                                                    <div class="timeline-item shadow-sm border-0 rounded-3">
                                                        <div class="timeline-header bg-success text-white">
                                                            <strong>Post Test</strong>
                                                            <span class="float-end" style="font-size: 14px;">Sesi
                                                                {{ $screening['post_test']['session_number'] }}</span>
                                                        </div>
                                                        <div class="timeline-body">
                                                            <div class="card border-0">
                                                                <div class="card-body">
                                                                    <div class="row g-4">

                                                                        <div class="col-md-6">
                                                                            <small class="text-muted d-block mb-2">
                                                                                Risiko Jatuh
                                                                            </small>

                                                                            @if ($screening['post_test']['fall_risk'])
                                                                                @php
                                                                                    $risk =
                                                                                        $screening['post_test'][
                                                                                            'fall_risk'
                                                                                        ];

                                                                                    $riskColor = match (
                                                                                        $risk->risk_level
                                                                                    ) {
                                                                                        'Rendah' => 'success',
                                                                                        'Sedang' => 'warning',
                                                                                        'Tinggi' => 'danger',
                                                                                        default => 'secondary',
                                                                                    };
                                                                                @endphp

                                                                                <h2 class="fw-bold mb-2">
                                                                                    {{ $risk->total_score }}
                                                                                </h2>

                                                                                <span class="badge bg-{{ $riskColor }}">
                                                                                    {{ $risk->risk_level }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-muted">
                                                                                    Belum ada data
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <small class="text-muted d-block mb-2">
                                                                                Kemandirian Kesehatan Keluarga
                                                                            </small>

                                                                            @if ($screening['post_test']['empowerment'])
                                                                                @php
                                                                                    $empowerment =
                                                                                        $screening['post_test'][
                                                                                            'empowerment'
                                                                                        ];

                                                                                    $empowermentColor = match (
                                                                                        $empowerment->empowerment_level
                                                                                    ) {
                                                                                        'Tinggi' => 'success',
                                                                                        'Sedang' => 'warning',
                                                                                        'Rendah' => 'danger',
                                                                                        default => 'secondary',
                                                                                    };
                                                                                @endphp

                                                                                <h2 class="fw-bold mb-2">
                                                                                    {{ $empowerment->total_score }}
                                                                                </h2>

                                                                                <span class="badge bg-{{ $empowermentColor }}">
                                                                                    {{ $empowerment->empowerment_level }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-muted">
                                                                                    Belum ada data
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <i class="timeline-icon bg-success text-white bi bi-check-lg"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center py-5">
                                                <i class="bi bi-clipboard-x text-secondary" style="font-size:60px"></i>
                                                <h5 class="mt-3">Belum Ada Hasil Skrining</h5>
                                                <p class="text-muted">Hasil skrining akan muncul setelah konselor melakukan
                                                    skrining.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @break

                            @case('evaluasi')
                                @if (Auth::user()->username == 'alamsyahfirdaus')
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle datatable">
                                            <thead>
                                                <tr>
                                                    <th>Sesi</th>
                                                    <th>Tanggal</th>
                                                    <th>Topik</th>
                                                    <th>Skor</th>
                                                    <th>Kategori</th>
                                                    <th style="width: 5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($evaluations as $sessionId => $items)
                                                    @foreach ($items as $evaluation)
                                                        <tr>
                                                            <td>Sesi {{ $sessionNumbers[$sessionId] ?? '-' }}</td>
                                                            <td>{{ $evaluation->created_at->translatedFormat('d F Y') }}</td>
                                                            <td>{{ $evaluation->topic->topic ?? '-' }}</td>
                                                            <td>{{ $evaluation->total_score }}</td>
                                                            <td>{{ $evaluation->category ?? '-' }}</td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                                        data-bs-toggle="dropdown">
                                                                        Aksi
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="javascript:void(0)"
                                                                                class="dropdown-item btn-edit-score"
                                                                                data-type="evaluation"
                                                                                data-id="{{ $evaluation->id }}"
                                                                                data-score="{{ $evaluation->total_score }}">
                                                                                Ubah Skor
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    @forelse($evaluations as $sessionId => $items)
                                        <div class="card border shadow-sm mb-4">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between">
                                                    <strong>Sesi {{ $sessionNumbers[$sessionId] }}</strong>
                                                    <small
                                                        class="text-muted">{{ $items->first()->created_at->translatedFormat('d F Y') }}</small>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                @foreach ($items as $evaluation)
                                                    <div class="border rounded p-3 mb-3">
                                                        <div class="fw-semibold">{{ $evaluation->topic->topic }}</div>
                                                        <div class="mt-2">
                                                            <span class="badge bg-primary">
                                                                Skor:
                                                                {{ $evaluation->total_score }}/{{ $evaluation->total_questions }}
                                                                ({{ $evaluation->percentage }}%)
                                                            </span>
                                                            @php
                                                                $color = match ($evaluation->category) {
                                                                    'Baik' => 'success',
                                                                    'Cukup' => 'warning',
                                                                    'Kurang' => 'danger',
                                                                    default => 'secondary',
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $color }}">{{ $evaluation->category }}</span>
                                                        </div>
                                                        @if (Auth::user()->username == 'alamsyahfirdaus')
                                                            <div class="mt-3">
                                                                <button class="btn btn-sm btn-outline-primary btn-edit-score"
                                                                    data-type="evaluation" data-id="{{ $evaluation->id }}"
                                                                    data-score="{{ $evaluation->total_score }}">
                                                                    <i class="bi bi-pencil"></i>Ubah Skor
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="bi bi-journal-x fs-1 text-secondary"></i>
                                            <h5 class="mt-3">Belum Ada Evaluasi</h5>
                                        </div>
                                    @endforelse
                                @endif
                                @break

                                @case('resume')
                                    @forelse ($counselingResumes as $resume)
                                        <div class="card border shadow-sm mb-3">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">Resume Sesi {{ $resume['session_number'] }}</h6>
                                                <small class="text-muted ms-auto">
                                                    {{ \Carbon\Carbon::createFromFormat('d-m-Y', $resume['session_date'])->translatedFormat('d F Y') }}
                                                </small>
                                            </div>

                                            <div class="card-body">
                                                @forelse ($resume['resume_options'] as $category => $options)
                                                    <div class="mb-4">
                                                        <div
                                                            class="fw-semibold text-warning border-start border-4 border-warning ps-2 mb-2">
                                                            {{ $category }}</div>
                                                        <ul class="list-group list-group-flush">
                                                            @foreach ($options as $option)
                                                                <li class="list-group-item border-0 py-1 px-0">
                                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                                    {{ $option['title'] }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @empty
                                                    <div class="text-center text-muted py-4">
                                                        <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                                        Belum terdapat resume konselor.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-light border text-center mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Belum ada data resume konseling.
                                        </div>
                                    @endforelse
                                @break

                                @case('tindak-lanjut')
                                    <div class="timeline">
                                        @forelse ($followUps as $followUp)
                                            <div class="time-label">
                                                <span class="badge bg-warning px-3 py-2 shadow-sm">
                                                    {{ \Carbon\Carbon::createFromFormat('d-m-Y', $followUp['session_date'])->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="timeline-item shadow-sm border-0 rounded-3">
                                                    <div class="timeline-header d-flex justify-content-between align-items-center"
                                                        style="padding: 10px 16px;">
                                                        <div><strong class="text-dark">Tindak Lanjut Sesi
                                                                {{ $followUp['session_number'] }}</strong></div>
                                                    </div>
                                                    <div class="timeline-body">
                                                        <div class="alert alert-light border mb-0">{!! nl2br(e($followUp['follow_up'])) !!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-5">
                                                <i class="bi bi-journal-x text-secondary" style="font-size:60px"></i>
                                                <h5 class="mt-3">Belum Ada Tindak Lanjut</h5>
                                                <p class="text-muted mb-0">
                                                    Tindak lanjut konseling akan muncul setelah
                                                    konselor menyelesaikan sesi konseling.
                                                </p>
                                            </div>
                                        @endforelse
                                        @if ($followUps->count())
                                            <div><i class="timeline-icon bg-success text-white bi bi-check-lg"></i></div>
                                        @endif
                                    </div>
                                @break
                            @endswitch

                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        @if (Auth::user()->username == 'alamsyahfirdaus')
            <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('scores.update') }}" id="scoreForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scoreModalLabel">Ubah Skor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="type" id="score_type">
                                <input type="hidden" name="id" id="score_id">
                                <div class="form-group">
                                    <label for="score_value" class="form-label">Skor</label>
                                    <input type="number" class="form-control" id="score_value" name="score"
                                        min="0">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

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

            .timeline-header {
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
                border-radius: 6px 6px 0 0;
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
