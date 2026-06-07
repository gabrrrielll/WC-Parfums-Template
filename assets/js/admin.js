(function ($) {
	'use strict';

	function openMediaFrame($field, $input, $preview, $remove) {
		var frame = wp.media({
			title: 'Selecteaz? imagine',
			button: { text: 'Folose?te aceast? imagine' },
			multiple: false,
			library: { type: 'image' }
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			$input.val(attachment.id);

			var url = attachment.sizes && attachment.sizes.medium
				? attachment.sizes.medium.url
				: attachment.url;

			$preview.html('<img src="' + url + '" alt="" />');
			$remove.removeClass('hidden');
		});

		frame.open();
	}

	$(document).on('click', '.wcp-image-field__upload', function (e) {
		e.preventDefault();

		var $field = $(this).closest('.wcp-image-field');
		openMediaFrame(
			$field,
			$field.find('.wcp-image-field__input'),
			$field.find('.wcp-image-field__preview'),
			$field.find('.wcp-image-field__remove')
		);
	});

	$(document).on('click', '.wcp-image-field__remove', function (e) {
		e.preventDefault();

		var $field = $(this).closest('.wcp-image-field');
		$field.find('.wcp-image-field__input').val('');
		$field.find('.wcp-image-field__preview').empty();
		$(this).addClass('hidden');
	});

	function toggleParfumFields() {
		var template = $('#wcp_template').val();
		var isParfum = template === 'parfum_landing';

		$('.wcp-field').each(function () {
			var slug = $(this).data('wcp-field');
			if (slug === 'template') {
				return;
			}
			$(this).toggle(isParfum);
		});
	}

	$(document).on('change', '#wcp_template', toggleParfumFields);
	$(toggleParfumFields);
})(jQuery);
