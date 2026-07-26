(function ($) {
	'use strict';

	var TEMPLATE_PARFUM    = (window.wcpAdmin && window.wcpAdmin.templateParfum) || 'parfum_landing';
	var TEMPLATE_DISCOVERY = (window.wcpAdmin && window.wcpAdmin.templateDiscovery) || 'discovery_kit';
	var TEMPLATE_DEFAULT   = (window.wcpAdmin && window.wcpAdmin.templateDefault) || 'default';

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
		return $('#postdivrich');
	}

	function restoreStandardPostboxes() {
		$('#poststuff .postbox').each(function () {
			var id = this.id || '';
			if (id === 'wcp-parfum-visual-editor' || id === 'wcp-discovery-visual-editor') {
				return;
			}

			this.style.removeProperty('display');
		});

		forceDisplay(getDescriptionEditorPanels(), true);
		$('#wp-content-wrap').each(function () {
			this.style.removeProperty('display');
		});
	}

	function setEditorInputsEnabled($editor, enabled) {
		if (!$editor.length) {
			return;
		}

		$editor.find(':input').prop('disabled', !enabled);
	}

	function showTemplateReloadNotice(mode) {
		var $notice = $('#wcp-template-reload-notice');
		var needsReload = false;

		if (mode === TEMPLATE_PARFUM && !$('#wcp-parfum-visual-editor').length) {
			needsReload = true;
		}
		if (mode === TEMPLATE_DISCOVERY && !$('#wcp-discovery-visual-editor').length) {
			needsReload = true;
		}
		if (mode === TEMPLATE_DEFAULT) {
			needsReload = false;
		}

		if (!needsReload) {
			$notice.remove();
			return;
		}

		if (!$notice.length) {
			$notice = $('<div id="wcp-template-reload-notice" class="notice notice-warning"><p></p></div>');
			$('#wcp-template-selector').prepend($notice);
		}

		$notice.find('p').text('Salveaza produsul, apoi reincarca pagina pentru a incarca editorul vizual al template-ului selectat.');
	}

	function setTemplateMode(template) {
		var mode = TEMPLATE_DEFAULT;
		if (template === TEMPLATE_PARFUM) {
			mode = TEMPLATE_PARFUM;
		} else if (template === TEMPLATE_DISCOVERY) {
			mode = TEMPLATE_DISCOVERY;
		}

		var isParfum = mode === TEMPLATE_PARFUM;
		var isDiscovery = mode === TEMPLATE_DISCOVERY;
		var isCustom = isParfum || isDiscovery;
		var $parfumEditor = $('#wcp-parfum-visual-editor');
		var $discoveryEditor = $('#wcp-discovery-visual-editor');

		$('body')
			.removeClass('wcp-edit-mode-default wcp-edit-mode-parfum_landing wcp-edit-mode-discovery_kit')
			.addClass('wcp-edit-mode-' + mode);

		document.body.setAttribute('data-wcp-template', mode);

		// Always restore standard Woo/Yoast/other metaboxes first.
		restoreStandardPostboxes();

		forceDisplay($parfumEditor, isParfum);
		forceDisplay($discoveryEditor, isDiscovery);

		if (isCustom) {
			forceDisplay(getDescriptionEditorPanels(), false);
		}

		setEditorInputsEnabled($parfumEditor, isParfum);
		setEditorInputsEnabled($discoveryEditor, isDiscovery);
		showTemplateReloadNotice(mode);
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
		if (value === TEMPLATE_PARFUM || value === TEMPLATE_DISCOVERY) {
			return value;
		}

		return TEMPLATE_DEFAULT;
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

	function updateBackgroundPreviewForInput($input) {
		var $canvas = $input.closest('.wcp-visual-editor').find('.wcp-visual-canvas');
		if (!$canvas.length) {
			$canvas = $('.wcp-visual-canvas');
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

	function updateBackgroundPreview() {
		$('input[name="wcp_background_image"]:not(:disabled)').each(function () {
			updateBackgroundPreviewForInput($(this));
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

		$(document).on('change', 'input[name="wcp_background_image"]', function () {
			updateBackgroundPreviewForInput($(this));
		});
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

	function getWooPriceLabel() {
		var $priceInput = $('#_regular_price');
		if ($priceInput.length && $priceInput.val()) {
			return $priceInput.val() + ' ' + ((window.wcpAdmin && window.wcpAdmin.currencySymbol) || 'RON');
		}

		return '150 RON';
	}

	function updatePricePreview() {
		var $modeSelect = $('select[name="wcp_price_display_mode"]:not(:disabled)').first();
		var mode = $modeSelect.length ? $modeSelect.val() : 'above_button';
		var priceLabel = getWooPriceLabel();
		var $previews = $('[data-wcp-cta-preview]');

		if (!$previews.length) {
			return;
		}

		if (mode === 'in_button') {
			$previews.text('COMANDA ACUM - ' + priceLabel);
			return;
		}

		$previews.html('PRET - ' + priceLabel + '<br>COMANDA ACUM');
	}

	function bindPricePreview() {
		$(document).on('change', 'select[name="wcp_price_display_mode"]', updatePricePreview);
		$(document).on('input change', '#_regular_price', updatePricePreview);
		updatePricePreview();
	}

	function init() {
		bindImageFields();
		initTemplateToggle();
		updateBackgroundPreview();
		bindPricePreview();
	}

	$(init);

	window.WCPApplyTemplateMode = function () {
		scheduleTemplateMode(getSelectedTemplate());
	};
})(jQuery);
