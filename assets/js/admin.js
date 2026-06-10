(function ($) {
	'use strict';

	var TEMPLATE_PARFUM  = (window.wcpAdmin && window.wcpAdmin.templateParfum)  || 'parfum_landing';
	var TEMPLATE_DEFAULT = (window.wcpAdmin && window.wcpAdmin.templateDefault) || 'default';

	function forceDisplay($elements, display) {
		$elements.each(function () {
			if (display === false) {
				this.style.setProperty('display', 'none', 'important');
			} else {
				this.style.removeProperty('display');
			}
		});
	}

	function getDescriptionEditorPanels() {
		return $([
			'#postdivrich',
			'#wp-content-wrap'
		].join(','));
	}

	function setTemplateMode(template) {
		var mode = template === TEMPLATE_PARFUM ? TEMPLATE_PARFUM : TEMPLATE_DEFAULT;
		var isParfum = mode === TEMPLATE_PARFUM;
		var $visualEditor = $('#wcp-parfum-visual-editor');

		$('body')
			.removeClass('wcp-edit-mode-default wcp-edit-mode-parfum_landing')
			.addClass('wcp-edit-mode-' + mode);

		document.body.setAttribute('data-wcp-template', mode);

		forceDisplay($visualEditor, isParfum);
		forceDisplay(getDescriptionEditorPanels(), !isParfum);
	}

	function scheduleTemplateMode(template) {
		setTemplateMode(template);

		[50, 250, 1000].forEach(function (delay) {
			window.setTimeout(function () {
				setTemplateMode(getSelectedTemplate());
			}, delay);
		});
	}

	function getSelectedTemplate() {
		var $select = $('#wcp_template');
		if (!$select.length) {
			return TEMPLATE_DEFAULT;
		}

		var value = $select.val();
		return value === TEMPLATE_PARFUM ? TEMPLATE_PARFUM : TEMPLATE_DEFAULT;
	}

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

	function updateBackgroundPreview() {
		var $input = $('#wcp_background_image');
		var $canvas = $('#wcp-visual-canvas');

		if (!$canvas.length) {
			return;
		}

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
	}

	function initTemplateToggle() {
		var $select = $('#wcp_template');

		if (!$select.length) {
			setTemplateMode(TEMPLATE_DEFAULT);
			return;
		}

		$select.on('change', function () {
			scheduleTemplateMode(this.value);
		});

		scheduleTemplateMode($select.val());
	}

	function init() {
		bindImageFields();
		initTemplateToggle();
		updateBackgroundPreview();
	}

	$(init);

	window.WCPApplyTemplateMode = function () {
		scheduleTemplateMode(getSelectedTemplate());
	};
})(jQuery);
