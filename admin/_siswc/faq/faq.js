$(function () {
    $('.add').click(function () {
        $('#cadastro').fadeIn(400);
        //Limpa Form
        $('form[name="faq_add"]').trigger('reset');
        $('input[name="faq_id"]').val("");
    });
    $('.close').click(function () {
        $('#cadastro').fadeOut(400);
    });

    var BTNACTION = function () {

        var ThisRel = $(this).attr('rel');
        var Callback = $(this).attr('cc');
        var Callback_action = $(this).attr('ca');

        $('.workcontrol_upload').fadeIn().css('display', 'flex');

        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, action_id: ThisRel}, function (data) {
            //EXIBE CALLBACKS
            if (data.trigger) {
                Trigger(data.trigger);
            }

            //MANIPULA RETORNO DE FORMULARIO VIA AJAX
            if (data.form) {
                var Form = $(data.form);
                $.each(data.result, function (key, value) {
                    Form.find("input[name='" + key + "']").val(value);
                    Form.find("textarea[name='" + key + "']").val(value);
                    Form.find("select[name='" + key + "'] option:selected").removeAttr("selected");
                    Form.find("select[name='" + key + "'] option[value='" + value + "']").attr({selected: "selected"});
                });
            }

            //DATA DINAMIC CONTENT
            if (data.divcontent) {
                $.each(data.divcontent, function (key, value) {
                    $(key).html(value);
                });
            }

            //DATA DINAMIC FADEIN
            if (data.fadein) {
                if (typeof (data.fadein) === 'string') {
                    $(data.fadein).fadeIn();
                } else if (typeof (data.fadein) === 'object') {
                    $.each(data.fadein, function (key, value) {
                        $(value).fadeIn();
                    });
                }
            }

            $('.workcontrol_upload').fadeOut();
        }, 'json');
        return false;
    };

    $('html').on('click', '.jbs_action', BTNACTION);
});