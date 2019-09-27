$('#btn-ignore').on('click', function () {
    $('#overlay').show();

    $.post({
        url : $(this).data('url'),
        data : {
            data : $(this).data('migrations')
        },
        dataType : 'json'
    }).done(function (data, textStatus, jqXHR) {
        location.reload();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $('#overlay-content pre').text(errorThrown);
        $('#overlay-content pre').removeClass('success');
        $('#overlay-content pre').addClass('error');

        $('.overlay-loading').hide();
        $('#overlay-content').show();
    });
});

$('#btn-apply').on('click', function () {
    $('#overlay').show();

    $.post({
        url : $(this).data('url'),
    }).done(function (data, textStatus, jqXHR) {
        if (typeof data.exitCode == 'undefined') {
            data.exitCode = 1;
        }
        if (typeof data.output == 'undefined') {
            data.output = 'Unknown server error!';
        }

        $('#overlay-content pre').text(data.output);
        if (data.exitCode == 0) {
            $('#overlay-content pre').removeClass('error');
            $('#overlay-content pre').addClass('success');
        } else {
            $('#overlay-content pre').removeClass('success');
            $('#overlay-content pre').addClass('error');
        }

        $('.overlay-loading').hide();
        $('#overlay-content').show();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $('#overlay-content pre').text(errorThrown);
        $('#overlay-content pre').removeClass('success');
        $('#overlay-content pre').addClass('error');

        $('.overlay-loading').hide();
        $('#overlay-content').show();
    });
});

$('#btn-continue').on('click', function () {
    location.reload();
});