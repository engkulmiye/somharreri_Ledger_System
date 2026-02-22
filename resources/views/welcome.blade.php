<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Somharreri • Financial Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0b0b0d] text-white antialiased">

<!-- NAVBAR -->
<header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
    <div class="text-xl font-semibold tracking-tight">
        Somharreri<span class="text-red-500">.</span>
    </div>

    <a href="{{ route('filament.admin.auth.login') }}"
       class="px-5 py-2.5 rounded-lg bg-white text-black font-medium hover:bg-zinc-200 transition">
        Login
    </a>
</header>

<!-- HERO -->
<section class="max-w-7xl mx-auto px-6 pt-24 pb-32 grid lg:grid-cols-2 gap-20 items-center">

    <!-- LEFT -->
    <div>
        <h1 class="text-5xl xl:text-6xl font-bold leading-tight tracking-tight">
            Financial clarity,<br>
            <span class="text-red-500">without complexity.</span>
        </h1>

        <p class="mt-6 text-lg text-zinc-400 max-w-xl">
            Manage transactions, commissions, and generate monthly & yearly statements
            through a secure, professional dashboard built for growing businesses.
        </p>

        <div class="mt-10 flex gap-4">
            <a href="{{ route('filament.admin.auth.login') }}"
               class="px-7 py-3 rounded-xl bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                Access Dashboard
            </a>

            <a href="#features"
               class="px-7 py-3 rounded-xl border border-zinc-700 hover:border-zinc-500 transition">
                View Features
            </a>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="relative">
        <div class="absolute inset-0 bg-linear-to-tr from-red-600/30 to-orange-500/20 blur-3xl rounded-full"></div>

        <div class="relative bg-zinc-900 border border-zinc-800 rounded-2xl p-8 shadow-2xl">
            <div class="space-y-6">
                <div class="h-3 w-32 bg-zinc-700 rounded"></div>
                <div class="h-3 w-48 bg-zinc-800 rounded"></div>

                <div class="grid grid-cols-2 gap-4 mt-8">
                    <div class="p-4 bg-zinc-800 rounded-xl">
                        <p class="text-sm text-zinc-400">Monthly Volume</p>
                        <p class="text-xl font-semibold">$124,800</p>
                    </div>
                    <div class="p-4 bg-zinc-800 rounded-xl">
                        <p class="text-sm text-zinc-400">Commissions</p>
                        <p class="text-xl font-semibold">$12,430</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- FEATURES -->
<section id="features" class="border-t border-zinc-800 bg-[#0e0e11]">
    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-3 gap-12">

        <div>
            <h3 class="text-xl font-semibold mb-2">Transaction Management</h3>
            <p class="text-zinc-400">
                Record, track, and audit all financial transactions with precision.
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold mb-2">Monthly & Yearly Statements</h3>
            <p class="text-zinc-400">
                Instantly generate professional statements filtered by date and type.
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold mb-2">Secure Admin Access</h3>
            <p class="text-zinc-400">
                Built on Laravel & React js with role-based authentication.
            </p>
        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="border-t border-zinc-800 py-8 text-center text-sm text-zinc-500">
    © {{ now()->year }} Somharreri System. All rights reserved.
    <span class="block mt-2 text-xs text-zinc-600">
        Built by <span class="font-medium text-zinc-400">Eng. Moha Kulmiy</span>
    </span>
</footer>

</body>
</html>
