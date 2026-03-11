<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100"> 
    <h1> class="text-2xl font-bold text-center mb-6">LOGIN </h1>

    <form action="/login" method="POST" class="space-y-4">
        <input
        type="text"
        name="username"
        placeholder="username"
        required
        class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-400"
        />

        <input
        type="password"
        name="password"
        placeholder="Password"
        required
        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
      />

      <button
      type="submit"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded"
      >

      LOGIN 
</button>
</form> 
</div>
    </body>
</html>