
document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", function () {
        const orderId = this.dataset.id;

        if (!confirm("Bạn có chắc muốn xóa đơn hàng này không?")) return;

        fetch("../php/delete_order.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `order_id=${orderId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert("Xóa đơn hàng thành công!");
                this.closest("tr").remove(); // xóa luôn dòng khỏi bảng
            } else {
                alert("Xóa thất bại!");
            }
        })
        .catch(() => alert("Lỗi kết nối server"));
    });
});


