<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ujuzi Inventory') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            background: #f4f7fb;
            font-family: Nunito, Arial, sans-serif;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #0f2f4f;
            color: white;
            padding: 25px 18px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar h3 {
            font-size: 22px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 14px;
            margin-bottom: 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar a:hover {
            background: #f4a261;
            color: #102a43;
        }

        .main-area {
            margin-left: 230px;
            width: calc(100% - 230px);
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 8px rgba(15, 47, 79, 0.08);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            border: none;
            background: #e63946;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .user-image {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #f4a261;
        }

        .page-content {
            padding: 30px;
        }
    </style>
</head>
<body>
    @guest
        <main class="py-4">
            @yield('content')
        </main>
    @else
        <div class="app-shell">
            @include('layouts.sidebar')

            <div class="main-area">
                @include('layouts.topbar')

                <main class="page-content">
                    @yield('content')
                </main>
            </div>
        </div>
    @endguest
</body>
</html>