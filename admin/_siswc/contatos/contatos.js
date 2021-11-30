$(document).ready(function() {
    /* HOME CONTATOS */
    /* Abre Modal Envio de E-mail */
    $('html').on('click', '.js-modal-open', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var button = $(this);
    	var target = $('.' + button.data('modal'));
    	var content = target.find('.bs_ajax_modal');
    
    	target.fadeIn('fast', function () {
    	content.fadeIn('fast');
    	});
    });
    
    /* Fecha Modal Envio de E-mail */
    $('html').on('click', '.js-modal-close', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var button = $(this);
    	var target = $('.' + button.data('modal'));
    	var content = target.find('.bs_ajax_modal');
    
    	content.fadeOut('fast', function () {
    	target.fadeOut('fast');
    	});
    });
    
    /* Toggle */
    $('html').on('click', '.js-modal-toggle', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var modal = $('.js-modal-message');
    	modal.slideToggle(1);
    });
    
    $('html').on('click', '.js-user-toggle', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var button = $(this);
    	button.toggleClass('is-active');
    
    	var id = button.data('id');
    	var name = button.data('name');
    
    	var total = $('.js-total-users');
    	var count = $('.js-modal-toggle');
    	var users = $('.js-users-checked');
    	var checked = $('.js-user-toggle.is-active').length;
    
    	if (users.val().length && users.val() != 'all') {
    		var split = users.val().split(',');
    		var index = split.indexOf(id.toString());
    
    		if (index > -1) {
    			split.splice(index, 1);
    		} else {
    			split.push(id.toString());
    		}
    
    		users.val(split.join(','));
    	} else {
    		users.val(id.toString());
    	}
    
    	if (checked == 1) {
    		count.html(name);
    	} else if (checked > 1) {
    		count.html(checked + '/' + total.val() + ' Contatos Selecionados');
    	} else {
    		count.html('Selecione o(s) Contato(s)');
    	}
    });
    
    /* Marca Contatos */
    $('html').on('click', '.js-modal-mark', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var list = [];
    	var users = $('.js-user-toggle');
    	var total = $('.js-total-users');
    	var count = $('.js-modal-toggle');
    	var value = $('.js-users-checked');
    
    	$.each(users, function () {
    		var button = $(this);
    		button.addClass('is-active');
    		list.push(button.data('id'));
    	});
    
    	count.html(total.val() + '/' + total.val() + ' Contatos Selecionados');
    	value.val('all');
    });
    
    /* Desmarca Contatos */
    $('html').on('click', '.js-modal-unmark', function (e) {
    	e.preventDefault();
    	e.stopPropagation();
    
    	var users = $('.js-user-toggle');
    	var count = $('.js-modal-toggle');
    	var value = $('.js-users-checked');
    
    	$.each(users, function () {
    		$(this).removeClass('is-active');
    	});
    
    	count.html('Selecione o(s) Contato(s)');
    	value.val('');
    });
    
    /* Pesquisa Contato Na Home */
    $('html').on('keyup', '.js-marketing-search', function () {
    	var search = $(this).val();
    	var back = $('.js-marketing-back');
    	var next = $('.js-marketing-next');
    	var loading = $('.j-marketing-load');
    	var content = $('.js-marketing-content');
    
    	loading.attr('class', 'spinner icon-spinner2 icon-notext');
    	content.css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Contacts',
    		'callback_action': 'content',
    		'search': search
    	};
    
    	$.post('_ajax/Contacts.ajax.php', data, function (data) {
    		content.html(data.content);
    
    		setTimeout(function () {
    			loading.attr('class', 'icon-search icon-notext');
    			content.css('opacity', '1');
    		}, '500');
    	}, 'json');
    
    	back.attr('data-offset', '0');
    	next.attr('data-offset', '0');
    });
    
    /* Pesquisa Contato Na Modal */
    $('html').on('keyup', '.js-modal-search', function (e) {
    	var field = $(this);
    	var search = field.val();
    	var content = $('.js-modal-content');
    
    	var data = {
    		'callback': 'Contacts',
    		'callback_action': 'search',
    		'search': search
    	};
    
    	$.post('_ajax/Contacts.ajax.php', data, function (data) {
    		content.html(data.content);
    	}, 'json');
    });
    
    /* Anterior */
    $('html').on('click', '.js-marketing-back', function () {
    	var button = $(this);
    	button.find('i').attr('class', 'spinner icon-spinner2 icon-notext');
    	$('.js-marketing-content').css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Contacts',
    		'callback_action': 'content',
    		'offset': button.attr('data-offset')
    	};
    
    	$.post('_ajax/Contacts.ajax.php', data, function (data) {
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
    		'callback': 'Contacts',
    		'callback_action': 'content',
    		'offset': button.attr('data-offset')
    	};
    
    	$.post('_ajax/Contacts.ajax.php', data, function (data) {
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
    
    /* Pr¨®ximo */
    $('html').on('click', '.js-marketing-next', function () {
    	var button = $(this);
    	button.find('i').attr('class', 'spinner icon-spinner2 icon-notext');
    	$('.js-marketing-content').css('opacity', '0.8');
    
    	var data = {
    		'callback': 'Contacts',
    		'callback_action': 'content',
    		'offset': parseInt(button.attr('data-offset')) + 10
    	};
    
    	$.post('_ajax/Contacts.ajax.php', data, function (data) {
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