$(document).ready(function(){
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
$('.panel_gallery_image .panel_gallery_action .j_edit_action').click(function(e){
    e.preventDefault();
    //Resgata Valores do ID da Imagem a Ser Alterada e da Legenda Atual da Imagem
    var parent = $(this).closest('[data-id]');
    var imageId = parent.attr('data-id');
    var legend = parent.children('.panel_gallery_image_legend').text();
    //Abre o modal
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
            //Caso Tenha Retorno, Altera o Span da Legenda
            if (data.gallery) {
                $(document).find('.panel_gallery_image[data-id="'+ imageId +'"]').find('.panel_gallery_image_legend').text(data.gallery);
            }
        }
    });
    $('.modal_legend').fadeOut();
}); 


