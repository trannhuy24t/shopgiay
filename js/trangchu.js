document.addEventListener("DOMContentLoaded", () => {
    // --- PHẦN BỔ SUNG: KIỂM TRA ĐĂNG NHẬP ---
    // Lấy trạng thái từ thuộc tính data-logged-in của body
    const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';

    // Chọn các link Menu và nút Thêm vào giỏ (Các phần tử có class .auth-link hoặc .btn)
    const authLinks = document.querySelectorAll('.auth-link, .btn');

   authLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        if (!isLoggedIn) {
            e.preventDefault();

            // Thay thế alert mặc định
            Swal.fire({
                title: 'Thông báo',
                text: 'Bạn cần đăng nhập để thực hiện chức năng này!',
                icon: 'warning',
                confirmButtonText: 'Đăng nhập ngay',
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../pages/dangnhap.html';
                }
            });
        }
    });
});
    // --- HẾT PHẦN BỔ SUNG ---

    // Lấy các phần tử
    const userArea = document.querySelector(".user-area");
    const userMenu = document.getElementById("usermenu");

    // Chỉ chạy nếu các phần tử này tồn tại (đã đăng nhập)
    if (userArea && userMenu) {
        
        // BƯỚC 1: Click vào vùng User (ảnh đại diện) để bật/tắt menu
        userArea.addEventListener("click", (e) => {
            e.stopPropagation(); // CỰC KỲ QUAN TRỌNG
            userMenu.classList.toggle("show-menu");
        });

        // BƯỚC 2: Ngăn menu bị đóng khi click vào bên trong các dòng chữ
        userMenu.addEventListener("click", (e) => {
            e.stopPropagation(); 
        });
    }

    // BƯỚC 3: Click ra bất cứ đâu bên ngoài thì đóng menu
    document.addEventListener("click", () => {
        if (userMenu && userMenu.classList.contains("show-menu")) {
            userMenu.classList.remove("show-menu");
            console.log("Đã đóng menu vì click ra ngoài");
        }
    });
});