<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuangeun by Mimih - Rumah Makan Khas Sunda Autentik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding-bottom: 120px;
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 100px;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        .hero-bg {
            background: linear-gradient(135deg, #d1e8e2 0%, #a7d7c5 50%, #81c3b0 100%);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- ================= NAVBAR ================= -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-lg shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo.jpg') }}"
                    class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover shadow-md" alt="Logo Tuangeun by Mimih">
                <h1 class="text-xl md:text-2xl font-bold text-emerald-700">Tuangeun by Mimih</h1>
            </div>

            <ul class="hidden md:flex items-center gap-8 font-medium text-gray-700">
                <li><a href="#beranda" class="hover:text-emerald-600 transition">Beranda</a></li>
                <li><a href="#menu" class="hover:text-emerald-600 transition">Menu</a></li>
                <li><a href="#tentang" class="hover:text-emerald-600 transition">Tentang</a></li>
                <li><a href="#tentang" class="hover:text-emerald-600 transition">Kontak</a></li>
                <!-- Tetap ada, scroll ke section yang sama -->
                <li><a href="#lokasi" class="hover:text-emerald-600 transition">Lokasi</a></li>
            </ul>

            <a href="{{ route('login') }}"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-full font-medium shadow-md hover:shadow-lg transition duration-300 transform hover:scale-105">
                Login
            </a>
        </div>
    </nav>

    <!-- ================= HERO (DITAMBAH TAGLINE) ================= -->
    <section id="beranda" class="relative min-h-screen hero-bg pt-32 md:pt-40 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 bg-amber-300 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-lg md:text-xl text-emerald-700 font-medium">Hoyong Tuang??</p>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 mt-3 leading-tight">
                    Tong Hilap!!
                </h1>
                <p class="mt-6 text-xl md:text-2xl text-gray-800 leading-relaxed">
                    Mampir ka rumah makan <br>
                    <span class="font-bold text-emerald-700">Tuangeun by Mimih</span> ayeuna keneh!
                </p>

                <!-- Tagline baru -->
                <p class="mt-8 text-2xl md:text-3xl font-semibold text-emerald-800 italic">
                    "Cita Rasa Autentik Sunda, Harga Ramah Kantong, Bikin Kangen Rumah!"
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="#menu"
                        class="bg-emerald-600 text-white px-8 py-4 rounded-full font-semibold shadow-lg hover:bg-emerald-700 transition transform hover:scale-105">
                        Lihat Menu
                    </a>
                    <a href="#tentang"
                        class="bg-white text-emerald-700 border-2 border-emerald-600 px-8 py-4 rounded-full font-semibold hover:bg-emerald-50 transition">
                        Tentang & Kontak
                    </a>
                </div>
            </div>

            <div class="relative">
                <img src="{{ asset('images/Liwet.jpg') }}"
                    class="w-full max-w-lg mx-auto rounded-3xl shadow-2xl object-cover transform rotate-3 hover:rotate-0 transition duration-500"
                    alt="Nasi Liwet Khas Sunda">
                <img src="{{ asset('images/Logo.jpg') }}"
                    class="absolute -bottom-12 -right-12 w-48 md:w-64 rounded-2xl shadow-xl border-8 border-white object-cover"
                    alt="Logo">
            </div>
        </div>
    </section>

    <!-- ================= MENU (8 CARD DUMMY) ================= -->
    <section id="menu" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-emerald-700 mb-4">Menu Favorit</h2>
            <p class="text-gray-600 text-lg md:text-xl mb-12 max-w-2xl mx-auto">Pilihan hidangan khas Sunda autentik
                dengan cita rasa rumahan yang bikin kangen</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- 6 Makanan -->
                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/29/600/400" class="w-full h-56 object-cover" alt="Nasi Liwet">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Nasi Liwet</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 25.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/201/600/400" class="w-full h-56 object-cover" alt="Ayam Goreng">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Ayam Goreng</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 18.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/318/600/400" class="w-full h-56 object-cover" alt="Ikan Bakar">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Ikan Bakar</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 35.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/251/600/400" class="w-full h-56 object-cover" alt="Sayur Asem">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Sayur Asem</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 12.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/292/600/400" class="w-full h-56 object-cover" alt="Karedok">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Karedok</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 15.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/1015/600/400" class="w-full h-56 object-cover"
                        alt="Sate Maranggi">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Makanan</span>
                        <h3 class="font-semibold text-xl mt-3">Sate Maranggi</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 22.000</p>
                    </div>
                </div>

                <!-- 2 Minuman -->
                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/106/600/400" class="w-full h-56 object-cover"
                        alt="Es Teh Manis">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-full">Minuman</span>
                        <h3 class="font-semibold text-xl mt-3">Es Teh Manis</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 8.000</p>
                    </div>
                </div>

                <div
                    class="group bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <img src="https://picsum.photos/id/160/600/400" class="w-full h-56 object-cover"
                        alt="Wedang Jahe">
                    <div class="p-6">
                        <span
                            class="inline-block text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-full">Minuman</span>
                        <h3 class="font-semibold text-xl mt-3">Wedang Jahe</h3>
                        <p class="text-emerald-600 font-bold text-2xl mt-1">Rp 10.000</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TENTANG + KONTAK (Tentang Kiri, Kontak Kanan) ================= -->
    <section id="tentang" class="py-20 md:py-28 bg-gradient-to-br from-emerald-50 to-teal-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-bold text-emerald-700 mb-12 text-center">Tentang Kami & Kontak</h2>

            <div class="grid md:grid-cols-2 gap-12 items-start">
                <!-- Kiri: Tentang Kami -->
                <div class="space-y-8">
                    <div class="relative">
                        <img src="{{ asset('images/Liwet.jpg') }}" class="rounded-3xl shadow-2xl w-full object-cover"
                            alt="Suasana Rumah Makan">
                        <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-xl p-6 max-w-[220px]">
                            <p class="text-sm text-emerald-600 font-medium">Sejak 2022</p>
                            <p class="text-4xl font-bold text-emerald-700">100K+</p>
                            <p class="text-gray-600">Pelanggan Puas</p>
                        </div>
                    </div>

                    <p class="text-lg md:text-xl text-gray-700 leading-relaxed">
                        Tuangeun by Mimih adalah rumah makan khas Sunda yang menyajikan hidangan autentik dengan suasana
                        nyaman dan harga terjangkau.
                        Berawal dari produk makanan ringan di Instagram, kini kami bangga menyajikan cita rasa asli
                        Sunda untuk keluarga Anda.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl p-6 shadow">
                            <span class="text-3xl">🍲</span>
                            <h4 class="font-semibold mt-3">Bahan Segar</h4>
                            <p class="text-gray-600 text-sm">Setiap hari bahan pilihan langsung dari petani lokal</p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow">
                            <span class="text-3xl">🏠</span>
                            <h4 class="font-semibold mt-3">Suasana Rumah</h4>
                            <p class="text-gray-600 text-sm">Makan seperti di rumah sendiri, ramah & hangat</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Kontak Elegan -->
                <div class="bg-white rounded-3xl shadow-2xl p-10 border border-emerald-100">
                    <h3 class="text-3xl font-semibold text-emerald-700 mb-8 text-center">Hubungi Kami</h3>
                    <div class="space-y-8 text-lg">
                        <div class="flex items-center gap-5">
                            <span class="text-5xl">📍</span>
                            <p class="font-medium text-gray-800">Subang, Jawa Barat</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <span class="text-5xl">📞</span>
                            <p class="font-medium text-gray-800">0812-3456-7890</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <span class="text-5xl">📸</span>
                            <a href="https://instagram.com/tuangeun_by_mimih" target="_blank"
                                class="font-medium text-emerald-600 hover:underline">@tuangeun_by_mimih</a>
                        </div>
                        <div class="flex items-center gap-5">
                            <span class="text-5xl">📧</span>
                            <a href="mailto:tuangeunbymimih@gmail.com"
                                class="font-medium text-emerald-600 hover:underline">tuangeunbymimih@gmail.com</a>
                        </div>
                    </div>

                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="mt-10 block text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-4 rounded-2xl shadow-lg transition transform hover:scale-105">
                        Chat WhatsApp Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= LOKASI ================= -->
    <section id="lokasi" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-bold text-emerald-700 mb-12 text-center">Lokasi Kami</h2>

            <div class="grid lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-7 rounded-3xl overflow-hidden shadow-2xl border border-gray-100">
                    <iframe class="w-full h-[520px]" loading="lazy" allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15839.000000000000!2d107.75000000000000!3d-6.56670000000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e691e353e7c1d0d%3A0x0!2sSubang%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1730000000000!5m2!1sid!2sid">
                    </iframe>
                </div>

                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-emerald-100">
                        <h3 class="font-semibold text-2xl text-emerald-700 mb-4 flex items-center gap-3">
                            📍 Alamat Lengkap
                        </h3>
                        <p class="text-gray-700 leading-relaxed">
                            Jl. Raya Subang No. 45, Kec. Subang, Kabupaten Subang, Jawa Barat 41211<br>
                            <span class="text-xs text-emerald-500">(Depan Pasar Tradisional Subang)</span>
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-emerald-100">
                        <h3 class="font-semibold text-2xl text-emerald-700 mb-4 flex items-center gap-3">
                            ⏰ Jam Operasional
                        </h3>
                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between">
                                <span>Senin - Sabtu</span>
                                <span class="font-medium">08.00 - 21.00 WIB</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Minggu & Hari Libur</span>
                                <span class="font-medium">07.30 - 22.00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER FIXED ================= -->
    <footer class="fixed bottom-0 left-0 right-0 z-40 bg-emerald-800 text-white py-5 shadow-2xl">
        <div
            class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo.jpg') }}" class="w-10 h-10 rounded-full object-cover" alt="Logo">
                <p class="text-lg font-medium">Tuangeun by Mimih</p>
            </div>
            <p class="text-sm md:text-base">&copy; {{ date('Y') }} All rights reserved. Makan kenyang, hati senang!
            </p>
            <div class="flex gap-6">
                <a href="#tentang" class="hover:text-emerald-300 transition">Tentang & Kontak</a>
                <a href="#lokasi" class="hover:text-emerald-300 transition">Lokasi</a>
                <a href="https://instagram.com/tuangeun_by_mimih" target="_blank"
                    class="hover:text-emerald-300 transition">Instagram</a>
            </div>
        </div>
    </footer>

</body>

</html>
