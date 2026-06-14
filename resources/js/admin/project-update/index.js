import Alpine from "alpinejs";
document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector('input[name="thumbnail"]');
    const container = document.getElementById("imageContainer");
    const placeholder = document.getElementById("placeholder");

    if (!input) return;

    input.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;
        if (!file.type.startsWith("image/")) {
            alert("File harus gambar");
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            if (placeholder) placeholder.remove();

            container.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-full object-cover rounded-xl transition-all duration-300" />
                
                <label class="absolute inset-0 flex items-center justify-center bg-blue-600/40 opacity-0 hover:opacity-100 cursor-pointer transition-all text-white text-[10px] font-bold">
                    <i class="fas fa-upload mr-2"></i> CHANGE IMAGE
                    <input type="file" name="thumbnail" class="hidden" accept="image/*">
                </label>
            `;
        };

        reader.readAsDataURL(file);
    });

    // Edit Progress
    document.querySelectorAll(".btn-edit").forEach((button) => {
        button.addEventListener("click", function (e) {
            e.stopPropagation();
            const projectId = this.getAttribute("data-id");
            const currentProgress =
                parseInt(this.getAttribute("data-progress")) || 0;
            const tr = this.closest("tr");
            const detailRow = document.getElementById(`details-${projectId}`);
            const descriptionElem = detailRow
                ? detailRow.querySelector(".project-description")
                : null;
            const currentDescription = descriptionElem
                ? descriptionElem.innerText.trim()
                : "";
            let selectedChartId = null;
            let selectedColor = null;
            Swal.fire({
                title: '<div class="pt-2"><span class="text-[14px] font-black uppercase tracking-[0.4em] text-blue-500/80">Project Update</span></div>',
                background: "#0f172a",
                color: "#fff",
                showCloseButton: true,
                showCancelButton: false,
                confirmButtonText: `
                                <div class="flex items-center justify-center gap-2.5">
                                    <i class="fas fa-rocket text-[10px] transform rotate-[-45deg] drop-shadow-[0_0_5px_rgba(255,255,255,0.5)]"></i>
                                    
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white">
                                        Push Update
                                    </span>
                                </div>
                            `,
                buttonsStyling: false,
                customClass: {
                    popup: "border border-gray-800 shadow-2xl rounded-[2rem] p-4",
                    confirmButton: `
                        w-full py-4 px-6 rounded-2xl font-black text-[11px] tracking-[0.2em]
                        bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500
                        text-white shadow-[0_10px_20px_-10px_rgba(37,99,235,0.5)] 
                        transition-all duration-300 active:scale-[0.98] uppercase
                    `,
                },
                html: `
                    <div class="text-left px-1 relative overflow-hidden group/modal max-h-[70vh] flex flex-col">

                        <!-- decorative bg -->
                        <div class="absolute -right-10 -top-10 w-24 h-24 bg-blue-600 opacity-10 blur-3xl rounded-full"></div>

                        <!-- SCROLL AREA -->
                        <div class="overflow-y-auto pr-2 space-y-6 relative z-10 custom-scrollbar">

                            <!-- PALETTE -->
                            <div>
                                <div class="flex items-center gap-2 mb-3 pl-1">
                                    <span class="w-1 h-3 bg-indigo-500 rounded-full"></span>
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        Status Project
                                    </label>
                                </div>

                                <div id="swal-palette"
                                    class="grid grid-cols-3 gap-2 place-items-center justify-center mx-auto">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- PROGRESS -->
                                <div class="space-y-3">
                                    <div class="flex items-end justify-between pl-1">
                                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-400">
                                            Progress
                                        </label>
                                        <div class="flex items-baseline gap-1">
                                            <span id="swal-progress-text" class="text-3xl font-black text-white">
                                                ${currentProgress}
                                            </span>
                                            <span class="text-xs text-slate-500">%</span>
                                        </div>
                                    </div>

                                    <input type="range" id="swal-progress" min="0" max="100"
                                        value="${currentProgress}"
                                        class="w-full h-1.5 bg-slate-800 rounded-full appearance-none cursor-pointer accent-blue-500">
                                </div>

                                <!-- COST -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 pl-1">
                                        Pengeluaran (Rp)
                                    </label>

                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-500 text-xs font-bold">
                                            Rp
                                        </span>

                                        <input type="number" id="swal-cost"
                                            class="w-full bg-gray-950/70 border border-gray-800 rounded-xl py-3 pl-10 pr-4 text-sm text-white
                                            focus:border-emerald-500/50 outline-none transition-all"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <!-- NOTES -->
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 pl-1">
                                    Activity Notes
                                </label>

                                <textarea id="swal-description"
                                    class="w-full mt-2 bg-gray-950/70 border border-gray-800 rounded-2xl p-4 text-sm text-slate-300
                                    focus:border-blue-500/50 outline-none transition-all resize-none"
                                    rows="3"
                                    placeholder="Apa yang Anda kerjakan?">${currentDescription}</textarea>

                                <p class="text-[9px] text-slate-600 mt-1 pl-1 italic">
                                    *Wajib diisi untuk history update
                                </p>
                            </div>
                        </div>
                    </div>
                `,
                didOpen: async () => {
                    const slider =
                        Swal.getPopup().querySelector("#swal-progress");
                    const text = Swal.getPopup().querySelector(
                        "#swal-progress-text",
                    );
                    const palette =
                        Swal.getPopup().querySelector("#swal-palette");

                    slider.addEventListener("input", () => {
                        text.innerText = slider.value;
                    });

                    palette.innerHTML = `
                        <div class="col-span-6 text-center text-gray-500 text-[10px]">
                            Loading palette...
                        </div>
                    `;

                    try {
                        const res = await fetch(
                            "/project-update/chart-settings",
                        );
                        const data = await res.json();

                        palette.innerHTML = "";

                        data.forEach((cs) => {
                            if (!cs?.color) return;

                            const item = document.createElement("div");

                            item.className = `
                                group relative flex items-center justify-center
                                w-14 h-14 rounded-lg
                                bg-[#0f111a]/60 backdrop-blur-md border border-white/5
                                hover:border-blue-500/50 hover:scale-105
                                transition-all duration-200 cursor-pointer
                            `;

                            item.setAttribute("data-id", cs.id);

                            item.innerHTML = `
                                <div class="group relative flex flex-col items-center justify-center gap-1
                                    w-14 h-16 rounded-lg
                                    bg-[#0f111a]/60 backdrop-blur-md border border-white/5
                                    hover:border-blue-500/40 transition-all duration-200">

                                    <!-- CHECK ICON -->
                                    <div class="absolute top-1 right-1 hidden selected-check">
                                        <i class="fas fa-check text-[8px] text-blue-400"></i>
                                    </div>

                                    <!-- COLOR DOT -->
                                    <div class="w-5 h-5 rounded-full transition-all duration-200"
                                        style="background:${cs.color}; box-shadow:0 0 10px ${cs.color}55;">
                                    </div>

                                    <!-- LABEL -->
                                    <span class="text-[8px] text-slate-400 text-center px-1 w-full break-words">
                                        ${cs.description ?? "No description"}
                                    </span>
                                </div>
                            `;
                            
                            item.addEventListener("click", () => {
                                const descInput = Swal.getPopup().querySelector("#swal-description");

                                if (descInput) {
                                    descInput.value = cs.description ?? "";
                                    descInput.focus();
                                }

                                const id = item.getAttribute("data-id");
                                selectedChartId = id;
                                // RESET ALL
                                document.querySelectorAll("#swal-palette > div").forEach(el => {
                                    el.classList.remove(
                                        "ring-2",
                                        "ring-blue-500",
                                        "scale-105",
                                        "shadow-lg"
                                    );

                                    const check = el.querySelector(".selected-check");
                                    if (check) check.classList.add("hidden");
                                });

                                // ACTIVE STYLE
                                item.classList.add(
                                    "ring-2",
                                    "ring-blue-500",
                                    "scale-105",
                                    "shadow-lg"
                                );

                                const check = item.querySelector(".selected-check");
                                if (check) check.classList.remove("hidden");

                                // micro animation feedback
                                item.animate(
                                    [
                                        { transform: "scale(0.95)" },
                                        { transform: "scale(1.05)" },
                                        { transform: "scale(1)" }
                                    ],
                                    { duration: 180, easing: "ease-out" }
                                );
                            });

                            palette.appendChild(item);
                        });
                    } catch (err) {
                        palette.innerHTML = `
                            <div class="col-span-6 text-center text-red-400 text-[10px]">
                                Failed to load palette
                            </div>
                        `;
                    }
                },
                preConfirm: () => {
                    const progress =
                        document.getElementById("swal-progress").value;
                    const cost = document.getElementById("swal-cost").value;
                    const description =
                        document.getElementById("swal-description").value;

                    if (!description.trim()) {
                        Swal.showValidationMessage(
                            "Catatan update wajib diisi",
                        );
                        return false;
                    }
                    return { progress, description, cost: cost || 0 };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Updating...",
                        background: "#111827",
                        color: "#fff",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                    });

                    fetch(`/project-update/update-progress`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]',
                            ).content,
                        },
                        body: JSON.stringify({
                            project_id: projectId,
                            progress_val: result.value.progress,
                            additional_cost: result.value.cost,
                            notes: result.value.description,
                            status_update:
                                result.value.progress == 100
                                    ? "completed"
                                    : "on-progress",
                            chart_setting_id: selectedChartId,
                        }),
                    })
                        .then(async (res) => {
                            const data = await res.json();
                            if (!res.ok)
                                throw new Error(data.message || "Server Error");
                            return data;
                        })
                        .then((data) => {
                            Swal.fire({
                                icon: "success",
                                title: "Updated!",
                                text: "Project berhasil diperbarui",
                                timer: 1500,
                                showConfirmButton: false,
                                background: "#111827",
                                color: "#fff",
                            });

                            window.location.reload();
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: err.message,
                                background: "#111827",
                                color: "#fff",
                            });
                        });
                }
            });
        });
    });

    let currentBar = null;

    document.addEventListener("click", function (e) {
        // =========================
        // COLOR PICKER (PRIORITAS)
        // =========================
        const wrapper = e.target.closest(".progress-wrapper");
        if (wrapper) {
            e.stopPropagation();
            currentBar = wrapper.querySelector(".progress-bar");
            return;
        }

        // =========================
        // PILIH WARNA
        // =========================
        const colorEl = e.target.closest(".color-option");
        if (colorEl) {
            e.stopPropagation();

            const color = colorEl.dataset.color;

            if (currentBar) {
                currentBar.style.backgroundColor = color;
            }

            document.getElementById("colorPickerPopup").classList.add("hidden");
            return;
        }

        // =========================
        // KLIK TOMBOL (EDIT)
        // =========================
        if (e.target.closest(".btn-edit")) {
            e.stopPropagation();
            return;
        }

        // =========================
        // ROW CLICK (DETAIL)
        // =========================
        const row = e.target.closest("tr[data-id]");
        if (row) {
            const id = row.dataset.id;
            toggleDetail("project_history_" + id, "icon_" + id);
            return;
        }
    });

    // Chat Modal
    const chatButtons = document.querySelectorAll('.btn-chat');
    const modal = document.getElementById('chatModal');

    const elName = document.getElementById('chatProjectName');
    const elCode = document.getElementById('chatProjectCode');

    const chatContainer = document.getElementById('chatMessagesContainer');

    chatButtons.forEach(btn => {
        btn.addEventListener('click', async function () {

            const id = this.dataset.id;
            const code = this.dataset.code;
            const name = this.dataset.name;

            // simpan project id ke modal
            modal.dataset.projectId = id;

            // header modal
            elName.textContent = name ?? 'Unknown Project';
            elCode.textContent = code ? `${code} • Support` : 'No Code';

            // buka modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // load chat/progress dari database
            await loadProjectProgress(id);
        });
    });

    document.querySelectorAll(".btn-delete").forEach((button) => {
        button.addEventListener("click", function (e) {
            e.stopPropagation();

            const id = this.getAttribute("data-id");
            Swal.fire({
                title: "Yakin hapus?",
                text: "Data project akan dihapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#374151",
                background: "#111827",
                color: "#fff",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Menghapus...",
                        allowOutsideClick: false,
                        background: "#111827",
                        color: "#fff",
                        didOpen: () => Swal.showLoading(),
                    });

                    fetch(`/project-update/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                            Accept: "application/json",
                        },
                    })
                        .then(async (res) => {
                            const data = await res.json();
                            if (!res.ok) {
                                throw new Error(data.message || "Gagal hapus");
                            }
                            return data;
                        })
                        .then(() => {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: "Project dihapus",
                                timer: 1500,
                                showConfirmButton: false,
                                background: "#111827",
                                color: "#fff",
                            });

                            // hapus row tanpa reload
                            const row = this.closest("tr");
                            if (row) row.remove();
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: err.message,
                                background: "#111827",
                                color: "#fff",
                            });
                        });
                }
            });
        });
    });
});

async function loadProjectProgress(projectId) {
    const chatContainer = document.getElementById('chatMessagesContainer');
    if (!chatContainer) return;

    chatContainer.innerHTML = `
        <div class="text-center text-slate-500 text-xs py-4">
            <i class="fas fa-circle-notch fa-spin text-cyan-500 mr-2"></i>Loading progress & chat...
        </div>
    `;

    try {
        const res = await fetch(`/getHistoryChat/${projectId}`);
        const json = await res.json();
        const data = json.data || [];
        const dynamicUserId = window.currentUserId || null;

        chatContainer.innerHTML = '';

        if (!data || !data.length) {
            chatContainer.innerHTML = `
                <div class="text-center text-slate-500 text-xs py-4 italic">
                    Belum ada diskusi atau progress proyek.
                </div>
            `;
            return;
        }

        let htmlContent = '';
        data.forEach(item => {
            const text = item.content || '-';
            const name = item.employee_name ?? 'User';
            const tanggal = item.created_at 
                ? new Date(item.created_at).toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
                : '';

            const isMe = item.is_me ?? (item.user_id == dynamicUserId);
            const alignmentClass = isMe ? 'justify-end text-right' : 'justify-start text-left';
            const bubbleRoundedClass = isMe ? 'rounded-tr-none bg-cyan-950/40 border-cyan-500/20' : 'rounded-tl-none bg-slate-800 border-white/10';
            const nameColorClass = isMe ? 'text-emerald-400' : 'text-cyan-400';

            let metadataInfo = '';
            if (item.item_type === 'chat') {
                metadataInfo = '💬 Chat Message';
            } else if (item.item_type === 'progress') {
                const statusText = item.status_update ? ` • Status: ${item.status_update}` : '';
                metadataInfo = `🔧 Progress: <b>${item.progress_val ?? 0}%</b>${statusText}`;
            }

            htmlContent += `
                <div class="flex gap-3 ${alignmentClass} animate-fade-in-up w-full my-2">
                    
                    ${!isMe ? `
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-md"
                         style="background:${item.chart_color ?? '#0891b2'}">
                        ${name.charAt(0).toUpperCase()}
                    </div>
                    ` : ''}

                    <div class="${bubbleRoundedClass} text-slate-200 px-4 py-3 rounded-2xl border text-[11px] max-w-[80%] shadow-lg">

                        <div class="font-semibold ${nameColorClass} mb-1">
                            ${name} ${isMe ? '(Anda)' : ''}
                        </div>

                        <div class="text-slate-300 leading-relaxed whitespace-pre-line">
                            ${text}
                        </div>

                        <div class="text-[9px] text-slate-500 mt-2 flex items-center justify-between gap-4 border-t border-white/5 pt-1.5">
                            <span>${metadataInfo}</span>
                            <span class="font-mono text-[8px]">${tanggal}</span>
                        </div>
                    </div>

                    ${isMe ? `
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-md"
                         style="background:${item.chart_color ?? '#0f172a'}">
                        ${name.charAt(0).toUpperCase()}
                    </div>
                    ` : ''}

                </div>
            `;
        });

        chatContainer.innerHTML = htmlContent;
        setTimeout(() => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 50);

    } catch (err) {
        chatContainer.innerHTML = `
            <div class="text-rose-400 text-xs text-center py-4">
                <i class="fas fa-exclamation-triangle mr-1"></i> Failed to load timeline data
            </div>
        `;
        console.error(err);
    }
}

window.editCost = function (id, currentValue) {

    Swal.fire({
        title: '<span style="font-size: 16px; letter-spacing: 1px;">UPDATE COST</span>',
        html: `
            <div style="text-align: left; font-family: 'Inter', sans-serif;">
                <div style="display: flex; justify-content: space-between; align-items: center; background: #1e293b; padding: 10px 15px; border-radius: 10px; margin-bottom: 15px;">
                    <span style="color: #94a3b8; font-size: 12px;">Current:</span>
                    <span style="color: #f59e0b; font-weight: 700; font-size: 14px;">Rp ${Number(currentValue).toLocaleString('id-ID')}</span>
                </div>

                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #06b6d4; font-weight: 700; font-size: 13px;">Rp</span>
                    <input id="swal-cost" class="swal2-input" 
                        type="text"
                        style="margin: 0; width: 100%; padding: 10px 10px 10px 35px; height: 45px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #f8fafc; font-size: 15px; font-family: 'JetBrains Mono', monospace;">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        background: '#0b1120',
        width: '320px',
        padding: '1.25rem',
        customClass: {
            popup: 'border border-slate-700 shadow-xl rounded-xl',
            confirmButton: 'bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg text-sm font-bold border-0',
            cancelButton: 'bg-transparent text-slate-400 px-4 py-2 text-sm border-0'
        },
        didOpen: () => {
            const input = document.getElementById('swal-cost');
            input.value = Number(currentValue).toLocaleString('id-ID'); // Set default value terformat
            
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, "");
                e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
            input.focus();
        },
        preConfirm: () => {
            const val = document.getElementById('swal-cost').value.replace(/\./g, '');
            if (!val) return Swal.showValidationMessage('Isi angka!');
            return val;
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch("/project-update/update-cost", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id: id,
                additional_cost: result.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    timer: 1200,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 800);
            } else {
                Swal.fire('Error', 'Gagal update', 'error');
            }
        });
    });
};

Alpine.data('projectSearchComponent', () => ({
    searchQuery: '',
    
    init() {
        this.$watch('searchQuery', (value) => {
            this.performSearch();
        });
    },

    debounceTimer: null,
    performSearch() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            fetch(`${window.location.pathname}?search=${this.searchQuery}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('project-list-container').innerHTML = html;
            });
        }, 300);
    }
}));

window.loadHistory = async function (projectId) {

    const desktop = document.getElementById(`content_desktop_${projectId}`);
    const mobile = document.getElementById(`content_mobile_${projectId}`);
    if (!desktop && !mobile) return;

    const checkTarget = desktop || mobile;
    if (checkTarget && checkTarget.dataset.loaded === "true") {
        return;
    }
    const loadingHtml = `
        <div class="p-4 text-center text-slate-500">
            Loading history...
        </div>
    `;
    if (desktop) desktop.innerHTML = loadingHtml;
    if (mobile) mobile.innerHTML = loadingHtml;

    try {
        const response = await fetch(`/project-entry/getHistory/${projectId}`);
        const result = await response.json();

        console.log("History Response:", result);

        const histories = Array.isArray(result)
            ? result
            : (result.data || []);

        if (!histories.length) {
            const emptyHtml = `
                <div class="p-4 text-slate-500 italic">
                    Belum ada histori update.
                </div>
            `;
            if (desktop) desktop.innerHTML = emptyHtml;
            if (mobile) mobile.innerHTML = emptyHtml;
            return;
        }

        let html = '';
        histories.forEach(item => {
            html += `
                <div class="border-b border-slate-800 p-4 text-left">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-cyan-400">
                            ${item.employee_name ?? 'System'}
                        </span>
                        <span class="text-[10px] text-slate-500">
                            ${item.created_at ?? ''}
                        </span>
                    </div>

                    <div class="text-slate-300 text-xs">
                        ${item.notes ?? '-'}
                    </div>

                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-[10px] text-slate-500">
                            Progress:
                        </span>
                        <span class="text-[10px] font-bold text-green-400">
                            ${item.progress_val ?? 0}%
                        </span>
                    </div>
                </div>
            `;
        });

        // MASUKKAN HTML KE KEDUA CONTAINER (Dua-duanya terisi otomatis)
        if (desktop) {
            desktop.innerHTML = html;
            desktop.dataset.loaded = "true";
        }
        if (mobile) {
            mobile.innerHTML = html;
            mobile.dataset.loaded = "true";
        }

    } catch (err) {
        console.error("History Error:", err);

        const errorHtml = `
            <div class="p-4 text-red-400">
                Gagal memuat histori.
            </div>
        `;
        if (desktop) desktop.innerHTML = errorHtml;
        if (mobile) mobile.innerHTML = errorHtml;
    }
};