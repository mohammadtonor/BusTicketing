<nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand d-md-none" href="#">
            <svg class="bi" width="24" height="24">
                <use xlink:href="#aperture" />
            </svg>
            Aperture
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"
            aria-controls="offcanvas" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasLabel">Aperture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="w-25"></div>

            <div class="offcanvas-body">
                <svg class="bi mt-2 ml-4 " width="24" height="24">
                    <use xlink:href="#aperture" />
                </svg>
                <ul class="navbar-nav flex-grow-1 justify-content-end">
                    @auth
                        <!-- For authenticated users -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('schedules') }}">Schedules-List</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('booked-schedules') }}">Booked-Schedule</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.profile.show') }}">Profile</a></li>

                        <!-- Logout -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                LogOut
                            </a>
                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endauth

                    @guest
                        <!-- For guests (unauthenticated users) -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.register') }}">Register</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
