<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk – {{ config('news_portal.site.name', 'Info Lantas Mojokerto') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $themeColor = \App\Models\WebSetting::get('theme_color', '#dc2626'); @endphp
    <style>
        :root { --color-primary: {{ $themeColor }}; }
        .input-field {
            width: 100%;
            background: #fff;
            border: 2px solid #e5e7eb;
            color: #111827;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.875rem 1rem;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .input-field:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-primary) 15%, transparent);
            background: #fff;
        }
        .input-field:hover:not(:focus) {
            border-color: #d1d5db;
        }
        .input-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: var(--color-primary);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px color-mix(in srgb, var(--color-primary) 30%, transparent);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-primary:hover { 
            background: color-mix(in srgb, var(--color-primary) 90%, black);
            box-shadow: 0 6px 8px -2px color-mix(in srgb, var(--color-primary) 40%, transparent);
            transform: translateY(-1px);
        }
        .btn-primary:active { 
            transform: translateY(0);
            box-shadow: none;
        }
        .left-panel {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: relative;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
<div class="min-h-screen flex">

    {{-- ─── Left Branding Panel ─── --}}
    <div class="left-panel hidden lg:flex w-5/12 flex-col justify-between p-12 overflow-hidden shrink-0">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full opacity-20 blur-3xl" style="background: var(--color-primary)"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full opacity-10 blur-3xl" style="background: var(--color-primary)"></div>
        </div>

        {{-- Top logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 relative z-10 transition-transform hover:scale-105 origin-left">
            <div class="p-2 bg-white rounded-lg shadow-sm">
                <img src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="h-8 w-auto">
            </div>
            <div class="leading-none">
                <div class="text-white text-lg font-black uppercase tracking-tight">Info Lantas</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-1 text-gray-300">Mojokerto</div>
            </div>
        </a>

        {{-- Center text --}}
        <div class="relative z-10 my-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 mb-6">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--color-primary)"></span>
                <span class="text-xs font-bold text-white uppercase tracking-wider">Selamat Datang Kembali</span>
            </div>
            
            <h2 class="text-4xl xl:text-5xl font-black text-white leading-[1.1] mb-6">
                Akses informasi<br>lalu lintas<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400" style="background-image: linear-gradient(to right, white, var(--color-primary))">lebih cepat.</span>
            </h2>
            <p class="text-gray-400 text-base font-medium leading-relaxed max-w-sm">
                Masuk ke akun Anda untuk ikut berdiskusi, memberikan komentar, dan mendapatkan update terkini seputar lalu lintas Mojokerto.
            </p>
        </div>

        {{-- Bottom footer --}}
        <div class="relative z-10 flex items-center justify-between text-xs font-medium text-gray-500">
            <p>&copy; {{ date('Y') }} Info Lantas Mojokerto</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition-colors">Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Ketentuan</a>
            </div>
        </div>
    </div>

    {{-- ─── Right Form Panel ─── --}}
    <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-10 lg:px-20 xl:px-32 bg-gray-50/50 relative">
        
        {{-- Background Pattern --}}
        <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.02]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 24px 24px;"></div>

        {{-- Mobile Logo --}}
        <div class="lg:hidden mb-8 text-center relative z-10">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3">
                <div class="p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                    <img src="{{ asset('LogoBaruILM.png') }}" alt="Logo" class="h-10 w-auto">
                </div>
                <div class="leading-none">
                    <div class="text-gray-900 text-xl font-black uppercase tracking-tight">Info Lantas</div>
                    <div class="text-xs font-bold uppercase tracking-widest mt-1" style="color: var(--color-primary)">Mojokerto</div>
                </div>
            </a>
        </div>

        <div class="w-full max-w-md mx-auto relative z-10">
            
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h1>
                <p class="text-gray-500 text-base">Senang melihat Anda kembali! Silakan masukkan detail login Anda.</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3.5 rounded-lg text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="auth-card p-6 sm:p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="input-label">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input-field @error('email') !border-red-500 !ring-red-100 @enderror"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="input-label !mb-0">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold hover:underline" style="color: var(--color-primary)">
                                    Lupa Sandi?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="input-field @error('password') !border-red-500 @enderror"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded focus:ring-2 focus:ring-offset-1 transition-all cursor-pointer"
                                    style="focus:ring-color: var(--color-primary)">
                                <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <div class="absolute inset-0 rounded border-2 opacity-0 peer-checked:opacity-100 peer-checked:bg-current pointer-events-none transition-all" style="color: var(--color-primary); border-color: var(--color-primary)"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary mt-2">
                        Masuk Sekarang
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center space-y-4">
                <p class="text-base text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold hover:underline" style="color: var(--color-primary)">Daftar Gratis</a>
                </p>
                
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors px-4 py-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</div>
</body>
</html>
