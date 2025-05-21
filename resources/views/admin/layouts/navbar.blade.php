<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </form>
    <ul class="navbar-nav navbar-right">

      <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
          <i class="p-2 far fa-user"></i>
        <div class="d-sm-none d-lg-inline-block">
          Привет, {{auth()->user()->name}}
        </div>
      </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="{{route('admin.account')}}" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Профиль
          </a>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
            @csrf
                <a href="{{route('logout')}}" onclick="event.preventDefault();
                this.closest('form').submit();" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Выйти
                </a>
            </form>
        </div>
      </li>
    </ul>
  </nav>
