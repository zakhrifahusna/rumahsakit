// public/js/pendaftaran.js

$(document).ready(function() {
    $('#pendaftaranForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah form dikirim secara normal

        var form = $(this);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil!',
                        text: 'Apa yang ingin Anda lakukan dengan Antrian.pdf?',
                        showCancelButton: true,
                        confirmButtonText: 'Kirim ke WhatsApp',
                        cancelButtonText: 'Cetak PDF'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim PDF ke WhatsApp
                            window.location.href = '/send-to-whatsapp'; // Ganti dengan URL yang sesuai untuk mengirim PDF ke WhatsApp
                        } else {
                            // Cetak PDF
                            window.open('/download-pdf', '_blank'); // Ganti dengan URL yang sesuai untuk mengunduh PDF
                        }
                    });
                }
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat melakukan pendaftaran.'
                });
            }
        });
    });
});
