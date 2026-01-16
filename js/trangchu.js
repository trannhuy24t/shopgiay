document.addEventListener("DOMContentLoaded", () => {

    const isLoggedIn = document.body.dataset.loggedIn === 'true';

    document.querySelectorAll(".btn-add-cart").forEach(btn => {
        btn.addEventListener("click", () => {

            if (!isLoggedIn) {
                Swal.fire({
                    title: 'Thông báo',
                    text: 'Bạn cần đăng nhập để mua hàng',
                    icon: 'warning',
                    confirmButtonText: 'Đăng nhập'
                }).then(() => {
                    window.location.href = '../pages/dangnhap.html';
                });
                return;
            }

            const pid = btn.dataset.id;

            document.getElementById("modalProductId").value = pid;
            document.getElementById("modalProductName").innerText = btn.dataset.name;

            fetch(`get_variants.php?id=${pid}`)
                .then(res => res.json())
                .then(data => {

                    const sizeSel  = document.getElementById("sizeSelect");
                    const colorSel = document.getElementById("colorSelect");

                    sizeSel.innerHTML  = '<option value="">-- Chọn size --</option>';
                    colorSel.innerHTML = '<option value="">-- Chọn màu --</option>';

                    data.sizes.forEach(s => {
                        sizeSel.innerHTML += `<option value="${s}">${s}</option>`;
                    });

                    data.colors.forEach(c => {
                        colorSel.innerHTML += `<option value="${c}">${c}</option>`;
                    });
                });

            document.getElementById("cartModal").style.display = "flex";
        });
    });

});

function closeModal() {
    document.getElementById("cartModal").style.display = "none";
}
