<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akses | UP Work Langgeng Consultant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #020617;
            height: 100vh;
            overflow: hidden;
            position: relative;
            font-family: 'Inter', sans-serif;
        }
        
        .crystal-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shard {
            position: absolute;
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.2), rgba(59, 130, 246, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(2px);
            animation: float-shard linear infinite;
        }

        @keyframes float-shard {
            0% {
                transform: translateY(110vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 0.4;
            }

            90% {
                opacity: 0.4;
            }

            100% {
                transform: translateY(-10vh) rotate(360deg);
                opacity: 0;
            }
        }

        .glass-container {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(52, 211, 153, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            transition: all 0.3s;
        }

        .input-glass:focus {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.05);
            outline: none;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);
        }

        .hidden-form {
            display: none;
            opacity: 0;
            transform: translateY(20px);
        }

        .show-form {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: slideUp 0.5s forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Aksen Glow */
        .glow-emerald {
            box-shadow: 0 0 30px rgba(52, 211, 153, 0.1);
        }
    </style>
</head>

<body class="flex items-center justify-center p-4 bg-gradient-to-tr from-slate-950 via-blue-900 to-emerald-900">

    <div class="crystal-container" id="crystal-container"></div>

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-[360px] z-10">
        <div class="text-center mb-8">
            <div
                class="inline-block p-4 rounded-[2rem] bg-gradient-to-br from-white/10 to-transparent border border-white/10 mb-4 glow-emerald">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <h2 class="text-white text-2xl font-light tracking-[0.2em] uppercase">UP Work <span
                    class="font-extrabold text-emerald-400">Langgeng Consultant</span></h2>
            <p id="system-subtitle"
                class="text-emerald-500/50 text-[10px] uppercase tracking-[0.4em] mt-2 font-semibold">Management System
            </p>
        </div>

        <div id="login-container" class="glass-container p-6 md:p-7 rounded-[1.8rem]">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                @if ($errors->has('login'))
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-[10px] px-4 py-2 rounded-xl text-center">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <div>
                    <label
                        class="block text-[9px] uppercase tracking-widest text-emerald-400/70 font-bold mb-1.5 ml-1">ID
                        Akses</label>
                    <input type="email" name="email" required
                        class="input-glass w-full px-4 py-3 rounded-xl text-xs" placeholder="abc@gmail.com">
                </div>
                <div>
                    <label
                        class="block text-[9px] uppercase tracking-widest text-emerald-400/70 font-bold mb-1.5 ml-1">Kata
                        Sandi</label>
                    <input type="password" name="password" required
                        class="input-glass w-full px-4 py-3 rounded-xl text-xs" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between px-0.5">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" id="remember"
                            class="w-3.5 h-3.5 rounded border-white/10 bg-white/5 text-emerald-500 focus:ring-0">
                        <span class="ml-2 text-[10px] text-slate-400 group-hover:text-slate-200 transition">Ingat
                            saya</span>
                    </label>
                    <button type="button" onclick="switchForm('forgot')"
                        class="text-[10px] text-emerald-500/80 hover:text-emerald-400 transition">Lupa?</button>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-[11px] font-extrabold py-3.5 rounded-xl transition-all active:scale-[0.98] mt-2 uppercase tracking-widest shadow-lg shadow-emerald-500/10">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <div id="forgot-container" class="glass-container p-6 md:p-7 rounded-[1.8rem] hidden-form">
            <form action="" method="POST" class="space-y-4">
                @csrf
                <div class="mb-2">
                    <h3 class="text-white text-sm font-bold">Pemulihan</h3>
                    <p class="text-slate-400 text-[10px] mt-0.5">Input email untuk reset sandi.</p>
                </div>
                <div>
                    <label
                        class="block text-[9px] uppercase tracking-widest text-emerald-400/70 font-bold mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" required
                        class="input-glass w-full px-4 py-3 rounded-xl text-xs" placeholder="nama@hayaatifood.com">
                </div>
                <button type="submit"
                    class="w-full bg-emerald-500 text-slate-950 text-[11px] font-extrabold py-3.5 rounded-xl uppercase tracking-widest">Kirim
                    Tautan</button>
                <button type="button" onclick="switchForm('login')"
                    class="w-full text-slate-500 text-[9px] font-bold uppercase tracking-widest mt-2 hover:text-white transition">Kembali</button>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-slate-600 text-[9px] tracking-[0.3em] uppercase">
                &copy; 2026 <span class="text-emerald-900 font-bold">UP Work Langgeng</span> Consultant</span>
            </p>
        </div>
    </div>

    <script>
        const container = document.getElementById('crystal-container');
        const shardCount = 15;

        for (let i = 0; i < shardCount; i++) {
            const shard = document.createElement('div');
            shard.className = 'shard';

            // Bentuk random (segitiga/poligon)
            const size = Math.random() * 30 + 10;
            const p1 = Math.random() * 100;
            const p2 = Math.random() * 100;

            shard.style.width = size + 'px';
            shard.style.height = size + 'px';
            shard.style.clipPath = `polygon(${p1}% 0%, 100% ${p2}%, 0% 100%)`;

            shard.style.left = Math.random() * 100 + '%';
            shard.style.animationDuration = Math.random() * 5 + 10 + 's';
            shard.style.animationDelay = Math.random() * 5 + 's';

            container.appendChild(shard);
        }

        // Switcher Form dengan Animasi Smooth
        function switchForm(target) {
            const login = document.getElementById('login-container');
            const forgot = document.getElementById('forgot-container');
            const subtitle = document.getElementById('system-subtitle');

            if (target === 'forgot') {
                login.style.opacity = '0';
                login.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    login.style.display = 'none';
                    forgot.style.display = 'block';
                    setTimeout(() => forgot.className = 'glass-container p-8 rounded-[2.5rem] show-form', 10);
                    subtitle.innerText = "Account Recovery";
                    subtitle.classList.add('text-blue-400');
                }, 400);
            } else {
                forgot.style.opacity = '0';
                forgot.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    forgot.style.display = 'none';
                    login.style.display = 'block';
                    setTimeout(() => {
                        login.style.opacity = '1';
                        login.style.transform = 'translateY(0)';
                    }, 10);
                    subtitle.innerText = "Management System";
                    subtitle.classList.remove('text-blue-400');
                }, 400);
            }
        }
    </script>
</body>

</html>
