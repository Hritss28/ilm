<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\News;
use App\Models\User;
use App\Models\Video;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional users
        $redaktur = User::firstOrCreate(
            ['email' => 'redaktur@ilm.co.id'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'redaktur',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $author1 = User::firstOrCreate(
            ['email' => 'andi@ilm.co.id'],
            [
                'name' => 'Andi Saputra',
                'password' => Hash::make('password'),
                'role' => 'author',
                'email_verified_at' => now(),
                'is_active' => true,
                'kecamatan' => 'Puri',
            ]
        );

        $author2 = User::firstOrCreate(
            ['email' => 'rina@ilm.co.id'],
            [
                'name' => 'Rina Kartika',
                'password' => Hash::make('password'),
                'role' => 'author',
                'email_verified_at' => now(),
                'is_active' => true,
                'kecamatan' => 'Mojosari',
            ]
        );

        $author3 = User::firstOrCreate(
            ['email' => 'fajar@ilm.co.id'],
            [
                'name' => 'Fajar Ramadhan',
                'password' => Hash::make('password'),
                'role' => 'author',
                'email_verified_at' => now(),
                'is_active' => true,
                'kecamatan' => 'Trawas',
            ]
        );

        $authors = [$redaktur, $author1, $author2, $author3];

        // Get categories
        $categories = Category::all()->keyBy('slug');

        // News data per category
        $newsData = $this->getNewsData();

        foreach ($newsData as $catSlug => $articles) {
            $category = $categories->get($catSlug);
            if (!$category) continue;

            foreach ($articles as $index => $article) {
                News::firstOrCreate(
                    ['title' => $article['title']],
                    [
                        'slug' => Str::slug($article['title']),
                        'content' => $article['content'],
                        'excerpt' => $article['excerpt'],
                        'thumbnail' => null,
                        'category_id' => $category->id,
                        'author_id' => $authors[array_rand($authors)]->id,
                        'status' => 'published',
                        'is_featured' => $article['featured'] ?? false,
                        'is_breaking_news' => $article['breaking'] ?? false,
                        'breaking_news_until' => ($article['breaking'] ?? false) ? now()->addHours(24) : null,
                        'views' => rand(50, 5000),
                        'published_at' => now()->subHours(rand(1, 168)),
                    ]
                );
            }
        }

        // Advertisements
        $this->seedAdvertisements();

        // Videos
        $this->seedVideos($authors);

        // Galleries
        $this->seedGalleries($authors);

        $this->command->info('Demo data seeded successfully!');
    }

    private function getNewsData(): array
    {
        return [
            'headline' => [
                [
                    'title' => 'Guru Non-ASN Dijamin Tetap Mengajar, Pemerintah Siapkan Skema Jadi ASN di Mojokerto',
                    'excerpt' => 'Guru non-ASN memiliki kesempatan mengikuti seleksi sesuai ketentuan. Bagi yang lolos, statusnya akan bertransformasi menjadi ASN baru.',
                    'content' => '<p>Pemerintah Kabupaten Mojokerto memastikan bahwa guru non-ASN yang selama ini mengabdi di sekolah-sekolah negeri akan tetap mendapatkan kesempatan mengajar. Hal ini disampaikan langsung oleh Bupati Mojokerto dalam rapat koordinasi pendidikan.</p><p>"Kami berkomitmen untuk memberikan jaminan kepada guru non-ASN. Mereka adalah tulang punggung pendidikan di daerah kita," ujar Bupati.</p><p>Skema yang disiapkan meliputi jalur seleksi PPPK yang akan dibuka secara berkala, serta program peningkatan kompetensi agar para guru siap menghadapi seleksi tersebut.</p>',
                    'featured' => true,
                ],
                [
                    'title' => 'Mulai 9 Mei KA Argo Bromo Anggrek Resmi Berubah Nama Jadi KA Anggrek',
                    'excerpt' => 'PT KAI resmi mengubah nama kereta api Argo Bromo Anggrek menjadi KA Anggrek mulai 9 Mei 2026.',
                    'content' => '<p>PT Kereta Api Indonesia (Persero) resmi mengumumkan perubahan nama kereta api Argo Bromo Anggrek menjadi KA Anggrek yang berlaku efektif mulai 9 Mei 2026.</p><p>Perubahan ini merupakan bagian dari rebranding layanan kereta api jarak jauh yang dilakukan PT KAI untuk menyederhanakan penamaan dan meningkatkan brand awareness.</p><p>Meski berganti nama, layanan dan fasilitas yang diberikan tetap sama bahkan akan ditingkatkan dengan penambahan wifi gratis di seluruh gerbong.</p>',
                    'featured' => true,
                ],
                [
                    'title' => 'BPS Mencatat Pertumbuhan Ekonomi Mojokerto Triwulan 1 2026 Mencapai 5.41 Persen',
                    'excerpt' => 'Badan Pusat Statistik mencatat pertumbuhan ekonomi Mojokerto pada triwulan pertama 2026 mencapai 5,41 persen.',
                    'content' => '<p>Badan Pusat Statistik (BPS) Kabupaten Mojokerto mencatat pertumbuhan ekonomi wilayah ini pada triwulan pertama tahun 2026 mencapai 5,41 persen year-on-year.</p><p>Angka ini lebih tinggi dibandingkan periode yang sama tahun lalu yang hanya mencapai 4,89 persen. Sektor yang paling berkontribusi adalah industri pengolahan, perdagangan, dan pertanian.</p><p>Kepala BPS Mojokerto menyatakan optimisme bahwa pertumbuhan ini akan terus berlanjut seiring dengan berbagai program pembangunan infrastruktur yang sedang berjalan.</p>',
                    'featured' => true,
                ],
            ],
            'regional' => [
                [
                    'title' => 'Pemkab Mojokerto Gelar Musyawarah Perencanaan Pembangunan Kecamatan',
                    'excerpt' => 'Serangkaian program prioritas tahun 2027 mulai dibahas di tingkat kecamatan untuk menyerap aspirasi langsung dari warga.',
                    'content' => '<p>Pemerintah Kabupaten Mojokerto menggelar Musyawarah Perencanaan Pembangunan (Musrenbang) tingkat kecamatan yang berlangsung serentak di 18 kecamatan.</p><p>Kegiatan ini bertujuan untuk menyerap aspirasi masyarakat secara langsung terkait program-program prioritas yang akan dilaksanakan pada tahun anggaran 2027.</p><p>Bupati Mojokerto menekankan pentingnya partisipasi aktif masyarakat dalam proses perencanaan pembangunan agar program yang dihasilkan benar-benar sesuai dengan kebutuhan warga.</p><p>Beberapa isu yang banyak diangkat antara lain perbaikan jalan desa, pembangunan saluran irigasi, dan peningkatan fasilitas kesehatan di puskesmas.</p>',
                    'breaking' => true,
                ],
                [
                    'title' => 'Warga Desa Wisata Bejijong Terima Bantuan Penguatan Literasi Budaya',
                    'excerpt' => 'Upaya pelestarian warisan Majapahit mendapatkan dukungan dari pemerintah provinsi melalui program hibah peralatan digital.',
                    'content' => '<p>Warga Desa Wisata Bejijong, Kecamatan Trowulan, menerima bantuan penguatan literasi budaya dari Pemerintah Provinsi Jawa Timur.</p><p>Bantuan berupa peralatan digital untuk dokumentasi dan promosi warisan budaya Majapahit yang ada di wilayah tersebut.</p><p>Program ini diharapkan dapat meningkatkan kunjungan wisatawan dan kesadaran masyarakat akan pentingnya pelestarian situs sejarah.</p>',
                ],
                [
                    'title' => 'Jalan Provinsi di Kecamatan Pacet Diperbaiki Senilai Rp 12 Miliar',
                    'excerpt' => 'Perbaikan jalan provinsi sepanjang 4,5 km di Kecamatan Pacet ditargetkan selesai sebelum musim hujan.',
                    'content' => '<p>Dinas Pekerjaan Umum Bina Marga Provinsi Jawa Timur memulai proyek perbaikan jalan provinsi di Kecamatan Pacet, Kabupaten Mojokerto.</p><p>Proyek senilai Rp 12 miliar ini mencakup perbaikan sepanjang 4,5 kilometer yang selama ini menjadi keluhan warga karena kondisinya yang rusak parah.</p><p>Kepala Dinas PUBM Jatim menyatakan bahwa proyek ini ditargetkan selesai sebelum musim hujan tiba agar tidak mengganggu aktivitas warga dan wisatawan yang menuju kawasan Trawas.</p>',
                ],
                [
                    'title' => 'Festival Kuliner Mojokerto 2026 Hadirkan 200 UMKM Lokal',
                    'excerpt' => 'Festival kuliner tahunan Mojokerto kembali digelar dengan menghadirkan 200 pelaku UMKM dari seluruh kecamatan.',
                    'content' => '<p>Festival Kuliner Mojokerto 2026 resmi dibuka di Alun-alun Kota Mojokerto dengan menghadirkan 200 pelaku UMKM kuliner dari seluruh kecamatan di Kabupaten dan Kota Mojokerto.</p><p>Acara yang berlangsung selama tiga hari ini menampilkan berbagai makanan khas Mojokerto mulai dari onde-onde, rujak cingur, hingga inovasi kuliner modern.</p><p>Walikota Mojokerto berharap festival ini dapat menjadi ajang promosi produk lokal sekaligus meningkatkan perekonomian pelaku UMKM.</p>',
                ],
            ],
            'nasional' => [
                [
                    'title' => 'Presiden RI Tinjau Kesiapan Infrastruktur Ketenagalistrikan Nasional',
                    'excerpt' => 'Presiden didampingi menteri terkait melakukan kunjungan kerja untuk memastikan stabilitas pasokan listrik nasional.',
                    'content' => '<p>Presiden Republik Indonesia melakukan kunjungan kerja ke Pembangkit Listrik Tenaga Gas dan Uap (PLTGU) untuk meninjau kesiapan infrastruktur ketenagalistrikan nasional.</p><p>Dalam kunjungan tersebut, Presiden menekankan pentingnya diversifikasi sumber energi dan percepatan transisi menuju energi bersih.</p><p>"Kita harus memastikan pasokan listrik tetap stabil sambil terus bergerak menuju energi terbarukan," ujar Presiden.</p>',
                ],
                [
                    'title' => 'Indeks Kepercayaan Publik Terhadap Lembaga Penegak Hukum Meningkat',
                    'excerpt' => 'Survei terbaru menunjukkan tren positif persepsi masyarakat terhadap transparansi penegakan hukum di tanah air.',
                    'content' => '<p>Lembaga survei independen merilis hasil penelitian terbaru yang menunjukkan peningkatan indeks kepercayaan publik terhadap lembaga penegak hukum di Indonesia.</p><p>Indeks naik dari 62,3 persen pada tahun lalu menjadi 67,8 persen pada survei terbaru. Peningkatan ini didorong oleh reformasi birokrasi dan transparansi penanganan kasus.</p><p>Meski demikian, responden masih mengharapkan peningkatan dalam hal kecepatan penanganan kasus dan pemberantasan korupsi di internal lembaga.</p>',
                ],
                [
                    'title' => 'Mensos Pastikan Pengadaan Sepatu Sekolah Rakyat Sesuai Prosedur',
                    'excerpt' => 'Kementerian Sosial memastikan program pengadaan sepatu sekolah untuk siswa kurang mampu berjalan sesuai prosedur.',
                    'content' => '<p>Menteri Sosial memastikan bahwa program pengadaan sepatu sekolah untuk siswa dari keluarga kurang mampu telah berjalan sesuai prosedur yang ditetapkan.</p><p>Program ini menargetkan distribusi 2 juta pasang sepatu sekolah ke seluruh provinsi di Indonesia pada tahun ajaran baru 2026/2027.</p><p>"Kami memastikan kualitas sepatu yang didistribusikan memenuhi standar dan proses pengadaannya transparan," tegas Mensos.</p>',
                    'breaking' => true,
                ],
                [
                    'title' => 'Pemerintah Luncurkan Program Beasiswa S2 untuk 10.000 Guru Daerah',
                    'excerpt' => 'Program beasiswa magister untuk guru di daerah 3T resmi diluncurkan dengan kuota 10.000 penerima.',
                    'content' => '<p>Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi resmi meluncurkan program beasiswa S2 untuk 10.000 guru yang bertugas di daerah tertinggal, terdepan, dan terluar (3T).</p><p>Program ini bertujuan meningkatkan kualitas pendidikan di daerah-daerah yang selama ini kekurangan tenaga pendidik berkualitas.</p><p>Beasiswa mencakup biaya kuliah penuh, biaya hidup, dan tunjangan penelitian selama masa studi dua tahun.</p>',
                ],
            ],
            'politik' => [
                [
                    'title' => 'Konsolidasi Parpol di Jawa Timur Mulai Menghangat Jelang Pilkada Serentak',
                    'excerpt' => 'Beberapa koalisi besar mulai menunjukkan strategi komunikasi politik melalui agenda kunjungan ke tokoh lokal.',
                    'content' => '<p>Dinamika politik di Jawa Timur mulai memanas menjelang Pilkada Serentak yang dijadwalkan berlangsung tahun depan.</p><p>Beberapa partai politik besar telah memulai konsolidasi internal dan menjajaki kemungkinan koalisi untuk mengusung calon kepala daerah.</p><p>Di Mojokerto sendiri, setidaknya tiga nama sudah mulai disebut-sebut sebagai calon kuat yang akan bertarung dalam kontestasi politik lokal.</p>',
                ],
                [
                    'title' => 'DPRD Mojokerto Sahkan Perda Penataan PKL di Pusat Kota',
                    'excerpt' => 'DPRD Kota Mojokerto resmi mengesahkan Peraturan Daerah tentang penataan pedagang kaki lima di kawasan pusat kota.',
                    'content' => '<p>Dewan Perwakilan Rakyat Daerah (DPRD) Kota Mojokerto resmi mengesahkan Peraturan Daerah (Perda) tentang Penataan Pedagang Kaki Lima (PKL) di kawasan pusat kota.</p><p>Perda ini mengatur zonasi, jam operasional, dan standar kebersihan yang harus dipenuhi oleh para PKL.</p><p>Ketua DPRD menyatakan bahwa Perda ini bertujuan untuk menciptakan ketertiban tanpa mematikan mata pencaharian warga.</p>',
                ],
                [
                    'title' => 'Partai Golkar Jatim Gelar Rapimda Bahas Strategi Pilkada 2027',
                    'excerpt' => 'Partai Golkar Jawa Timur menggelar Rapat Pimpinan Daerah untuk membahas strategi pemenangan Pilkada 2027.',
                    'content' => '<p>DPD Partai Golkar Jawa Timur menggelar Rapat Pimpinan Daerah (Rapimda) yang dihadiri seluruh pengurus tingkat kabupaten/kota se-Jawa Timur.</p><p>Agenda utama Rapimda adalah membahas strategi pemenangan Pilkada Serentak 2027 dan penjaringan bakal calon kepala daerah.</p><p>Ketua DPD Golkar Jatim menegaskan bahwa partainya akan mengusung kader terbaik yang memiliki rekam jejak dan elektabilitas tinggi.</p>',
                ],
            ],
            'ekonomi' => [
                [
                    'title' => 'UMKM Mojokerto Raya Tembus Pasar Ekspor Kerajinan Alas Kaki',
                    'excerpt' => 'Sektor industri kecil kembali menggeliat dengan ditandatanganinya kerjasama dagang bersama distributor dari Australia.',
                    'content' => '<p>Pelaku UMKM kerajinan alas kaki di Mojokerto berhasil menembus pasar ekspor setelah menandatangani kontrak kerjasama dengan distributor asal Australia.</p><p>Kontrak ini mencakup pengiriman 5.000 pasang sepatu kulit handmade per bulan selama satu tahun ke depan.</p><p>Keberhasilan ini tidak lepas dari program pendampingan yang dilakukan Dinas Koperasi dan UMKM Kabupaten Mojokerto dalam meningkatkan kualitas produk dan standar ekspor.</p>',
                ],
                [
                    'title' => 'Kenaikan Harga Beras Mulai Meresahkan Warga Pasar Tanjung',
                    'excerpt' => 'Harga beras di Pasar Tanjung Mojokerto mengalami kenaikan signifikan dalam dua pekan terakhir.',
                    'content' => '<p>Harga beras di Pasar Tanjung, Kota Mojokerto, mengalami kenaikan signifikan dalam dua pekan terakhir. Beras medium yang biasanya dijual Rp 12.000 per kg kini mencapai Rp 14.500 per kg.</p><p>Para pedagang mengaku kenaikan ini disebabkan oleh berkurangnya pasokan dari petani lokal akibat musim kemarau yang berkepanjangan.</p><p>Dinas Perdagangan Kota Mojokerto menyatakan akan melakukan operasi pasar jika kenaikan harga terus berlanjut.</p>',
                ],
                [
                    'title' => 'Bank Jatim Salurkan KUR Rp 50 Miliar untuk Petani Mojokerto',
                    'excerpt' => 'Bank Jatim menyalurkan Kredit Usaha Rakyat senilai Rp 50 miliar untuk petani di Kabupaten Mojokerto.',
                    'content' => '<p>Bank Jatim Cabang Mojokerto menyalurkan Kredit Usaha Rakyat (KUR) senilai Rp 50 miliar untuk para petani di Kabupaten Mojokerto.</p><p>Dana ini ditujukan untuk modal kerja pertanian, pembelian alat mesin pertanian, dan pengembangan usaha agribisnis.</p><p>Kepala Cabang Bank Jatim Mojokerto menyatakan bahwa penyaluran KUR ini merupakan komitmen bank dalam mendukung sektor pertanian lokal.</p>',
                ],
            ],
            'olahraga' => [
                [
                    'title' => 'PSMP Mojokerto Siapkan Skuat Muda untuk Liga 3 Musim Depan',
                    'excerpt' => 'Manajemen PSMP Mojokerto tengah berburu pemain muda berkualitas untuk memperkuat tim menjelang Liga 3.',
                    'content' => '<p>Persatuan Sepakbola Mojokerto Putra (PSMP) tengah mempersiapkan skuat muda untuk menghadapi kompetisi Liga 3 musim depan.</p><p>Manajemen klub telah melakukan seleksi terbuka yang diikuti ratusan peserta dari berbagai daerah di Jawa Timur.</p><p>"Kami ingin membangun tim yang solid dengan pemain-pemain muda berbakat. Target kami adalah promosi ke Liga 2," ujar Manajer PSMP.</p>',
                ],
                [
                    'title' => 'Atlet Pencak Silat Mojokerto Raih Emas di Kejurnas',
                    'excerpt' => 'Atlet pencak silat asal Mojokerto berhasil meraih medali emas di Kejuaraan Nasional Pencak Silat 2026.',
                    'content' => '<p>Atlet pencak silat asal Kabupaten Mojokerto, Muhammad Rizki, berhasil meraih medali emas di Kejuaraan Nasional Pencak Silat 2026 yang digelar di Jakarta.</p><p>Rizki tampil dominan di kelas C putra dan mengalahkan lawannya dari DKI Jakarta di babak final dengan skor telak.</p><p>Bupati Mojokerto memberikan apresiasi dan menjanjikan bonus serta fasilitas latihan yang lebih baik bagi atlet berprestasi.</p>',
                ],
                [
                    'title' => 'Turnamen Bulu Tangkis Antar Kecamatan Digelar di GOR Mojokerto',
                    'excerpt' => 'GOR Kota Mojokerto menjadi tuan rumah turnamen bulu tangkis antar kecamatan yang diikuti 200 atlet.',
                    'content' => '<p>Gelanggang Olahraga (GOR) Kota Mojokerto menjadi tuan rumah Turnamen Bulu Tangkis Antar Kecamatan yang diikuti oleh 200 atlet dari 21 kecamatan.</p><p>Turnamen ini digelar dalam rangka menjaring bibit-bibit atlet muda yang berpotensi untuk dibina menuju kompetisi tingkat provinsi dan nasional.</p><p>Pertandingan berlangsung selama empat hari dengan kategori tunggal putra, tunggal putri, ganda putra, ganda putri, dan ganda campuran.</p>',
                ],
            ],
            'lalu-lintas' => [
                [
                    'title' => 'Pantauan CCTV: Arus Lalu Lintas Jalur By Pass Mojokerto Terpantau Padat Merayap',
                    'excerpt' => 'Peningkatan volume kendaraan terpantau di perempatan terminal Mojokerto. Petugas melakukan rekayasa lalu lintas.',
                    'content' => '<p>Berdasarkan pantauan CCTV milik Dinas Perhubungan Kota Mojokerto, arus lalu lintas di Jalur By Pass Mojokerto terpantau padat merayap pada Jumat sore.</p><p>Kemacetan terjadi terutama di sekitar perempatan Terminal Kertajaya akibat volume kendaraan yang meningkat menjelang akhir pekan.</p><p>Petugas Satlantas Polres Mojokerto Kota telah melakukan rekayasa lalu lintas dengan sistem buka-tutup jalur untuk mengurai kemacetan.</p>',
                    'breaking' => true,
                ],
                [
                    'title' => 'Jalan Nasional Mojokerto-Jombang Ditutup Sementara untuk Perbaikan',
                    'excerpt' => 'Ruas jalan nasional Mojokerto-Jombang ditutup sementara selama 3 hari untuk perbaikan overlay aspal.',
                    'content' => '<p>Ruas jalan nasional yang menghubungkan Mojokerto dan Jombang akan ditutup sementara selama tiga hari untuk perbaikan overlay aspal.</p><p>Penutupan berlaku mulai pukul 22.00 hingga 05.00 WIB. Kendaraan dialihkan melalui jalur alternatif via Kecamatan Mojoanyar.</p><p>Dinas Perhubungan telah memasang rambu-rambu pengalihan dan menerjunkan petugas di titik-titik rawan kemacetan.</p>',
                ],
                [
                    'title' => 'Kecelakaan Beruntun di Tol Mojokerto-Surabaya, 3 Kendaraan Terlibat',
                    'excerpt' => 'Kecelakaan beruntun melibatkan 3 kendaraan terjadi di KM 42 Tol Mojokerto-Surabaya arah Surabaya.',
                    'content' => '<p>Kecelakaan beruntun melibatkan tiga kendaraan terjadi di KM 42 Tol Mojokerto-Surabaya arah Surabaya pada Kamis pagi.</p><p>Kecelakaan bermula dari sebuah truk yang mengerem mendadak sehingga ditabrak oleh mobil di belakangnya, yang kemudian juga ditabrak oleh kendaraan ketiga.</p><p>Beruntung tidak ada korban jiwa dalam kejadian ini. Dua pengemudi mengalami luka ringan dan langsung ditangani tim medis.</p>',
                ],
            ],
            'dikbud' => [
                [
                    'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2026 Mulai Disosialisasikan di Mojokerto',
                    'excerpt' => 'Dinas Pendidikan memastikan sistem zonasi tahun ini akan lebih fleksibel untuk membantu siswa di daerah perbatasan.',
                    'content' => '<p>Dinas Pendidikan Kabupaten Mojokerto mulai mensosialisasikan mekanisme Penerimaan Peserta Didik Baru (PPDB) tahun ajaran 2026/2027.</p><p>Tahun ini, sistem zonasi akan lebih fleksibel dengan mempertimbangkan kondisi geografis dan aksesibilitas transportasi bagi siswa di daerah perbatasan administratif.</p><p>Kepala Dinas Pendidikan menyatakan bahwa kuota jalur zonasi tetap menjadi yang terbesar yaitu 50 persen, diikuti jalur afirmasi 15 persen, perpindahan orang tua 5 persen, dan prestasi 30 persen.</p>',
                ],
                [
                    'title' => 'SMK Negeri 1 Mojokerto Raih Juara Umum LKS Tingkat Provinsi',
                    'excerpt' => 'SMK Negeri 1 Mojokerto berhasil meraih juara umum Lomba Kompetensi Siswa tingkat Provinsi Jawa Timur.',
                    'content' => '<p>SMK Negeri 1 Mojokerto berhasil meraih predikat juara umum dalam Lomba Kompetensi Siswa (LKS) tingkat Provinsi Jawa Timur 2026.</p><p>Sekolah ini mengirimkan 12 siswa yang bertanding di berbagai bidang lomba dan berhasil meraih 4 emas, 5 perak, dan 3 perunggu.</p><p>Kepala Sekolah menyatakan keberhasilan ini merupakan buah dari program pembinaan intensif yang dilakukan sejak awal tahun ajaran.</p>',
                ],
            ],
            'pariwisata' => [
                [
                    'title' => 'Pesona Air Terjun Dlundung Tarik Minat Wisatawan Mancanegara',
                    'excerpt' => 'Keindahan alam Trawas kembali menjadi primadona dengan peningkatan kunjungan turis dari kawasan Asia Tenggara.',
                    'content' => '<p>Air Terjun Dlundung yang terletak di Kecamatan Trawas, Kabupaten Mojokerto, kembali menjadi primadona wisata alam di Jawa Timur.</p><p>Pengelola mencatat adanya peningkatan kunjungan wisatawan mancanegara, terutama dari Malaysia, Singapura, dan Thailand dalam beberapa bulan terakhir.</p><p>Peningkatan ini tidak lepas dari promosi digital yang gencar dilakukan melalui media sosial dan platform travel internasional.</p>',
                ],
                [
                    'title' => 'Desa Wisata Trowulan Kembangkan Paket Wisata Edukasi Majapahit',
                    'excerpt' => 'Desa wisata di kawasan Trowulan mengembangkan paket wisata edukasi bertema Kerajaan Majapahit.',
                    'content' => '<p>Kelompok Sadar Wisata (Pokdarwis) di kawasan Trowulan mengembangkan paket wisata edukasi bertema Kerajaan Majapahit yang menyasar segmen pelajar dan keluarga.</p><p>Paket wisata ini mencakup kunjungan ke Museum Trowulan, Candi Bajang Ratu, Kolam Segaran, dan workshop pembuatan replika artefak Majapahit.</p><p>Program ini mendapat dukungan penuh dari Dinas Pariwisata Kabupaten Mojokerto sebagai upaya meningkatkan lama tinggal wisatawan di Mojokerto.</p>',
                ],
            ],
            'tekno' => [
                [
                    'title' => 'Startup Lokal Mojokerto Ciptakan Aplikasi Prediksi Harga Pangan Berbasis AI',
                    'excerpt' => 'Inovasi teknologi ini diharapkan mampu membantu petani lokal menentukan waktu tanam yang tepat.',
                    'content' => '<p>Sebuah startup teknologi asal Mojokerto berhasil mengembangkan aplikasi prediksi harga pangan berbasis kecerdasan buatan (AI).</p><p>Aplikasi bernama "TaniCerdas" ini menggunakan data historis harga, cuaca, dan pola tanam untuk memprediksi harga komoditas pangan hingga 3 bulan ke depan.</p><p>Pendiri startup menyatakan bahwa aplikasi ini sudah diuji coba oleh 500 petani di Mojokerto dan menunjukkan tingkat akurasi prediksi hingga 85 persen.</p>',
                ],
                [
                    'title' => 'Pemkab Mojokerto Luncurkan Aplikasi Layanan Publik Terintegrasi',
                    'excerpt' => 'Aplikasi "Mojokerto Smart City" resmi diluncurkan untuk memudahkan akses layanan publik bagi warga.',
                    'content' => '<p>Pemerintah Kabupaten Mojokerto resmi meluncurkan aplikasi "Mojokerto Smart City" yang mengintegrasikan berbagai layanan publik dalam satu platform.</p><p>Melalui aplikasi ini, warga dapat mengakses layanan administrasi kependudukan, pengaduan, informasi pembangunan, dan layanan kesehatan secara online.</p><p>Aplikasi tersedia di Google Play Store dan Apple App Store dan dapat diunduh secara gratis oleh seluruh warga Mojokerto.</p>',
                ],
            ],
            'kuliner' => [
                [
                    'title' => 'Eksplorasi Rasa: Onde-onde Mojokerto Kini Hadir dengan Varian Rasa Kekinian',
                    'excerpt' => 'Inovasi kuliner khas Mojokerto mulai merambah rasa matcha dan salted egg, menarik minat generasi muda.',
                    'content' => '<p>Onde-onde, jajanan tradisional khas Mojokerto, kini hadir dengan sentuhan modern melalui varian rasa kekinian seperti matcha, salted egg, dan red velvet.</p><p>Inovasi ini dilakukan oleh pelaku UMKM muda yang ingin memperkenalkan kembali jajanan tradisional kepada generasi milenial dan Gen Z.</p><p>Produk ini sudah dipasarkan melalui platform online dan mendapat respons positif dari konsumen di berbagai kota.</p>',
                ],
            ],
            'hukum' => [
                [
                    'title' => 'Kejaksaan Negeri Mojokerto Tuntaskan Kasus Sengketa Lahan Milik Warga',
                    'excerpt' => 'Setelah melalui proses mediasi panjang, Kejari berhasil mengembalikan hak atas tanah kepada kelompok tani.',
                    'content' => '<p>Kejaksaan Negeri (Kejari) Mojokerto berhasil menuntaskan kasus sengketa lahan yang telah berlangsung selama lima tahun.</p><p>Melalui proses mediasi yang difasilitasi oleh Kejari, hak atas tanah seluas 2 hektar berhasil dikembalikan kepada kelompok tani yang merupakan pemilik sah.</p><p>Kajari Mojokerto menyatakan bahwa penyelesaian melalui mediasi ini merupakan langkah terbaik untuk menghindari proses litigasi yang panjang dan mahal.</p>',
                ],
                [
                    'title' => 'Polres Mojokerto Ungkap Jaringan Penipuan Online Bermodus Investasi Bodong',
                    'excerpt' => 'Polres Mojokerto berhasil mengungkap jaringan penipuan online dengan modus investasi bodong yang merugikan puluhan korban.',
                    'content' => '<p>Satuan Reserse Kriminal Polres Mojokerto berhasil mengungkap jaringan penipuan online dengan modus investasi bodong.</p><p>Tiga tersangka ditangkap setelah dilaporkan oleh puluhan korban dengan total kerugian mencapai Rp 2,3 miliar.</p><p>Kapolres Mojokerto mengimbau masyarakat untuk selalu berhati-hati terhadap tawaran investasi yang menjanjikan keuntungan tidak wajar.</p>',
                ],
            ],
            'literasi' => [
                [
                    'title' => 'Festival Literasi Mojokerto: Menumbuhkan Budaya Membaca Sejak Dini',
                    'excerpt' => 'Rangkaian acara bedah buku dan lomba menulis cerpen digelar untuk meningkatkan indeks literasi masyarakat.',
                    'content' => '<p>Festival Literasi Mojokerto 2026 resmi dibuka dengan rangkaian acara yang bertujuan menumbuhkan budaya membaca sejak dini.</p><p>Kegiatan meliputi bedah buku, lomba menulis cerpen tingkat pelajar, workshop creative writing, dan pameran buku murah.</p><p>Dinas Perpustakaan dan Kearsipan Kabupaten Mojokerto berharap festival ini dapat meningkatkan indeks literasi masyarakat Mojokerto yang saat ini masih di bawah rata-rata nasional.</p>',
                ],
            ],
            'info-pelayanan-public' => [
                [
                    'title' => 'Jadwal SIM Keliling Mojokerto Minggu Kedua Mei 2026 Tersedia Sekarang',
                    'excerpt' => 'Masyarakat diimbau untuk mengecek lokasi operasional bus SIM Keliling guna mempermudah perpanjangan dokumen.',
                    'content' => '<p>Satlantas Polres Mojokerto mengumumkan jadwal layanan SIM Keliling untuk minggu kedua Mei 2026.</p><p>Layanan akan beroperasi di beberapa titik strategis meliputi Kecamatan Sooko (Senin), Kecamatan Puri (Selasa), Kecamatan Mojosari (Rabu), dan Kecamatan Ngoro (Kamis).</p><p>Warga yang ingin memperpanjang SIM diimbau membawa persyaratan lengkap berupa KTP asli, SIM lama, dan surat keterangan sehat dari dokter.</p>',
                ],
            ],
        ];
    }

    private function seedAdvertisements(): void
    {
        $ads = [
            ['title' => 'Banner Top 1', 'position' => 'top', 'priority' => 10, 'link_url' => 'https://example.com'],
            ['title' => 'Banner Top 2', 'position' => 'top', 'priority' => 9, 'link_url' => 'https://example.com'],
            ['title' => 'Banner Content 1', 'position' => 'content', 'priority' => 10, 'link_url' => 'https://example.com'],
            ['title' => 'Banner Sidebar 1', 'position' => 'sidebar', 'priority' => 10, 'link_url' => 'https://example.com'],
            ['title' => 'Banner Sidebar 2', 'position' => 'sidebar', 'priority' => 9, 'link_url' => 'https://example.com'],
            ['title' => 'Banner Sidebar 3', 'position' => 'sidebar', 'priority' => 8, 'link_url' => 'https://example.com'],
        ];

        foreach ($ads as $ad) {
            Advertisement::firstOrCreate(
                ['title' => $ad['title']],
                array_merge($ad, [
                    'image_url' => 'advertisements/placeholder-' . Str::slug($ad['title']) . '.jpg',
                    'is_active' => true,
                    'starts_at' => now()->subDay(),
                    'ends_at' => now()->addYear(),
                ])
            );
        }
    }

    private function seedVideos(array $authors): void
    {
        $videos = [
            ['title' => '41 Reka Adegan dilakukan GRT dalam Rekonstruksi', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Rekonstruksi kasus yang melibatkan 41 adegan dilakukan oleh tim GRT Polres Mojokerto.'],
            ['title' => 'Tiga Tahun di Jalan yang Sesat, Kisah Tobat Seorang Maling Motor', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Kisah inspiratif seorang mantan pencuri motor yang bertobat setelah tiga tahun menjalani kehidupan kriminal.'],
            ['title' => 'Lapor SS Adiknya Hilang, 15 Menit Kemudian Langsung Ditemukan', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Berkat laporan cepat melalui Suara Surabaya, adik yang hilang berhasil ditemukan dalam waktu 15 menit.'],
            ['title' => 'Air Mata Bahagia, Adik yang Hilang 8 Tahun Ditemukan', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Momen haru pertemuan kembali keluarga yang terpisah selama 8 tahun.'],
            ['title' => 'Lapor SS: Mobil Hilang di Jombang, Selang 2 Jam Ditemukan di Gresik', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Kekuatan jaringan informasi masyarakat berhasil menemukan mobil yang hilang dalam waktu singkat.'],
            ['title' => 'Pakar: Subsidi BBM Diganti BLT Justru Menyulitkan Masyarakat Bawah', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Analisis pakar ekonomi tentang dampak perubahan kebijakan subsidi BBM.'],
            ['title' => 'Dua Orang Meninggal Akibat Ledakan Rumah di Mojokerto', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Liputan kejadian ledakan rumah yang menewaskan dua orang di wilayah Mojokerto.'],
            ['title' => 'Menko Pangan Sebut Program MBG Utamakan Standar Gizi', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'description' => 'Pernyataan Menko Pangan terkait standar gizi dalam program Makan Bergizi Gratis.'],
        ];

        foreach ($videos as $video) {
            Video::firstOrCreate(
                ['title' => $video['title']],
                array_merge($video, [
                    'thumbnail' => null,
                    'views' => rand(100, 10000),
                    'is_active' => true,
                    'created_by' => $authors[array_rand($authors)]->id,
                ])
            );
        }
    }

    private function seedGalleries(array $authors): void
    {
        $galleries = [
            'Upacara Hari Pendidikan Nasional 2026 di Mojokerto',
            'Fun Run 5K Launching JConnect Run Mojokerto 10K 2026',
            'Kebakaran Rumah di Putat Gede Mojokerto',
            'Peringatan May Day 2026 di Alun-alun Mojokerto',
            'Peringatan Hari Tari Dunia 2026',
            'Siswa SD Pelajari Public Speaking di Kantor ILM',
        ];

        foreach ($galleries as $title) {
            $gallery = Gallery::firstOrCreate(
                ['title' => $title],
                [
                    'description' => 'Dokumentasi foto ' . $title,
                    'is_active' => true,
                    'created_by' => $authors[array_rand($authors)]->id,
                ]
            );
        }
    }
}
