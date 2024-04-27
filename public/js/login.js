$(document).ready(function () {
    $("#error-box").css('display', 'none');
    $("#error-message").text("");

    $("#submit-btn").click(function (e) {
        e.preventDefault();
        $("#error-box").css('display', 'none');
        $("#error-message").text("");

        let formData = {
            usernameOrEmail: $('#usernameOrEmail').val(),
            password: $('#password').val(),
        };

        $.ajax({
            type: "POST",
            url: "/myapp/auth/login/register",
            data: formData,
            success: function (response) {
                $("#error-box").css('display', 'none');
                $("#error-message").text('');

                try {
                    if (response.status === 'error') {
                        $("#error-box").css('display', 'block');
                        $("#error-message").html(response.message);
                        console.error(response.status, response.message);
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
    });
});