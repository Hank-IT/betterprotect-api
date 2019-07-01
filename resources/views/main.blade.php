<!doctype html>

<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

@if(\Illuminate\Support\Facades\App::environment('production'))
    <meta http-equiv="Content-Security-Policy" content="default-src 'none'; manifest-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'">
@endif

<title>Betterprotect</title>

<div id="app"></div>

<script src="{{ asset('js/app.js') }}"></script>