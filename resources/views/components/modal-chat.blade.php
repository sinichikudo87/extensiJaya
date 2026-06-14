<div id="chatModal"
    class="fixed inset-0 z-[999999] hidden items-center justify-center bg-slate-950/60 backdrop-blur-md p-4 transition-all duration-300"
    x-cloak>

    <div
        class="relative z-10 w-full max-w-2xl bg-slate-900/90 rounded-[2rem] border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] my-auto flex flex-col h-[550px] overflow-hidden">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-white/[0.02]">

            <div class="flex items-center gap-3">

                <div class="relative">

                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg">

                        <i class="fas fa-headset text-white text-base"></i>

                    </div>

                    <div
                        class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full animate-pulse">
                    </div>

                </div>

                <div>

                    <h3 id="chatProjectName"
                        class="text-[11px] font-black text-white uppercase tracking-widest leading-none">

                        Discussion

                    </h3>

                    <p id="chatProjectCode"
                        class="text-[9px] text-slate-500 mt-1 uppercase tracking-tighter">

                        PRJ-00 • Support

                    </p>

                </div>

            </div>

            <button
                type="button"
                onclick="closeChatModal()"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-slate-400 hover:bg-rose-500/20 hover:text-rose-400 transition-all">

                <i class="fas fa-times text-xs"></i>

            </button>

        </div>

        {{-- BODY --}}
        <div id="chatMessageBody"
            class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">

            <div id="chatMessagesContainer" class="space-y-6"></div>

        </div>

        {{-- FOOTER --}}
        <div class="p-4 bg-slate-800/50 border-t border-white/5">

            <form id="chatForm" class="flex items-center gap-2">

                <input
                    type="text"
                    id="chatInput"
                    autocomplete="off"
                    placeholder="Type your response..."
                    class="flex-1 bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-[11px] text-white focus:border-cyan-500/50 outline-none placeholder:text-slate-600 transition-all"
                >

                <button
                    type="submit"
                    onclick="console.log('SEND BUTTON CLICKED')"
                    class="w-11 h-11 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl shadow-lg flex items-center justify-center transition-all active:scale-95 border border-white/10">

                    <i class="fas fa-paper-plane text-xs"></i>

                </button>

            </form>

        </div>

    </div>

</div>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.4s ease-out forwards;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #06b6d4;
    }
</style>