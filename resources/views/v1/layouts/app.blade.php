<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @include(__v().'.layouts.partials.head')
    @if (is_route(Route::currentRouteName()))
        <body class="hold-transition login-page with-bg modern-messenger-skin">
            @yield('content')
            <script src="{{ asset(__v().'/js/app.js') }}"></script>
            @yield('js')
        </body>
    @else
        <body class="hold-transition modern-messenger-skin fixed sidebar-mini">
            <div id="app" class="wrapper">
                @include(__v().'.layouts.partials.header')
                @include(__v().'.layouts.partials.leftbar')
                <div class="content-wrapper">
                    @include(__v().'.layouts.partials.breadcrumb')
                    @yield('content')
                </div>
                @include(__v().'.layouts.partials.footer')
                @include(__v().'.layouts.partials.rightbar')
            </div>
            @stack('lang')
            <script src="{{ asset(__v().'/js/app.js') }}"></script>
            @stack('js')
            @yield('js')
        </body>
    @endif
</html>