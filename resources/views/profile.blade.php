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
                            <select class="form-select select2 @error('user_id') is-invalid @enderror" id="user_id" name="user_id" style="width: 100%;">
                                <option value="">-- Pilih Pengguna --</option>
                                @foreach ($users as $item)
                                    @php
                                        $encryptedId = encrypt($item->id);
                                        $isSelected = old('user_id')
                                            ? (function () use ($item) {
                                                try {
                                                    return decrypt(old('user_id')) == $item->id;
                                                } catch (\Exception $e) {
                                                    return false;
                                                }
                                            })()
                                            : ($item->id == $user->id);
                                    @endphp
                                    <option value="{{ $encryptedId }}" {{ $isSelected ? 'selected' : '' }}
                                        data-username="{{ $item->username }}">
                                        {{ $item->name }} ({{ $item->username ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback"></div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" placeholder="Username" autocomplete="off">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback"></div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password <span style="font-size: 12px;">(Opsional)</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password" autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback"></div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white" id="btnUpdateProfile" form="editProfileForm">
                        Simpan Perubahan
                    </button>
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
            });

            $('#editProfileModal').on('hidden.bs.modal', function() {
                $('#btnUpdateProfile').prop('disabled', false).html('Simpan Perubahan');
            });

            $('#user_id').change(function() {
                const selectedOpt = $(this).find(':selected');
                const uname = selectedOpt.attr('data-username') || selectedOpt.data('username') || '';
                $('#username').val(uname);
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

            $('#editProfileForm').on('submit', function(e) {
                let isValid = true;
                resetValidation();

                const userId = $('#user_id').val();
                if (!userId) {
                    $('#user_id')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .addClass('d-block')
                        .html('Pengguna wajib dipilih.');
                    isValid = false;
                }

                const username = $('#username').val().trim();
                if (username === '') {
                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .addClass('d-block')
                        .html('Username wajib diisi.');
                    isValid = false;
                } else if (username.length < 3) {
                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .addClass('d-block')
                        .html('Username minimal 3 karakter.');
                    isValid = false;
                }

                const password = $('#password').val();
                if (password !== '' && password.length < 6) {
                    $('#password')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .addClass('d-block')
                        .html('Password minimal 6 karakter.');
                    isValid = false;
                }

                const selectedUsername = ($('#user_id').find(':selected').attr('data-username') || '').trim();
                if (isValid && username === selectedUsername && password === '') {
                    $('#username')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .addClass('d-block')
                        .html('Silakan ubah username atau isi password.');
                    e.preventDefault();
                    return false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                $('#btnUpdateProfile')
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
            });

            @if ($errors->any())
                const editProfileModalEl = document.getElementById('editProfileModal');
                if (editProfileModalEl) {
                    const modalInstance = new bootstrap.Modal(editProfileModalEl);
                    modalInstance.show();
                }
            @endif

        });

        function resetValidation() {
            $('#editProfileForm')
                .find('.is-invalid')
                .removeClass('is-invalid');

            $('#editProfileForm')
                .find('.invalid-feedback')
                .removeClass('d-block')
                .html('');
        }
    </script>

@endsection
