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

                <div class="col-md-4 mb-2 mb-md-0">
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

                <div class="col-md-4 mb-2 mb-md-0">
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

                <div class="col-md-4 text-md-end mb-2 mb-md-0">
                    <div class="btn-group" style="width: 100%;">

                        <button type="button" id="filter-date" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            Filter
                        </button>

                        <button type="button" id="export-excel" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel-fill"></i>
                            Excel
                        </button>

                        <button type="button" id="export-pdf" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                            PDF
                        </button>

                    </div>
                </div>

            </div>

            @if ($report == 'elderly')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lansia</th>
                            <th>Jenis Kelamin</th>
                            <th>Usia</th>
                            <th>Konseli</th>
                            <th>Lama Merawat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['elderlies'] as $index => $elderly)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td>{{ $elderly['elderly_name'] }}</td>
                                <td>{{ $elderly['elderly_gender'] == 'L' ? 'Laki-Laki' : ($elderly['elderly_gender'] == 'P' ? 'Perempuan' : '-') }}
                                </td>
                                <td>{{ $elderly['elderly_age'] }} Tahun</td>
                                <td>{{ $elderly['counselee_name'] }}</td>
                                <td>{{ $elderly['care_duration_months'] }} Bulan</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'counselor')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Konselor</th>
                            <th>Nomor HP</th>
                            <th>Puskesmas</th>
                            <th>Total Konseling</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['counselors'] as $index => $counselor)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td>{{ $counselor['name'] }}</td>
                                <td>{{ $counselor['phone'] }}</td>
                                <td>{{ $counselor['puskesmas_name'] }}</td>
                                <td>{{ $counselor['total_counselings'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'counseling')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Konseli</th>
                            <th>Konselor</th>
                            <th>Total Sesi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['counselings'] as $counseling)
                            <tr>

                                <td style="text-align: center;">{{ $loop->iteration }}</td>

                                <td>
                                    {{ $counseling['counselee_name'] }}
                                </td>

                                <td>
                                    {{ $counseling['counselor_name'] }}
                                </td>

                                <td>
                                    {{ $counseling['total_sessions'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'screening')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Konseli</th>
                            <th>Tanggal Skrining</th>
                            <th>Risiko Jatuh</th>
                            <th>Pemberdayaan Keluarga</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['screenings'] as $screening)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $screening['counselee_name'] }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($screening['screening_date'])->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    {{ $screening['fall_risk_score'] }}
                                    <span class="text-muted">
                                        ({{ $screening['fall_risk_category'] }})
                                    </span>
                                </td>

                                <td>
                                    {{ $screening['empowerment_score'] }}
                                    <span class="text-muted">
                                        ({{ $screening['empowerment_category'] }})
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @elseif ($report == 'evaluation')
                <table class="table table-bordered table-striped datatable" style="width: 100%;">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
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

                                <td class="text-center">
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
                                    {{ round($evaluation['percentage'])}}
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

                                <td>
                                    {{ $evaluation['interpretation'] }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @endif
        </div>
    </div>
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
