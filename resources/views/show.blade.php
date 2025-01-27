<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Page</title>
    <link rel="stylesheet" href="{{ asset('fonts.css') }}">
    @vite(['resources/css/output.css'])
    <style>
        body {
            background-color: {{ $data['color'] }};
            color: {{ $data['colorTexto'] }};
            font-family: {{ $data['letra'] }};
            font-size: {{ $data['tamanyo'] }}%;
        }
    </style>
</head>
<body>
    <a href="/" class="flex mt-3 ms-3">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 30 30" stroke="currentColor" width="45" height="45">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>Volver
    </a>    
    <div class="grid
     justify-center px-[50%]">
        <h1 class="mb-5">Show Page</h1>
        <p class="mb-5">ID: {{ $id }}</p>
        <p  class="text-justify mb-3">Data: {{ json_encode($data) }}</p>
    </div>
</body>
</html>