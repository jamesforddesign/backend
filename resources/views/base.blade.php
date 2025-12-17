<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst(config('nodes.project.name', 'Backend')) }}</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Work+Sans:400,500,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" media="all" href="/css/project.css">
    @yield('project-css')
    @include('nodes.backend::partials.favicon')
    @if (env('APP_ENV') == 'staging')
        <script>
            window.markerConfig = {
                project: '6942bc687ee72c01c9fcaf5d',
                source: 'snippet'
            };

            !function(e,r,a){if(!e.__Marker){e.__Marker={};var t=[],n={__cs:t};["show","hide","isVisible","capture","cancelCapture","unload","reload","isExtensionInstalled","setReporter","clearReporter","setCustomData","on","off"].forEach(function(e){n[e]=function(){var r=Array.prototype.slice.call(arguments);r.unshift(e),t.push(r)}}),e.Marker=n;var s=r.createElement("script");s.async=1,s.src="https://edge.marker.io/latest/shim.js";var i=r.getElementsByTagName("script")[0];i.parentNode.insertBefore(s,i)}}(window,document);
        </script>
    @endif
</head>

<body class="{{ env('APP_ENV') }} fullbleed">
    @yield('layout')

    @if (env('APP_ENV') == 'local' || env('APP_ENV') == 'staging')
        <div id="environment-notice" class="{{ env('APP_ENV') }}">
            <p>
                <span class="fa fa-warning"></span>
                {{ ucfirst(env('APP_ENV')) }} environment
            </p>
        </div>
    @endif

    <script src="/js/vendor.js"></script>
    <script src="/js/project.js"></script>
    @yield('project-js')
    <script src="//cdn.ckeditor.com/4.5.2/standard/ckeditor.js"></script>
</body>
</html>