@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar {{ $title }}</h3>
        </div>
        <div class="card-body table-responsive">
            <table id="table" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Lansia</th>
                        <th>Konseli</th>
                        <th>Konselor</th>
                        <th>Sesi</th>
                        <th>Sesi Terakhir</th>
                        <th>Status</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($counselingSessions as $index => $session)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $session->elderlyCounselee->elderly_name ?? '-' }} </td>
                            <td>{{ $session->elderlyCounselee->counselee->name ?? '-' }}</td>
                            <td>{{ $session->counselor->name ?? '-' }}</td>
                            <td>{{ $session->session_count }} Sesi</td>
                            <td>{{ $session->last_session_datetime ?? '-' }}</td>
                            <td>{{ $session->status_konseling }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('counseling.session', encrypt($session->id)) }}">
                                                <i class="bi bi-eye me-1"></i> Lihat Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item text-warning btnChangeCounselor"
                                                data-url="{{ route('counseling.change-counselor', encrypt($session->id)) }}"
                                                data-elderly-name="{{ $session->elderlyCounselee->elderly_name ?? '-' }}"
                                                data-counselee-name="{{ $session->elderlyCounselee->counselee->name ?? '-' }}"
                                                data-current-counselor-id="{{ $session->counselor_id }}"
                                                data-current-counselor-name="{{ $session->counselor->name ?? 'Belum Ditentukan' }}"
                                                data-session-count="{{ $session->session_count }}">
                                                <i class="bi bi-person-gear me-1"></i> Ubah Konselor
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item text-danger btnDelete"
                                                data-url="{{ route('counseling.delete', encrypt($session->id)) }}"
                                                data-name="{{ $session->elderlyCounselee->elderly_name ?? '-' }}">
                                                <i class="bi bi-trash me-1"></i> Hapus
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
    </div>

    <!-- Modal Ubah Konselor -->
    <div class="modal fade" id="changeCounselorModal" tabindex="-1" aria-labelledby="changeCounselorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="changeCounselorForm" method="POST" action="" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="changeCounselorModalLabel">
                            <i class="bi bi-person-gear me-1 text-warning"></i> Ubah Konselor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Nama Lansia</small>
                                        <strong id="modalElderlyName" class="text-dark">-</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Nama Konseli</small>
                                        <strong id="modalCounseleeName" class="text-dark">-</strong>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <small class="text-muted d-block">Jumlah Sesi</small>
                                        <span class="badge bg-info text-dark fw-semibold" id="modalSessionCount">- Sesi</span>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <small class="text-muted d-block">Konselor Saat Ini</small>
                                        <span class="badge bg-secondary" id="modalCurrentCounselor">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="selectNewCounselor" class="form-label fw-semibold">
                                Konselor Baru <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-counselor" id="selectNewCounselor" name="counselor_id" style="width: 100%;">
                                <option value="">-- Pilih Konselor Baru --</option>
                                @foreach ($counselors as $counselor)
                                    <option value="{{ $counselor->id }}">
                                        {{ $counselor->name }}
                                        @if ($counselor->puskesmas)
                                            (Puskesmas {{ $counselor->puskesmas->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm text-dark fw-semibold" id="btnSubmitChangeCounselor">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .select2-container .select2-selection.is-invalid,
        .select2-container--bootstrap-5.is-invalid .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>
    <script>
        $(function() {

            // Inisialisasi Select2 di dalam Modal jika library tersedia
            if ($.fn.select2) {
                $('#selectNewCounselor').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#changeCounselorModal'),
                    placeholder: '-- Pilih Konselor Baru --',
                    allowClear: true
                });
            }

            // Inisialisasi jQuery Validation untuk Form Ubah Konselor
            const changeCounselorValidator = $('#changeCounselorForm').validate({
                ignore: [], // Memastikan elemen select yang disembunyikan Select2 tetap divalidasi
                rules: {
                    counselor_id: {
                        required: true,
                    }
                },
                messages: {
                    counselor_id: {
                        required: "Silakan pilih konselor baru.",
                    }
                },
                errorElement: "div",
                errorClass: "invalid-feedback",
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                    $(element).next(".select2-container").find(".select2-selection").addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                    $(element).next(".select2-container").find(".select2-selection").removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    error.addClass("d-block");
                    if (element.next(".select2-container").length) {
                        error.insertAfter(element.next(".select2-container"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    const newCounselorId = $('#selectNewCounselor').val();
                    const newCounselorText = $('#selectNewCounselor option:selected').text().trim();
                    const currentCounselorId = $('#modalCurrentCounselor').data('current-id');
                    const currentCounselorText = $('#modalCurrentCounselor').text().trim();
                    const elderlyName = $('#modalElderlyName').text().trim();
                    const sessionCount = $('#modalSessionCount').text().trim();

                    if (!newCounselorId) {
                        return false;
                    }

                    if (newCounselorId == currentCounselorId) {
                        alert('Konselor yang dipilih sama dengan konselor saat ini. Silakan pilih konselor yang berbeda.');
                        return false;
                    }

                    const confirmMessage =
                        'Apakah Anda yakin ingin mengubah konselor?\n\n' +
                        'Perubahan ini akan diterapkan pada SELURUH sesi konseling milik konseli/lansia tersebut (' + elderlyName + ').\n\n' +
                        '• Dari: ' + currentCounselorText + '\n' +
                        '• Menjadi: ' + newCounselorText + '\n' +
                        '• Jumlah sesi terdampak: ' + sessionCount + '\n\n' +
                        'Klik OK untuk mengonfirmasi perubahan.';

                    if (!confirm(confirmMessage)) {
                        return false;
                    }

                    form.submit();
                }
            });

            // Trigger re-validasi ketika pengguna memilih opsi pada Select2
            $('#selectNewCounselor').on('change', function() {
                $(this).valid();
            });

            // Trigger Modal Ubah Konselor
            $(document).on('click', '.btnChangeCounselor', function() {
                const url = $(this).data('url');
                const elderlyName = $(this).data('elderly-name');
                const counseleeName = $(this).data('counselee-name');
                const currentCounselorId = $(this).data('current-counselor-id');
                const currentCounselorName = $(this).data('current-counselor-name');
                const sessionCount = $(this).data('session-count');

                $('#changeCounselorForm').attr('action', url);
                $('#modalElderlyName').text(elderlyName);
                $('#modalCounseleeName').text(counseleeName);
                $('#modalSessionCount').text(sessionCount + ' Sesi');
                $('#modalCurrentCounselor').text(currentCounselorName).data('current-id', currentCounselorId);

                // Reset validasi dan pilihan konselor
                changeCounselorValidator.resetForm();
                $('#selectNewCounselor').removeClass('is-invalid is-valid');
                $('#selectNewCounselor').next('.select2-container').find('.select2-selection').removeClass('is-invalid is-valid');
                $('#changeCounselorForm').find('.invalid-feedback').remove();
                $('#selectNewCounselor').val('').trigger('change.select2');

                $('#changeCounselorModal').modal('show');
            });

            // Bersihkan error saat modal ditutup
            $('#changeCounselorModal').on('hidden.bs.modal', function() {
                changeCounselorValidator.resetForm();
                $('#selectNewCounselor').removeClass('is-invalid is-valid');
                $('#selectNewCounselor').next('.select2-container').find('.select2-selection').removeClass('is-invalid is-valid');
                $('#changeCounselorForm').find('.invalid-feedback').remove();
                $('#selectNewCounselor').val('').trigger('change.select2');
            });

            // Handler Hapus Data Konseling
            $(document).on('click', '.btnDelete', function() {

                const action = $(this).data('url');
                const name = $(this).data('name');

                if (!confirm(
                        'Apakah Anda yakin ingin menghapus data konseling "' +
                        name +
                        '"?'
                    )) {
                    return;
                }

                const form = $('<form>', {
                    method: 'POST',
                    action: action
                });

                form.append(
                    $('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: $('meta[name="csrf-token"]').attr('content')
                    })
                );

                $('body').append(form);

                form.submit();

            });

        });
    </script>
@endsection
