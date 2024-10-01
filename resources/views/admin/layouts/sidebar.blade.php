<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark d-none d-lg-block" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32">
            <use xlink:href="#bootstrap" />
        </svg>
        <span class="fs-4">Reservation</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#speedometer2" />
                </svg>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('buses.index') }}" class="nav-link active" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#home" />
                </svg>
                Besses
            </a>
        </li>
        <li>
            <a href="{{ route('cities.index') }}" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#table" />
                </svg>
                Cities
            </a>
        </li>
        <li>
            <a href="{{ route('terminals.index') }}" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#table" />
                </svg>
                Terminals
            </a>
        </li>
        <li>
            <a href="{{ route('routes.index') }}" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#grid" />
                </svg>
                Routes
            </a>
        </li>
        <li>
            <a href="{{ route('schedules.index') }}" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#grid" />
                </svg>
                Schedules
            </a>
        </li>
        <li>
            <a href="" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#people-circle" />
                </svg>
                Bookings
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#grid" />
                </svg>
                Payments
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#people-circle" />
                </svg>
                Passengers
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                class="rounded-circle me-2">
            <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</div>

<div class="d-flex flex-column flex-shrink-0 b d-lg-none d-sm-block d-md-block" style="width: 4.5rem;">
    <a href="/" class="d-block p-3 link-body-emphasis text-decoration-none" data-bs-toggle="tooltip"
        data-bs-placement="right" data-bs-original-title="Icon-only">
        <svg class="bi pe-none" width="40" height="32">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="visually-hidden">Icon-only</span>
    </a>
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">

        <li>
            <a href="{{ route('admin.dashboard') }}" class="nav-link py-3 active border-bottom rounded-0"
                data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Dashboard"
                data-bs-original-title="Dashboard">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Dashboard">
                    <use xlink:href="#speedometer2"></use>
                </svg>
            </a>
        </li>
        <li>
            <a href="{{ route('buses.index') }}" class="nav-link py-3 border-bottom rounded-0"
                data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Orders"
                data-bs-original-title="Buses">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Buses">
                    <use xlink:href="#table"></use>
                </svg>
            </a>
        </li>
        <li>
            <a href="{{ route('cities.index') }}" class="nav-link py-3 border-bottom rounded-0"
                data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Products"
                data-bs-original-title="Cities">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Cities">
                    <use xlink:href="#grid"></use>
                </svg>
            </a>
        </li>
        <li>
            <a href="{{ route('terminals.index') }}" class="nav-link py-3 border-bottom rounded-0"
                data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Terminals"
                data-bs-original-title="Terminals">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Terminals">
                    <use xlink:href="#people-circle"></use>
                </svg>
            </a>
        </li>
    </ul>

</div>
