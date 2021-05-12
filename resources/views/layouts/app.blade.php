<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSS Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/my_css.css') }}" rel="stylesheet"> <!-- My CSS Styles -->
	
	
	<!-- Bootsrap -->
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->  
	<!-- Bootsrap -->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<!-- Fa Library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right black">
					
					    <!-- Common links (make link highlighted )-->
                        <li class="{{ Request::is('shopSimple*') ? 'active' : '' }}"> <a href="{{ route('shopSimple') }}">E-shop</a></li>
						<li class="{{ Request::is('shopAdminPanel*') ? 'active' : '' }}"><a href="{{ route('shopAdminPanel') }}">E-shop AdminP</a></li>
                            
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li class="{{ Request::is('login*') ? 'active' : '' }}">  <a href="{{ route('login') }}">Login </a> </li>
                            <li class="{{ Request::is('register*') ? 'active' : '' }}">  <a href="{{ route('register') }}">Register </a></li>
							
                        @else
							
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
								
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
						
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> <!-- Mega Fix (collapsed main menu won't open)-->	
	
	<!-- To register JS file for specific view only (In layout template) (for home '/' only. Loads JS for home Vue component <example>. If is loaded globally will inerfere with Appointmant vue-->
    @if (in_array(Route::getFacadeRoot()->current()->uri(), ['/'])) <!--Route::getFacadeRoot()->current()->uri()  returns testRest--> 
        <script src="{{ asset('js/app.js') }}"></script> <!-- as included always -->
    @endif
	
	
	<!-- To register JS/CSS for specific view only (for ShopSimple asset only) + Some JS/CSS are included in View itself -->
    @if (in_array(Route::getFacadeRoot()->current()->uri(), ['shopSimple', 'admin-products'])) <!--Route::getFacadeRoot()->current()->uri()  returns testRest--> 
	    <!-- Autocomplete -->
        <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script> <!--autocomplete JS-->
        <link rel="stylesheet" href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'> <!--autocomplete CSS -->
        <script src="{{ asset('js/ShopPaypalSimple/autocomplete.js') }}"></script><!--autocomplete itself -->
		
		<script src="{{ asset('js/ShopPaypalSimple/LazyLoad/jquery.lazyload.js')}}"></script> <!--Lazy Load lib JS-->
    @endif
	
	
	<!-- To register JS/CSS for specific view only (for showOneProduct(page result from SearchBar) asset only) + Some JS/CSS are included in View itself -->
    @if (in_array(Route::getFacadeRoot()->current()->uri(), ['showOneProduct/{id}'])) <!--Route::getFacadeRoot()->current()->uri()  returns testRest--> 
		<script src="{{ asset('js/ShopPaypalSimple/LazyLoad/jquery.lazyload.js')}}"></script> <!--Lazy Load lib JS-->
    @endif

	<!-- ALL OTHER/SOME OTHER CSS/JS SCRIPT ARE LOADED IN EVERY SPECIFIC VIEW (before {endsection}) -->

</body>
</html>
