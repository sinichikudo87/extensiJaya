<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UP Work Langgeng Consultant </title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        #splash-screen {
            transition: opacity 0.8s ease-in-out, visibility 0.8s;
        }
        .fade-out { opacity: 0; visibility: hidden; }

        /* Style untuk serpihan kristal */
        .shard {
            position: absolute;
            pointer-events: none;
            z-index: 60;
        }
    </style>
</head>
<body class="bg-slate-950 overflow-hidden">

    <div id="splash-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gradient-to-tr from-slate-950 via-blue-900 to-emerald-900">
        
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-emerald-500/10 rounded-full blur-[120px]"></div>

        <div id="crystal-box" class="relative mb-8 animate-float transition-all duration-300">
            <div id="main-icon" class="bg-white/10 backdrop-blur-2xl p-7 rounded-[2.5rem] border border-white/20 shadow-2xl relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div id="shards-container" class="absolute inset-0 flex items-center justify-center"></div>
        </div>

        <div id="text-wrapper" class="text-center mb-10 transition-all duration-500">
            <h1 class="text-white text-3xl md:text-4xl font-light tracking-[0.3em] uppercase">
                Up Work <span class="font-bold text-sky-400">Langgeng</span> Consultant 
            </h1>
            <div class="h-[2px] w-32 bg-gradient-to-r from-transparent via-sky-500/50 to-transparent mx-auto mt-4 mb-2"></div>
            <p class="text-sky-200/60 text-[10px] md:text-xs tracking-[0.6em] uppercase font-bold">
                Enviromental and Engineering Consultant
            </p>
        </div>
        
        <div id="progress-wrapper" class="relative w-64 h-[2px] bg-white/10 rounded-full overflow-hidden">
            <div id="progress-bar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-400 to-emerald-400 transition-all duration-300 shadow-[0_0_15px_rgba(52,211,153,0.5)]" style="width: 0%"></div>
        </div>

    </div>

    <script>
        const bar = document.getElementById('progress-bar');
        const splash = document.getElementById('splash-screen');
        const mainIcon = document.getElementById('main-icon');
        const shardsContainer = document.getElementById('shards-container');
        const textWrapper = document.getElementById('text-wrapper');
        const progressWrapper = document.getElementById('progress-wrapper');
        
        let progress = 0;

        function explode() {
            const shardCount = 20; // Jumlah pecahan
            const colors = ['#6EE7B7', '#34D399', '#3B82F6', '#FFFFFF'];

            for (let i = 0; i < shardCount; i++) {
                const shard = document.createElement('div');
                shard.className = 'shard';
                
                // Ukuran acak
                const size = Math.random() * 40 + 10;
                shard.style.width = `${size}px`;
                shard.style.height = `${size}px`;
                
                // Bentuk segitiga/pecahan kristal acak
                const p1 = Math.random() * 100;
                const p2 = Math.random() * 100;
                const p3 = Math.random() * 100;
                shard.style.clipPath = `polygon(${p1}% 0%, 100% ${p2}%, 0% ${p3}%)`;
                shard.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                shard.style.opacity = '0.8';
                shard.style.filter = 'blur(0.5px)';

                shardsContainer.appendChild(shard);

                // Kalkulasi arah ledakan
                const angle = Math.random() * Math.PI * 2;
                const distance = 150 + Math.random() * 250;
                const destX = Math.cos(angle) * distance;
                const destY = Math.sin(angle) * distance;
                const rotation = Math.random() * 1000 - 500;

                // Animasi GSAP style menggunakan Web Animations API
                shard.animate([
                    { transform: 'translate(0, 0) rotate(0deg) scale(1)', opacity: 1 },
                    { transform: `translate(${destX}px, ${destY}px) rotate(${rotation}deg) scale(0)`, opacity: 0 }
                ], {
                    duration: 1200,
                    easing: 'cubic-bezier(0.1, 0.8, 0.3, 1)',
                    fill: 'forwards'
                });
            }

            // Efek guncangan layar
            splash.animate([
                { transform: 'translate(0,0)' },
                { transform: 'translate(-8px, 8px)' },
                { transform: 'translate(8px, -8px)' },
                { transform: 'translate(0,0)' }
            ], { duration: 150 });
        }

        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 100) progress = 100;
            bar.style.width = progress + '%';

            if (progress === 100) {
                clearInterval(interval);
                
                setTimeout(() => {
                    // Jalankan ledakan
                    explode();
                    
                    // Sembunyikan elemen utama secara instan
                    mainIcon.style.opacity = '0';
                    mainIcon.style.transform = 'scale(1.5)';
                    textWrapper.style.opacity = '0';
                    textWrapper.style.transform = 'translateY(20px)';
                    progressWrapper.style.opacity = '0';
                    setTimeout(() => {
                        splash.classList.add('fade-out');
                        setTimeout(() => {
                            window.location.href = "{{ route('login') }}";
                        }, 800);
                    }, 800);
                }, 500);
            }
        }, 250);
    </script>
</body>
</html>