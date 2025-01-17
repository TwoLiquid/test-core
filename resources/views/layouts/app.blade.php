<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        #messages-main {
            position: relative;
            margin: 0 auto;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
        }
        #messages-main:after, #messages-main:before {
            content: " ";
            display: table;
        }
        #messages-main .ms-menu {
            position: absolute;
            left: 0;
            top: 0;
            border-right: 1px solid #eee;
            padding-bottom: 50px;
            height: 100%;
            width: 240px;
            background: #fff;
        }
        @media (min-width:768px) {
            #messages-main .ms-body {
                padding-left: 240px;
            }
        }@media (max-width:767px) {
            #messages-main .ms-menu {
                height: calc(100% - 58px);
                display: none;
                z-index: 1;
                top: 58px;
            }
            #messages-main .ms-menu.toggled {
                display: block;
            }
            #messages-main .ms-body {
                overflow: hidden;
            }
        }
        #messages-main .ms-user {
            padding: 15px;
            background: #f8f8f8;
        }
        #messages-main .ms-user>div {
            overflow: hidden;
            padding: 3px 5px 0 15px;
            font-size: 11px;
        }
        #messages-main #ms-compose {
            position: fixed;
            bottom: 120px;
            z-index: 1;
            right: 30px;
            box-shadow: 0 0 4px rgba(0, 0, 0, .14), 0 4px 8px rgba(0, 0, 0, .28);
        }
        #ms-menu-trigger {
            user-select: none;
            position: absolute;
            left: 0;
            top: 0;
            width: 50px;
            height: 100%;
            padding-right: 10px;
            padding-top: 19px;
        }
        #ms-menu-trigger i {
            font-size: 21px;
        }
        #ms-menu-trigger.toggled i:before {
            content: '\f2ea'
        }
        .fc-toolbar:before, .login-content:after {
            content: ""
        }
        .message-feed {
            padding: 20px;
        }
        #footer, .fc-toolbar .ui-button, .fileinput .thumbnail, .four-zero, .four-zero footer>a, .ie-warning, .login-content, .login-navigation, .pt-inner, .pt-inner .pti-footer>a {
            text-align: center;
        }
        .message-feed.right>.pull-right {
            margin-left: 15px;
        }
        .message-feed:not(.right) .mf-content {
            background: #03a9f4;
            color: #fff;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
        }
        .message-feed.right .mf-content {
            background: #eee;
        }
        .mf-content {
            padding: 12px 17px 13px;
            border-radius: 2px;
            display: inline-block;
            max-width: 80%
        }
        .mf-date {
            display: block;
            color: #B3B3B3;
            margin-top: 7px;
        }
        .mf-date>i {
            font-size: 14px;
            line-height: 100%;
            position: relative;
            top: 1px;
        }
        .msb-reply {
            box-shadow: 0 -20px 20px -5px #fff;
            position: relative;
            margin-top: 30px;
            border-top: 1px solid #eee;
            background: #f8f8f8;
        }
        .four-zero, .lc-block {
            box-shadow: 0 1px 11px rgba(0, 0, 0, .27);
        }
        .msb-reply textarea {
            width: 100%;
            font-size: 13px;
            border: 0;
            padding: 10px 15px;
            resize: none;
            height: 60px;
            background: 0 0;
        }
        .msb-reply button {
            position: absolute;
            top: 0;
            right: 0;
            border: 0;
            height: 100%;
            width: 60px;
            font-size: 25px;
            color: #2196f3;
            background: 0 0;
        }
        .msb-reply button:hover {
            background: #f2f2f2;
        }
        .img-avatar {
            height: 37px;
            border-radius: 2px;
            width: 37px;
        }
        .list-group.lg-alt .list-group-item {
            border: 0;
        }
        .p-15 {
            padding: 15px!important;
        }
        .btn:not(.btn-alt) {
            border: 0;
        }
        .action-header {
            position: relative;
            background: #f8f8f8;
            padding: 15px 13px 15px 17px;
        }
        .ah-actions {
            z-index: 3;
            float: right;
            margin-top: 7px;
            position: relative;
        }
        .actions {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .actions>li {
            display: inline-block;
        }

        .actions:not(.a-alt)>li>a>i {
            color: #939393;
        }
        .actions>li>a>i {
            font-size: 20px;
        }
        .actions>li>a {
            display: block;
            padding: 0 10px;
        }
        .ms-body{
            background:#fff;
        }
        #ms-menu-trigger {
            user-select: none;
            position: absolute;
            left: 0;
            top: 0;
            width: 50px;
            height: 100%;
            padding-right: 10px;
            padding-top: 19px;
            cursor:pointer;
        }
        #ms-menu-trigger, .message-feed.right {
            text-align: right;
        }
        #ms-menu-trigger, .toggle-switch {
            -webkit-user-select: none;
            -moz-user-select: none;
        }
    </style>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
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
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link" href="{{ route('home.chats.index') }}" role="button">
                                    Chats
                                </a>

                                {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="">All</a>
                                    <a class="dropdown-item" href="{{ route('home.chats.create') }}">New</a>
                                </div> --}}
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->login }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
