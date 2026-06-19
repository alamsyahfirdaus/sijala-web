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
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Nomor HP</th>
                        <th>Role</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <a href="javascript:void(0)" class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                                <br>
                                <span class="text-muted" style="font-size: 12px;">Username:
                                    {{ $user->username }}</span>
                            </td>
                            <td> {{ $user->gender ? ($user->gender == 'L' ? 'Laki-laki' : 'Perempuan') : '-' }}
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ ucwords($user->role) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Lihat Detail</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)">Hapus</a>
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
    {{-- @push('scripts')
        <script>
            $(document).ready(function() {

                $('#userTable').DataTable({

                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Semua"]
                    ],

                    language: {
                        processing: "Sedang memproses...",
                        search: "Pencarian:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Data tidak ditemukan",
                        emptyTable: "Tidak ada data tersedia",
                        loadingRecords: "Memuat data...",
                        paginate: {
                            first: '<i class="bi bi-chevron-bar-left"></i>',
                            previous: '<i class="bi bi-chevron-left"></i>',
                            next: '<i class="bi bi-chevron-right"></i>',
                            last: '<i class="bi bi-chevron-bar-right"></i>'
                        }
                    }

                });

            });
        </script>
    @endpush --}}

@endsection
