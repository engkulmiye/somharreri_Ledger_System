<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center">

<div class="grid grid-cols-1 md:grid-cols-2 w-full min-h-screen">

    <!-- LEFT SIDE -->
    <div class="hidden md:flex flex-col justify-center px-20 relative">
        <div class="absolute inset-0 bg-linear-to-br from-red-600/20 to-black"></div>

        <div class="relative z-10">
            <h1 class="text-5xl font-bold leading-tight">
                Financial clarity,<br>
                <span class="text-red-500">without complexity.</span>
            </h1>

            <p class="mt-6 text-gray-400 max-w-md">
                Manage transactions, commissions and reports through a secure dashboard.
            </p>
        </div>

        <footer class="border-t border-zinc-800 py-8 text-center text-sm text-zinc-500">
         © {{ now()->year }} Somharreri System. All rights reserved.
      <span class="block mt-2 text-xs text-zinc-600">
        Built by <span class="font-medium text-zinc-400">Eng. Moha Kulmiy</span>
        </span>
    </footer>
    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center justify-center px-6">

        <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8">

            <h2 class="text-2xl font-semibold mb-6">
                Login
            </h2>

            <form method="POST" action="/login">
                @csrf

                <div class="mb-4">
                    <label>Email</label>
                    <input type="email" name="email"
                        class="w-full mt-2 p-3 rounded bg-black border border-gray-700"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label>Password</label>
                    <input type="password" name="password"
                        class="w-full mt-2 p-3 rounded bg-black border border-gray-700"
                        required>
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-400">Remember me</span>
                </div>

                @error('email')
                    <div class="text-red-500 mb-4 text-sm">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 transition p-3 rounded-lg font-semibold">
                    Sign In
                </button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
