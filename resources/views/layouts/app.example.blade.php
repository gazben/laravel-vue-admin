<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
                    "csrfToken" => csrf_token()
            ]) !!};
    </script>
    <meta http-equiv=x-ua-compatible content="ie=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href=/logo.png>
</head>
<body>
<div id=app></div>
<script type="text/javascript" src="/vendor.js"></script>
<script type="text/javascript" src="/assets/js/app.js"></script>
</body>
</html>