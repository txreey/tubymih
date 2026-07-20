<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tuangeun by Mimih</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center 
             bg-gradient-to-br from-[#BDfBFE] to-[#4a7c59]">

    <div class="w-[900px] h-[500px] bg-[#ffffff] 
                rounded-[30px] border-2 border-green-700 
                overflow-hidden flex relative shadow-2xl">

        <!-- SHAPE LENGKUNG KIRI -->
        <div class="absolute -left-48 -top-16 w-[600px] h-[600px] 
                    bg-gradient-to-br from-[#4a7c59] to-[#7fb28d] 
                    rounded-full">
        </div>

        <!-- KIRI LOGO -->
        <div class="w-1/2 flex items-center justify-center relative z-10">
            <div class="w-60 h-60 rounded-full overflow-hidden">
                <img src="{{ asset('images/Logo.jpg') }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://via.placeholder.com/240x240/f9caca/4a7c59?text=TM'">
            </div>
        </div>

        <!-- KANAN FORM -->
        <div class="w-1/2 flex items-center justify-center px-16 relative z-10">

            <div class="w-full max-w-sm">

                <h1 class="text-4xl font-bold text-green-700 mb-2 font-serif">
                    Selamat<br>Datang
                </h1>

                <p class="text-gray-600 mb-8">
                    Silahkan login untuk melanjutkan
                </p>

                @if ($errors->has('login'))
                    <div class="bg-red-100 text-red-600 text-sm rounded-lg p-2 mb-4 text-center">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="text-sm text-gray-700">Username</label>
                        <input type="text" name="username"
                               class="w-full mt-2 px-4 py-3 
                                      rounded-xl bg-gray-200 
                                      focus:outline-none 
                                      focus:ring-2 focus:ring-green-700">
                    </div>

                    <div class="mb-8">
                        <label class="text-sm text-gray-700">Password</label>
                        <input type="password" name="password"
                               class="w-full mt-2 px-4 py-3 
                                      rounded-xl bg-gray-200 
                                      focus:outline-none 
                                      focus:ring-2 focus:ring-green-700">
                    </div>

                    

                    <div class="flex gap-6">
                        <button type="submit"
                                class="bg-green-700 hover:bg-green-800 
                                       text-white px-8 py-2 
                                       rounded-full transition">
                            Masuk
                        </button>

                        {{-- <a href="{{ route('landing') }}"
                           class="bg-red-500 hover:bg-red-600 
                                  text-white px-8 py-2 
                                  rounded-full transition text-center">
                            Batal
                        </a> --}}
                    </div>
                </form>

            </div>

        </div>

    </div>

</body>
</html>