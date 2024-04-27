$(document).ready(function () {
    let errorMessages = [];

    let id = window.location.pathname.match(/\/user\/(\d+)/)[1];
    let currentUsername = $('#username').val();
    let currentEmail = $('#email').val();
    let currentPhone = $('#phone').val();
    let currentPassword = $('#password').val();
    let currentConfirmPassword = $('#confirmPassword').val();

    $("#error-box").css('display', 'none');
    $("#error-message").text("");

    $("#updateAccount").click(function (e) {
        e.preventDefault();
        $("#error-box").css('display', 'none');
        $("#error-message").text("");
        checkSignup();

        if (errorMessages.length === 0) {
            let newUsername = $("#username").val();
            let newEmail = $("#email").val();
            let newPhone = $("#phone").val();
            let newPassword = $("#password").val();
            let newConfirmPassword = $("#confirmPassword").val();

            let formData = {};

            if (newUsername !== currentUsername) {
                formData.username = newUsername;
            }

            if (newEmail !== currentEmail) {
                formData.email = newEmail;
            }

            if (newPhone !== currentPhone) {
                formData.phone = newPhone;
            }

            if (newPassword !== "") {
                formData.password = newPassword;
            }

            if (newConfirmPassword !== "") {
                formData.confirmPassword = newConfirmPassword;
            }

            $.ajax({
                type: "POST",
                url: `/myapp/user/${id}/update`,
                data: formData,
                success: function (response) {
                    $("#error-box").css('display', 'none');
                    $("#error-message").text('');
                
                    let errorMessage;
                    try {
                        if (response.status === 'error') {
                            if (response.errors) {
                                errorMessage = response.errors.join('<br>');
                            } else {
                                errorMessage = response.message;
                            }
                            $("#error-box").css('display', 'block');
                            $("#error-message").html(errorMessage);
                            console.error(response.status);
                        } else if (response.status === 'success' && response.redirect) {
                            window.location.href = response.redirect;
                        }
                    } catch (e) {
                        console.error("Erreur lors du traitement de la réponse JSON :", e);
                    }
                },                
                error: function (xhr, status, error) {
                    $("#error-box").css('display', 'none');
                    $("#error-message").text("");

                    console.error(xhr.responseText);
                }
            });
        }
    });

    function checkSignup() {
        errorMessages = [];

        const username = $('#username');
        const email = $('#email');
        const phone = $('#phone');
        const confirmPassword = $('#confirmPassword');

        if (!checkPseudo(username.val())) {
            errorMessages.push('- Votre pseudonyme est incorrect.');
        }

        if (!checkEmail(email.val())) {
            errorMessages.push('- Votre adresse e-mail est incorrecte.');
        }

        if (!checkPhone(phone.val())) {
            errorMessages.push('- Votre numéro de téléphone est incorrect.');
        }

        if (!checkPassword(confirmPassword.val())) {
            errorMessages.push('- Votre nouveau mot de passe est incorrect.');
        }

        displayErrors(errorMessages.join('<br>'));
    }

    function displayErrors(messages) {
        $('#error-box').css('display', 'block');
        $('#error-message').css('display', 'block');
        $('#error-message').html(messages);
    }

    function checkEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function checkPhone(phone) {
        const regex = /^[0-9]{10}$/;
        return regex.test(phone);
    }

    function checkPseudo(pseudo) {
        return pseudo.length > 5;
    }

    function checkPassword(password) {
        if (password === "") {
            return true;
        }

        return password.length > 8;
    }
});