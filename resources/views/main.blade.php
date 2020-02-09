<!doctype html>

<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

<title>{{ config('app.name') }}</title>

<div id="app"></div>

<script src="{{ asset('js/app.js') }}"></script>