document.addEventListener('DOMContentLoaded', function () {

    const s1 = document.getElementById('slider-1')
    const i1 = document.getElementById('input-1')

    const s2 = document.getElementById('slider-2')
    const i2 = document.getElementById('input-2')

    const s3 = document.getElementById('slider-3')
    const i3 = document.getElementById('input-3')

    // ❗ safety biar gak error kalau element belum ada
    if (!s1 || !s2 || !s3) return

    function updateUI(id, val) {
        document.getElementById('slider-' + id).value = val
        document.getElementById('input-' + id).value = val
    }

    function validateChain() {
        let v1 = parseInt(s1.value)
        let v2 = parseInt(s2.value)
        let v3 = parseInt(s3.value)

        if (v1 > v2) {
            v2 = v1
            updateUI(2, v2)
        }

        if (v2 > v3) {
            v3 = v2
            updateUI(3, v3)
        }

        if (v3 < v2) {
            v3 = v2
            updateUI(3, v3)
        }

        if (v2 < v1) {
            v2 = v1
            updateUI(2, v2)
        }
    }

    // 🎨 color picker
    document.querySelectorAll('.color-picker').forEach(picker => {
        picker.addEventListener('input', function () {
            const hexDisplay = this.closest('.group')?.querySelector('.hex-display')
            if (!hexDisplay) return

            hexDisplay.value = this.value.toUpperCase()
            hexDisplay.style.color = this.value
        })
    })

    // 🎚 slider
    ;[s1, s2, s3].forEach((slider, index) => {
        slider.addEventListener('input', () => {
            updateUI(index + 1, slider.value)
            validateChain()
        })
    })

    // 🔢 input number
    ;[i1, i2, i3].forEach((input, index) => {
        input.addEventListener('change', () => {
            let val = Math.max(0, Math.min(100, parseInt(input.value) || 0))
            updateUI(index + 1, val)
            validateChain()
        })
    })

})

document.getElementById('colorSettingsForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;

    Swal.fire({
        title: 'Menyimpan...',
        allowOutsideClick: false,
        background: '#111827',
        color: '#fff',
        didOpen: () => Swal.showLoading()
    });

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!res.ok) throw new Error('Gagal menyimpan');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Pengaturan warna berhasil disimpan',
            background: '#111827',
            color: '#fff',
            timer: 2000,
            showConfirmButton: false
        });

    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: err.message,
            background: '#111827',
            color: '#fff'
        });
    }
});