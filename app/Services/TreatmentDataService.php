<?php
// app/Services/TreatmentDataService.php

namespace App\Services;

class TreatmentDataService
{
    public static function getAllTreatments()
    {
        return [
            // KATEGORI 1: FACIAL
            "facial" => [
                "facial-only" => self::getFacialOnly(),
                "facial-premium" => self::getFacialPremium(),
                "facial-vitc" => self::getFacialVitC(),
            ],
            // KATEGORI 2: PEELING (Menggantikan Body Spa)
            "peeling" => [
                "peeling-chemical" => self::getPeelingChemical(),
                "peeling-acne" => self::getPeelingAcne(),
                "peeling-korea" => self::getPeelingKorea(),
                "peeling-madu" => self::getPeelingMadu(),
                "peeling-flek" => self::getPeelingFlek(),
            ],
            // KATEGORI 3: LASER
            "laser" => [
                "ipl-rejuve" => self::getIPLRejuve(),
                "ipl-acne" => self::getIPLAcne(),
                "hr-underarm" => self::getHRUnderarm(),
                "laser-dark-lip" => self::getLaserDarkLip(),
                "laser-karbon" => self::getLaserKarbon(),
                "laser-flek" => self::getLaserFlek(),
                "laser-tato" => self::getLaserTato(),
            ],
        ];
    }

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

    public static function getTreatmentsByCategory($category)
    {
        $allTreatments = self::getAllTreatments();
        return $allTreatments[$category] ?? [];
    }

    // ==================== 1. FACIAL TREATMENTS ====================

    private static function getFacialOnly()
    {
        return [
            "slug" => "facial-only",
            "title" => "Facial Only",
            "category" => "Facial",
            "short_description" => "Perawatan dasar untuk membersihkan wajah secara menyeluruh",
            "description" => "Perawatan facial dasar yang fokus pada pembersihan mendalam (deep cleansing), massage wajah, dan ekstraksi komedo ringan.",
            "long_description" => "Facial Only adalah langkah awal yang sempurna untuk menjaga kebersihan kulit. Mencakup pembersihan ganda, scrub ringan, pemijatan relaksasi, pengambilan komedo, dan masker penenang.",
            "image" => asset("images/treatment/facial-acne.png"), // Ganti dengan foto facial
            "tags" => ["Facial", "Basic", "Cleansing"],
            "benefits" => ["Membersihkan pori-pori", "Relaksasi otot wajah", "Kulit lebih segar"],
            "process" => [
                ["step" => "1", "title" => "Cleansing", "desc" => "Membersihkan wajah dari kotoran"],
                ["step" => "2", "title" => "Massage", "desc" => "Pijat wajah untuk melancarkan sirkulasi"],
                ["step" => "3", "title" => "Masker", "desc" => "Masker wajah sesuai jenis kulit"],
            ],
            "faq" => [
                ["q" => "Berapa lama durasinya?", "a" => "Sekitar 45-60 menit."],
            ],
            "price_range" => "Rp 99.000",
            "duration" => "60 menit",
        ];
    }

    private static function getFacialPremium()
    {
        return [
            "slug" => "facial-premium",
            "title" => "Facial Premium",
            "category" => "Facial",
            "short_description" => "Facial lengkap dengan serum dan teknologi HF",
            "description" => "Upgrade dari facial biasa dengan penambahan serum premium dan penggunaan alat High Frequency untuk sterilisasi.",
            "long_description" => "Facial Premium memberikan perawatan lebih intensif dengan tambahan serum vitamin dan alat khusus untuk memastikan nutrisi menyerap sempurna ke dalam kulit.",
            "image" => asset("images/treatment/facial-premium.png"), // Ganti dengan foto facial
            "tags" => ["Facial", "Premium", "Nutrisi"],
            "benefits" => ["Nutrisi lebih maksimal", "Mencegah jerawat", "Kulit lebih kenyal"],
            "process" => [
                ["step" => "1", "title" => "Deep Cleanse", "desc" => "Pembersihan mendalam"],
                ["step" => "2", "title" => "Serum Infusion", "desc" => "Memasukkan serum dengan alat"],
                ["step" => "3", "title" => "Premium Mask", "desc" => "Masker peel off premium"],
            ],
            "faq" => [
                ["q" => "Apa bedanya dengan facial biasa?", "a" => "Menggunakan serum kualitas premium dan alat penunjang."],
            ],
            "price_range" => "Rp 299.000",
            "duration" => "75 menit",
        ];
    }

    private static function getFacialVitC()
    {
        return [
            "slug" => "facial-vitc",
            "title" => "Facial Vit C",
            "category" => "Facial",
            "short_description" => "Mencerahkan wajah kusam dengan Vitamin C murni",
            "description" => "Facial yang diformulasikan khusus untuk mencerahkan kulit kusam dan memudarkan bekas jerawat menggunakan Vitamin C konsentrasi tinggi.",
            "long_description" => "Kombinasi teknik facial dengan infus Vitamin C murni yang efektif sebagai antioksidan, mencerahkan warna kulit, dan meningkatkan produksi kolagen.",
            "image" => asset("images/treatment/facial-vitc.png"),
            "tags" => ["Facial", "Brightening", "Vitamin C"],
            "benefits" => ["Mencerahkan kulit instan", "Antioksidan tinggi", "Memudarkan noda hitam"],
            "process" => [
                ["step" => "1", "title" => "Cleansing", "desc" => "Persiapan kulit"],
                ["step" => "2", "title" => "Vit C Infusion", "desc" => "Aplikasi serum Vit C murni"],
                ["step" => "3", "title" => "Brightening Mask", "desc" => "Masker pencerah"],
            ],
            "faq" => [
                ["q" => "Apakah perih?", "a" => "Mungkin ada sensasi cekit-cekit sedikit karena Vit C aktif, tapi aman."],
            ],
            "price_range" => "Rp 399.000",
            "duration" => "75 menit",
        ];
    }

    // ==================== 2. PEELING TREATMENTS ====================

    private static function getPeelingChemical()
    {
        return [
            "slug" => "peeling-chemical",
            "title" => "Peeling Chemical",
            "category" => "Peeling",
            "short_description" => "Mengangkat sel kulit mati dengan cairan khusus",
            "description" => "Eksfoliasi kulit menggunakan larutan kimia aman untuk memperbaiki tekstur kulit dan mengangkat sel kulit mati.",
            "long_description" => "Chemical peeling membantu regenerasi kulit dengan cara mengelupaskan lapisan kulit terluar yang rusak, merangsang pertumbuhan kulit baru yang lebih halus.",
            "image" => asset("images/treatment/peeling-chamical.png"),
            "tags" => ["Peeling", "Exfoliation", "Resurfacing"],
            "benefits" => ["Kulit lebih halus", "Mengurangi kerutan halus", "Mencerahkan"],
            "process" => [
                ["step" => "1", "title" => "Prep", "desc" => "Pembersihan wajah"],
                ["step" => "2", "title" => "Peel App", "desc" => "Pengolesan cairan peeling"],
                ["step" => "3", "title" => "Neutralize", "desc" => "Menetralkan cairan"],
            ],
            "faq" => [
                ["q" => "Apakah mengelupas?", "a" => "Ya, akan terjadi pengelupasan ringan 3-5 hari setelahnya."],
            ],
            "price_range" => "Rp 199.000",
            "duration" => "30 menit",
        ];
    }

    private static function getPeelingAcne()
    {
        return [
            "slug" => "peeling-acne",
            "title" => "Peeling Acne",
            "category" => "Peeling",
            "short_description" => "Mengeringkan jerawat dan mengurangi minyak",
            "description" => "Peeling khusus dengan Salicylic Acid untuk mengatasi jerawat meradang dan mengontrol minyak berlebih.",
            "long_description" => "Sangat efektif untuk mematikan bakteri jerawat, membuka pori-pori yang tersumbat, dan mengurangi peradangan pada jerawat aktif.",
            "image" => asset("images/treatment/peeling-acne.png"),
            "tags" => ["Peeling", "Acne", "Oil Control"],
            "benefits" => ["Mengeringkan jerawat", "Mengontrol minyak", "Membersihkan komedo"],
            "process" => [
                ["step" => "1", "title" => "Cleansing", "desc" => "Pembersihan"],
                ["step" => "2", "title" => "Acne Peel", "desc" => "Aplikasi peeling BHA/Salicylic"],
                ["step" => "3", "title" => "Soothing", "desc" => "Pendinginan kulit"],
            ],
            "faq" => [
                ["q" => "Sakit tidak?", "a" => "Terasa sedikit perih/gatal saat aplikasi, itu normal."],
            ],
            "price_range" => "Rp 259.000",
            "duration" => "30 menit",
        ];
    }

    private static function getPeelingKorea()
    {
        return [
            "slug" => "peeling-korea",
            "title" => "Peeling Korea",
            "category" => "Peeling",
            "short_description" => "Rahasia kulit glass skin ala Korea",
            "description" => "Peeling inovasi terbaru untuk hasil kulit glowing, halus, dan bening seperti kaca (Glass Skin).",
            "long_description" => "Menggunakan formulasi peeling yang lembut namun efektif mengangkat sel kulit mati sekaligus memberikan hidrasi, sehingga kulit langsung terlihat glowing tanpa pengelupasan ekstrim.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Peeling", "Glass Skin", "Glowing"],
            "benefits" => ["Efek Glass Skin", "Pori-pori samar", "Tidak merah berlebihan"],
            "process" => [
                ["step" => "1", "title" => "Double Cleanse", "desc" => "Pembersihan maksimal"],
                ["step" => "2", "title" => "Korean Peel", "desc" => "Aplikasi peeling layer"],
                ["step" => "3", "title" => "Hydrating", "desc" => "Masker hidrasi"],
            ],
            "faq" => [
                ["q" => "Apakah ada downtime?", "a" => "Minim downtime, kulit langsung terlihat segar."],
            ],
            "price_range" => "Rp 499.000",
            "duration" => "45 menit",
        ];
    }

    private static function getPeelingMadu()
    {
        return [
            "slug" => "peeling-madu",
            "title" => "Peeling Madu",
            "category" => "Peeling",
            "short_description" => "Peeling alami untuk kulit sensitif dan kering",
            "description" => "Menggunakan ekstrak fermentasi madu untuk eksfoliasi yang sangat lembut sekaligus melembabkan.",
            "long_description" => "Cocok untuk kulit yang tidak tahan peeling kimia keras. Madu memiliki sifat antibakteri dan humektan alami yang menjaga kelembaban kulit.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Peeling", "Natural", "Sensitive Skin"],
            "benefits" => ["Melembabkan", "Mencerahkan lembut", "Aman untuk kulit sensitif"],
            "process" => [
                ["step" => "1", "title" => "Cleanse", "desc" => "Pembersihan"],
                ["step" => "2", "title" => "Honey Peel", "desc" => "Oles peeling madu"],
                ["step" => "3", "title" => "Massage", "desc" => "Pijatan ringan"],
            ],
            "faq" => [
                ["q" => "Cocok untuk ibu hamil?", "a" => "Ya, karena menggunakan bahan natural (konsultasikan dulu)."],
            ],
            "price_range" => "Rp 549.000",
            "duration" => "60 menit",
        ];
    }

    private static function getPeelingFlek()
    {
        return [
            "slug" => "peeling-flek",
            "title" => "Peeling Flek",
            "category" => "Peeling",
            "short_description" => "Memudarkan flek hitam membandel",
            "description" => "Peeling konsentrasi khusus (TCA/Glycolic) untuk menargetkan pigmentasi dan noda hitam.",
            "long_description" => "Bekerja pada lapisan kulit yang lebih dalam untuk memecah melanin yang menumpuk, efektif untuk melasma dan bekas jerawat menghitam.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Peeling", "Flek", "Melasma"],
            "benefits" => ["Memudarkan flek", "Meratakan warna kulit", "Peremajaan kulit"],
            "process" => [
                ["step" => "1", "title" => "Analisa", "desc" => "Cek area flek"],
                ["step" => "2", "title" => "Spot Peel", "desc" => "Aplikasi di area flek"],
                ["step" => "3", "title" => "Full Peel", "desc" => "Aplikasi seluruh wajah"],
            ],
            "faq" => [
                ["q" => "Berapa kali harus peeling?", "a" => "Disarankan paket 3-5 kali untuk hasil maksimal."],
            ],
            "price_range" => "Rp 400.000",
            "duration" => "45 menit",
        ];
    }

    // ==================== 3. LASER TREATMENTS ====================

    private static function getIPLRejuve()
    {
        return [
            "slug" => "ipl-rejuve",
            "title" => "IPL Rejuve",
            "category" => "Laser",
            "short_description" => "Peremajaan kulit dengan teknologi cahaya",
            "description" => "Intense Pulsed Light (IPL) untuk merangsang kolagen dan mencerahkan wajah.",
            "long_description" => "Perawatan non-invasif menggunakan cahaya spektrum luas untuk memperbaiki tekstur kulit, mengecilkan pori, dan memberikan efek cerah.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Laser", "IPL", "Rejuve"],
            "benefits" => ["Mencerahkan", "Kenyal", "Pori kecil"],
            "process" => [
                ["step" => "1", "title" => "Gel", "desc" => "Aplikasi gel dingin"],
                ["step" => "2", "title" => "IPL", "desc" => "Penyinaran"],
                ["step" => "3", "title" => "Masker", "desc" => "Masker"],
            ],
            "faq" => [
                ["q" => "Sakit?", "a" => "Hanya hangat sedikit."],
            ],
            "price_range" => "Rp 299.000",
            "duration" => "30 menit",
        ];
    }

    private static function getIPLAcne()
    {
        return [
            "slug" => "ipl-acne",
            "title" => "IPL Acne",
            "category" => "Laser",
            "short_description" => "Membunuh bakteri jerawat dengan cahaya",
            "description" => "Menggunakan filter cahaya khusus IPL yang menargetkan bakteri P. Acnes penyebab jerawat.",
            "long_description" => "Sangat efektif untuk jerawat meradang. Cahaya IPL akan memanaskan kelenjar sebaceous untuk mengurangi produksi minyak dan membunuh kuman.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Laser", "IPL", "Acne"],
            "benefits" => ["Keringkan jerawat", "Bunuh bakteri", "Kurangi merah"],
            "process" => [
                ["step" => "1", "title" => "Cleanse", "desc" => "Bersihkan wajah"],
                ["step" => "2", "title" => "IPL Shot", "desc" => "Tembak di area jerawat"],
                ["step" => "3", "title" => "Calming", "desc" => "Masker dingin"],
            ],
            "faq" => [
                ["q" => "Berapa sering?", "a" => "Seminggu sekali saat jerawat parah."],
            ],
            "price_range" => "Rp 299.000",
            "duration" => "30 menit",
        ];
    }

    private static function getHRUnderarm()
    {
        return [
            "slug" => "hr-under-arm",
            "title" => "HR Under Arm",
            "category" => "Laser",
            "short_description" => "Hair Removal ketiak permanen",
            "description" => "Menghilangkan bulu ketiak secara permanen menggunakan teknologi IPL/Laser.",
            "long_description" => "Mematikan akar rambut sehingga bulu tumbuh makin halus dan akhirnya hilang permanen. Ketiak juga jadi lebih cerah.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Laser", "Hair Removal", "Ketiak"],
            "benefits" => ["Bebas bulu", "Ketiak cerah", "Tidak sakit"],
            "process" => [
                ["step" => "1", "title" => "Shaving", "desc" => "Cukur area"],
                ["step" => "2", "title" => "Gel", "desc" => "Gel dingin"],
                ["step" => "3", "title" => "Laser", "desc" => "Penyinaran"],
            ],
            "faq" => [
                ["q" => "Berapa sesi?", "a" => "8-10 sesi untuk hasil permanen."],
            ],
            "price_range" => "Rp 299.000",
            "duration" => "15 menit",
        ];
    }

    private static function getLaserDarkLip()
    {
        return [
            "slug" => "laser-dark-lip",
            "title" => "Laser Dark Lip",
            "category" => "Laser",
            "short_description" => "Mencerahkan bibir gelap",
            "description" => "Laser khusus untuk memecah pigmen gelap pada bibir agar kembali merah alami.",
            "long_description" => "Solusi bagi perokok atau faktor genetik yang memiliki bibir hitam. Laser Q-switch ND yag memecah melanin di bibir.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Laser", "Lips", "Pink Lips"],
            "benefits" => ["Bibir cerah alami", "Mengurangi hitam", "Pink natural"],
            "process" => [
                ["step" => "1", "title" => "Anastesi", "desc" => "Krim kebal di bibir"],
                ["step" => "2", "title" => "Laser", "desc" => "Tembak laser"],
                ["step" => "3", "title" => "Moist", "desc" => "Pelembab bibir"],
            ],
            "faq" => [
                ["q" => "Apakah bengkak?", "a" => "Sedikit bengkak 1-2 hari wajar."],
            ],
            "price_range" => "Rp 244.000",
            "duration" => "30 menit",
        ];
    }

    private static function getLaserKarbon()
    {
        return [
            "slug" => "laser-karbon",
            "title" => "Laser Karbon",
            "category" => "Laser",
            "short_description" => "Black Doll Laser untuk pori dan cerah",
            "description" => "Menggunakan masker karbon hitam yang ditembak laser untuk mengangkat komedo, minyak, dan sel kulit mati.",
            "long_description" => "Sangat populer untuk mengecilkan pori-pori dan membuat wajah instan glowing dan halus seketika.",
            "image" => asset("images/produk1.jpeg"),
            "tags" => ["Laser", "Karbon", "Pori Besar"],
            "benefits" => ["Pori kecil", "Minyak berkurang", "Wajah halus"],
            "process" => [
                ["step" => "1", "title" => "Carbon", "desc" => "Oles masker karbon"],
                ["step" => "2", "title" => "Laser", "desc" => "Laser memecah karbon"],
                ["step" => "3", "title" => "Clean", "desc" => "Bersihkan sisa"],
            ],
            "faq" => [
                ["q" => "Ada downtime?", "a" => "Tidak ada, langsung bisa aktivitas."],
            ],
            "price_range" => "Rp 499.000",
            "duration" => "45 menit",
        ];
    }

    private static function getLaserFlek()
    {
        return [
            "slug" => "laser-flek",
            "title" => "Laser Flek",
            "category" => "Laser",
            "short_description" => "Menghilangkan flek hitam dengan akurat",
            "description" => "Laser dengan panjang gelombang spesifik untuk menghancurkan pigmen melanin penyebab flek.",
            "long_description" => "Laser Q-Switch Nd:YAG menargetkan noda hitam tanpa merusak kulit sekitarnya, flek akan mengering dan lepas sendirinya.",
            "image" => asset("images/produk2.jpeg"),
            "tags" => ["Laser", "Flek", "Melasma"],
            "benefits" => ["Flek pudar", "Wajah bersih", "Rata warna kulit"],
            "process" => [
                ["step" => "1", "title" => "Anastesi", "desc" => "Krim kebal"],
                ["step" => "2", "title" => "Laser", "desc" => "Tembak titik flek"],
                ["step" => "3", "title" => "Soothing", "desc" => "Anti iritasi"],
            ],
            "faq" => [
                ["q" => "Berapa kali?", "a" => "Tergantung kedalaman, biasanya 3-5x."],
            ],
            "price_range" => "Rp 599.000",
            "duration" => "45 menit",
        ];
    }

    private static function getLaserTato()
    {
        return [
            "slug" => "laser-tato",
            "title" => "Laser Tato",
            "category" => "Laser",
            "short_description" => "Hapus tato permanen",
            "description" => "Menghapus gambar tato permanen atau sulam alis yang gagal.",
            "long_description" => "Memecah partikel tinta tato menjadi butiran mikro yang akan dibuang oleh sistem imun tubuh.",
            "image" => asset("images/produk3.jpeg"),
            "tags" => ["Laser", "Tato", "Removal"],
            "benefits" => ["Tato hilang", "Aman", "Minim bekas"],
            "process" => [
                ["step" => "1", "title" => "Numbing", "desc" => "Anastesi 45 menit"],
                ["step" => "2", "title" => "Laser", "desc" => "Laser penghancur tinta"],
                ["step" => "3", "title" => "Cooling", "desc" => "Kompres dingin"],
            ],
            "faq" => [
                ["q" => "Sakit?", "a" => "Lumayan terasa, tapi ada anastesi."],
            ],
            "price_range" => "Rp 500.000",
            "duration" => "30-60 menit",
        ];
    }
}