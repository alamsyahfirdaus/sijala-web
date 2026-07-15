@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    @php
        $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');

        $role = match ($user->role) {
            'admin' => 'Administrator',
            'konselor' => 'Konselor',
            'konseli' => 'Konseli',
            default => ucfirst($user->role),
        };
    @endphp

    <div class="card">
        <div class="card-body text-center">

            <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3 fw-bold"
                style="width:96px; height:96px; font-size:2rem;">
                {{ $initials }}
            </div>
            <h3 class="h5 mb-1">{{ $user->name }}</h3>
            <p class="text-secondary mb-3">{{ $role }}</p>
            <button type="button" class="btn btn-warning text-white w-100" data-bs-toggle="modal"
                data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Profil
            </button>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label class="form-label">Pengguna</label>
                            <select class="form-select select2" id="user_id" name="user_id" style="width: 100%;">
                                <option value="">-- Pilih Pengguna --</option>
                                @foreach ($users as $item)
                                    <option value="{{ encrypt($item->id) }}" {{ $item->id == $user->id ? 'selected' : '' }}
                                        data-username="{{ $item->username }}">
                                        {{ $item->name }} ({{ $item->username ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ $user->username }}" placeholder="Username" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password <span style="font-size: 12px;">(Opsional)</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning text-white" id="btnUpdateProfile">Simpan
                        Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $('#editProfileModal').on('shown.bs.modal', function() {
                if (!$('#user_id').hasClass('select2-hidden-accessible')) {

                    $('#user_id').select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Pilih Pengguna',
                        allowClear: true,
                        dropdownParent: $('#editProfileModal')
                    });

                }

                resetValidation();
            });

            $('#user_id').change(function() {

                $('#username').val(
                    $(this).find(':selected').data('username') ?? ''
                );

                resetValidation();

            });

            $('#togglePassword').on('click', function() {
                const password = $('#password');
                const icon = $('#togglePasswordIcon');
                if (password.attr('type') === 'password') {
                    password.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    password.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            $('#btnUpdateProfile').click(function() {

                let isValid = true;

                $('#editProfileForm .form-control').removeClass('is-invalid');
                $('#editProfileForm .form-select').removeClass('is-invalid');
                $('#editProfileForm .invalid-feedback').html('');

                const userId = $('#user_id').val();

                if (userId === '') {

                    $('#user_id')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Pengguna wajib dipilih.');

                    isValid = false;
                }

                const username = $('#username').val().trim();

                if (username === '') {

                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Username wajib diisi.');

                    isValid = false;

                } else if (username.length < 4) {

                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Username minimal 4 karakter.');

                    isValid = false;
                }

                const password = $('#password').val().trim();

                if (password !== '' && password.length < 8) {

                    $('#password')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Password minimal 8 karakter.');

                    isValid = false;
                }

                if (
                    isValid &&
                    $('#username').val().trim() === $('#user_id').find(':selected').data('username') &&
                    $('#password').val().trim() === ''
                ) {

                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Silakan ubah username atau isi password.');

                    return;
                }

                if (isValid) {

                    $('#btnUpdateProfile')
                        .prop('disabled', true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...'
                        );

                    $('#editProfileForm').submit();
                }

            });

        });

        function resetValidation() {

            $('#editProfileForm')
                .find('.is-invalid')
                .removeClass('is-invalid');

            $('#editProfileForm')
                .find('.invalid-feedback')
                .html('');

        }
    </script>

@endsection
