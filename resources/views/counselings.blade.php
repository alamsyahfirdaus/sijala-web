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
                        <th>Konseling</th>
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
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="{{ route('counseling.session', encrypt($session->id)) }}">Lihat
                                                Detail</a></li>
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
@endsection
