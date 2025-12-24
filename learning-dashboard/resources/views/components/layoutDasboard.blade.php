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
        <a href="#" class="text-lg font-semibold text-gray-800 hover:text-gray-600 transition-colors">
            🚀 Training Dashboard
        </a>
        <p class="text-sm text-gray-500 hidden sm:block">Weekly Challenge Progress</p>
    </div>
</header>

<main class="flex-1 max-w-5xl mx-auto px-6 w-full">

   {{$slot}}

</main>

<footer class="mt-auto py-8 text-center">
    <p class="text-sm text-gray-500">© 2025 Learning Dashboard - Elkin Vasquez</p>
</footer>

</body>
</html>

