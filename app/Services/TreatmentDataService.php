<?php
// app/Services/TreatmentDataService.php

namespace App\Services;

class TreatmentDataService
{
    /**
     * Get all treatments data
     */
    public static function getAllTreatments()
    {
        return [
            "facial" => [
                "facial-acne" => self::getFacialAcne(),
                "facial-brightening" => self::getFacialBrightening(),
                "facial-anti-aging" => self::getFacialAntiAging(),
                "facial-hydrating" => self::getFacialHydrating(),
                "facial-glow" => self::getFacialGlow(),
                "facial-detox" => self::getFacialDetox(),
            ],
            "laser" => [
                "laser-hair-removal" => self::getLaserHairRemoval(),
                "laser-carbon-peel" => self::getLaserCarbonPeel(),
                "laser-tattoo-removal" => self::getLaserTattooRemoval(),
                "laser-rejuvenation" => self::getLaserRejuvenation(),
                "laser-acne-scar" => self::getLaserAcneScar(),
                "laser-pigmentation" => self::getLaserPigmentation(),
            ],
            "body" => [
                "body-slimming" => self::getBodySlimming(),
                "body-whitening" => self::getBodyWhitening(),
                "body-massage" => self::getBodyMassage(),
                "body-scrub" => self::getBodyScrub(),
                "body-detox" => self::getBodyDetox(),
                "body-firming" => self::getBodyFirming(),
            ],
        ];
    }

    /**
     * Get treatment by slug
     */
    public static function getTreatmentBySlug($slug)
    {
        $allTreatments = self::getAllTreatments();

        foreach ($allTreatments as $category => $treatments) {
            if (isset($treatments[$slug])) {
                return $treatments[$slug];
            }
        }

        return null;
    }

    /**
     * Get treatments by category
     */
    public static function getTreatmentsByCategory($category)
    {
        $allTreatments = self::getAllTreatments();
        return $allTreatments[$category] ?? [];
    }

    // ==================== FACIAL TREATMENTS ====================

    private static function getFacialAcne()
    {
        return [
            "slug" => "facial-acne",
            "title" => "Facial Acne",
            "category" => "Facial",
            "short_description" =>
                "Perawatan khusus untuk mengatasi jerawat dan bekasnya dengan teknologi terkini",
            "description" =>
                "Perawatan khusus untuk mengatasi jerawat dan bekasnya dengan teknologi terkini dan bahan aktif yang aman. Treatment ini dirancang untuk membersihkan pori-pori tersumbat, mengurangi peradangan, dan mencegah jerawat kembali muncul.",
            "long_description" =>
                "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Facial", "Acne", "Kulit Berjerawat", "Anti Inflamasi"],
            "benefits" => [
                "Mengatasi jerawat aktif dan peradangan",
                "Membersihkan pori-pori tersumbat",
                "Mengurangi produksi minyak berlebih",
                "Mencegah jerawat kembali muncul",
                "Menyamarkan bekas jerawat",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Konsultasi",
                    "desc" =>
                        "Konsultasi dengan dokter untuk analisa kondisi kulit dan jenis jerawat",
                ],
                [
                    "step" => "2",
                    "title" => "Deep Cleansing",
                    "desc" => "Pembersihan mendalam dan ekstraksi komedo",
                ],
                [
                    "step" => "3",
                    "title" => "Treatment",
                    "desc" =>
                        "Aplikasi serum anti-acne dan teknologi LED therapy",
                ],
                [
                    "step" => "4",
                    "title" => "Home Care",
                    "desc" =>
                        "Panduan perawatan di rumah dan produk yang sesuai",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa lama durasi treatment?",
                    "a" =>
                        "Durasi treatment berkisar 60-75 menit tergantung tingkat keparahan jerawat.",
                ],
                [
                    "q" => "Apakah treatment ini aman?",
                    "a" =>
                        "Ya, treatment ini menggunakan produk dan teknologi yang telah teruji klinis dan aman untuk kulit berjerawat.",
                ],
                [
                    "q" => "Berapa kali treatment diperlukan?",
                    "a" =>
                        "Untuk hasil optimal, disarankan 8-12 kali treatment dengan jarak 1-2 minggu sekali.",
                ],
                [
                    "q" => "Apakah ada efek samping?",
                    "a" =>
                        "Mungkin terjadi sedikit kemerahan atau purging di awal treatment, ini normal dan akan hilang dalam 1-2 hari.",
                ],
            ],
            "price_range" => "Rp 350.000 - Rp 500.000",
            "duration" => "60-75 menit",
        ];
    }

    private static function getFacialBrightening()
    {
        return [
            "slug" => "facial-brightening",
            "title" => "Facial Brightening",
            "category" => "Facial",
            "short_description" =>
                "Mencerahkan kulit wajah kusam dan meratakan warna kulit",
            "description" =>
                "Mencerahkan kulit wajah kusam dan meratakan warna kulit untuk tampilan lebih bercahaya. Treatment ini menggunakan kombinasi vitamin C, niacinamide, dan teknologi whitening untuk hasil maksimal.",
            "long_description" =>
                "Treatment brightening kami dirancang khusus untuk mengatasi hiperpigmentasi, flek hitam, dan warna kulit tidak merata. Dengan menggunakan bahan aktif berkualitas tinggi dan teknologi modern.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Facial", "Brightening", "Cerah", "Glowing"],
            "benefits" => [
                "Mencerahkan kulit kusam",
                "Meratakan warna kulit",
                "Mengurangi flek hitam",
                "Kulit tampak lebih bercahaya",
                "Melembabkan kulit",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Konsultasi",
                    "desc" => "Analisa tingkat kekusaman dan pigmentasi kulit",
                ],
                [
                    "step" => "2",
                    "title" => "Cleansing",
                    "desc" => "Pembersihan wajah dengan produk brightening",
                ],
                [
                    "step" => "3",
                    "title" => "Treatment",
                    "desc" => "Aplikasi serum vitamin C dan masker whitening",
                ],
                [
                    "step" => "4",
                    "title" => "Protection",
                    "desc" => "Sunscreen dan tips menjaga hasil treatment",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa lama hasil terlihat?",
                    "a" =>
                        "Hasil awal dapat terlihat setelah 2-3 kali treatment, hasil optimal dalam 6-8 minggu.",
                ],
                [
                    "q" => "Apakah aman untuk kulit sensitif?",
                    "a" =>
                        "Ya, kami menggunakan bahan yang gentle dan dapat disesuaikan dengan kondisi kulit.",
                ],
                [
                    "q" => "Berapa kali treatment diperlukan?",
                    "a" =>
                        "Disarankan 6-8 kali treatment dengan jarak 2 minggu untuk hasil optimal.",
                ],
                [
                    "q" => "Apakah hasilnya permanen?",
                    "a" =>
                        "Dengan perawatan yang tepat dan perlindungan dari sinar matahari, hasil dapat bertahan lama.",
                ],
            ],
            "price_range" => "Rp 400.000 - Rp 600.000",
            "duration" => "60 menit",
        ];
    }

    private static function getFacialAntiAging()
    {
        return [
            "slug" => "facial-anti-aging",
            "title" => "Facial Anti Aging",
            "category" => "Facial",
            "short_description" =>
                "Mengurangi tanda-tanda penuaan seperti garis halus dan kerutan",
            "description" =>
                "Mengurangi tanda-tanda penuaan seperti garis halus dan kerutan untuk kulit lebih muda. Treatment menggunakan peptide, retinol, dan teknologi radio frequency.",
            "long_description" =>
                "Treatment anti-aging komprehensif yang mengatasi berbagai tanda penuaan seperti garis halus, kerutan, kulit kendur, dan kehilangan elastisitas.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Facial", "Anti Aging", "Kerutan", "Lifting"],
            "benefits" => [
                "Mengurangi garis halus dan kerutan",
                "Meningkatkan elastisitas kulit",
                "Mengencangkan kulit wajah",
                "Merangsang produksi kolagen",
                "Kulit tampak lebih muda",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Konsultasi",
                    "desc" => "Evaluasi tanda-tanda penuaan pada kulit",
                ],
                [
                    "step" => "2",
                    "title" => "Persiapan",
                    "desc" => "Cleansing dan toning dengan produk anti-aging",
                ],
                [
                    "step" => "3",
                    "title" => "Treatment",
                    "desc" => "RF therapy dan aplikasi serum peptide",
                ],
                [
                    "step" => "4",
                    "title" => "Maintenance",
                    "desc" => "Rekomendasi produk anti-aging untuk home care",
                ],
            ],
            "faq" => [
                [
                    "q" => "Mulai usia berapa bisa treatment ini?",
                    "a" =>
                        "Disarankan mulai usia 25+ untuk pencegahan, atau kapan saja ketika tanda penuaan mulai muncul.",
                ],
                [
                    "q" => "Apakah treatment ini sakit?",
                    "a" =>
                        "Tidak sakit, hanya terasa hangat saat RF therapy. Sangat comfortable dan relaxing.",
                ],
                [
                    "q" => "Berapa lama hasil bertahan?",
                    "a" =>
                        "Dengan perawatan rutin, hasil dapat bertahan 3-6 bulan per siklus treatment.",
                ],
                [
                    "q" => "Apakah bisa dikombinasi dengan botox?",
                    "a" =>
                        "Ya, namun perlu konsultasi dengan dokter untuk timing yang tepat.",
                ],
            ],
            "price_range" => "Rp 600.000 - Rp 900.000",
            "duration" => "75-90 menit",
        ];
    }

    private static function getFacialHydrating()
    {
        return [
            "slug" => "facial-hydrating",
            "title" => "Facial Hydrating",
            "category" => "Facial",
            "short_description" =>
                "Memberikan kelembaban intensif untuk kulit kering",
            "description" =>
                "Memberikan kelembaban intensif untuk kulit kering dan dehidrasi dengan serum khusus berbasis hyaluronic acid dan ceramide.",
            "long_description" =>
                "Treatment hydrating dirancang untuk mengatasi kulit kering, dehidrasi, dan barrier kulit yang rusak.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Facial", "Hydrating", "Moisturizing", "Kulit Kering"],
            "benefits" => [
                "Melembabkan kulit secara intensif",
                "Memperbaiki skin barrier",
                "Mengurangi kulit kering dan bersisik",
                "Kulit terasa lebih kenyal",
                "Mengurangi garis dehidrasi",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Skin Analysis",
                    "desc" => "Mengukur tingkat hidrasi kulit",
                ],
                [
                    "step" => "2",
                    "title" => "Gentle Cleansing",
                    "desc" =>
                        "Pembersihan lembut tanpa menghilangkan minyak alami",
                ],
                [
                    "step" => "3",
                    "title" => "Hydration Boost",
                    "desc" => "Layer serum hyaluronic acid dan ceramide",
                ],
                [
                    "step" => "4",
                    "title" => "Sealing",
                    "desc" =>
                        "Lock moisture dengan moisturizer dan tips hydration",
                ],
            ],
            "faq" => [
                [
                    "q" => "Cocok untuk kulit apa?",
                    "a" =>
                        "Cocok untuk semua jenis kulit, terutama kulit kering, dehidrasi, atau sensitif.",
                ],
                [
                    "q" => "Seberapa sering harus treatment?",
                    "a" =>
                        "Untuk hasil optimal, 1-2 minggu sekali selama 4-6 kali, lalu maintenance sebulan sekali.",
                ],
                [
                    "q" => "Apakah bisa untuk kulit berminyak?",
                    "a" =>
                        "Ya, kulit berminyak juga bisa dehidrasi. Treatment ini membantu balance oil-water.",
                ],
                [
                    "q" => "Kapan hasilnya terasa?",
                    "a" =>
                        "Langsung terasa lebih lembab dan kenyal setelah treatment pertama.",
                ],
            ],
            "price_range" => "Rp 350.000 - Rp 550.000",
            "duration" => "60 menit",
        ];
    }

    private static function getFacialGlow()
    {
        return [
            "slug" => "facial-glow",
            "title" => "Facial Glow",
            "category" => "Facial",
            "short_description" =>
                "Perawatan untuk mendapatkan kulit glowing dan sehat",
            "description" =>
                "Perawatan untuk mendapatkan kulit glowing dan sehat dengan hasil instan. Perfect untuk special occasion atau maintenance rutin.",
            "long_description" =>
                "Treatment glow memberikan efek instant radiance dengan kombinasi exfoliation, brightening serum, dan oxygen therapy.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Facial", "Glowing", "Radiant", "Instant"],
            "benefits" => [
                "Kulit langsung glowing",
                "Wajah tampak segar dan sehat",
                "Meningkatkan radiance",
                "Tekstur kulit lebih halus",
                "Makeup lebih menempel",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Konsultasi",
                    "desc" => "Menentukan jenis glow yang diinginkan",
                ],
                [
                    "step" => "2",
                    "title" => "Exfoliation",
                    "desc" => "Gentle peeling untuk angkat sel kulit mati",
                ],
                [
                    "step" => "3",
                    "title" => "Glow Boost",
                    "desc" => "Vitamin C serum dan oxygen therapy",
                ],
                [
                    "step" => "4",
                    "title" => "Finishing",
                    "desc" => "Glow enhancer dan tips maintain glow",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa lama hasil glow bertahan?",
                    "a" =>
                        "Hasil instant dapat bertahan 3-5 hari, untuk hasil lasting perlu treatment rutin.",
                ],
                [
                    "q" => "Bisa treatment sebelum acara penting?",
                    "a" =>
                        "Ya, sangat recommended! Best dilakukan 1-2 hari sebelum acara.",
                ],
                [
                    "q" => "Apakah cocok untuk semua jenis kulit?",
                    "a" =>
                        "Ya, treatment dapat disesuaikan dengan jenis dan kondisi kulit.",
                ],
                [
                    "q" => "Apakah ada downtime?",
                    "a" =>
                        "Tidak ada downtime, bisa langsung beraktivitas dan makeup.",
                ],
            ],
            "price_range" => "Rp 400.000 - Rp 650.000",
            "duration" => "60 menit",
        ];
    }

    private static function getFacialDetox()
    {
        return [
            "slug" => "facial-detox",
            "title" => "Facial Detox",
            "category" => "Facial",
            "short_description" => "Membersihkan racun dan kotoran dalam kulit",
            "description" =>
                "Membersihkan racun dan kotoran dalam kulit untuk wajah lebih segar dan bersih. Menggunakan charcoal dan clay mask untuk deep cleansing.",
            "long_description" =>
                "Treatment detox mengeluarkan impurities, pollution, dan toxins yang menumpuk di kulit akibat lingkungan urban.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Facial", "Detox", "Deep Cleansing", "Purifying"],
            "benefits" => [
                "Membersihkan pori-pori mendalam",
                "Mengeluarkan racun dalam kulit",
                "Mengurangi komedo dan whitehead",
                "Kulit terasa lebih bersih dan segar",
                "Mencegah breakout",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Assessment",
                    "desc" => "Evaluasi tingkat kekotoran dan pori-pori",
                ],
                [
                    "step" => "2",
                    "title" => "Steam & Extract",
                    "desc" => "Pembukaan pori dan ekstraksi lembut",
                ],
                [
                    "step" => "3",
                    "title" => "Detox Mask",
                    "desc" => "Charcoal dan clay mask untuk absorb toxins",
                ],
                [
                    "step" => "4",
                    "title" => "Soothing",
                    "desc" =>
                        "Calming treatment dan tips mencegah clogged pores",
                ],
            ],
            "faq" => [
                [
                    "q" => "Seberapa sering perlu detox facial?",
                    "a" =>
                        "Disarankan sebulan sekali atau setiap 2 minggu untuk kulit yang mudah berkomedo.",
                ],
                [
                    "q" => "Apakah wajah akan merah setelah treatment?",
                    "a" =>
                        "Mungkin sedikit kemerahan yang normal dan akan hilang dalam 1-2 jam.",
                ],
                [
                    "q" => "Bisa untuk kulit sensitif?",
                    "a" =>
                        "Ya, kami gunakan produk yang gentle dan dapat disesuaikan dengan sensitivitas kulit.",
                ],
                [
                    "q" => "Apakah perlu persiapan khusus?",
                    "a" =>
                        "Tidak perlu persiapan khusus, datang dengan wajah tanpa makeup lebih baik.",
                ],
            ],
            "price_range" => "Rp 350.000 - Rp 500.000",
            "duration" => "75 menit",
        ];
    }

    // ==================== LASER TREATMENTS ====================

    private static function getLaserHairRemoval()
    {
        return [
            "slug" => "laser-hair-removal",
            "title" => "Laser Hair Removal",
            "category" => "Laser",
            "short_description" =>
                "Menghilangkan bulu secara permanen dengan teknologi laser",
            "description" =>
                "Menghilangkan bulu secara permanen dengan teknologi laser yang aman dan nyaman. Cocok untuk semua area tubuh.",
            "long_description" =>
                "Laser hair removal menggunakan teknologi diode laser yang aman untuk semua jenis kulit dan warna rambut.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Laser", "Hair Removal", "Permanent", "Smooth Skin"],
            "benefits" => [
                "Menghilangkan bulu secara permanen",
                "Hasil halus dan smooth",
                "Tidak sakit seperti waxing",
                "Cocok untuk semua area tubuh",
                "Hemat waktu dan biaya jangka panjang",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Konsultasi",
                    "desc" => "Evaluasi area dan jenis rambut",
                ],
                [
                    "step" => "2",
                    "title" => "Patch Test",
                    "desc" => "Test treatment di area kecil",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Aplikasi laser dengan cooling system",
                ],
                [
                    "step" => "4",
                    "title" => "Aftercare",
                    "desc" => "Soothing gel dan panduan perawatan",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa kali sesi diperlukan?",
                    "a" =>
                        "Rata-rata 6-8 sesi dengan jarak 4-6 minggu untuk hasil permanen.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Tidak sakit, hanya terasa seperti snap karet dengan cooling effect.",
                ],
                [
                    "q" => "Area mana saja yang bisa?",
                    "a" =>
                        "Semua area: wajah, ketiak, bikini, kaki, tangan, punggung, dll.",
                ],
                [
                    "q" => "Apakah aman untuk kulit gelap?",
                    "a" =>
                        "Ya, kami menggunakan teknologi yang aman untuk semua skin tone.",
                ],
            ],
            "price_range" => "Mulai dari Rp 200.000/area",
            "duration" => "15-60 menit tergantung area",
        ];
    }

    private static function getLaserCarbonPeel()
    {
        return [
            "slug" => "laser-carbon-peel",
            "title" => "Laser Carbon Peel",
            "category" => "Laser",
            "short_description" =>
                "Mengangkat sel kulit mati dan mencerahkan kulit",
            "description" =>
                "Mengangkat sel kulit mati dan mencerahkan kulit dengan teknologi laser carbon. Hollywood facial untuk instant glow.",
            "long_description" =>
                "Carbon peel menggunakan kombinasi carbon lotion dan Q-switch laser untuk deep cleansing dan brightening instant.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => [
                "Laser",
                "Carbon Peel",
                "Brightening",
                "Hollywood Facial",
            ],
            "benefits" => [
                "Mengangkat sel kulit mati",
                "Mencerahkan kulit instant",
                "Mengecilkan pori-pori",
                "Mengurangi minyak berlebih",
                "Kulit tampak glowing",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Cleansing",
                    "desc" => "Pembersihan wajah menyeluruh",
                ],
                [
                    "step" => "2",
                    "title" => "Carbon Application",
                    "desc" => "Aplikasi carbon lotion merata",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Q-switch laser untuk blast carbon",
                ],
                [
                    "step" => "4",
                    "title" => "Soothing",
                    "desc" => "Masker calming dan sunscreen",
                ],
            ],
            "faq" => [
                [
                    "q" => "Apakah ada downtime?",
                    "a" => "Tidak ada downtime, bisa langsung beraktivitas.",
                ],
                [
                    "q" => "Seberapa sering bisa treatment?",
                    "a" => "Bisa 2-4 minggu sekali untuk maintenance.",
                ],
                [
                    "q" => "Apakah cocok untuk kulit berminyak?",
                    "a" =>
                        "Sangat cocok! Carbon peel excellent untuk oil control.",
                ],
                [
                    "q" => "Kapan hasil terlihat?",
                    "a" =>
                        "Langsung terlihat setelah treatment, kulit lebih cerah dan smooth.",
                ],
            ],
            "price_range" => "Rp 500.000 - Rp 800.000",
            "duration" => "45 menit",
        ];
    }

    private static function getLaserTattooRemoval()
    {
        return [
            "slug" => "laser-tattoo-removal",
            "title" => "Laser Tattoo Removal",
            "category" => "Laser",
            "short_description" =>
                "Menghilangkan tato dengan aman menggunakan laser",
            "description" =>
                "Menghilangkan tato dengan aman menggunakan teknologi laser Q-Switch. Efektif untuk semua warna tinta.",
            "long_description" =>
                "Laser tattoo removal menggunakan Q-Switch laser yang memecah partikel tinta menjadi fragmen kecil yang diserap tubuh.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Laser", "Tattoo Removal", "Q-Switch", "Safe"],
            "benefits" => [
                "Menghilangkan tato secara aman",
                "Efektif untuk berbagai warna",
                "Minimal scarring",
                "FDA approved technology",
                "Dapat menghilangkan tato lama",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Consultation",
                    "desc" => "Evaluasi ukuran, warna, dan kedalaman tato",
                ],
                [
                    "step" => "2",
                    "title" => "Numbing",
                    "desc" => "Aplikasi numbing cream untuk comfort",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Q-Switch laser untuk break down ink",
                ],
                [
                    "step" => "4",
                    "title" => "Post Care",
                    "desc" => "Bandaging dan instruksi perawatan luka",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa sesi untuk hilangkan tato?",
                    "a" =>
                        "Tergantung ukuran dan warna, rata-rata 5-10 sesi dengan jarak 6-8 minggu.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Ada sedikit discomfort, namun manageable dengan numbing cream.",
                ],
                [
                    "q" => "Apakah akan bekas?",
                    "a" =>
                        "Dengan teknologi modern, scarring minimal jika perawatan tepat.",
                ],
                [
                    "q" => "Warna apa yang paling mudah dihilangkan?",
                    "a" =>
                        "Hitam dan biru paling mudah, warna terang seperti kuning dan hijau lebih challenging.",
                ],
            ],
            "price_range" => "Mulai dari Rp 500.000/sesi",
            "duration" => "15-45 menit tergantung ukuran",
        ];
    }

    private static function getLaserRejuvenation()
    {
        return [
            "slug" => "laser-rejuvenation",
            "title" => "Laser Rejuvenation",
            "category" => "Laser",
            "short_description" =>
                "Meremajakan kulit dan mengurangi tanda penuaan",
            "description" =>
                "Meremajakan kulit dan mengurangi tanda penuaan dengan laser fraksional. Meningkatkan produksi kolagen.",
            "long_description" =>
                "Laser rejuvenation menggunakan fraksional CO2 atau erbium laser untuk skin resurfacing dan collagen remodeling.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Laser", "Rejuvenation", "Anti Aging", "Collagen Boost"],
            "benefits" => [
                "Mengurangi garis halus dan kerutan",
                "Meningkatkan produksi kolagen",
                "Mengencangkan kulit",
                "Memperbaiki tekstur kulit",
                "Kulit tampak lebih muda",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Assessment",
                    "desc" => "Evaluasi tanda penuaan dan skin laxity",
                ],
                [
                    "step" => "2",
                    "title" => "Preparation",
                    "desc" => "Cleansing dan numbing cream",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Fraksional laser dengan controlled depth",
                ],
                [
                    "step" => "4",
                    "title" => "Recovery Care",
                    "desc" => "Post-treatment care dan sunscreen strict",
                ],
            ],
            "faq" => [
                [
                    "q" => "Apakah ada downtime?",
                    "a" =>
                        "Ya, downtime 3-7 hari tergantung intensity. Kulit akan peeling.",
                ],
                [
                    "q" => "Kapan hasil terlihat?",
                    "a" =>
                        "Hasil awal 2-3 minggu, hasil optimal 3-6 bulan saat kolagen terbentuk.",
                ],
                [
                    "q" => "Berapa sesi diperlukan?",
                    "a" =>
                        "Biasanya 1-3 sesi dengan jarak 4-6 bulan untuk hasil optimal.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Ada discomfort, namun dengan numbing cream sangat manageable.",
                ],
            ],
            "price_range" => "Rp 3.000.000 - Rp 7.000.000",
            "duration" => "60-90 menit",
        ];
    }

    private static function getLaserAcneScar()
    {
        return [
            "slug" => "laser-acne-scar",
            "title" => "Laser Acne Scar",
            "category" => "Laser",
            "short_description" =>
                "Mengatasi bekas jerawat dan lubang bekas jerawat",
            "description" =>
                "Mengatasi bekas jerawat dan lubang bekas jerawat dengan laser resurfacing. Memperbaiki tekstur kulit yang tidak rata.",
            "long_description" =>
                "Treatment laser acne scar menggunakan fraksional laser untuk meratakan kulit dan mengurangi kedalaman acne scar.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Laser", "Acne Scar", "Resurfacing", "Texture"],
            "benefits" => [
                "Mengurangi kedalaman acne scar",
                "Meratakan tekstur kulit",
                "Mencerahkan bekas jerawat gelap",
                "Merangsang regenerasi kulit",
                "Kulit tampak lebih smooth",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Scar Assessment",
                    "desc" => "Evaluasi jenis dan kedalaman bekas jerawat",
                ],
                [
                    "step" => "2",
                    "title" => "Numbing",
                    "desc" => "Aplikasi numbing cream 30-45 menit",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Fraksional laser targeting scar tissue",
                ],
                [
                    "step" => "4",
                    "title" => "Post Care",
                    "desc" => "Panduan recovery dan produk support healing",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa sesi untuk hasil optimal?",
                    "a" =>
                        "Tergantung keparahan scar, biasanya 3-6 sesi dengan jarak 4-6 minggu.",
                ],
                [
                    "q" => "Apakah bisa hilang 100%?",
                    "a" =>
                        "Tergantung kedalaman scar, biasanya dapat improve 50-80%.",
                ],
                [
                    "q" => "Apakah ada downtime?",
                    "a" =>
                        "Ya, downtime 5-7 hari dengan peeling dan kemerahan.",
                ],
                [
                    "q" => "Kapan bisa lihat hasil?",
                    "a" =>
                        "Hasil bertahap terlihat setelah 2-3 minggu, optimal setelah semua sesi selesai.",
                ],
            ],
            "price_range" => "Rp 1.500.000 - Rp 3.000.000",
            "duration" => "60 menit",
        ];
    }

    private static function getLaserPigmentation()
    {
        return [
            "slug" => "laser-pigmentation",
            "title" => "Laser Pigmentation",
            "category" => "Laser",
            "short_description" =>
                "Menghilangkan flek hitam dan hiperpigmentasi",
            "description" =>
                "Menghilangkan flek hitam dan hiperpigmentasi dengan laser targeting. Efektif untuk melasma, age spots, dan sun damage.",
            "long_description" =>
                "Laser pigmentation menggunakan Q-switch atau pico laser yang target melanin untuk break down pigmentation.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Laser", "Pigmentation", "Melasma", "Flek Hitam"],
            "benefits" => [
                "Menghilangkan flek hitam",
                "Mengatasi melasma",
                "Meratakan warna kulit",
                "Mencerahkan kulit",
                "Hasil lasting dengan maintenance",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Pigmentation Analysis",
                    "desc" => "Mapping area pigmentasi dengan woods lamp",
                ],
                [
                    "step" => "2",
                    "title" => "Cleansing",
                    "desc" => "Deep cleansing dan persiapan kulit",
                ],
                [
                    "step" => "3",
                    "title" => "Laser Treatment",
                    "desc" => "Laser targeting pada area pigmentasi",
                ],
                [
                    "step" => "4",
                    "title" => "Sun Protection",
                    "desc" => "Sunscreen dan tips prevent pigmentation",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa kali treatment diperlukan?",
                    "a" =>
                        "Biasanya 3-5 sesi dengan jarak 3-4 minggu untuk flek ringan-sedang.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Tidak sakit, hanya terasa seperti karet dipentil dengan cooling.",
                ],
                [
                    "q" => "Apakah pigmentasi bisa kembali?",
                    "a" =>
                        "Bisa kembali jika tidak pakai sunscreen dan terpapar sinar matahari berlebihan.",
                ],
                [
                    "q" => "Bisa untuk melasma yang parah?",
                    "a" =>
                        "Ya bisa, namun perlu kombinasi dengan treatment lain dan membutuhkan lebih banyak sesi.",
                ],
            ],
            "price_range" => "Rp 800.000 - Rp 1.500.000",
            "duration" => "30-45 menit",
        ];
    }

    // ==================== BODY SPA TREATMENTS ====================

    private static function getBodySlimming()
    {
        return [
            "slug" => "body-slimming",
            "title" => "Body Slimming",
            "category" => "Body Spa",
            "short_description" =>
                "Program pelangsingan tubuh dengan teknologi canggih",
            "description" =>
                "Program pelangsingan tubuh dengan teknologi canggih untuk hasil optimal. Mengurangi lemak membandel dan membentuk tubuh ideal.",
            "long_description" =>
                "Body slimming menggunakan kombinasi teknologi RF, cavitation, dan vacuum therapy untuk membakar lemak dan mengencangkan kulit.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => [
                "Body Spa",
                "Slimming",
                "Fat Burning",
                "Body Contouring",
            ],
            "benefits" => [
                "Mengurangi lemak membandel",
                "Membentuk kontur tubuh",
                "Mengencangkan kulit kendur",
                "Meningkatkan metabolisme",
                "Hasil terukur dan visible",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Body Analysis",
                    "desc" => "Pengukuran body fat dan problem area",
                ],
                [
                    "step" => "2",
                    "title" => "Warming Up",
                    "desc" => "Infrared therapy untuk warm up tissue",
                ],
                [
                    "step" => "3",
                    "title" => "Slimming Treatment",
                    "desc" => "RF cavitation dan vacuum therapy",
                ],
                [
                    "step" => "4",
                    "title" => "Diet Plan",
                    "desc" => "Rekomendasi diet dan exercise untuk support",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa kg bisa turun?",
                    "a" =>
                        "Hasil bervariasi, rata-rata 2-5kg dalam 8-12 sesi dengan diet support.",
                ],
                [
                    "q" => "Apakah aman?",
                    "a" =>
                        "Ya sangat aman, menggunakan teknologi non-invasive yang sudah FDA approved.",
                ],
                [
                    "q" => "Berapa kali seminggu?",
                    "a" => "Disarankan 2-3x seminggu untuk hasil optimal.",
                ],
                [
                    "q" => "Apakah perlu diet ketat?",
                    "a" =>
                        "Tidak perlu diet ketat, namun perlu pola makan sehat dan seimbang.",
                ],
            ],
            "price_range" => "Rp 300.000 - Rp 500.000/sesi",
            "duration" => "90 menit",
        ];
    }

    private static function getBodyWhitening()
    {
        return [
            "slug" => "body-whitening",
            "title" => "Body Whitening",
            "category" => "Body Spa",
            "short_description" => "Mencerahkan kulit tubuh secara menyeluruh",
            "description" =>
                "Mencerahkan kulit tubuh secara menyeluruh untuk tampilan lebih cerah. Treatment full body untuk even skin tone.",
            "long_description" =>
                "Body whitening menggunakan whitening serum, masker, dan teknologi LED untuk mencerahkan kulit tubuh secara merata.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Body Spa", "Whitening", "Brightening", "Full Body"],
            "benefits" => [
                "Mencerahkan kulit tubuh",
                "Meratakan warna kulit",
                "Mengurangi kusam",
                "Kulit lebih glowing",
                "Melembabkan kulit",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Body Scrub",
                    "desc" => "Exfoliasi untuk angkat sel kulit mati",
                ],
                [
                    "step" => "2",
                    "title" => "Whitening Mask",
                    "desc" => "Full body whitening mask application",
                ],
                [
                    "step" => "3",
                    "title" => "LED Therapy",
                    "desc" => "Red LED untuk boost whitening",
                ],
                [
                    "step" => "4",
                    "title" => "Moisturizing",
                    "desc" => "Body lotion whitening dan sun protection tips",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa lama hasil terlihat?",
                    "a" =>
                        "Hasil awal terlihat setelah 3-4 kali treatment, optimal dalam 8-10 minggu.",
                ],
                [
                    "q" => "Apakah aman untuk semua skin type?",
                    "a" =>
                        "Ya, produk dapat disesuaikan dengan jenis dan kondisi kulit.",
                ],
                [
                    "q" => "Seberapa sering treatment?",
                    "a" =>
                        "Disarankan seminggu sekali untuk 8-10 sesi, lalu maintenance sebulan sekali.",
                ],
                [
                    "q" => "Apakah hasilnya permanen?",
                    "a" =>
                        "Hasil dapat lasting dengan maintenance dan sun protection yang baik.",
                ],
            ],
            "price_range" => "Rp 400.000 - Rp 700.000",
            "duration" => "120 menit",
        ];
    }

    private static function getBodyMassage()
    {
        return [
            "slug" => "body-massage",
            "title" => "Body Massage",
            "category" => "Body Spa",
            "short_description" => "Pijat relaksasi untuk menghilangkan stress",
            "description" =>
                "Pijat relaksasi untuk menghilangkan stress dan ketegangan otot. Berbagai teknik massage untuk deep relaxation.",
            "long_description" =>
                "Body massage dengan teknik Swedish, deep tissue, atau aromatherapy untuk relaksasi maksimal dan meredakan muscle tension.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Body Spa", "Massage", "Relaxation", "Wellness"],
            "benefits" => [
                "Menghilangkan stress dan tension",
                "Melancarkan peredaran darah",
                "Meredakan nyeri otot",
                "Meningkatkan kualitas tidur",
                "Relaksasi total",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Consultation",
                    "desc" =>
                        "Menentukan jenis massage dan pressure preference",
                ],
                [
                    "step" => "2",
                    "title" => "Warming",
                    "desc" => "Aromatherapy dan muscle warming",
                ],
                [
                    "step" => "3",
                    "title" => "Massage",
                    "desc" => "Full body massage dengan teknik pilihan",
                ],
                [
                    "step" => "4",
                    "title" => "Relaxation",
                    "desc" => "Post massage tea dan relaxation time",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa lama durasi massage?",
                    "a" => "Pilihan 60, 90, atau 120 menit sesuai kebutuhan.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Tidak sakit, pressure dapat disesuaikan dengan preference Anda.",
                ],
                [
                    "q" => "Jenis massage apa yang tersedia?",
                    "a" =>
                        "Swedish, Deep Tissue, Aromatherapy, Hot Stone, Thai Massage.",
                ],
                [
                    "q" => "Seberapa sering perlu massage?",
                    "a" =>
                        "Untuk stress relief, seminggu sekali atau sesuai kebutuhan.",
                ],
            ],
            "price_range" => "Rp 250.000 - Rp 600.000",
            "duration" => "60-120 menit",
        ];
    }

    private static function getBodyScrub()
    {
        return [
            "slug" => "body-scrub",
            "title" => "Body Scrub",
            "category" => "Body Spa",
            "short_description" =>
                "Mengangkat sel kulit mati untuk kulit lebih halus",
            "description" =>
                "Mengangkat sel kulit mati untuk kulit tubuh lebih halus dan cerah. Berbagai pilihan scrub natural ingredients.",
            "long_description" =>
                "Body scrub menggunakan bahan natural seperti coffee, green tea, atau sea salt untuk exfoliasi dan nutrisi kulit.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Body Spa", "Scrub", "Exfoliation", "Smooth Skin"],
            "benefits" => [
                "Mengangkat sel kulit mati",
                "Kulit lebih halus dan lembut",
                "Meningkatkan regenerasi kulit",
                "Kulit lebih cerah",
                "Melancarkan peredaran darah",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Cleansing",
                    "desc" => "Body shower dan cleansing",
                ],
                [
                    "step" => "2",
                    "title" => "Scrubbing",
                    "desc" => "Full body scrub dengan circular motion",
                ],
                [
                    "step" => "3",
                    "title" => "Rinse",
                    "desc" => "Steam dan shower untuk rinse",
                ],
                [
                    "step" => "4",
                    "title" => "Moisturize",
                    "desc" => "Body lotion dan moisturizer",
                ],
            ],
            "faq" => [
                [
                    "q" => "Seberapa sering perlu scrub?",
                    "a" => "Disarankan 1-2 minggu sekali untuk hasil optimal.",
                ],
                [
                    "q" => "Apakah cocok untuk kulit sensitif?",
                    "a" => "Ya, tersedia scrub gentle untuk kulit sensitif.",
                ],
                [
                    "q" => "Jenis scrub apa yang tersedia?",
                    "a" => "Coffee, Green Tea, Sea Salt, Brown Sugar, Coconut.",
                ],
                [
                    "q" => "Apakah bisa scrub sendiri di rumah?",
                    "a" =>
                        "Bisa, namun scrub profesional memberikan hasil lebih maksimal.",
                ],
            ],
            "price_range" => "Rp 200.000 - Rp 400.000",
            "duration" => "60 menit",
        ];
    }

    private static function getBodyDetox()
    {
        return [
            "slug" => "body-detox",
            "title" => "Body Detox",
            "category" => "Body Spa",
            "short_description" => "Mengeluarkan racun dari tubuh",
            "description" =>
                "Mengeluarkan racun dari tubuh untuk kesehatan dan kecantikan optimal. Detox therapy untuk inner beauty.",
            "long_description" =>
                "Body detox menggunakan infrared sauna, body wrap, dan lymphatic drainage untuk mengeluarkan toxins dari tubuh.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Body Spa", "Detox", "Cleansing", "Wellness"],
            "benefits" => [
                "Mengeluarkan racun dalam tubuh",
                "Meningkatkan metabolisme",
                "Melancarkan sistem limfatik",
                "Kulit lebih sehat dan cerah",
                "Meningkatkan energi",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Detox Drink",
                    "desc" => "Konsumsi detox juice sebelum treatment",
                ],
                [
                    "step" => "2",
                    "title" => "Infrared Sauna",
                    "desc" => "20-30 menit infrared therapy",
                ],
                [
                    "step" => "3",
                    "title" => "Body Wrap",
                    "desc" => "Detox body wrap dengan algae atau clay",
                ],
                [
                    "step" => "4",
                    "title" => "Lymphatic Massage",
                    "desc" => "Drainage massage untuk boost detox",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa kali perlu detox?",
                    "a" =>
                        "Disarankan sebulan sekali atau setiap 2 minggu untuk maintenance.",
                ],
                [
                    "q" => "Apakah ada efek samping?",
                    "a" =>
                        "Mungkin terasa sedikit lemas setelah treatment, ini normal dan akan recovery.",
                ],
                [
                    "q" => "Apakah perlu persiapan khusus?",
                    "a" =>
                        "Sebaiknya makan ringan 2 jam sebelum treatment dan banyak minum air.",
                ],
                [
                    "q" => "Siapa yang tidak boleh detox?",
                    "a" =>
                        "Ibu hamil, menyusui, atau dengan kondisi medis tertentu perlu konsultasi dulu.",
                ],
            ],
            "price_range" => "Rp 500.000 - Rp 800.000",
            "duration" => "90-120 menit",
        ];
    }

    private static function getBodyFirming()
    {
        return [
            "slug" => "body-firming",
            "title" => "Body Firming",
            "category" => "Body Spa",
            "short_description" => "Mengencangkan kulit tubuh yang kendur",
            "description" =>
                "Mengencangkan kulit tubuh yang kendur dengan treatment khusus. Efektif untuk post-pregnancy atau weight loss.",
            "long_description" =>
                "Body firming menggunakan RF technology, ultrasound, dan firming serum untuk mengencangkan dan lift kulit tubuh.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Body Spa", "Firming", "Tightening", "Body Lift"],
            "benefits" => [
                "Mengencangkan kulit kendur",
                "Meningkatkan elastisitas kulit",
                "Mengurangi selulit",
                "Membentuk kontur tubuh",
                "Kulit lebih kencang dan firm",
            ],
            "process" => [
                [
                    "step" => "1",
                    "title" => "Skin Assessment",
                    "desc" => "Evaluasi area yang perlu firming",
                ],
                [
                    "step" => "2",
                    "title" => "Body Scrub",
                    "desc" => "Exfoliasi untuk persiapan treatment",
                ],
                [
                    "step" => "3",
                    "title" => "RF Treatment",
                    "desc" => "Radio frequency untuk collagen stimulation",
                ],
                [
                    "step" => "4",
                    "title" => "Firming Wrap",
                    "desc" => "Body wrap dengan firming serum",
                ],
            ],
            "faq" => [
                [
                    "q" => "Berapa sesi untuk hasil optimal?",
                    "a" =>
                        "Disarankan 10-15 sesi dengan frekuensi 2-3x seminggu.",
                ],
                [
                    "q" => "Apakah sakit?",
                    "a" =>
                        "Tidak sakit, hanya terasa hangat saat RF treatment.",
                ],
                [
                    "q" => "Kapan hasil terlihat?",
                    "a" =>
                        "Hasil bertahap terlihat setelah 4-5 sesi, optimal setelah 10-15 sesi.",
                ],
                [
                    "q" => "Bisa untuk stretch marks?",
                    "a" =>
                        "Ya, treatment ini juga efektif untuk mengurangi tampilan stretch marks.",
                ],
            ],
            "price_range" => "Rp 400.000 - Rp 700.000",
            "duration" => "90 menit",
        ];
    }
}
