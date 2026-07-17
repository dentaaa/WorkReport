@extends('layouts.mantis')

@section('content')
    <div class="container-fluid px-0">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Manage Users</h4>
                {{-- <div>
                    <a href="{{ route('workreport.create') }}" class="btn btn-primary">
                        Tambah Data
                    </a>
                </div> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 text-start" id="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Update Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>

                                    <td>
                                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                            @csrf
                                            <select name="role" class="form-control">
                                                <option value="Mekanik" {{ $user->isMekanik() ? 'selected' : '' }}>Mekanik
                                                </option>
                                                <option value="Foreman" {{ $user->isForeman() ? 'selected' : '' }}>Foreman
                                                </option>
                                                <option value="Supervisor" {{ $user->isSupervisor() ? 'selected' : '' }}>
                                                    Supervisor</option>
                                                <option value="Dept. Head" {{ $user->isDeptHead() ? 'selected' : '' }}>Dept.
                                                    Head</option>
                                                <option value="Trainer" {{ $user->isTrainer() ? 'selected' : '' }}>Trainer
                                                </option>
                                                <option value="Admin" {{ $user->isAdmin() ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>

                                            <button class="btn btn-primary btn-sm mt-2">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container-fluid px-0">
        <div class="card-header">
            <h4>Manage Users</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Update Role</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>

                            <td>
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                    @csrf
                                    <select name="role" class="form-control">
                                        <option value="Mekanik" {{ $user->isMekanik() ? 'selected' : '' }}>Mekanik
                                        </option>
                                        <option value="Foreman" {{ $user->isForeman() ? 'selected' : '' }}>Foreman
                                        </option>
                                        <option value="Supervisor" {{ $user->isSupervisor() ? 'selected' : '' }}>
                                            Supervisor</option>
                                        <option value="Dept. Head" {{ $user->isDeptHead() ? 'selected' : '' }}>Dept.
                                            Head</option>
                                        <option value="Trainer" {{ $user->isTrainer() ? 'selected' : '' }}>Trainer
                                        </option>
                                        <option value="Admin" {{ $user->isAdmin() ? 'selected' : '' }}>Admin</option>
                                    </select>

                                    <button class="btn btn-primary btn-sm mt-2">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div> --}}
@endsection
