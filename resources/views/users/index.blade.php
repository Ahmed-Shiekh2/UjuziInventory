@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Users</h2>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links('pagination::bootstrap-4') }}
</div>
@endsection
