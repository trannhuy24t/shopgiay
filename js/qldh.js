document.querySelectorAll(".order-status").forEach(select => {
    select.addEventListener("change", function () {
        const orderId = this.dataset.id;
        const status  = this.value;

        fetch("../php/update_order_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `order_id=${orderId}&status=${encodeURIComponent(status)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") {
                alert("❌ Cập nhật thất bại!");
            }
        })
        .catch(() => alert("⚠ Lỗi kết nối server"));
    });
});
