@extends('layouts.app')

@section('content')
<div class="container-custom py-16 max-w-4xl">
    <div class="flex flex-col items-center mb-12">
        <div class="flex items-center gap-4 mb-8 w-full">
            <div class="flex-1 flex flex-col gap-[2px]">
                <div class="h-[3px] bg-red-600 w-full"></div>
                <div class="h-[3px] bg-red-600 w-full"></div>
            </div>
            <h1 class="text-3xl font-black text-red-700 tracking-tighter px-6 whitespace-nowrap uppercase">
                Tentang Kami
            </h1>
            <div class="flex-1 flex flex-col gap-[2px]">
                <div class="h-[3px] bg-red-600 w-full"></div>
                <div class="h-[3px] bg-red-600 w-full"></div>
            </div>
        </div>

        <div class="w-full aspect-video bg-gray-100 rounded-lg overflow-hidden mb-12 shadow-xl border border-gray-100">
            <img loading="lazy" src="https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&q=80&w=1200" alt="Info Lantas Mojokerto Office" class="w-full h-full object-cover" loading="lazy">
        </div>
    </div>

    <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed space-y-8">
        <section>
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-4 border-l-4 border-red-600 pl-4">
                Visi & Misi
            </h2>
            <p>
                <strong>Info Lantas Mojokerto</strong> hadir sebagai portal berita terpercaya yang berfokus pada dinamika wilayah Mojokerto Raya. Kami berkomitmen untuk menyajikan informasi yang akurat, cepat, dan berimbang bagi masyarakat Mojokerto dan sekitarnya.
            </p>
            <p>
                Visi kami adalah menjadi rujukan utama informasi lalu lintas, pelayanan publik, dan dinamika sosial di Mojokerto, serta berperan aktif dalam mencerdaskan kehidupan bangsa melalui jurnalisme yang berkualitas dan beretika.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-4 border-l-4 border-red-600 pl-4">
                Nilai Utama
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="p-6 bg-red-50 rounded-xl border border-red-100">
                    <h3 class="font-bold text-red-700 mb-2">Akurasi</h3>
                    <p class="text-sm">Setiap berita melalui proses verifikasi ketat sebelum sampai ke tangan pembaca.</p>
                </div>
                <div class="p-6 bg-red-50 rounded-xl border border-red-100">
                    <h3 class="font-bold text-red-700 mb-2">Interaktivitas</h3>
                    <p class="text-sm">Kami membangun jembatan komunikasi dua arah antara penyedia layanan dan masyarakat.</p>
                </div>
                <div class="p-6 bg-red-50 rounded-xl border border-red-100">
                    <h3 class="font-bold text-red-700 mb-2">Integritas</h3>
                    <p class="text-sm">Menjaga independensi jurnalisme demi kepentingan publik di atas segalanya.</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-16 border-l-4 border-red-600 pl-4">
                Struktur Organisasi
            </h2>

            <div class="relative flex flex-col items-center pb-20">
                {{-- Vertical trunk --}}
                <div class="absolute top-0 bottom-32 left-1/2 w-0.5 bg-gradient-to-b from-red-600 via-red-500 to-gray-200 -translate-x-1/2 -z-10 hidden md:block"></div>

                {{-- Level 1: Pemimpin Umum --}}
                <div class="relative mb-20 z-10">
                    <div class="bg-red-800 text-white p-5 rounded-xl shadow-[0_10px_25px_-5px_rgba(185,28,28,0.4)] w-80 text-center border-b-4 border-red-950 transition-all hover:-translate-y-1">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] mb-1 opacity-70">Pemimpin Umum</h3>
                        <p class="text-2xl font-black">Bambang Wijaya</p>
                    </div>
                </div>

                {{-- Level 2: Pemimpin Redaksi --}}
                <div class="relative mb-24 z-10">
                    <div class="bg-red-600 text-white p-5 rounded-xl shadow-[0_10px_20px_-5px_rgba(220,38,38,0.3)] w-80 text-center border-b-4 border-red-800 transition-all hover:-translate-y-1">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] mb-1 opacity-70">Pemimpin Redaksi</h3>
                        <p class="text-2xl font-black">Siti Aminah, M.I.Kom</p>
                    </div>
                    {{-- Horizontal T-Bar --}}
                    <div class="absolute left-1/2 -bottom-12 w-[85vw] md:w-[750px] h-0.5 bg-red-400 -translate-x-1/2 hidden md:block"></div>
                </div>

                {{-- Level 3: Departments --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8 w-full relative z-10">
                    {{-- Redaksi --}}
                    <div class="relative">
                        <div class="absolute top-0 left-1/2 w-0.5 h-12 bg-red-400 -translate-x-1/2 -mt-12 hidden md:block"></div>
                        <div class="bg-white border-b-4 border-red-600 p-6 rounded-xl shadow-lg text-center border border-gray-100 transition-all hover:shadow-xl hover:-translate-y-1">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Redaksi</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">HK & POL</p>
                                    <p class="font-black text-gray-800">Andi Saputra</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">EKONOMI</p>
                                    <p class="font-black text-gray-800">Rina Kartika</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Infrastruktur --}}
                    <div class="relative">
                        <div class="absolute top-0 left-1/2 w-0.5 h-12 bg-red-400 -translate-x-1/2 -mt-12 hidden md:block"></div>
                        <div class="bg-white border-b-4 border-red-600 p-6 rounded-xl shadow-lg text-center border border-gray-100 transition-all hover:shadow-xl hover:-translate-y-1">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Infrastruktur</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">WEB DEV</p>
                                    <p class="font-black text-gray-800">Agus Setiawan</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">DATABASE</p>
                                    <p class="font-black text-gray-800">Indra Kesuma</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Media Kreatif --}}
                    <div class="relative">
                        <div class="absolute top-0 left-1/2 w-0.5 h-12 bg-red-400 -translate-x-1/2 -mt-12 hidden md:block"></div>
                        <div class="bg-white border-b-4 border-red-600 p-6 rounded-xl shadow-lg text-center border border-gray-100 transition-all hover:shadow-xl hover:-translate-y-1">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Media Kreatif</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">DESIGN</p>
                                    <p class="font-black text-gray-800">Eko Prasetyo</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">SOSMED</p>
                                    <p class="font-black text-gray-800">Diana Putri</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reporter Lapangan --}}
                <div class="mt-20 w-full relative z-10">
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-0.5 h-12 bg-gray-200 hidden md:block"></div>
                        <div class="bg-gray-900 text-white text-[10px] font-black px-8 py-2 rounded-full uppercase tracking-[0.4em] shadow-xl">
                            Reporter Lapangan
                        </div>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach(['Fajar Ramadhan', 'Gita Permata', 'Hadi Wijaya', 'Salsabila Putri'] as $name)
                        <div class="bg-white p-4 rounded-xl border border-gray-100 text-center shadow-sm transition-all hover:shadow-md hover:border-red-200 group">
                            <p class="text-xs font-black text-gray-600 group-hover:text-red-700 uppercase tracking-tighter">{{ $name }}</p>
                            <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest mt-1">Koresponden</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-4 border-l-4 border-red-600 pl-4">
                Fokus Kami
            </h2>
            <p>
                Selain berita lalu lintas, kami secara mendalam meliput sektor pendidikan (Dikbud), pariwisata, hukum & politik, serta literasi digital. Kami percaya bahwa informasi yang komprehensif adalah kunci kemajuan suatu daerah.
            </p>
        </section>

        <div class="mt-16 p-8 bg-gray-900 text-white rounded-2xl flex flex-col md:flex-row items-center gap-8 shadow-2xl">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-black mb-2 uppercase tracking-tight">Hubungi Redaksi</h2>
                <p class="text-gray-400 text-sm">Punya informasi penting atau ingin berkolaborasi?</p>
            </div>
            <div class="flex flex-col gap-2">
                <a href="mailto:{{ config('news_portal.site.contact_email', 'redaksi@infolantasmojokerto.com') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-all text-center">
                    Email Redaksi
                </a>
                <span class="text-xs text-gray-500 text-center">Jl. Raya Mojokerto No. 123, Jawa Timur</span>
            </div>
        </div>
    </div>
</div>
@endsection
