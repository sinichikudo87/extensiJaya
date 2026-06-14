// =========================================================================
// GLOBAL STATE & USER CONFIG
// =========================================================================
let activeEchoChannel = null;

// ===============================
// FUNCTION: SCROLL TO BOTTOM
// ===============================
function scrollChatToBottom() {
    const body = document.getElementById("chatMessageBody");
    if (!body) return;

    setTimeout(() => {
        body.scrollTo({
            top: body.scrollHeight,
            behavior: "smooth",
        });
    }, 50);
}

// ===============================
// FUNCTION: APPEND BUBBLE
// ===============================
function appendMessageBubble(message, time, isMe = true) {
    const container = document.getElementById("chatMessagesContainer");
    if (!container) return;

    let bubble = "";

    if (isMe) {
        bubble = `
            <div class="flex gap-3 max-w-[85%] ml-auto flex-row-reverse animate-fade-in-up">
                <div class="w-8 h-8 rounded-lg bg-cyan-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-user text-white text-[10px]"></i>
                </div>
                <div class="space-y-1 text-right">
                    <div class="bg-cyan-600 text-white px-4 py-2.5 rounded-2xl rounded-tr-none text-[11px] leading-relaxed break-words">
                        ${message}
                    </div>
                    <p class="text-[8px] text-slate-500 font-bold uppercase mt-1">
                        ${time}
                    </p>
                </div>
            </div>
        `;
    } else {
        bubble = `
            <div class="flex gap-3 max-w-[85%] animate-fade-in-up">
                <div class="w-8 h-8 rounded-lg bg-slate-800 border border-white/10 flex items-center justify-center overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1f2937&color=3b82f6" class="w-full h-full object-cover">
                </div>
                <div class="space-y-1">
                    <div class="bg-slate-800 text-slate-200 px-4 py-2.5 rounded-2xl rounded-tl-none border border-white/10 text-[11px] leading-relaxed break-words shadow-xl">
                        ${message}
                    </div>
                    <p class="text-[8px] text-slate-500 font-bold ml-1 uppercase">
                        ${time}
                    </p>
                </div>
            </div>
        `;
    }

    container.insertAdjacentHTML("beforeend", bubble);
}

// =========================================================================
// LISTEN REAL-TIME VIA LARAVEL ECHO
// =========================================================================
function listenToChatChannel(projectId) {
    if (activeEchoChannel) {
        window.Echo.leave(`chat.${activeEchoChannel}`);
    }

    activeEchoChannel = projectId;

    window.Echo.private(`chat.${projectId}`).listen(".message.sent", (e) => {
        console.log("REALTIME MESSAGE RECEIVED:", e);
        const dynamicUserId = window.currentUserId || null;
        const isMe = e.sender_id == dynamicUserId;

        if (!isMe) {
            const formattedTime = new Date(e.created_at).toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
            });

            appendMessageBubble(e.message, formattedTime, false);
            scrollChatToBottom();
        }
    });
}

// ===============================
// SEND CHAT TO DATABASE
// ===============================
export function sendChat(projectId, message) {
    console.log("SEND CHAT TO DB:", projectId, message);

    fetch("/chat/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'Accept': 'application/json',
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            project_id: projectId,
            message: message,
        }),
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error("Server bermasalah"); 
        }
        return response.json();
    })
    .then((data) => {
        console.log("CHAT RESPONSE SUKSES:", data);
        
        // JALANKAN LOGIKAMU DI SINI SETELAH SUKSES
        // Contoh: appendChatToUI(data); 
    })
    .catch((err) => {
        // Semua error (baik error jaringan atau error status 500) ditangkap di sini
        console.error("Gagal menyimpan pesan ke database:", err);
    });
}

// ===============================
// INIT CHAT FORM SUBMIT
// ===============================
function initChatForm() {
    const chatForm = document.getElementById("chatForm");
    if (!chatForm || chatForm.dataset.initialized) return;

    chatForm.dataset.initialized = "true";

    chatForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const input = document.getElementById("chatInput");
        const message = input.value.trim();
        if (!message) return;

        const modal = document.getElementById("chatModal");
        const projectId = modal.dataset.projectId;

        if (!projectId) {
            console.error("Project ID kosong");
            return;
        }

        const now = new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });

        appendMessageBubble(message, now, true);
        scrollChatToBottom();
        sendChat(projectId, message);

        input.value = "";
    });
}

// =========================================================================
// WINDOW GLOBAL FUNCTIONS (Disorot dari HTML/Blade)
// =========================================================================

// 1. LOAD CHAT HISTORY
window.loadChatHistory = async function (projectId) {
    const container = document.getElementById("chatMessagesContainer");
    if (!container) return;

    // Tampilkan spinner loading awal
    container.innerHTML = `
        <div class="p-6 text-center">
            <i class="fas fa-circle-notch fa-spin text-cyan-500"></i>
        </div>
    `;

    try {
        // 1. DISESUAIKAN: Menembak route yang benar untuk histori chat gabungan
        const response = await fetch(`/getHistoryChat/${projectId}`);
        const result = await response.json();
        container.innerHTML = "";

        const chats = result.data || result.chats || [];
        const dynamicUserId = window.currentUserId || null;
 
        if (result.status === "success" && chats.length > 0) {
            
            // Variable penampung HTML agar render DOM berjalan cepat dan mulus
            let htmlContent = '';

            chats.forEach((chat) => {
                // Format tanggal agar seragam dan rapi
                const displayTime = chat.created_at
                    ? new Date(chat.created_at).toLocaleString("id-ID", {
                        day: "2-digit",
                        month: "long",
                        year: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                    })
                    : "-";

                // 2. DISESUAIKAN: Membaca kolom 'content' dari hasil Stored Procedure UNION
                const textContent = chat.content || "-";

                // Jika nama dari database kosong (null), gunakan default 'User'
                const name = chat.employee_name ?? 'User';

                // Cek status kepemilikan pesan (Kiri atau Kanan)
                const isMe = chat.is_me ?? (chat.user_id == dynamicUserId);

                // Atur styling layout chat secara dinamis
                const alignmentClass = isMe ? 'justify-end text-right' : 'justify-start text-left';
                const bubbleRoundedClass = isMe ? 'rounded-tr-none bg-cyan-950/40 border-cyan-500/20' : 'rounded-tl-none bg-slate-800 border-white/10';
                const nameColorClass = isMe ? 'text-emerald-400' : 'text-cyan-400';

                // 3. DISESUAIKAN: Set keterangan metadata berdasarkan item_type
                let metadataInfo = '';
                if (chat.item_type === 'chat') {
                    metadataInfo = '💬 Chat Message';
                } else if (chat.item_type === 'progress') {
                    const statusText = chat.status_update ? ` • Status: ${chat.status_update}` : '';
                    metadataInfo = `🔧 Progress: <b>${chat.progress_val ?? 0}%</b>${statusText}`;
                }

                htmlContent += `
                    <div class="flex gap-3 ${alignmentClass} animate-fade-in-up w-full my-2">
                        
                        ${!isMe ? `
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-md"
                             style="background:${chat.chart_color ?? '#0891b2'}">
                            ${name.charAt(0).toUpperCase()}
                        </div>
                        ` : ''}

                        <div class="${bubbleRoundedClass} text-slate-200 px-4 py-3 rounded-2xl border text-[11px] max-w-[80%] shadow-lg text-left">

                            <div class="font-semibold ${nameColorClass} mb-1">
                                ${name} ${isMe ? '(Anda)' : ''}
                            </div>

                            <div class="text-slate-300 leading-relaxed whitespace-pre-line">
                                ${textContent}
                            </div>

                            <div class="text-[9px] text-slate-500 mt-2 flex items-center justify-between gap-4 border-t border-white/5 pt-1.5">
                                <span>${metadataInfo}</span>
                                <span class="font-mono text-[8px]">${displayTime}</span>
                            </div>
                        </div>

                        ${isMe ? `
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-md"
                             style="background:${chat.chart_color ?? '#0f172a'}">
                            ${name.charAt(0).toUpperCase()}
                        </div>
                        ` : ''}

                    </div>
                `;
            });

            container.innerHTML = htmlContent;

        } else {
            container.innerHTML = `
                <div class="p-6 text-center text-slate-600 text-[10px] uppercase tracking-wider italic">
                    Belum ada diskusi atau progress. Mulai obrolan di bawah.
                </div>
            `;
        }
        
        // Panggil fungsi scroll bawaan kamu
        if (typeof scrollChatToBottom === "function") {
            scrollChatToBottom();
        } else {
            const bodyModal = document.getElementById('chatMessageBody');
            if (bodyModal) bodyModal.scrollTop = bodyModal.scrollHeight;
        }

    } catch (e) {
        console.error("Load Chat Error:", e);
        container.innerHTML = `
            <div class="p-6 text-center text-red-500 text-[10px] uppercase tracking-wider">
                Error loading chat history.
            </div>
        `;
    }
};

// 2. OPEN MODAL
window.openChatModal = async function (projectId, projectCode, projectName) {
    initChatForm();

    const modal = document.getElementById("chatModal");
    if (!modal) return;

    modal.dataset.projectId = projectId;
    document.getElementById("chatProjectName").innerText = projectName;
    document.getElementById("chatProjectCode").innerText = `${projectCode} • Support`;

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    listenToChatChannel(projectId);
    await window.loadChatHistory(projectId);
};

// 3. CLOSE MODAL
window.closeChatModal = function () {
    const modal = document.getElementById("chatModal");
    if (!modal) return;

    modal.classList.remove("flex");
    modal.classList.add("hidden");

    if (activeEchoChannel) {
        window.Echo.leave(`chat.${activeEchoChannel}`);
        activeEchoChannel = null;
    }
};

// ===============================
// AUTO INIT ON LOAD
// ===============================
initChatForm();