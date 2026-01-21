<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Dashboard</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        * {
            border-radius: 0.5rem;
        }
        .rounded-custom {
            border-radius: 0.75rem;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-[#f7f6f3]">

<header class="bg-white border-b border-gray-200 px-6 py-4 mb-8">
    <div class="max-w-5xl mx-auto flex justify-between items-center">
        <a href="/" class="text-lg font-semibold text-gray-800 hover:text-gray-600 transition-colors">
            🚀 Training Dashboard
        </a>

        <div class="flex items-center gap-2">
            <a href="{{route('home')}}" class="text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-1">
            <span class="text-sm">Weekly Challenge Progress</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1-1m-6 0h6"></path>
            </svg>
            </a>
        </div>
    </div>
</header>

<main class="flex-1 max-w-5xl mx-auto px-6 w-full">

   {{$slot}}

</main>

<footer class="mt-auto py-8 text-center">
    <p class="text-sm text-gray-500">© {{ date('Y') }} Learning Dashboard</p>
</footer>

</body>
</html>

