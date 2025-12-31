let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("demo-thumb"); // Kiểm tra kỹ tên này

    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }

    // Ẩn tất cả slides chính
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Xóa class active ở các ảnh nhỏ (chỉ chạy nếu tìm thấy dots)
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Hiển thị slide được chọn
    if (slides[slideIndex - 1]) {
        slides[slideIndex - 1].style.display = "block";
    }
    
    // Thêm class active cho ảnh nhỏ tương ứng
    if (dots[slideIndex - 1]) {
        dots[slideIndex - 1].className += " active";
    }
}