document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('employeeModal')
    const modalContent = document.getElementById('employeeModalContent')
    const btnClose = document.getElementById('btnCloseEmployeeModal')
    const form = document.getElementById('formEmployee')
    const btnOpen = document.getElementById('openModal')
    const modalTitle = document.getElementById('modalTitle')

    /* =========================
       OPEN EDIT
    ========================= */
    document.querySelectorAll('.btn-edit-employee').forEach(btn => {
        btn.addEventListener('click', () => {

            clearForm()
            fillForm(btn.dataset)

            modalTitle.innerText = 'Edit Data Karyawan'

            openModal()
        })
    })

    /* =========================
       OPEN CREATE
    ========================= */
    btnOpen?.addEventListener('click', () => {
        clearForm()
        modalTitle.innerText = 'Entri Data Karyawan'
        openModal()
    })

    /* =========================
       CLOSE MODAL
    ========================= */
    btnClose?.addEventListener('click', closeModal)

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal()
        }
    })

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal()
        }
    })

    /* =========================
       FUNCTIONS
    ========================= */
    function openModal() {

        modal.classList.remove('hidden')
        modal.classList.add('flex')

        if (modalContent) {

            modalContent.classList.remove('scale-95', 'opacity-0')

            setTimeout(() => {
                modalContent.classList.add('scale-100', 'opacity-100')
            }, 50)
        }
    }

    function closeModal() {
        if (modalContent) {
            modalContent.classList.remove('scale-100', 'opacity-100')
            modalContent.classList.add('scale-95', 'opacity-0')
        }

        setTimeout(() => {
            modal.classList.add('hidden')
            modal.classList.remove('flex')
        }, 200)
    }

    function fillForm(data) {
        form.querySelector('[name="id"]').value = data.id || 0
        form.querySelector('[name="users_id"]').value = data.users_id || ''
        form.querySelector('[name="employee_code"]').value = data.code || ''
        form.querySelector('[name="name"]').value = data.name || ''
        form.querySelector('[name="email"]').value = data.email || ''
        form.querySelector('[name="phone"]').value = data.phone || ''
        form.querySelector('[name="address"]').value = data.address || ''
        form.querySelector('[name="city"]').value = data.city || ''
        form.querySelector('[name="salary"]').value = data.salary || ''
        form.querySelector('[name="join_date"]').value = data.join_date || ''
        form.querySelector('[name="status"]').value = data.status || 'active'

        const division = form.querySelector('[name="division_id"]')
        if (division) {
            division.value = data.division_id || ''
        }

        const role = form.querySelector('[name="role_id"]')
        if (role) {
            role.value = data.role_id || ''
        }
    }

    function clearForm() {
        form.reset()
        form.querySelector('[name="id"]').value = 0
        form.querySelector('[name="users_id"]').value = ''

        const division = form.querySelector('[name="division_id"]')
        if (division) division.selectedIndex = 0

        const role = form.querySelector('[name="role_id"]')
        if (role) role.selectedIndex = 0
    }

    /* =========================
       FORM SUBMIT AJAX
    ========================= */
    if (!form) return

    form.addEventListener('submit', async (e) => {

        e.preventDefault()

        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content')

        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            background: '#111827',
            color: '#fff',
            didOpen: () => Swal.showLoading()
        })

        try {
            const formData = new FormData(form)
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })

            const result = await res.json()
            if (!res.ok) {
                throw new Error(result.message || 'Gagal menyimpan data')
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: result.message || 'Data berhasil disimpan',
                timer: 1500,
                showConfirmButton: false,
                background: '#111827',
                color: '#fff'
            })

            closeModal()

            setTimeout(() => {
                location.reload()
            }, 1500)

        } catch (err) {

            console.error(err)

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.message || 'Terjadi kesalahan',
                background: '#111827',
                color: '#fff'
            })
        }
    })

    /* =========================
       DELETE
    ========================= */
    document.addEventListener('click', async function (e) {

        const btn = e.target.closest('.btn-delete-employee')

        if (!btn) return

        const id = btn.dataset.id
        const name = btn.dataset.name

        const confirm = await Swal.fire({
            title: 'Yakin?',
            text: `Hapus ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!'
        })

        if (!confirm.isConfirmed) return

        try {

            const res = await fetch(`/employee-profile/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    'Accept': 'application/json'
                }
            })

            const result = await res.json()

            if (!res.ok) {
                throw new Error(result.message || 'Gagal menghapus')
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: result.message || 'Data berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
            })

            setTimeout(() => {
                location.reload()
            }, 1500)

        } catch (err) {

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.message || 'Terjadi kesalahan'
            })
        }
    })
})