;(function ($, window, document, undefined) {
	"use strict";

	if ( $('.ywca-field-sorter').length ) {

		$('.ywca-field-sorter').each( function() {

			var $this     = $(this),
				$enabled  = $this.find('.ywca-enabled'),
				$disabled = $this.find('.ywca-disabled');

			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				update: function( event, ui ) {

					var $el = ui.item.find('input');

					if( ui.item.parent().hasClass('ywca-enabled') ) {
						$el.attr('name', $el.attr('name').replace('disabled', 'ywca_settings'));
					} else {
						$el.attr('name', $el.attr('name').replace('ywca_settings', 'disabled'));
					}

				}
			});

			// avoid conflict
			$disabled.sortable({
				connectWith: $enabled,
				placeholder: 'ui-sortable-placeholder'
			});
		});
	}

})(jQuery, window, document);