<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>

    <meta name="application-name" content="{{ config('app.name') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased">

<!-- Sidenav -->
<nav
    id="sidenav-2"
    class="fixed left-0 top-0 z-[1035] h-screen w-60 -translate-x-full overflow-hidden bg-white shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] data-[te-sidenav-hidden='false']:translate-x-0 dark:bg-zinc-800"
    data-te-sidenav-init
    data-te-sidenav-hidden="false"
    data-te-sidenav-mode="side"
    data-te-sidenav-content="#content">
    <ul class="relative mt-3 ml-4  list-none px-[0.2rem]" data-te-sidenav-menu-ref>
        <li class="relative">
            <a  href="{{route('projects.index')}}">LaraEngine</a>
        </li>

        <li class="relative mt-3">
            <a  href="{{route('projects.index')}}">Projects</a>
        </li>
    </ul>
</nav>
<!-- Sidenav -->
<div class="p-5 !pl-[260px] text-center" id="content">
    @if (session('status'))
        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
            <span class="font-medium">
            {{ session('status') }}
            </span>
        </div>
    @endif
    <!-- Toggler -->
    <div class="my-5 flex text-start">

        {{ $slot }}

    </div>
</div>


@livewire('notifications')

@filamentScripts
@vite('resources/js/app.js')

</body>
</html>
