<div class="topbar">
    <div>
        Welcome, {{ Auth::user()->name ?? 'User' }}
    </div>

    <div class="topbar-right">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <img src="{{ asset('images/user2.png') }}" alt="User" class="user-image">
    </div>
</div>