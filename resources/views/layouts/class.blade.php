<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Requiered for the text editor to work -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">

</head>
<body>
    @yield('content')

    <!-- Requiered for the code mirror editor to work -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/mode/xml/xml.min.js" integrity="sha512-XPih7uxiYsO+igRn/NA2A56REKF3igCp5t0W1yYhddwHsk70rN1bbbMzYkxrvjQ6uk+W3m+qExHIJlFzE6m5eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Requiered for the text editor to work -->


    <!-- User code from codemirror which will be executed -->
{{--    <script id="code-to-execute"></script>--}}
    <script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js"></script>

    {{--    settings 1--}}
{{--
<script>
        window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
        window.APP_DEBUG = {{ config('app.debug') ? 'true' : 'false' }};
    </script>--}}
    <script src="{{asset('js/app.js')}}"></script>

</body>
</html>
