/*!
 * Al-Kautsar Settings Page — Media uploader & helpers
 */
(function($) {
	'use strict';

	$(document).ready(function() {

		// Image upload button.
		$(document).on('click', '.alkautsar-upload-btn', function(e) {
			e.preventDefault();
			var target = $(this).data('target');
			var $input = $('#' + target);
			var $preview = $(this).closest('td').find('.alkautsar-image-preview');

			var frame = wp.media({
				title: 'Pilih / Upload Gambar',
				library: { type: 'image' },
				multiple: false
			});

			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				$input.val(attachment.url);
				$preview.html('<img src="' + attachment.url + '" alt="" style="max-width:100%; max-height:100%; object-fit:cover;">');
			});

			frame.open();
		});

		// Clear image button.
		$(document).on('click', '.alkautsar-clear-btn', function(e) {
			e.preventDefault();
			var target = $(this).data('target');
			var $input = $('#' + target);
			var $preview = $(this).closest('td').find('.alkautsar-image-preview');
			$input.val('');
			$preview.html('<span style="font-size:11px; color:#999;">No image</span>');
		});

		// Live preview: format Rupiah for finance fields.
		function formatRupiah(angka) {
			angka = String(angka).replace(/[^0-9]/g, '');
			if (!angka) return 'Rp 0';
			return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
		}

		var $incomeInput = $('#alkautsar_finance_total_income');
		var $expenseInput = $('#alkautsar_finance_total_expense');

		if ($incomeInput.length && $expenseInput.length) {
			// Add preview span.
			$incomeInput.after('<p class="alkautsar-preview" style="font-size:13px; color:#128C7E; margin-top:4px; font-weight:600;"></p>');
			$expenseInput.after('<p class="alkautsar-preview" style="font-size:13px; color:#B85C00; margin-top:4px; font-weight:600;"></p>');

			function updateFinancePreview() {
				var inc = $incomeInput.val();
				var exp = $expenseInput.val();
				$incomeInput.next('.alkautsar-preview').text(inc ? 'Preview: ' + formatRupiah(inc) : '');
				$expenseInput.next('.alkautsar-preview').text(exp ? 'Preview: ' + formatRupiah(exp) : '');
			}

			$incomeInput.on('input', updateFinancePreview);
			$expenseInput.on('input', updateFinancePreview);
			updateFinancePreview();
		}

		// Hide Permalink notice on settings pages.
		$('.alkautsar-settings-page #permalink-notice').hide();

		// Confirmation when leaving with unsaved changes.
		var formChanged = false;
		$('.alkautsar-settings-page form').on('change input', 'input, textarea, select', function() {
			formChanged = true;
		});
		$('.alkautsar-settings-page form').on('submit', function() {
			formChanged = false;
		});
		$(window).on('beforeunload', function() {
			if (formChanged) {
				return 'Perubahan belum disimpan. Yakin mau keluar?';
			}
		});
	});
})(jQuery);
