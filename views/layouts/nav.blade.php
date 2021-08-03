
<!-- NAV -->
<div class="nav" data-uk-sticky="cls-active: uk-background-secondary uk-box-shadow-medium; top: 100vh; animation: uk-animation-slide-top">
    <div class="uk-container">
        <nav class="uk-navbar uk-navbar-container uk-navbar-transparent" data-uk-navbar>
            <div class="uk-navbar-left">
                <div class="uk-navbar-item uk-padding-remove-horizontal">
                    <a class="uk-logo uk-text-default" title="Logo" href=""><img src="img/marketing-logo.svg" alt="">salva|framework</a>
                </div>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav uk-visible@s">
                    <li class="uk-active uk-visible@m"><a href="{{ $router->route('home') }}" uk-icon="home"></a></li>
                   @if (isset($_SESSION['login']))
                   @if ($_SESSION['image'])
                   <li><a><img class="uk-border-circle" src="{{ url($_SESSION['image']) }}" width="40" height="40" ></a></li>
                   @else
                   <li><a><img class="uk-border-circle" src="{{ url($_SESSION['avatar']) }}" width="40" height="40" ></a></li>
                   @endif
                   <li>
                    <a class="white">{{ $_SESSION['name'] }}</a>
                    <div class="uk-navbar-dropdown" uk-dropdown="pos: bottom-justify;">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                            <li class="uk-active"><a href="#">User Manager</a></li>
                            <li><a class="uk-link-reset" href="{{ $router->route('profile') }}">Perfil</a></li>
                            <li><a class="uk-text-danger uk-link" href="{{ $router->route('logout') }}">Log Out</a></li>
                        </ul>
                    </div>
                </li>
                   @else
                   <li><a href="{{ $router->route('login') }}">Registar</a></li>
    
                   <li><a href="{{ $router->route('login') }}">Login</a></li>  
                   @endif
                    
                </ul>
                <a class="uk-navbar-toggle uk-navbar-item uk-hidden@s" data-uk-toggle data-uk-navbar-toggle-icon href="#offcanvas-nav"></a>
            </div>
        </nav>
    </div>
</div>
<!-- /NAV -->

<!-- OFFCANVAS -->
<div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: false">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
        <button class="uk-offcanvas-close uk-close uk-icon" type="button" data-uk-close></button>
        <ul class="uk-nav uk-nav-default">
            <li class="uk-active"><a href="#">Active</a></li>
            <li class="uk-parent">
                <a href="#">Parent</a>
                <ul class="uk-nav-sub">
                    <li><a href="#">Sub item</a></li>
                    <li><a href="#">Sub item</a></li>
                </ul>
            </li>
            <li class="uk-nav-header">Header</li>
            <li><a href="#js-options"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: table"></span> Item</a></li>
            <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: thumbnails"></span> Item</a></li>
            <li class="uk-nav-divider"></li>
            <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: trash"></span> Item</a></li>
        </ul>
        <h3>Title</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
</div>
<!-- /OFFCANVAS -->