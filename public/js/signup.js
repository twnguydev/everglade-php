$(document).ready(function () {
    let errorMessages = [];

    $("#error-box").css('display', 'none');
    $("#error-message").text("");

    $("#submit-btn").click(function (e) {
        e.preventDefault();
        $("#error-box").css('display', 'none');
        $("#error-message").text("");
        checkSignup();

        if (errorMessages.length === 0) {
            let formData = {
                genre: $('#gender').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                username: $('#username').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                birthdate: $('#birthdate').val(),
                password: $('#password').val(),
                confirmPassword: $('#confirm-password').val(),
            };

            $.ajax({
                type: "POST",
                url: "/auth/signup/register",
                data: formData,
                success: function (response) {
                    $("#error-box").css('display', 'none');
                    $("#error-message").text('');
                
                    let errorMessage;
                    try {
                        if (response.status === 'error') {
                            errorMessage = response.errors.join('<br>');
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

        const firstname = $('#firstname');
        const lastname = $('#lastname');
        const username = $('#username');
        const email = $('#email');
        const phone = $('#phone');
        const birthdate = $('#birthdate');
        const password = $('#password');
        const confirmPassword = $('#confirm-password');
        // const checkbox = $('#signup-form input[type="checkbox"]');

        if (!checkNames(firstname.val()) || !checkNames(lastname.val())) {
            errorMessages.push('- Votre nom ou prénom semble incorrect.');
        }

        if (!checkPseudo(username.val())) {
            errorMessages.push('- Votre pseudonyme est incorrect.');
        }

        if (!checkEmail(email.val())) {
            errorMessages.push('- Votre adresse e-mail est incorrecte.');
        }

        if (!checkPhone(phone.val())) {
            errorMessages.push('- Votre numéro de téléphone est incorrect.');
        }

        if (!checkBirthdate(birthdate.val())) {
            errorMessages.push('- Votre âge ne vous permet pas de vous inscrire sur la plateforme.');
        }

        if (!checkPasswords(password.val(), confirmPassword.val())) {
            errorMessages.push('- Vos mots de passe sont incorrects.');
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

    function checkNames(fullname) {
        const regex = /^[a-zA-Z]{5,}$/;
        return regex.test(fullname);
    }

    function checkPseudo(pseudo) {
        return pseudo.length > 5;
    }

    function checkBirthdate(birthdate) {
        const actualDate = new Date();
        const actualYear = actualDate.getFullYear();
        const birthdayValue = new Date(birthdate);
        const birthdayYear = birthdayValue.getFullYear();

        return actualYear - birthdayYear >= 18;
    }

    function checkPasswords(password, confirmPassword) {
        return password !== "" && confirmPassword !== "" &&
            password.length > 8 && confirmPassword.length > 8 && 
            password === confirmPassword;
    }
});