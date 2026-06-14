<div
    x-data="{
        isMenuOpen:false,

        toggleMenu(){
            this.isMenuOpen = !this.isMenuOpen;

            document.body.classList.toggle(
                'overflow-hidden',
                this.isMenuOpen
            );
        }
    }"
    class="lg:hidden"
>

    {{-- TOP BAR --}}
    <div class="
        fixed top-0 left-0 right-0
        h-16
        px-4

        flex items-center justify-between

        bg-[#060B18]/95
        backdrop-blur-xl

        border-b border-white/5

        z-[60]
    ">

        <div>

            <h1 class="
                text-base
                font-black
                italic
                uppercase
                tracking-tight
                text-white
            ">
                UP Work

                <span class="text-cyan-500">
                    Langgeng
                </span>

            </h1>

        </div>


        {{-- BUTTON --}}
        <button
            @click="toggleMenu"
            class="
                w-10 h-10

                rounded-xl

                border border-cyan-500/20

                bg-slate-900

                text-cyan-400

                flex items-center justify-center
            "
        >

            <svg
                class="w-6 h-6 transition duration-300"
                :class="isMenuOpen ? 'rotate-90' : ''"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >

                <path
                    x-show="!isMenuOpen"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />

                <path
                    x-show="isMenuOpen"
                    x-cloak
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />

            </svg>

        </button>

    </div>


    {{-- BACKDROP --}}
    <div

        x-show="isMenuOpen"
        x-cloak

        x-transition.opacity

        @click="toggleMenu"

        class="
            fixed inset-0

            bg-black/70

            z-[65]
        "

    ></div>


    {{-- MENU --}}
    <div

        x-show="isMenuOpen"
        x-cloak

        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"

        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"

        class="
            fixed

            top-16
            left-0
            right-0
            bottom-0

            bg-[#060B18]

            z-[70]

            overflow-y-auto

            px-4
            py-6
        "

    >

        {{-- USER CARD --}}
        <div class="
            mb-6

            p-4

            rounded-3xl

            bg-white/[0.03]

            border border-white/[0.05]

            flex items-center justify-between
        ">

            <div class="flex items-center gap-3">

                <div class="
                    w-11 h-11

                    rounded-2xl

                    bg-cyan-500/10

                    border border-cyan-500/20

                    flex items-center justify-center
                ">

                    <i class="fas fa-user text-cyan-400"></i>

                </div>

                <div>

                    <p class="
                        text-[10px]

                        uppercase

                        tracking-[0.3em]

                        text-slate-500
                    ">

                        Logged In As

                    </p>

                    <p class="
                        text-sm

                        font-black

                        text-white
                    ">

                        {{ session('user_name') ?? auth()->user()->name }}

                    </p>

                </div>

            </div>


            <a

                href="{{ route('logout') }}"

                onclick="
                    event.preventDefault();

                    document
                        .getElementById('logout-form-mobile')
                        .submit();
                "

                class="
                    w-10 h-10

                    rounded-xl

                    bg-red-500/10

                    border border-red-500/20

                    text-red-400

                    flex items-center justify-center
                "

            >

                <i class="fas fa-sign-out-alt"></i>

            </a>

            <form

                id="logout-form-mobile"

                action="{{ route('logout') }}"

                method="POST"

                class="hidden"

            >

                @csrf

            </form>

        </div>


        {{-- GRID MENU --}}
        @include('components.mobile-menu-content')


        {{-- SUPPORT --}}
        <div class="
            mt-8

            p-4

            rounded-3xl

            bg-cyan-500/5

            border border-cyan-500/10

            flex items-center justify-between
        ">

            <div class="flex items-center gap-3">

                <i class="fas fa-headset text-cyan-400"></i>

                <div>

                    <div class="
                        text-[10px]

                        uppercase

                        font-bold

                        text-cyan-400
                    ">

                        Fleet Support

                    </div>

                    <div class="text-xs text-slate-500">

                        Kendala sistem? Chat WA

                    </div>

                </div>

            </div>

            <a

                href="https://wa.me/62881036959865"

                target="_blank"

                class="
                    px-4 py-2

                    rounded-xl

                    bg-emerald-600

                    text-white

                    text-xs

                    font-bold
                "

            >

                Chat

            </a>

        </div>

    </div>

</div>