<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ __app_name() }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ url(__prefix()) }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="{{ asset(__v().'/css/app.css') }}">
    <link href="{{ asset('/favicon.ico') }}" rel="icon" type="image/x-icon" />
    <meta name="theme-color" content="#7F00FF">
    @yield('css')
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'APP_NAME' => __app_name(),
            'APP_URL' => url('/'),
            'APP_ROUTE' => url(Route::current()->uri()),
            'APP_VERSION' => __v(),
            'APP_LANG' => lang()
        ]) !!};
        const Label = {!! json_encode([
            'all' => __('All'),
            'sweetTitle' => __('Warning'),
            'sweetText' => __('will be deleted and cannot be returned'),
            'sweetTextCancel' => __('Canceled'),
            'options' => __('Please select')
        ]) !!};
    </script>
</head>