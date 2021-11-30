$(function () {
    /* OPEN MODAL */
    $(".j_create_modal").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        //Limpa Form
        $('form[name="tutorial_create_modal"]').trigger('reset');
        $('input[name="tutorial_id"]').val("");
        $('select[name="tutorial_type"]').val("");

        var modal = $(this).data('modal');
        $(modal).fadeIn('slow');
    });

    /* CLOSE MODAL */
    $(".j_close_modal").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var modal = $(this).data('modal');
        $(modal).fadeOut('slow');
    });

    /* EDIT MODAL */
    $('html').on('click', '.j_edit_modal', function () {
        var EditId = $(this).attr('id');
        var Callback = $(this).attr('callback');
        var Modal = $('.js-tutorial');
        var Form = Modal.find('form');

        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: 'edit', edit_id: EditId}, function (data) {
            if (data.alert) {
                bs_alert(data.alert[0], data.alert[1], data.alert[2], data.alert[3]);
            } else {
                Form.trigger('reset');
                tinyMCE.activeEditor.setContent('');
                tinyMCE.triggerSave();

                $.each(data.data, function (key, value) {
                    $('input[name="' + key + '"]').val(value);
                    $('select[name="' + key + '"] option[value="' + value + '"]').attr({selected: "selected"});

                    if (key == 'tutorial_content') {
                        tinyMCE.activeEditor.insertContent(value);
                        tinyMCE.triggerSave();
                    }
                });

                Modal.fadeIn('fast');
            }
        }, 'json');
        return false;
    });

    /* VIDEO MODAL */
    $('html').on('click', '.j_video_modal', function () {
        var VideoId = $(this).attr('id');
        var Callback = $(this).attr('callback');
        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: 'video', id: VideoId}, function (data) {
            if (data.VideoModal) {
                $('.bs_ajax_modal_content_video .embed-container').html(data.VideoModal);
                $('.js-video').fadeIn(400);
            }
        }, 'json');

        return false;
    });

    /* CLOSE MODAL */
    $(".j_close_modal_video").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $('.js-video').fadeOut(400, function () {
            $('.bs_ajax_modal_content_video .embed-container').html('');
        });
    });
});