document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("projectEntryForm");
    const input = document.querySelector('input[name="thumbnail"]');
    const container = document.getElementById("imageContainer");
    const placeholder = document.getElementById("placeholder");

    if (!input) return;

    input.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;

        // validasi simple (optional)
        if (!file.type.startsWith("image/")) {
            alert("File harus gambar");
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            // hapus placeholder icon
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

    function resetThumbnailPreview() {
        const existingImg = container.querySelector("img");
        if (existingImg) {
            existingImg.remove();
        }
        placeholder.classList.remove("hidden");
        input.value = "";
    }

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const token = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;

        Swal.fire({
            title: "Menyimpan...",
            allowOutsideClick: false,
            background: "#111827",
            color: "#fff",
            didOpen: () => Swal.showLoading(),
        });

        try {
            const res = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: {
                    "X-CSRF-TOKEN": token,
                    Accept: "application/json",
                },
            });

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || "Gagal menyimpan");
            }

            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: data.message,
                timer: 1500,
                showConfirmButton: false,
                background: "#111827",
                color: "#fff",
            });

            form.reset();
            resetThumbnailPreview();
            closeProjectModal();
            window.location.reload();
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: err.message,
                background: "#111827",
                color: "#fff",
            });
        }
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

                    fetch(`/project-entry/${id}`, {
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

    window.openProjectModal = function() {
        const modal = document.getElementById('projectModal'); 
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    };

    window.closeProjectModal = function() {
        const modal = document.getElementById('projectModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };
});
