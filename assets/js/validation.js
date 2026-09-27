function showError(id, message) {
    let span = document.getElementById(id);
    if (span) { span.textContent = message; }
}
function clearError(id) {
    showError(id, "");
}

document.addEventListener("DOMContentLoaded", function () {

    // Register form
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            let valid = true;
            const fullName = document.getElementById("full_name").value.trim();
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone").value.trim();
            const password = document.getElementById("password").value;
            const confirm = document.getElementById("confirm_password").value;

            clearError("full_name_error");
            clearError("email_error");
            clearError("phone_error");
            clearError("password_error");
            clearError("confirm_error");

            if (fullName.length < 3) {
                showError("full_name_error", "Full name must be at least 3 characters.");
                valid = false;
            }
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                showError("email_error", "Enter a valid email address.");
                valid = false;
            }
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone)) {
                showError("phone_error", "Phone number must be exactly 10 digits.");
                valid = false;
            }
            if (password.length < 6) {
                showError("password_error", "Password must be at least 6 characters.");
                valid = false;
            }
            if (password !== confirm) {
                showError("confirm_error", "Passwords do not match.");
                valid = false;
            }
            if (!valid) { e.preventDefault(); }
        });
    }

    // Contact form
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            let valid = true;
            const subject = document.getElementById("subject").value.trim();
            const message = document.getElementById("message").value.trim();

            clearError("subject_error");
            clearError("message_error");

            if (subject.length < 3) {
                showError("subject_error", "Subject must be at least 3 characters.");
                valid = false;
            }
            if (message.length < 10) {
                showError("message_error", "Message must be at least 10 characters.");
                valid = false;
            }
            if (!valid) { e.preventDefault(); }
        });
    }

    // Donation form
    const donateForm = document.getElementById("donateForm");
    if (donateForm) {
        donateForm.addEventListener("submit", function (e) {
            let valid = true;
            const amount = parseFloat(document.getElementById("amount").value);

            clearError("amount_error");

            if (isNaN(amount) || amount <= 0) {
                showError("amount_error", "Enter a valid donation amount greater than 0.");
                valid = false;
            }
            if (!valid) { e.preventDefault(); }
        });
    }

    // Campaign form (add + edit both)
    const campaignForm = document.getElementById("campaignForm");
    if (campaignForm) {
        campaignForm.addEventListener("submit", function (e) {
            let valid = true;
            const title = document.getElementById("title").value.trim();
            const description = document.getElementById("description").value.trim();
            const goalAmount = parseFloat(document.getElementById("goal_amount").value);

            clearError("title_error");
            clearError("description_error");
            clearError("goal_amount_error");

            if (title.length < 3) {
                showError("title_error", "Title must be at least 3 characters.");
                valid = false;
            }
            if (description.length < 10) {
                showError("description_error", "Description must be at least 10 characters.");
                valid = false;
            }
            if (isNaN(goalAmount) || goalAmount <= 0) {
                showError("goal_amount_error", "Goal amount must be greater than 0.");
                valid = false;
            }
            if (!valid) { e.preventDefault(); }
        });
    }

    // Admin reply form
    const replyForm = document.getElementById("replyForm");
    if (replyForm) {
        replyForm.addEventListener("submit", function (e) {
            let valid = true;
            const reply = document.getElementById("admin_reply").value.trim();
            clearError("reply_error");
            if (reply.length < 5) {
                showError("reply_error", "Reply must be at least 5 characters.");
                valid = false;
            }
            if (!valid) { e.preventDefault(); }
        });
    }
});