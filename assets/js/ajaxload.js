jQuery(document).ready(function ($) {

	//ЗАКРЫТИЕ ОКНА
	$('.btn-close').click(function () {
		$('.modal-cart').empty();
	});

	//ОТКРЫТИЕ ОКНА И AJAX ПОДГРУЗКА
	$('.ajax-post').click(function (e) {
		$('.popup').css('display', 'flex');
		e.preventDefault();
		let post_id = $(this).attr('id');
		$.ajax({
			cache: false,
			timeout: 8000,
			url: php_array.admin_ajax,
			type: "POST",
			data: ({
				action: 'theme_post_example',
				id: post_id
			}),

			success: function (data, textStatus, jqXHR) {
				var $ajax_response = $(data);

				$('#ajax-response').html($ajax_response);
				$('.modal').slideDown();
			},

			error: function (jqXHR, textStatus, errorThrown) {
				console.log('The following error occured: ' + textStatus, errorThrown);
			},

			complete: function (jqXHR, textStatus) {}

		});

	});
});