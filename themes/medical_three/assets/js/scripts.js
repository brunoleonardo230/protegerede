$(function () {
    /* JS THEME */
    /* URL */
    BASE = $("link[rel='base']").attr("href");
    var url = BASE + '/themes/medical_three/_ajax/medical_three.ajax.php';

    //Contato Form
    $('.jwc_contact_close').click(function () {
        $('.jwc_contact_sended').fadeOut(200);
        $('.contato-form').fadeIn(400, function () {
            $(this).trigger('reset');
        });
        return false;
    });

    /* Enviar Contato */
    $('html').on('submit', 'form[name="contact_form"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var form = $(this);
        var data = form.serialize() + '&action=contact_form';

        $.post(url, data, function (data) {
            if (data.wc_contact_error) {
                $('.jwc_contact_error').html(data.wc_contact_error).fadeIn();
            } else {
                $('.jwc_contact_error').fadeOut();
            }

            if (data.wc_send_mail) {
                $('.jwc_contact_sended_name').text(data.wc_send_mail);
                $('.contato-form').fadeOut(400, function () {
                    $('.jwc_contact_sended').fadeIn(400);
                });
            }
        }, 'json');
    });

    //Agendamentos
    $('.jwc_contact_close').click(function () {
        $('.jwc_schedule_sended').fadeOut(200);
        $('.schedule-form').fadeIn(400, function () {
            $(this).trigger('reset');
        });
        return false;
    });

    $('html').on('submit', 'form[name="schedule_form"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var form = $(this);
        var data = form.serialize() + '&action=schedule_form';

        $.post(url, data, function (data) {
            if (data.wc_schedule_error) {
                $('.jwc_contact_error').html(data.wc_schedule_error).fadeIn();
            } else {
                $('.jwc_contact_error').fadeOut();
            }

            if (data.wc_send_mail) {
                $('.jwc_contact_sended_name').text(data.wc_send_mail);
                $('.schedule-form').fadeOut(400, function () {
                    $('.jwc_schedule_sended').fadeIn(400);
                });
            }
        }, 'json');
        return false;
    });

    //Newsletter
    $('html').on('submit', 'form[name="newsletter"]', function () {
        
        var form = $(this);
        var data = form.serialize() + '&action=newsletter';

        $.post(url, data, function (data) {
            setTimeout(function () {
                form.trigger('reset');
            }, '500');

            if (data.notify) {
                trigger(data.notify);
            }
        });
        return false;
    });

    //TRIGGERS PERSONALIZADAS
    function trigger(data) {
        if (data[0]) {
            $.each(data, function (key, value) {
                triggerNotify(data[key]);
            });
        } else {
            triggerNotify(data);
        }
    }

    function triggerNotify(data) {

        var triggerContent = "<div class='trigger_notify trigger_notify_" + data.color + "' style='left: 100%; opacity: 0;'>";
        triggerContent += "<p><i class='" + data.icon + "'></i> " + data.title + "</p>";
        triggerContent += "<span class='trigger_notify_timer'></span>";
        triggerContent += "</div>";

        if (!$('.trigger_notify_box').length) {
            $('body').prepend("<div class='trigger_notify_box'></div>");
        }

        $('.trigger_notify_box').prepend(triggerContent);
        $('.trigger_notify').stop().animate({'left': '0', 'opacity': '1'}, 200, function () {
            $(this).find('.trigger_notify_timer').animate({'width': '100%'}, data.timer, 'linear', function () {
                $(this).parent('.trigger_notify').animate({'left': '100%', 'opacity': '0'}, function () {
                    $(this).remove();
                });
            });
        });

        $('body').on('click', '.trigger_notify', function () {
            $(this).animate({'left': '100%', 'opacity': '0'}, function () {
                $(this).remove();
            });
        });
    }
});