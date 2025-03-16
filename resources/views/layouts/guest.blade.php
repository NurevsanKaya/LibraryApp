<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            <div class="absolute inset-0 z-0">
                <img src="https://images.pexels.com/photos/1290141/pexels-photo-1290141.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" 
                     alt="Background" 
                     class="w-full h-full object-cover filter brightness-50">
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg relative z-10">
                {{ $slot }}
            </div>
        </div>

        <style>
            body {
                background-color: #1d3557;
            }
            
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"] {
                background-color: #f1faee;
                border-color: #a8dadc;
            }
            
            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="tel"]:focus {
                border-color: #e63946;
                box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.2);
            }
            
            .text-gray-600 {
                color: #457b9d;
            }
            
            .text-gray-900 {
                color: #1d3557;
            }
            
            button[type="submit"] {
                background-color: #e63946;
            }
            
            button[type="submit"]:hover {
                background-color: #d62839;
            }
            
            a {
                color: #e63946;
            }
            
            a:hover {
                color: #d62839;
            }
            
            input[type="checkbox"]:checked {
                background-color: #e63946;
                border-color: #e63946;
            }
        </style>
    </body>
</html>
