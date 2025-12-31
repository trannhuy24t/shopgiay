document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Chặn hành động load lại trang mặc định

    // 1. Kiểm tra dữ liệu (Validation)
    let isValid = true;
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();

    if (name === "" || email === "" || subject === "" || message === "") {
        alert("Vui lòng điền đầy đủ thông tin!");
        return;
    }

    // 2. Gom dữ liệu form
    const formData = new FormData(this);

    // 3. Gửi dữ liệu bằng AJAX (fetch)
    fetch('../php/lienhe_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("success")) {
            alert("Gửi liên hệ thành công!");
            document.getElementById('contactForm').reset(); // Xóa trắng form sau khi gửi
        } else {
            alert("Có lỗi xảy ra: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Không thể kết nối tới máy chủ.");
    });
});