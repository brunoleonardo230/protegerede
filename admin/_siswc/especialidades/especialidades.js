$(function () {
    //SELECT O TIPO DE ÍCONE ESPECIALIDADE (IMAGEM OU TEXTO)
    $('.j_icon_image').hide();
    $('.j_icon_text').hide();

    if ($('.j_icon').val() == 1) {
        $('.j_icon_text').hide();
        $('.j_icon_image').show();
    } else {
        $('.j_icon_image').hide();
        $('.j_icon_text').show();
    }
    $('.j_icon').change(function () {
        if ($('.j_icon').val() == 1) {
            $('.j_icon_text').hide();
            $('.j_icon_image').show();
        } else {
            $('.j_icon_image').hide();
            $('.j_icon_text').show();
        }
    });
    
    //SELECT O TIPO DE ÍCONE DO BENEFÍCIO DA ESPECIALIDADE (IMAGEM OU TEXTO)
    $('.j_benefits_icon_image').hide();
    $('.j_benefits_icon_text').hide();

    if ($('.j_benefits_icon').val() == 1) {
        $('.j_benefits_icon_text').hide();
        $('.j_benefits_icon_image').show();
    } else {
        $('.j_benefits_icon_image').hide();
        $('.j_benefits_icon_text').show();
    }
    $('.j_benefits_icon').change(function () {
        if ($('.j_benefits_icon').val() == 1) {
            $('.j_benefits_icon_text').hide();
            $('.j_benefits_icon_image').show();
        } else {
            $('.j_benefits_icon_image').hide();
            $('.j_benefits_icon_text').show();
        }
    });
    
   /* OPEN MODAL PROCEDIMENTOS */
    $(".j_create_procedure_modal").on('click', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    	
    	//Limpa Form
        $('form[name="procedure_create_modal"]').trigger('reset');
        $('input[name="specialtie_procedure_id"]').val("");

    	var modal = $(this).data('modal');
    	$(modal).fadeIn('slow');
    });
    
    /* OPEN MODAL MÉDICOS */
    $(".j_create_doctor_modal").on('click', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    	
    	//Limpa Form
        $('form[name="doctor_create_modal"]').trigger('reset');
        $('input[name="specialtie_doctor_id"]').val("");
        $('select[name="doctor_id"]').val("");

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
    
    /* EDIT MODAL PROCEDIMENTOS */
    $('html').on('click', '.j_edit_procedure_modal', function () {
        var EditId = $(this).attr('id');
        var Callback = $(this).attr('callback');
        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: 'edit_procedure', edit_id: EditId}, function (data) {
            //ALERT DINAMIC
            if (data.alert) {
                bs_alert(data.alert[0], data.alert[1], data.alert[2], data.alert[3]);
            } else {
                $.each(data.data, function (key, value) {
                    $('input[name="' + key + '"], textarea[name="' + key + '"]').val(value);
                    $('select[name="' + key + '"] option[value="' + value + '"]').attr({selected: "selected"});
                });

                $('.js-procedure').fadeIn('fast');
            }
        }, 'json');
        return false;
    });
    
    /* EDIT MODAL MÉDICOS */
    $('html').on('click', '.j_edit_doctor_modal', function () {
        var EditId = $(this).attr('id');
        var Callback = $(this).attr('callback');
        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: 'edit', edit_id: EditId}, function (data) {
            //ALERT DINAMIC
            if (data.alert) {
                bs_alert(data.alert[0], data.alert[1], data.alert[2], data.alert[3]);
            } else {
                $.each(data.data, function (key, value) {
                    $('input[name="' + key + '"], textarea[name="' + key + '"]').val(value);
                    $('select[name="' + key + '"] option[value="' + value + '"]').attr({selected: "selected"});
                });

                $('.js-doctors').fadeIn('fast');
            }
        }, 'json');
        return false;
    });
});