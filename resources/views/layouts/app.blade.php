<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FoodShare - SDG 2 Tanpa Kelaparan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">FoodShare</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('donasi.*') ? 'active' : '' }}" href="{{ route('donasi.index') }}">
            Katalog Donasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}" href="{{ route('pengajuan.index') }}">
            {{ auth()->user()->role === 'admin' ? 'Kelola Pengajuan' : 'Pengajuan Saya' }}
        </a>
    </li>
</ul>
                <div class="d-flex align-items-center text-white gap-3">
                    <small>{{ auth()->user()->name }} ({{ auth()->user()->role }})</small>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mb-5">
        @yield('content')
    </main>
</body>
</html>