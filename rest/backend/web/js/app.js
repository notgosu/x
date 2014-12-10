function parseResponse(response) {
	if (response.replaces instanceof Array) {
		for (var i = 0, ilen = response.replaces.length; i < ilen; i++) {
			$(response.replaces[i].what).replaceWith(response.replaces[i].data);
		}
	}
	if (response.append instanceof Array) {
		for (i = 0, ilen = response.append.length; i < ilen; i++) {
			$(response.append[i].what).append(response.append[i].data);
		}
	}
	if (response.js) {
		$("body").append(response.js);
	}
}


$(function () {
	$(document).on('click', '.clonable-item-plus', function (event) {
		event.preventDefault();

		var elem = $(this).parents('.input-group').find('input');
		if (elem) {
			var elemToWorkWith = elem.data('item-to-count');
			var elemNameToChange = elem.data('name');
			var elemGroupAppendTo = elem.data('field-to-append');
			var elemCount = $(elemToWorkWith).length;


			if (elemCount < 3) {
				var clone = $(this).parents('.input-group').clone();
				clone.find('.clonable-item-plus').attr('class', 'clonable-item-minus');
				clone.find('.clonable-item-minus i').attr('class', 'glyphicon glyphicon-minus');
				clone.find(elemToWorkWith).attr('name', elemNameToChange).val('');

				$(elemGroupAppendTo).append(clone);
				$(elemGroupAppendTo).append('<div class="help-block"></div>');

				if ($('.phone-to-clone').length) {
					$('.phone-to-clone').inputmask("(999) 999-99-99");
				}
			}

		}

		return false;

	});

	$(document).on('click', '.clonable-item-minus', function (event) {
		event.preventDefault();

		$(this).parents('.input-group').remove();
		return false;
	});

	$(document).on('change', '.dependent', function (event) {
		event.preventDefault();
		var that = this;
		var url = $(that).data('url');
		var name = $(that).data('name');
		jQuery.ajax({
			'cache': false,
			'type': 'POST',
			'dataType': 'json',
			'data': name+'='+that.value,
			'success':
				function (response) {
			parseResponse(response);
		}, 'error': function (response) {
			alert(response.responseText);
		}, 'beforeSend': function () {
		}, 'complete': function () {
			//$('.dependent-dropdown').attr('disabled', false);
		}, 'url': url});
	});

});
