@extends('layouts.mantis')

@section('content')
    <div class="row">

        {{-- Total Work Reports --}}
        @isset($summary['totalWorkReports'])
            <x-dashboard-card title="Total Work Reports" :value="$summary['totalWorkReports']" subtitle="All Work Reports" color="primary"
                icon="fas fa-file-lines" />
        @endisset

        {{-- Total Users --}}
        @isset($summary['totalUsers'])
            <x-dashboard-card title="Total Users" :value="$summary['totalUsers']" subtitle="Registered Users" color="info"
                icon="fas fa-users" />
        @endisset

        {{-- Pending Review --}}
        @isset($summary['pendingReview'])
            <x-dashboard-card title="Pending Review" :value="$summary['pendingReview']" subtitle="Waiting Foreman Review" color="warning"
                icon="fas fa-clock" />
        @endisset

        {{-- Pending Final Approval --}}
        @isset($summary['pendingApproval'])
            <x-dashboard-card title="Pending Final Approval" :value="$summary['pendingApproval']" subtitle="Waiting Final Approval"
                color="secondary" icon="fas fa-user-check" />
        @endisset

        {{-- Approved --}}
        {{-- @isset($summary['approved'])
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-success">Approved</h6>
                        <h2>{{ $summary['approved'] }}</h2>
                    </div>
                </div>
            </div>
        @endisset --}}

        @isset($summary['approved'])
            <x-dashboard-card title="Approved" :value="$summary['approved']" subtitle="Approved Work Reports" color="success"
                icon="fas fa-check-circle" />
        @endisset

        {{-- Rejected --}}
        @isset($summary['rejected'])
            <x-dashboard-card title="Rejected" :value="$summary['rejected']" subtitle="Rejected Work Reports" color="danger"
                icon="fas fa-times-circle" />
        @endisset

        {{-- Submitted --}}
        @isset($summary['submitted'])
            <x-dashboard-card title="My Work Reports" :value="$summary['submitted']" subtitle="Total Reports Created" color="primary"
                icon="fas fa-folder-open" />
        @endisset

        {{-- Approved Today --}}
        @isset($summary['approvedToday'])
            <x-dashboard-card title="Approved Today" :value="$summary['approvedToday']" subtitle="Approved Today" color="success"
                icon="fas fa-calendar-check" />
        @endisset

        {{-- Rejected Today --}}
        @isset($summary['rejectedToday'])
            <x-dashboard-card title="Rejected Today" :value="$summary['rejectedToday']" subtitle="Rejected Today" color="danger"
                icon="fas fa-calendar-times" />
        @endisset

    </div>

    <div class="card mt-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Recent Work Reports

            </h5>

            <a href="{{ route('workreport.index') }}" class="btn btn-sm btn-primary">

                View All

            </a>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Work Order</th>

                        <th>Unit</th>

                        <th>Mekanik</th>

                        <th>Status</th>

                        <th>Created</th>

                        <th width="90">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($recentWorkReports as $report)
                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $report->no_wo ?? '-' }}</td>

                            <td>{{ $report->nomor_unit ?? '-' }}</td>

                            <td>{{ $report->user?->name ?? '-' }}</td>

                            <td>

                                <x-approval-status-badge :status="$report->approval_status" />

                            </td>

                            <td>

                                {{-- {{ $report->created_at->format('d M Y') }} --}}
                                {{ $report->created_at->diffForHumans() }}
                            </td>

                            <td>

                                <a href="{{ route('workreport.show', $report) }}" class="btn btn-sm btn-outline-primary">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center text-muted">

                                No Work Report Available

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
