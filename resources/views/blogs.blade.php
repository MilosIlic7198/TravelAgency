<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Laravel</title>
</head>

<body class="antialiased">
    <div id="app">
        <form method="POST" action="/create-blog" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="naslov" class="inline-block text-lg mb-2">Naslov:</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="naslov" />
            </div>
            <div class="mb-6">
                <label for="opis" class="inline-block text-lg mb-2">Opis:</label>
                <textarea class="border border-gray-200 rounded p-2 w-full" name="opis" rows="10"></textarea>
            </div>
            <div class="mb-6">
                <label for="image" class="inline-block text-lg mb-2">
                    Image
                </label>
                <input type="file" class="border border-gray-200 rounded p-2 w-full" name="image" />
            </div>
            <select class="form-select" name="type">
                <option selected value="0">Izaberi tip!</option>
                <option value="Vest">Vest</option>
                <option value="Post">Post</option>
            </select>
            <div class="mb-6">
                <button type="submit" class="btn btn-success text-white rounded py-2 px-4 hover:bg-black">
                    Create Blog
                </button>
            </div>
        </form>
    </div>
</body>

</html>