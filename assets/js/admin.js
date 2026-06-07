(function ($) {
	'use strict';

	var config = window.wcpAdmin || {};

	function openMediaFrame($input, $preview, $remove) {
		var frame = wp.media({
			title: 'Selecteaza imagine',
			button: { text: 'Foloseste aceasta imagine' },
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

			$input.trigger('change');
		});

		frame.open();
	}

	function updateBodyClass(template) {
		var $body = $('body');
		$body.removeClass('wcp-edit-mode-default wcp-edit-mode-parfum_landing');

		if (template === config.templateParfum) {
			$body.addClass('wcp-edit-mode-parfum_landing');
		} else {
			$body.addClass('wcp-edit-mode-default');
		}
	}

	function updateBackgroundPreview() {
		var $input = $('#wcp_background_image');
		var $canvas = $('#wcp-visual-canvas');
		var attachmentId = parseInt($input.val(), 10);

		if (!attachmentId) {
			$canvas.css('background-image', '');
			return;
		}

		var attachment = wp.media.attachment(attachmentId);
		attachment.fetch().then(function () {
			var url = attachment.get('url');
			if (url) {
				$canvas.css('background-image', 'url(' + url + ')');
			}
		});
	}

	function updateLogoMirror() {
		var $source = $('#wcp_title_logo_image');
		var $mirror = $('[data-wcp-mirror="wcp_title_logo_image"] .wcp-visual-mirror__preview');
		var $sourcePreview = $source.closest('.wcp-image-field').find('.wcp-image-field__preview');

		if (!$mirror.length || !$sourcePreview.length) {
			return;
		}

		var $img = $sourcePreview.find('img');
		if ($img.length) {
			$mirror.html('<img src="' + $img.attr('src') + '" alt="" />');
		} else {
			$mirror.html('<span class="wcp-image-field__placeholder">Aceasta imagine apare si jos stanga</span>');
		}
	}

	function bindImageFields() {
		$(document).on('click', '.wcp-image-field__upload', function (e) {
			e.preventDefault();

			var $field = $(this).closest('.wcp-image-field');
			openMediaFrame(
				$field.find('.wcp-image-field__input'),
				$field.find('.wcp-image-field__preview'),
				$field.find('.wcp-image-field__remove')
			);
		});

		$(document).on('click', '.wcp-image-field__remove', function (e) {
			e.preventDefault();

			var $field = $(this).closest('.wcp-image-field');
			$field.find('.wcp-image-field__input').val('').trigger('change');
			$field.find('.wcp-image-field__preview').html('<span class="wcp-image-field__placeholder">Fara imagine</span>');
			$(this).addClass('hidden');
		});

		$(document).on('change', '#wcp_background_image', updateBackgroundPreview);
		$(document).on('change', '#wcp_title_logo_image', updateLogoMirror);
	}

	function initTemplateToggle() {
		var $select = $('#wcp_template');

		if (!$select.length) {
			return;
		}

		$select.on('change', function () {
			updateBodyClass($(this).val());
		});

		updateBodyClass($select.val());
	}

	$(function () {
		bindImageFields();
		initTemplateToggle();
		updateBackgroundPreview();
		updateLogoMirror();
	});
})(jQuery);
