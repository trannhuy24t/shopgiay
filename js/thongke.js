document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("table tr");
    const msg = document.getElementById("message");

    if (rows.length <= 2) {
        msg.innerHTML = "❌ Không có dữ liệu thống kê";
        msg.style.color = "red";
    } else {
        msg.innerHTML = "✅ Thống kê thành công";
        msg.style.color = "green";
    }
});
