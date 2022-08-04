<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')|{{ config('app.name', 'Laravel') }}</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body>
    <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            </ul>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('img\avatar\avatar-1.png') }}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg-end">
                        <div class="dropdown-title">
                            Welcome {{ Auth::user()->fullname }}<br>
                            <small>
                                you are  
                                @if (Auth::user()->level==0)
                                    Visitors
                                @else
                                    Admin
                                @endif
                                now
                            </small>
                        </div>
                        <a href="features-profile.html" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> Profile
                        </a>
                        <a href="features-activities.html" class="dropdown-item has-icon">
                            <i class="fas fa-bolt"></i> Activities
                        </a>
                        <a href="features-settings.html" class="dropdown-item has-icon">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item has-icon text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">{{ config('app.name', 'Laravel') }}</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            @php
                $nameArr = explode(" ",config('app.name', 'Laravel'));
                $nameSm = count($nameArr)<2?($nameArr[0][0].$nameArr[0][1]):($nameArr[0][0].$nameArr[1][0]);
            @endphp
            <a href="index.html">{{ $nameSm }}</a>
          </div>
          <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="@yield('dashboard')">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-fire me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="@yield('store')">
                    <a class="nav-link" href="{{ route('store.index') }}">
                        <i class="fas fa-store me-2"></i>
                        <span>Stores</span>
                    </a>
                </li>
                <li class="@yield('product')">
                    <a class="nav-link" href="{{ route('product.index') }}">
                        <i class="fas fa-boxes me-2"></i>
                        <span>Product</span>
                    </a>
                </li>
                <li class="menu-header">Starter</li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <span>Bills</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="3">
                                <i class="fas fa-circle-notch fa-lg text-success m-0"></i>
                                <span>List Order</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="#">
                                <i class="fas fa-circle-notch fa-lg text-warning m-0"></i>
                                <span>Split Bills</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
            </ul>
        </aside>
      </div>
      <!-- Main Content -->

      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>@yield('title') Page</h1>
          </div>

          <div class="section-body">
            <div class="status-login d-block">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            @yield('content')
          </div>
        </section>
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="{{ asset('js\jquery.nicescroll-master\dist\jquery.nicescroll.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @yield('script')
</body>
</html>
