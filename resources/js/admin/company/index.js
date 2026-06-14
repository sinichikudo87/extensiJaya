import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    const companyForm = document.getElementById('companyProfileForm');
    const logoInput = document.getElementById('logoInput'); 
    const container = document.getElementById('logoContainer');

    // --- LOGIKA PREVIEW GAMBAR ---
    if (logoInput) {
        logoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function (e) {
                    const placeholder = document.getElementById('placeholder');
                    if (placeholder) {
                        placeholder.remove(); 
                    }
                    
                    let preview = document.getElementById('preview');
                    
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'preview';
                        preview.className = 'w-full h-full object-contain block mx-auto'; 
                        if (container) {
                            container.prepend(preview);
                        }
                    }
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (companyForm) {
        companyForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                background: '#111827',
                color: '#fff',
                didOpen: () => Swal.showLoading()
            });

            const formData = new FormData(companyForm);

            try {
                const response = await fetch(companyForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                let result = {};
                const contentType = response.headers.get('content-type');

                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                }

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message || 'Profil perusahaan berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#111827',
                        color: '#fff'
                    });
                } else {
                    let errorHtml = 'Terjadi kesalahan pada server.';

                    if (result.errors) {
                        errorHtml = Object.values(result.errors)
                            .flat()
                            .join('<br>');
                    } else if (result.message) {
                        errorHtml = result.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        html: errorHtml,
                        background: '#111827',
                        color: '#fff'
                    });
                }
            } catch (err) {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Bermasalah',
                    text: 'Tidak dapat menghubungi server.',
                    background: '#111827',
                    color: '#fff'
                });
            }
        });
    }

});