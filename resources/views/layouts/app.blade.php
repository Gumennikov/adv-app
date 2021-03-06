<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Adverts</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js', 'build') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'build') }}" rel="stylesheet">
</head>
<body id="app">
    <header>
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Adverts
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a href="{{ route('admin.home') }}" class="dropdown-item">Admin</a>
                                    <a href="{{ route('cabinet.home') }}" class="dropdown-item">Cabinet</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @section('search')
            <div class="search-bar pt-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <form method="GET" action="{{ route( 'adverts.index')}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="text"
                                                   value="{{ request('text') }}" placeholder="Search for ...">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button class="btn btn-light border" type="submit"><span class="fa fa-search">
                                                </span></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3" style="text-align: right"><p>
                                <a href="{{ route('cabinet.adverts.create') }}" class="btn btn-success">
                                    <span class="btn btn-plus"></span>Add New Advertisement</a></p>
                        </div>
                    </div>
                </div>
            </div>
        @show
    </header>

    <main class="app-content py-3">
        <div class="container">
            @section('breadcrumbs', \Diglactic\Breadcrumbs\Breadcrumbs::render())
            @yield('breadcrumbs')
            @include('layouts/partials/flash')
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="border-top pt-3">
                <p>&copy; {{ date('Y') }} - Adverts</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js', 'build') }}"></script>
</body>
</html>
