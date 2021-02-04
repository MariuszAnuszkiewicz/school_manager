<div class="container">
    @if (Request::segment(1) === 'admin')
        <a class="navbar-brand" href="{{ url('/admin/index') }}">
            @php
                $subName = explode(',', config('app.name'));
            @endphp
            {{ $subName[0] . ' ' . $subName[1] }}
        </a>
    @endif
    @if (Request::segment(1) === 'pupil')
        <a class="navbar-brand" href="{{ url('/pupil/events') }}">
            @php
                $subName = explode(',', config('app.name'));
            @endphp
            {{ $subName[0] . ' ' . $subName[1] }}
        </a>
    @endif
    @if (Request::segment(1) === 'teacher')
        <a class="navbar-brand" href="{{ url('/teacher/pupils') }}">
            @php
                $subName = explode(',', config('app.name'));
            @endphp
            {{ $subName[0] . ' ' . $subName[1] }}
        </a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            @auth
                <div id="nav-container">
                    @if (Request::segment(1) === 'admin')
                        <div class="mt-2">
                            <admin-navbar></admin-navbar>
                        </div>
                    @endif
                    @if (Request::segment(1) === 'pupil')
                        <div class="mt-2">
                            <pupil-navbar></pupil-navbar>
                        </div>
                    @endif
                    @if (Request::segment(1) === 'teacher')
                        <div class="mt-2">
                            <teacher-navbar></teacher-navbar>
                        </div>
                    @endif
                </div>
            @endauth
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
                @if (Route::has('register_pupil'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register_pupil') }}">{{ __('Register Pupil') }}</a>
                    </li>
                @endif
                @if (Route::has('register_teacher'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register_teacher') }}">{{ __('Register Teacher') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span class="caret"><img src="{{ asset('/images/user/avatars/'. Auth::user()->avatar) }}" class="avatar-little"></span>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <div class="user-profile">
                            <div class="mt-1">
                                <a class="dropdown-item" href="{{ url('/user_profile/user/' . Auth::user()->id) }}">
                                    User Profile
                                </a>
                            </div>
                        </div>
                        @if (Request::segment(1) === 'user_profile')
                            <div class="home-link">
                                @if (Auth::user()->roles[0]->name == 'admin')
                                    <div>
                                        <a class="navbar-link" href="{{ url('/admin/index') }}">Home</a>
                                    </div>
                                @endif
                                @if (Auth::user()->roles[0]->name == 'pupil')
                                    <div>
                                        <a class="navbar-link" href="{{ url('/pupil/events') }}">Home</a>
                                    </div>
                                @endif
                                @if (Auth::user()->roles[0]->name == 'teacher')
                                    <div>
                                        <a class="navbar-link" href="{{ url('/teacher/pupils') }}">Home</a>
                                    </div>
                                @endif
                            </div>
                        @endif
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
