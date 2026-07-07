@extends('layouts.app')

@section('title', $title)

@section('page_title')
    {{ $title }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail {{ ucwords($user->role) }}</h3>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item d-flex justify-content-between px-0 pt-0">
                    <span>Nama Lengkap</span>
                    <span>{{ $user->name }} <small class="text-muted" title="Username">({{ $user->username }})</small></span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Jenis Kelamin</span>
                    <span>
                        {{ $user->gender ? ($user->gender == 'L' ? 'Laki-laki' : ($user->gender == 'P' ? 'Perempuan' : '-')) : '-' }}
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Nomor HP</span>
                    <span>
                       {{ $user->phone ?? '-' }}
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Wilayah</span>
                    <span>Puskesmas {{ $user->puskesmas->name ?? '-' }},
                       {{ $user->puskesmas->village->name ?? '-' }}, {{ $user->puskesmas->village->district->name ?? '-' }}
                       {{-- {{ $user->puskesmas->village->district->name ?? '-' }}, {{ $user->puskesmas->village->district->regency->name ?? '-' }}, {{ $user->puskesmas->village->district->regency->province->name ?? '-' }} --}}
                    </span>
                </li>
                {{-- {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Usia</span>
                    <span>{{ $counseling->elderlyCounselee->elderly_age }} Tahun</span>
                </li> --}}
                {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Jenis Kelamin</span>
                    <span>{{ $counseling->elderlyCounselee->elderly_gender == 'L' ? 'Laki-Laki' : ($counseling->elderlyCounselee->elderly_gender == 'P' ? 'Perempuan' : '-') }}</span>
                </li> --}}
                {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Konseli</span>
                    <span><a href=""
                            class="text-decoration-none">{{ $counseling->elderlyCounselee->counselee->name }}</a></span>
                </li> --}}
                {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Lama Merawat</span>
                    <span>{{ $counseling->elderlyCounselee->care_duration_months }} Bulan</span>
                </li> --}}
                {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Pernah Jatuh</span>
                    <span>{{ $counseling->elderlyCounselee->has_fallen ? 'Ya' : 'Tidak' }}</span>
                </li> --}}
                {{-- <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Konselor</span>
                    <span><a href="" class="text-decoration-none">{{ $counseling->counselor->name }}</a></span>
                </li> --}}
                {{-- <li
                    class="list-group-item d-flex justify-content-between px-0 {{ $counseling->elderlyCounselee->health_problems ? 'border-bottom' : 'pb-0' }}">
                    <span>Wilayah</span>
                    <span>Puskesmas {{ $counseling->counselor->puskesmas->name }}</span>
                </li> --}}
            </ul>
            {{-- @if ($counseling->elderlyCounselee->health_problems)
                <div class="form-group mt-2">
                    <label for="health_problems" class="form-label">Kondisi Kesehatan</label>
                    <textarea id="health_problems" class="form-control" disabled style="background-color: #fff;">{{ $counseling->elderlyCounselee->health_problems ?? '-' }}</textarea>
                </div>
            @endif --}}
        </div>
    </div>

@endsection
