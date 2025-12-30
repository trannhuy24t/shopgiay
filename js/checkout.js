function validatePhone() {
    const phone = document.getElementById("phone");
    const error = document.getElementById("phone-error");

    // SĐT VN: 03 05 07 08 09 + 8 số
    const regex = /^(03|05|07|08|09)[0-9]{8}$/;

    if (!regex.test(phone.value.trim())) {
        error.innerText = "⚠ Số điện thoại không đúng định dạng Việt Nam";
        error.style.display = "block";
        phone.classList.add("error");
        phone.classList.remove("success");
        return false;
    }

    error.style.display = "none";
    phone.classList.remove("error");
    phone.classList.add("success");
    return true;
}

// Chỉ cho nhập số + realtime check
document.addEventListener("DOMContentLoaded", function () {
    const phone = document.getElementById("phone");
    const error = document.getElementById("phone-error");

    phone.addEventListener("input", function () {
        // Chỉ cho nhập số
        this.value = this.value.replace(/\D/g, '');

        const regex = /^(03|05|07|08|09)[0-9]{8}$/;

        if (regex.test(this.value)) {
            this.classList.add("success");
            this.classList.remove("error");
            error.style.display = "none";
        } else {
            this.classList.add("error");
            this.classList.remove("success");
            error.innerText = "⚠ SĐT phải bắt đầu bằng 03,05,07,08,09 và đủ 10 số";
            error.style.display = "block";
        }
    });
});
