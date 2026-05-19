<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaticPageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('static_pages')->insert([
            [
                'slug' => 'tentang-kami',
                'title' => 'Tentang Kami',
                'content' => '<h2>Tentang Info Lantas Mojokerto</h2>
<p>Info Lantas Mojokerto (ILM) adalah portal berita online yang menyajikan informasi terkini seputar wilayah Mojokerto, baik Kabupaten maupun Kota Mojokerto.</p>
<p>Didirikan dengan semangat memberikan informasi yang akurat, cepat, dan terpercaya kepada masyarakat Mojokerto dan sekitarnya. Kami berkomitmen untuk menjadi sumber berita utama yang menjunjung tinggi etika jurnalistik.</p>
<h3>Visi</h3>
<p>Menjadi portal berita terdepan dan terpercaya di wilayah Mojokerto yang memberikan informasi berkualitas kepada masyarakat.</p>
<h3>Misi</h3>
<ul>
<li>Menyajikan berita yang akurat, berimbang, dan terpercaya</li>
<li>Memberikan informasi yang bermanfaat bagi masyarakat Mojokerto</li>
<li>Mendukung transparansi informasi publik</li>
<li>Menjadi wadah aspirasi masyarakat</li>
</ul>',
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'redaksi',
                'title' => 'Susunan Redaksi',
                'content' => '<h2>Susunan Redaksi Info Lantas Mojokerto</h2>
<h3>Penanggung Jawab</h3>
<p>-</p>
<h3>Pemimpin Redaksi</h3>
<p>-</p>
<h3>Redaktur</h3>
<p>-</p>
<h3>Reporter</h3>
<p>-</p>
<h3>Kontributor</h3>
<p>-</p>
<h3>Alamat Redaksi</h3>
<p>Mojokerto, Jawa Timur, Indonesia</p>
<h3>Kontak</h3>
<p>Email: redaksi@infolantasmojokerto.com</p>
<p>Telepon: +62-321-123456</p>',
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
