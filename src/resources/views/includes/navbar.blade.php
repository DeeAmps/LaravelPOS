<nav class="navbar navbar-expand-lg">
    <div class="menu-btn-wrapper">
        <i class="fas fa-bars menu-toggle"></i>
    </div>
    <a class="navbar-brand" href="#">easyPOS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            {{-- <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li> --}}
            @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link {{strpos(Request::url(), "/") <= -1 ? 'active-menu' : ''}}" 
                    href="{{url('/')}}">Home</a>
            </li>
            @endif
            @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'manager']))
                <li class="nav-item">
                    <a class="nav-link {{strpos(Request::url(), '/inventory')>-1 ? 'active-menu' : ''}}" 
                        href="{{route('inventory.index')}}">Inventory</a>
                </li>
            @endif
            @if(Auth::check() && Auth::user()->hasAnyRole(['sales-rep', 'admin', 'manager']))
            <li class="nav-item {{strpos(Request::url(), '/pos')>-1 ? 'active-menu' : ''}}">
                <a class="nav-link" href="{{route('pos.index')}}">Sales</a>
            </li>
            @endif
            @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'manager']))
            <li class="nav-item {{strpos(Request::url(), '/purchase')>-1 ? 'active-menu' : ''}}">
                <a class="nav-link" href="{{route('purchase.index')}}">Purchases</a>
            </li>
            @endif
            @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'manager']))
            <li class="nav-item">
                <a class="nav-link {{strpos(Request::url(), '/report')>-1 ? 'active-menu' : ''}}" 
                    href="{{route('report.index')}}">Reports</a>
            </li>
            @endif
            @if(Auth::check() && Auth::user()->hasAnyRole(['admin']))
            <li class="nav-item {{strpos(Request::url(), '/admin')>-1 ? 'active-menu' : ''}}">
                <a class="nav-link" href="{{route('admin.users')}}">Admin</a>
            </li>  
            @endif
        </ul>
        <ul class="user-management-wrapper">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-name" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::check())
                        {{Auth::user()->username}}
                    @else
                        Register
                    @endif
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if(!Auth::check())
                        <a class="dropdown-item" href="{{route('login')}}">Login</a>
                    @endif    
                    @if(Auth::check())
                        <a class="dropdown-item" href="{{route('home')}}">Profile</a>
                    @endif    
                    <div class="dropdown-divider"></div>
                    @if(Auth::check())
                        <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                    @endif    
                </div>  
            </li>
        </ul>
    </div>
  </nav>