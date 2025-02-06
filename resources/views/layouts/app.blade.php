<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Laravel Newspaper</title>

    <!-- Include Tailwind CSS -->
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900 container mx-auto p-6">

    <nav class="mb-6 flex justify-between items-center bg-white p-4 shadow rounded-lg">
        <a href="{{ url('/') }}" class="text-lg font-bold text-blue-600">Home</a>
    </nav>

    <main>
        @yield('content')
    </main>

</body>
</html>
