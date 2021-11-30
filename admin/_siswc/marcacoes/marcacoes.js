$(document).ready(function() {
    /* HOME MARCAÇÕES */
    /* Pesquisa Marcações de Consultas Na Home */
    $('html').on('keyup', '.js-marketing-search', function () {
    	var search = $(this).val();
    	var back = $('.js-marketing-back');
    	var next = $('.js-marketing-next');
    	var loading = $('.j-marketing-load');
    	var content = $('.js-marketing-content');
    
    	loading.attr('class', 'spinner icon-spinner2 icon-notext');
    	content.css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Consultation',
    		'callback_action': 'content',
    		'search': search
    	};
    
    	$.post('_ajax/Consultation.ajax.php', data, function (data) {
    		content.html(data.content);
    
    		setTimeout(function () {
    			loading.attr('class', 'icon-search icon-notext');
    			content.css('opacity', '1');
    		}, '500');
    	}, 'json');
    
    	back.attr('data-offset', '0');
    	next.attr('data-offset', '0');
    });
    
    /* Anterior */
    $('html').on('click', '.js-marketing-back', function () {
    	var button = $(this);
    	button.find('i').attr('class', 'spinner icon-spinner2 icon-notext');
    	$('.js-marketing-content').css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Consultation',
    		'callback_action': 'content',
    		'offset': button.attr('data-offset')
    	};
    
    	$.post('_ajax/Consultation.ajax.php', data, function (data) {
    		if (data.content) {
    			$('.js-marketing-content').html(data.content);
    
    			if (parseInt(button.attr('data-offset')) >= 10) {
    				button.attr('data-offset', parseInt(button.attr('data-offset')) - 10);
    				$('.js-marketing-next').attr('data-offset', parseInt(button.attr('data-offset')) + 10);
    			} else {
    				$('.js-marketing-next').attr('data-offset', '0');
    			}
    
    			if ($('.js-marketing-search').val().length) {
    				$('.js-marketing-search').val('');
    			}
    		}
    
    		setTimeout(function () {
    			button.find('i').attr('class', 'icon-arrow-left icon-notext');
    			$('.js-marketing-content').css('opacity', '1');
    		}, '500');
    	}, 'json');
    });
    
    /* Inicial */
    $('html').on('click', '.js-marketing-initial', function () {
    	var button = $(this);
    	button.find('i').attr('class', 'spinner icon-spinner2 icon-notext');
    	$('.js-marketing-content').css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Consultation',
    		'callback_action': 'content',
    		'offset': button.attr('data-offset')
    	};
    
    	$.post('_ajax/Consultation.ajax.php', data, function (data) {
    		if (data.content) {
    			$('.js-marketing-content').html(data.content);
    			$('.js-marketing-back').attr('data-offset', '0');
    			$('.js-marketing-next').attr('data-offset', '0');
    
    			if ($('.js-marketing-search').val().length) {
    				$('.js-marketing-search').val('');
    			}
    		}
    
    		setTimeout(function () {
    			button.find('i').attr('class', 'icon-radio-checked icon-notext');
    			$('.js-marketing-content').css('opacity', '1');
    		}, '500');
    	}, 'json');
    });
    
    /* Próximo */
    $('html').on('click', '.js-marketing-next', function () {
    	var button = $(this);
    	button.find('i').attr('class', 'spinner icon-spinner2 icon-notext');
    	$('.js-marketing-content').css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Consultation',
    		'callback_action': 'content',
    		'offset': parseInt(button.attr('data-offset')) + 10
    	};
    
    	$.post('_ajax/Consultation.ajax.php', data, function (data) {
    		if (data.content) {
    			$('.js-marketing-content').html(data.content);
    			button.attr('data-offset', parseInt(button.attr('data-offset')) + 10);
    			$('.js-marketing-back').attr('data-offset', parseInt(button.attr('data-offset')) - 10);
    
    			if ($('.js-marketing-search').val().length) {
    				$('.js-marketing-search').val('');
    			}
    		}
    
    		setTimeout(function () {
    			button.find('i').attr('class', 'icon-arrow-right icon-notext');
    			$('.js-marketing-content').css('opacity', '1');
    		}, '500');
    	}, 'json');
    });
});