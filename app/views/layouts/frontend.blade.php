<html>
    <head>
        @section('title')
            <title>Martyn Bissett</title>
        @show

        @section('head')
            @include('partials.head')

            <link rel="stylesheet" type="text/css" href="/css/frontend.css">
        @show
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Martyn Bissett</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                @if ($current_user)
                    <form id="deleteSession" method="post" action="/session">
                        <input type="hidden" name="_METHOD" value="DELETE">
                        <ul class="nav navbar-nav navbar-right">
                            @include ('partials.admin.navbar_links')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $current_user->first_name }} <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/articles">Admin</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" onclick="$('form#deleteSession').submit(); return false;">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </form>
                @else
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            @include ('partials.frontend.navbar_links')
                            <li><a href="/users/create">Register</a></li>
                            <li><a href="/session/login">Login</a></li>
                        </li>
                    </ul>
                @endif
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="container">
            @if (isset($flash_messages->success))
            <div class="alert alert-success">
                {{ $flash_messages->success }}
            </div>
            @endif

            @if (isset($flash_messages->errors))
            <div class="alert alert-danger">
                @foreach ($flash_messages->errors as $error)
                {{ $error }}<br>
                @endforeach
            </div>
            @endif

            @yield('content')
        </div>

        <footer>
            &copy; Martyn Bissett 2016 - Powered by <a href="#">Wordup</a>
        </footer>

        @section('scripts')
            <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>
            <script src="/js/app.js" type="text/javascript"></script>
        @show
    </body>
</html>
