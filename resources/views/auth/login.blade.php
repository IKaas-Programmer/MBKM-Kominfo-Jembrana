<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jembrana Kab</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100 flex min-h-screen items-center justify-center p-4">

    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl border border-slate-200">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Jembrana Kab v2</h2>
            <p class="mt-2 text-sm text-slate-500">Sistem Monitoring Publikasi Pegawai</p>
        </div>

        <!-- Alert Error jika Login Gagal -->
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-600 border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ url('/login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Official</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-blue-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all cursor-pointer">
                Masuk ke Aplikasi
            </button>
        </form>
    </div>

</body>

</html>
