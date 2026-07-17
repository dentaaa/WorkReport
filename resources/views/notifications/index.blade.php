@extends('layouts.mantis')

@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                Notifications

            </h4>

            <form action="{{ route('notifications.markAllRead') }}" method="POST">

                @csrf

                <button class="btn btn-sm btn-primary">

                    <i class="ti ti-check"></i>

                    Mark All as Read

                </button>

            </form>

        </div>

        <div class="card-body">

            @forelse($notifications as $notification)
                <a href="{{ route('notifications.open', $notification) }}" class="text-decoration-none">

                    <div
                        class="border rounded p-3 mb-3
                    {{ !$notification->is_read ? 'bg-light' : '' }}">

                        <div class="fw-bold">

                            <i class="{{ $notification->icon }}"></i>

                            {{ $notification->title }}

                        </div>

                        <div class="text-muted">

                            {{ $notification->message }}

                        </div>

                        <small class="text-secondary">

                            {{ $notification->created_at->diffForHumans() }}

                        </small>

                    </div>

                </a>

            @empty

                <div class="text-center text-muted py-5">

                    Belum ada notification.

                </div>
            @endforelse

            <div class="mt-4">

                {{ $notifications->links() }}

            </div>

        </div>

    </div>
@endsection
