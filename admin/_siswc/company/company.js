$(document).ready(function(){
    //SELECT O TIPO DE ÍCONE DO DIFERENCIAL DA EMPRESA (IMAGEM OU TEXTO)
    $('.j_differential_icon_image').hide();
    $('.j_differential_icon_text').hide();

    if ($('.j_differential_icon').val() == 1) {
        $('.j_differential_icon_text').hide();
        $('.j_differential_icon_image').show();
    } else {
        $('.j_differential_icon_image').hide();
        $('.j_differential_icon_text').show();
    }
    $('.j_differential_icon').change(function () {
        if ($('.j_differential_icon').val() == 1) {
            $('.j_differential_icon_text').hide();
            $('.j_differential_icon_image').show();
        } else {
            $('.j_differential_icon_image').hide();
            $('.j_differential_icon_text').show();
        }
    });
    
    /* OPEN MODAL FAQ */
    $(".j_create_faq_modal").on('click', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    	
    	//Limpa Form
        $('form[name="faq_create_modal"]').trigger('reset');
        $('input[name="faq_id"]').val("");
    
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
    
    /* EDIT MODAL FAQ */
    $('html').on('click', '.j_edit_faq_modal', function () {
        var EditId = $(this).attr('id');
        var Callback = $(this).attr('callback');
        $.post('_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: 'edit_faq', edit_id: EditId}, function (data) {
            //ALERT DINAMIC
            if (data.alert) {
                bs_alert(data.alert[0], data.alert[1], data.alert[2], data.alert[3]);
            } else {
                $.each(data.data, function (key, value) {
                    $('input[name="' + key + '"], textarea[name="' + key + '"]').val(value);
                    $('select[name="' + key + '"] option[value="' + value + '"]').attr({selected: "selected"});
                });
    
                $('.js-faq').fadeIn('fast');
            }
        }, 'json');
        return false;
    });
    
    $('.j_formsubmit').submit(function () {
        $('.gallery').fadeOut(1000, function(){
            $(this).empty();
        });
    });
});

//Fecha Modal Para Edição da Legenda
$('.modal_legend_content .modal_cancel').click(function(){
    $('.modal_legend').fadeOut();
});

//Abre Modal Para Edição da Legenda
$('.panel_gallery_image .panel_gallery_action .j_edit_action').on('click', function(e){
    e.preventDefault();
    //Resgata valores do ID da imagem a ser altera e da legenda atual da imagem
    var parent = $(this).closest('[data-id]');
    var imageId = parent.attr('data-id');
    var legend = parent.children('.panel_gallery_image_legend').text();
    //Abre a Modal
    $('.modal_legend').css("display", "flex")
    .hide()
    .fadeIn(function(){
        $(this).find("input[name='gallery_image_id']").val(imageId);
        $(this).find("input[name='gallery_image_legend']").val(legend);
    });
});

//Altera a Legenda da Foto    
$('.j_form_legend').on('submit', function(e){
    e.preventDefault();
    var form = $(this);
    var callback = form.find('input[name="callback"]').val();
    var callback_action = form.find('input[name="callback_action"]').val();
    //Resgata o ID da imagem a ser alterada e a nova legenda
    var newlegend = form.find('input[name="gallery_image_legend"]').val();
    var imageId = form.find('input[name="gallery_image_id"]').val();
    form.ajaxSubmit({
        url: '_ajax/' + callback + '.ajax.php',
        data: {callback_action: callback_action,
        gallery_image_id: imageId,
        gallery_image_legend: newlegend},
        dataType: 'json',
        success: function (data) {
            //Caso tenha retorno, altera o span da legenda
            if (data.gallery) {
                $(document).find('.panel_gallery_image[data-id="'+ imageId +'"]').find('.panel_gallery_image_legend').text(data.gallery);
            }
        }
    });
    $('.modal_legend').fadeOut();
}); 

