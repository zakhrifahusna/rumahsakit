function beriNilai(dokterId, namaDokter, antrianId) {
    // Tambahkan CSS ke halaman
    const style = document.createElement('style');
    style.innerHTML = `
        .star {
            font-size: 2.5rem;  /* Memperbesar ukuran bintang */
            cursor: pointer;
            color: #ccc;
        }
        input[type="radio"] {
            display: none;
        }
        label.star {
            display: inline-block;
        }
        input[type="radio"]:checked ~ label {
            color: gold;
        }
    `;
    document.head.appendChild(style);

    Swal.fire({
        title: '<p style="font-size:22px; margin-bottom: 4px;">Beri Rating untuk</p>',
        html: `
            <h3 style="font-weight: bold; color: #000; margin-top: 0; margin-bottom: 12px;">${namaDokter}</h3>
            <div id="star-rating" style="text-align: center;">
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" class="star">&#9734;</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" class="star">&#9734;</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" class="star">&#9734;</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" class="star">&#9734;</label>
                <input type="radio" id="star5" name="rating" value="5"><label for="star5" class="star">&#9734;</label>
            </div>
            <form id="form-rating" method="POST" action="/beri-nilai">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="dokter_id" value="${dokterId}">
                <input type="hidden" name="antrian_id" value="${antrianId}">
                <input type="hidden" id="rating-value" name="rating">
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const rating = document.getElementById('rating-value').value;
            if (!rating) {
                Swal.showValidationMessage('Silakan pilih rating');
                return false;
            }
            document.getElementById('form-rating').submit();
        },
        didOpen: () => {
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = this.previousElementSibling.value;
                    document.getElementById('rating-value').value = rating;

                    // Set semua bintang dari yang pertama hingga yang dipilih menjadi penuh
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.innerHTML = '&#9733;';  // Bintang penuh
                            s.style.color = 'gold'; // Mengubah warna bintang menjadi kuning
                        } else {
                            s.innerHTML = '&#9734;';  // Bintang kosong
                            s.style.color = '#ccc'; // Mengembalikan warna bintang kosong
                        }
                    });

                    // Pilih radio button yang sesuai
                    this.previousElementSibling.checked = true;
                });
            });
        }
    });
}
