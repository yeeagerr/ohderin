<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

  <style>
    .font-anton{
      font-family:'Anton', sans-serif;
    }
  </style>

</head>

<body class="min-h-screen bg-gray-300">

<div class="w-full min-h-screen grid md:grid-cols-2">

  <!-- Bagian kiri -->
  <div class="relative flex items-center justify-center bg-gradient-to-b from-orange-300 via-orange-400 to-red-500">

    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>

  </div>

  <!-- Bagian kanan -->
  <div class="flex items-center justify-center bg-gray-100 p-10">

    <div class="w-full max-w-md">

      <h1 class="text-5xl font-anton mb-10 tracking-wider text-black">
        LOG IN
      </h1>

      <form class="space-y-6">

        <input
        type="text"
        placeholder="Nama"
        class="w-full px-4 py-3 bg-gray-200 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400"
        />

        <input
        type="password"
        placeholder="Password"
        class="w-full px-4 py-3 bg-gray-200 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400"
        />

        <p class="text-sm text-gray-600">
          Belum punya akun?
          <a href="{{route('register')}}" class="text-blue-600 hover:underline">Daftar</a>
        </p>

        <button
        type="submit"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-md shadow-md"
        >
        SUBMIT
        </button>

      </form>

    </div>

  </div>

</div>

</body>
</html>