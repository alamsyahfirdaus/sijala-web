@extends('layouts.app')

@section('title', 'Laporan')

@section('page_title')
    {{ 'Laporan' }}
@endsection

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Laporan {{ $title }}</h3>
            <div class="card-tools">
                <a href="{{ url()->current() }}" class="btn btn-tool">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <div class="row mb-md-3 mb-2">

                <div class="col-md-5 mb-2 mb-md-0">
                    <select class="form-select select2" id="start_date" name="start_date">
                        <option value="">Tanggal Awal</option>

                        @foreach ($data['availableDates'] as $date)
                            <option value="{{ $date }}" {{ request('start_date') == $date ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                            </option>
                        @endforeach

                    </select>
                    <div class="invalid-feedback d-block" id="start_date_error"></div>
                </div>

                <div class="col-md-5 mb-2 mb-md-0">
                    <select class="form-select select2" id="end_date" name="end_date">
                        <option value="">Tanggal Akhir</option>

                        @foreach ($data['availableDates'] as $date)
                            <option value="{{ $date }}" {{ request('end_date') == $date ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                            </option>
                        @endforeach

                    </select>
                    <div class="invalid-feedback d-block" id="end_date_error"></div>
                </div>

                <div class="col-md-2 text-md-end mb-2 mb-md-0">
                    <div class="btn-group" style="width: 100%;">
                        <button type="button" id="filter-date" class="btn btn-primary">Filter</button>
                        <button type="button" id="export-excel" class="btn btn-success">Excel</button>

                        {{-- <button type="button" id="export-pdf" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                            PDF
                        </button> --}}

                    </div>
                </div>

            </div>

            @if ($report == 'counselee')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama<span style="font-size: 10px; color: #fff;">_</span>Konseli</th>
                            <th>Jenis<span style="font-size: 10px; color: #fff;">_</span>Kelamin</th>
                            <th>Usia</th>
                            <th>Nomor<span style="font-size: 10px; color: #fff;">_</span>HP</th>
                            <th>Jml.<span style="font-size: 10px; color: #fff;">_</span>Sesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['counselees'] as $index => $counselee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $counselee['name'] }}</td>
                                <td>{{ $counselee['gender'] }}</td>
                                <td>{{ $counselee['age'] }}</td>
                                <td>{{ $counselee['phone'] }}</td>
                                <td>{{ $counselee['jml_counselings'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif ($report == 'elderly')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama<span style="font-size: 10px; color: #fff;">_</span>Lansia</th>
                            <th>Jenis<span style="font-size: 10px; color: #fff;">_</span>Kelamin</th>
                            <th>Usia</th>
                            <th>Puskesmas</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['elderlies'] as $index => $elderly)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $elderly['elderly_name'] }}</td>
                                <td>{{ $elderly['elderly_gender'] }}</td>
                                <td>{{ $elderly['elderly_age'] }} Tahun</td>
                                <td>{{ $elderly['puskesmas'] }}</td>
                                {{-- <td>{{ $elderly['has_fallen'] }}</td>
                                <td>{{ $elderly['counselee_name'] }}</td>
                                <td>{{ $elderly['care_duration_months'] }} Bulan</td> --}}
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'counselor')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama<span style="font-size: 10px; color: #fff;">_</span>Konselor</th>
                            <th>Jenis<span style="font-size: 10px; color: #fff;">_</span>Kelamin</th>
                            <th>Nomor<span style="font-size: 10px; color: #fff;">_</span>HP</th>
                            <th>Ditangani</th>
                            <th>Puskesmas</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['counselors'] as $index => $counselor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $counselor['name'] }}</td>
                                <td>{{ $counselor['gender'] }}</td>
                                <td>{{ $counselor['phone'] }}</td>
                                <td>{{ $counselor['total_elderlies'] }}</td>
                                <td title="{{ $counselor['puskesmas'] }}">
                                    {{ \Illuminate\Support\Str::limit($counselor['puskesmas'], 25) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'counseling')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Konseli</th>
                            <th>Lansia</th>
                            <th>Konselor</th>
                            <th>Puskesmas</th>
                            <th>Total<span style="font-size: 10px; color: #fff;">_</span>Sesi</th>
                            <th>Konseling<span style="font-size: 10px; color: #fff;">_</span>Terakhir</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['counselings'] as $counseling)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $counseling['counselee_name'] }}</td>
                                <td>{{ $counseling['elderly_name'] }}</td>
                                <td>{{ $counseling['counselor_name'] }}</td>
                                <td title="{{ $counseling['puskesmas'] }}">
                                    {{ \Illuminate\Support\Str::limit($counseling['puskesmas'], 25) }}</td>
                                <td>{{ $counseling['total_sessions'] }}</td>
                                <td>{{ $counseling['last_counseling_date'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'screening')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Konseli</th>
                            <th>Lansia</th>
                            <th>Risiko Jatuh</th>
                            <th>Selisih</th>
                            <th>Pemberdayaan</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['screenings'] as $screening)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $screening['counselee_name'] }}</td>
                                <td>{{ $screening['elderly_name'] }}</td>
                                <td>
                                    <span>{{ $screening['fall_risk_pre_test'] !== '-' ? 'Pre ' . $screening['fall_risk_pre_test'] : '-' }}</span>
                                    <span> | </span>
                                    <span>{{ $screening['fall_risk_post_test'] !== '-' ? 'Post ' . $screening['fall_risk_post_test'] : '-' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @if ($screening['fall_risk_difference'] === '-')
                                        <span>-</span>
                                    @elseif ($screening['fall_risk_difference'] < 0)
                                        <span class="text-success">
                                            {{ $screening['fall_risk_difference'] }}
                                        </span>
                                    @elseif ($screening['fall_risk_difference'] > 0)
                                        <span class="text-danger">
                                            +{{ $screening['fall_risk_difference'] }}
                                        </span>
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>
                                <td>
                                    <span>{{ $screening['empowerment_pre_test'] !== '-' ? 'Pre ' . $screening['empowerment_pre_test'] : '-' }}</span>
                                    <span> | </span>
                                    <span>{{ $screening['empowerment_post_test'] !== '-' ? 'Post ' . $screening['empowerment_post_test'] : '-' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @if ($screening['empowerment_difference'] === '-')
                                        <span>-</span>
                                    @elseif ($screening['empowerment_difference'] > 0)
                                        <span class="text-success">
                                            +{{ $screening['empowerment_difference'] }}
                                        </span>
                                    @elseif ($screening['empowerment_difference'] < 0)
                                        <span class="text-danger">
                                            {{ $screening['empowerment_difference'] }}
                                        </span>
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'evaluation')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Konseli</th>
                            <th>Tgl.<span style="font-size: 10px; color: #fff;">_</span>Evaluasi</th>
                            <th>Topik</th>
                            <th>Hasil<span style="font-size: 10px; color: #fff;">_</span>Evaluasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['evaluations'] as $evaluation)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $evaluation['counselee_name'] }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($evaluation['evaluation_date'])->translatedFormat('d F Y') }}
                                </td>

                                <td>
                                    {{ $evaluation['topic_name'] }}
                                </td>

                                <td>
                                    {{ round($evaluation['percentage']) }}
                                    <span class="text-muted">
                                        ({{ $evaluation['category'] }})
                                    </span>
                                </td>

                                {{-- <td>
                                    Skor {{ $evaluation['score'] }}
                                    <span class="text-muted">
                                        ({{ $evaluation['percentage'] }}%)
                                    </span>
                                    {{ $evaluation['category'] }}
                                </td> --}}


                                <td title="{{ $evaluation['interpretation'] }}">
                                    {{ \Illuminate\Support\Str::limit($evaluation['interpretation'], 50) }}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @endif
        </div>
    </div>

    <style>
        table.dataTable thead th {
            vertical-align: middle;
            text-align: center;
        }

        table.dataTable thead th:first-child {
            width: 5%;
        }

        table.dataTable tbody td:first-child {
            text-align: center;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#start_date').select2({
                theme: 'bootstrap-5',
                placeholder: 'Tanggal Awal',
                allowClear: true,
            });
            $('#end_date').select2({
                theme: 'bootstrap-5',
                placeholder: 'Tanggal Akhir',
                allowClear: true,
            });
        });

        $('#filter-date').click(function() {

            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $('#start_date_error').text('');
            $('#end_date_error').text('');

            $('#start_date').next('.select2-container').removeClass('select2-error');
            $('#end_date').next('.select2-container').removeClass('select2-error');

            let isValid = true;

            if (!startDate) {
                $('#start_date_error').text('Tanggal awal harus dipilih.');
                $('#start_date').next('.select2-container').addClass('select2-error');
                isValid = false;
            }

            if (!endDate) {
                $('#end_date_error').text('Tanggal akhir harus dipilih.');
                $('#end_date').next('.select2-container').addClass('select2-error');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            if (new Date(startDate) > new Date(endDate)) {

                $('#start_date_error').text('Tanggal awal harus sebelum tanggal akhir.');
                $('#end_date_error').text('Tanggal akhir harus setelah tanggal awal.');

                $('#start_date').next('.select2-container').addClass('select2-error');
                $('#end_date').next('.select2-container').addClass('select2-error');

                return false;
            }

            const form = $('<form>', {
                method: 'POST',
                action: window.location.pathname
            });

            form.append(
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                })
            );

            form.append(
                $('<input>', {
                    type: 'hidden',
                    name: 'start_date',
                    value: startDate
                })
            );

            form.append(
                $('<input>', {
                    type: 'hidden',
                    name: 'end_date',
                    value: endDate
                })
            );

            $('body').append(form);

            form.submit();

        });

        $('#start_date, #end_date').on('change', function() {

            $('#start_date_error').text('');
            $('#end_date_error').text('');

            $('#start_date')
                .next('.select2-container')
                .removeClass('select2-error');

            $('#end_date')
                .next('.select2-container')
                .removeClass('select2-error');

        });

        function submitExport(url, target = '_self') {

            const form = $('<form>', {
                method: 'POST',
                action: url,
                target: target
            });

            form.append(
                '<input type="hidden" name="_token" value="' +
                $('meta[name="csrf-token"]').attr('content') +
                '">'
            );

            form.append(
                '<input type="hidden" name="start_date" value="' +
                $('#start_date').val() +
                '">'
            );

            form.append(
                '<input type="hidden" name="end_date" value="' +
                $('#end_date').val() +
                '">'
            );

            $('body').append(form);

            form.submit();
            form.remove();
        }

        $('#export-excel').click(function() {
            submitExport(
                "{{ route('reports.excel', ['report' => $report]) }}"
            );
        });

        $('#export-pdf').click(function() {
            submitExport(
                "{{ route('reports.pdf', ['report' => $report]) }}",
                '_blank'
            );
        });
    </script>

@endsection
