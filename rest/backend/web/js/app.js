/**
 * Created by notgosu on 11/25/14.
 */

$(function(){
	$(document).on('click', '.clonable-item-plus', function(event){
		event.preventDefault();

		var elem = $(this).parents('.input-group').find('input');
		if (elem){
			var elemToWorkWith = elem.data('item-to-count');
			var elemNameToChange = elem.data('name');
			var elemGroupAppendTo = elem.data('field-to-append');
			var elemCount = $(elemToWorkWith).length;


			if (elemCount < 3){
				var clone = $(this).parents('.input-group').clone();
				clone.find('.clonable-item-plus').attr('class', 'clonable-item-minus');
				clone.find('.clonable-item-minus i').attr('class', 'glyphicon glyphicon-minus');
				clone.find(elemToWorkWith).attr('name', elemNameToChange).val('');

				$(elemGroupAppendTo).append(clone);
				$(elemGroupAppendTo).append('<div class="help-block"></div>');

				if ($('.phone-to-clone').length){
					$('.phone-to-clone').inputmask("(999) 999-99-99");
				}
			}

		}

		return false;

	});

	$(document).on('click', '.clonable-item-minus', function(event){
		event.preventDefault();

		$(this).parents('.input-group').remove();
		return false;
	});

});
