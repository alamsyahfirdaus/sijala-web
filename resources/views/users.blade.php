@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="card-title mb-0">
                Daftar {{ $title }}
            </h3>

            <div class="card-tools">
                <div class="btn-group btn-group-sm">

                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bx bx-menu me-1"></i> Aksi
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a href="javascript:void(0)" class="dropdown-item" id="btnAdd" data-bs-toggle="modal"
                                data-bs-target="#userModal">
                                Tambah Pengguna
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <button href="javascript:void(0)" class="dropdown-item" id="btnEdit" disabled>
                                Ubah Pengguna
                            </button>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a href="javascript:void(0)" class="dropdown-item text-danger"
                                data-url="{{ route('user.bulk-delete') }}" id="btnDelete" disabled>
                                Hapus Pengguna Terpilih
                            </a>
                        </li>

                    </ul>

                </div>
            </div>

        </div>
        <div class="card-body table-responsive">
            <table id="table" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">
                            <input class="form-check-input" type="checkbox" id="check-all" />
                        </th>
                        {{-- <th style="width: 5%;">No</th> --}}
                        <th>Nama<span style="font-size: 10px; color: #fff;">_</span>Lengkap</th>
                        <th>Jenis<span style="font-size: 10px; color: #fff;">_</span>Kelamin</th>
                        <th>Nomor<span style="font-size: 10px; color: #fff;">_</span>HP</th>
                        <th>Role</th>
                        <th>Wilayah</th>
                        {{-- <th style="width: 5%;">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center">

                                <input type="checkbox" class="form-check-input check-item" value="{{ encrypt($user->id) }}"
                                    data-id="{{ encrypt($user->id) }}" data-name="{{ $user->name }}"
                                    data-gender="{{ $user->gender }}" data-phone="{{ $user->phone }}"
                                    data-role="{{ $user->role }}" data-puskesmas="{{ $user->puskesmas_id }}">
                            </td>
                            {{-- <td class="text-center">{{ $loop->iteration }}</td> --}}
                            <td>
                                {{ $user->name }}
                                <br>
                                <span class="text-muted" style="font-size: 12px;">Username:
                                    {{ $user->username }}</span>
                            </td>
                            <td> {{ $user->gender ? ($user->gender == 'L' ? 'Laki-laki' : ($user->gender == 'P' ? 'Perempuan' : '-')) : '-' }}
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ ucwords($user->role) }}</td>
                            <td>Puskesmas {{ $user->puskesmas->name ?? '-' }}
                                <br>
                                <span class="text-muted"
                                    style="font-size: 12px;">{{ $user->puskesmas->village->name ?? '-' }},
                                    {{ $user->puskesmas->village->district->name ?? '-' }},
                                    {{ $user->puskesmas->village->district->regency->name ?? '-' }}</span>
                            </td>
                            {{-- <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('user.show', encrypt($user->id)) }}">
                                                <i class="bx bx-show me-1"></i> Lihat Detail
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('user.delete', encrypt($user->id)) }}"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">#</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST" action="{{ route('user.save') }}">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <input type="hidden" name="id" id="user_id">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama Lengkap" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor HP"
                                autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">-- Pilih Role --</option>
                                <option value="konseli">Konseli</option>
                                <option value="konselor">Konselor</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Puskesmas</label>
                            <select class="form-select select2" id="puskesmas_id" name="puskesmas_id">
                                <option value="">-- Pilih Puskesmas --</option>
                                @foreach ($puskesmas as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - {{ $item->village->district->name ?? '-' }},
                                        {{ $item->village->district->regency->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSaveUser">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $('#userModal').on('shown.bs.modal', function() {

                $('#puskesmas_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih Puskesmas',
                    allowClear: true,
                    dropdownParent: $('#userModal')
                });

            });
            /*
            |--------------------------------------------------------------------------
            | UPDATE SELECTION
            |--------------------------------------------------------------------------
            */

            function updateSelection() {

                const total = $('.check-item').length;
                const checked = $('.check-item:checked').length;

                $('#check-all').prop(
                    'checked',
                    total > 0 && total === checked
                );

                // Edit
                $('#btnEdit')
                    .prop('disabled', checked !== 1)
                    .toggleClass('text-primary', checked === 1)
                    .toggleClass('text-secondary', checked !== 1);

                // Delete
                $('#btnDelete')
                    .prop('disabled', checked === 0)
                    .toggleClass('text-success', checked > 0)
                    .toggleClass('text-danger', checked === 0)
                    .html(
                        (checked > 0 ?
                            'Hapus ' + checked + ' Pengguna' :
                            'Hapus Pengguna Terpilih')
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | TAMBAH
            |--------------------------------------------------------------------------
            */

            $('#btnAdd').on('click', function() {

                $('#userModalLabel').text('Tambah Pengguna');

                $('#userForm')[0].reset();

                $('#form_method').val('POST');

                $('#user_id').val('');

                $('#userModal').modal('show');

            });

            /*
            |--------------------------------------------------------------------------
            | UBAH
            |--------------------------------------------------------------------------
            */

            $('#btnEdit').on('click', function() {

                const item = $('.check-item:checked');

                if (item.length !== 1) {
                    return;
                }

                $('#userModalLabel').text('Ubah Pengguna');

                $('#user_id').val(item.data('id'));

                $('#name').val(item.data('name'));
                $('#gender').val(item.data('gender'));
                $('#phone').val(item.data('phone'));
                $('#role').val(item.data('role'));
                $('#puskesmas_id').val(item.data('puskesmas'));

                $('#password').val('');
                $('#password_confirmation').val('');

                $('#form_method').val('PUT');

                $('#userModal').modal('show');

            });

            /*
            |--------------------------------------------------------------------------
            | BULK DELETE
            |--------------------------------------------------------------------------
            */

            $('#btnDelete').on('click', function() {

                const checked = $('.check-item:checked');

                if (checked.length === 0) {
                    alert('Silakan pilih minimal satu pengguna.');
                    return;
                }

                if (!confirm(
                        'Apakah Anda yakin ingin menghapus ' +
                        checked.length +
                        ' pengguna yang dipilih?'
                    )) {
                    return;
                }

                const form = $('<form>', {
                    method: 'POST',
                    action: $(this).data('url')
                });

                form.append(
                    $('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: $('meta[name="csrf-token"]').attr('content')
                    })
                );

                checked.each(function() {

                    form.append(
                        $('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: $(this).val()
                        })
                    );

                });

                $('body').append(form);

                form.submit();

            });

            /*
            |--------------------------------------------------------------------------
            | CHECKBOX
            |--------------------------------------------------------------------------
            */

            $('#check-all').on('change', function() {

                $('.check-item').prop(
                    'checked',
                    $(this).is(':checked')
                );

                updateSelection();

            });

            $(document).on('change', '.check-item', function() {

                updateSelection();

            });

            /*
            |--------------------------------------------------------------------------
            | INIT
            |--------------------------------------------------------------------------
            */

            updateSelection();


            $('#btnSaveUser').on('click', function(e) {

                e.preventDefault();

                // Reset validasi
                $('#userForm .form-control, #userForm .form-select')
                    .removeClass('is-invalid');

                $('#userForm .invalid-feedback').html('');

                let valid = true;

                /*
                |--------------------------------------------------------------------------
                | Nama
                |--------------------------------------------------------------------------
                */
                if ($('#name').val().trim() === '') {

                    $('#name')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .html('Nama lengkap wajib diisi.');

                    valid = false;
                }

                /*
                |--------------------------------------------------------------------------
                | Jenis Kelamin
                |--------------------------------------------------------------------------
                */
                if ($('#gender').val() === '') {

                    $('#gender')
                        .addClass('is-invalid');

                    $('#gender')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Jenis kelamin wajib dipilih.');

                    valid = false;
                }

                /*
                |--------------------------------------------------------------------------
                | Nomor HP
                |--------------------------------------------------------------------------
                */
                if ($('#phone').val().trim() === '') {

                    $('#phone')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .html('Nomor HP wajib diisi.');

                    valid = false;

                } else if (!/^[0-9]{10,15}$/.test($('#phone').val().trim())) {

                    $('#phone')
                        .addClass('is-invalid')
                        .next('.invalid-feedback')
                        .html('Nomor HP tidak valid.');

                    valid = false;
                }

                /*
                |--------------------------------------------------------------------------
                | Role
                |--------------------------------------------------------------------------
                */
                if ($('#role').val() === '') {

                    $('#role')
                        .addClass('is-invalid');

                    $('#role')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Role wajib dipilih.');

                    valid = false;
                }

                /*
                |--------------------------------------------------------------------------
                | Puskesmas
                |--------------------------------------------------------------------------
                */
                if ($('#puskesmas_id').val() === '' || $('#puskesmas_id').val() === null) {

                    $('#puskesmas_id')
                        .addClass('is-invalid');

                    $('#puskesmas_id')
                        .closest('.form-group')
                        .find('.invalid-feedback')
                        .html('Puskesmas wajib dipilih.');

                    valid = false;
                }

                /*
                |--------------------------------------------------------------------------
                | Submit
                |--------------------------------------------------------------------------
                */
                if (valid) {
                    $('#userForm').submit();
                }

            });


        });
    </script>
@endsection
