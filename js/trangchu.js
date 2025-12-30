document.addEventListener("DOMContentLoaded", () => {
    // Lấy các phần tử
    const userArea = document.querySelector(".user-area");
    const userMenu = document.getElementById("usermenu");

    // Chỉ chạy nếu các phần tử này tồn tại (đã đăng nhập)
    if (userArea && userMenu) {
        
        // BƯỚC 1: Click vào vùng User (ảnh đại diện) để bật/tắt menu
        userArea.addEventListener("click", (e) => {
            e.stopPropagation(); // CỰC KỲ QUAN TRỌNG: Ngăn không cho click này "bay" ra ngoài document
            userMenu.classList.toggle("show-menu");
        });

        // BƯỚC 2: Ngăn menu bị đóng khi click vào bên trong các dòng chữ (Thông tin, Đơn hàng...)
        userMenu.addEventListener("click", (e) => {
            e.stopPropagation(); 
        });
    }

    // BƯỚC 3: Click ra bất cứ đâu bên ngoài (body, banner, footer...) thì đóng menu
    document.addEventListener("click", () => {
        if (userMenu && userMenu.classList.contains("show-menu")) {
            userMenu.classList.remove("show-menu");
            console.log("Đã đóng menu vì click ra ngoài");
        }
    });
    
});