document.addEventListener("DOMContentLoaded", () => {
    const formCategory = document.getElementById("projectCategoryForm");
    if (!formCategory) return;

    formCategory.addEventListener("submit", async (e) => {
        e.preventDefault();

        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        Swal.fire({
            title: "Menyimpan...",
            allowOutsideClick: false,
            background: "#111827",
            color: "#fff",
            didOpen: () => Swal.showLoading(),
        });

        try {
            const res = await fetch(formCategory.action, {
                method: "POST",
                body: new FormData(formCategory),
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

            formCategory.reset();
            closeProjectModalCategory();

            setTimeout(() => {
                window.location.reload();
            }, 1000);

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

    document.addEventListener("click", function (e) {
        const button = e.target.closest(".btn-edit-category");
        if (!button) return;

        const id = button.dataset.id;
        const name = button.dataset.name;
        const status = button.dataset.status;

        // 🔥 inject ke form
        document.getElementById("category_id").value = id;
        document.getElementById("name").value = name;
        document.getElementById("status").value = status;

        // 🔥 ubah title kalau mau (optional)
        document.querySelector("#projectCategoryModal h3").innerText = "Edit Category";

        // 🔥 buka modal
        openProjectModalCategory();
    });
});
