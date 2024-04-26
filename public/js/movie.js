$(document).ready(function () {
    let cookies = document.cookie.split('; ');
    let userIdCookie = cookies.find(row => row.startsWith('user_id='));
    let userId = userIdCookie.split('=')[1];

    $('[data-id]').click(function () {
        let dataId = $(this).data('id');

        $.ajax({
            url: `/history/${userId}/add`,
            type: 'POST',
            data: { dataId: dataId },
            success: function (response) {
                try {
                    if (response.status === 'error') {
                        console.error(response.status, response.message);
                    } else if (response.status === 'success' && response.redirect) {
                        console.log(response.message);
                        window.location.href = response.redirect;
                    }
                    console.error(response.status, response.message);
                } catch (e) {
                    console.error("Erreur lors du traitement de la réponse JSON :", e);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            }
        });
    });
});