$(function () {
    var base = $('link[rel="base"]').attr('href') + "/_cdn/widgets/imobi";

    $('.wc_mobile_filter').click(function () {
        $('.wc_imobi_filter .content').slideToggle();
    });

    $('.wc_imobi_filter_form select').change(function () {
        var wcDataAction = $(this).attr('name');
        var wcDataFilter = $('.wc_imobi_filter_form').serialize() + "&workcontrol=" + wcDataAction;

        $(this).nextAll('select').html('<option value="">Selecione o campo anterior...</option>');
        $.post(base + '/filter.ajax.php', wcDataFilter, function (data) {
            if (data.type) {
                $('select[name="type"]').html("<option value=''>Selecione o Tipo:</option>" + data.type);
            }
            if (data.finality) {
                $('select[name="finality"]').html("<option value=''>Selecione a Finalidade:</option>" + data.finality);
            }
            if (data.district) {
                $('select[name="district"]').html('<option value="">Selecione o Bairro:</option>' + data.district);
            }
            if (data.bedrooms) {
                $('select[name="bedrooms"]').html('<option value="">Selecione Quantidade de Quartos:</option>' + data.bedrooms);
            }
            if (data.min_price) {
                $('select[name="min_price"]').html('<option value="">Selecione Um Valor Mínimo:</option>' + data.min_price);
            }
            if (data.max_price) {
                $('select[name="max_price"]').html('<option value="">Selecione Um Valor Máximo:</option>' + data.max_price);
            }
        }, 'json');
    });
});