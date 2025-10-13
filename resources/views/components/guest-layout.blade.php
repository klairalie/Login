<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css',  'resources/js/app.js'])
</head>
<body>

    <nav class="bg-transparent shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <h1 class="font-bold text-2xl">BookRentingStore</h1>
            <div class="flex space-x-6">
                <a href="/" class="text-black hover:bg-blue-200 backdrop-blur-s rounded-lg px-3 py-1.5 hover:underline font-mono text-2xl">Home</a>
                <a href="" class="text-gray-800 font-mono hover:bg-blue-200 backdrop-blur-s rounded-lg hover:underline px-3 py-1.5 text-2xl">Explore</a>
                <a href="{{ route('show.login') }}" class="text-gray-800 font-mono hover:bg-blue-200 hover:underline backdrop-blur-s rounded-lg px-3 py-1.5 text-2xl">Sign-in</a>
                <a href="#" class="text-gray-800 font-mono hover:bg-blue-200 backdrop-blur-s hover:underline rounded-lg px-3 py-1.5 text-2xl">About Us</a>
            </div>
        </div>
    </div>
</nav>
<br>
<br>
    <main class="ml-30 flex-1 p-6">

        {{ $slot }}
    </main>
    
</body>
</html>