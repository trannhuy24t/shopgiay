const form = document.getElementById("productForm");
const btn = form.querySelector("button");

document.querySelectorAll(".row-product").forEach(row => {
    row.addEventListener("click", () => {
        // 1. Chuyển trạng thái form sang update
        form.action.value = "update";
        
        // 2. Đổ dữ liệu cơ bản
        form.id.value = row.dataset.id;
        form.name.value = row.dataset.name;
        form.price.value = row.dataset.price;
        form.quantity.value = row.dataset.quantity;
        form.image.value = row.dataset.image;

        // 3. Đổ dữ liệu PHÂN LOẠI và MÔ TẢ (Sửa lỗi undefined tại đây)
        // Lưu ý: dataset.category và dataset.description phải có trong thẻ <tr> của file PHP
        if (form.category_id) {
            form.category_id.value = row.dataset.category || "";
        }
        if (form.description) {
            form.description.value = row.dataset.description || "";
        }

        // 4. Đổi giao diện nút bấm
        btn.textContent = "💾 Cập nhật sản phẩm";
        btn.style.backgroundColor = "#2980b9"; // Đổi màu để nhận diện đang sửa

        // 5. Cuộn lên đầu trang mượt mà
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});

// XỬ LÝ XÓA (Giữ nguyên logic của bạn nhưng tối ưu hơn)
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".btn-delete").forEach(btnDelete => {
        btnDelete.addEventListener("click", (e) => {
            e.stopPropagation(); // Ngăn việc nhấn nút xóa bị dính sự kiện click vào hàng (sửa thành cập nhật)

            const id = btnDelete.dataset.id;
            if (!confirm("Xoá sản phẩm này?")) return;

            fetch("qlsp_process.php?action=delete&id=" + id)
                .then(response => {
                    if (response.ok) {
                        btnDelete.closest("tr").remove();
                        showToast("🗑 Đã xoá sản phẩm");
                    }
                })
                .catch(err => console.error("Lỗi xóa:", err));
        });
    });
});

/* Toast thông báo */
function showToast(msg) {
    const toast = document.getElementById("toast");
    if (toast) {
        toast.innerText = msg;
        toast.className = "show";
        setTimeout(() => toast.className = "", 2000);
    }
}