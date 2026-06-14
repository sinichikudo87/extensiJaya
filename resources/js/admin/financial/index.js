const editPemasukan = async (projectId, currentValue) => {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!csrfToken) {
        console.error('CSRF token tidak ditemukan');
        return Swal.fire('Error', 'CSRF token tidak ditemukan', 'error');
    }

    const { value: newValue } = await Swal.fire({
        title: 'Edit Pemasukan',
        html: `
            <div style="text-align:left;font-size:0.8rem;color:#9ca3af;margin-bottom:5px;">
                Nominal (Rp)
            </div>
            <input id="swal-input-pemasukan" class="swal2-input custom-dark-input" placeholder="0">
        `,
        customClass: {
            popup: 'custom-dark-popup',
            title: 'custom-dark-title',
            confirmButton: 'btn-save-purple',
            cancelButton: 'btn-cancel-gray'
        },
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',

        didOpen: () => {
            const input = document.getElementById('swal-input-pemasukan');

            input.value = new Intl.NumberFormat('id-ID').format(currentValue || 0);

            input.addEventListener('input', (e) => {
                let val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(val);
            });
        },

        preConfirm: () => {
            const raw = document
                .getElementById('swal-input-pemasukan')
                .value.replace(/[^0-9]/g, '');

            const val = parseInt(raw);

            if (!val || val <= 0) {
                Swal.showValidationMessage('Masukkan nominal yang valid!');
                return false;
            }

            return val;
        }
    });

    if (!newValue) return;

    // =========================
    // LOADING STATE
    // =========================
    Swal.fire({
        title: 'Memperbarui...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        customClass: { popup: 'custom-dark-popup' }
    });

    try {
        const formData = new FormData();
        formData.append('budget', newValue);
        formData.append('_method', 'PUT');
        formData.append('_token', csrfToken);

        const response = await fetch(`/financial/${projectId}/budget`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        // 🔥 HANDLE NON-JSON RESPONSE (500, HTML, dll)
        const text = await response.text();

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            throw new Error('Response bukan JSON (kemungkinan error server)');
        }

        if (!response.ok) {
            throw new Error(data.message || 'Gagal memperbarui data');
        }

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'custom-dark-popup' }
            });

            location.reload();
        } else {
            throw new Error(data.message || 'Update gagal');
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message,
            customClass: { popup: 'custom-dark-popup' }
        });
    }
};
window.editPemasukan = editPemasukan;