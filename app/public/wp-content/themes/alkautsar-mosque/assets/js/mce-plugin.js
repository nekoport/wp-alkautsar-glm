/*!
 * Al-Kautsar TinyMCE Plugin
 * Adds custom buttons to the Classic Editor toolbar:
 *   - "Kotak Donasi" — insert donation banner
 *   - "Pengumuman" — insert announcement box
 */

(function() {
	'use strict';

	tinymce.PluginManager.add('alkautsar_mce', function(editor, url) {

		// ─── Button: Kotak Donasi ─────────────────────────
		editor.addButton('alkautsar_donasi', {
			title: 'Tambah Kotak Donasi',
			icon: 'charmap', // pakai icon bawaan tinyMCE (will be styled via CSS)
			onclick: function() {
				var donasiUrl = '/';
				try {
					donasiUrl = (typeof alkautsarMceData !== 'undefined' && alkautsarMceData.donasiUrl) ? alkautsarMceData.donasiUrl : '/donasi';
				} catch (e) {
					donasiUrl = '/donasi';
				}

				var content =
					'<div class="akp-banner-donasi">' +
						'<h3>Salurkan Donasi Terbaik Anda</h3>' +
						'<p>Setiap rupiah yang Anda salurkan menjadi sebab turunnya berkah dan terpeliharanya rumah Allah. Donasi dapat melalui transfer bank atau QRIS.</p>' +
						'<p style="text-align:center;margin-top:1.5rem;">' +
							'<a href="' + donasiUrl + '" class="btn btn--gold" style="display:inline-block;padding:0.875rem 1.75rem;background:linear-gradient(135deg,#D4AF37 0%,#B8901E 100%);color:#3B1E12;font-weight:700;border-radius:999px;text-decoration:none;">Donasi Sekarang</a>' +
						'</p>' +
					'</div><p>&nbsp;</p>';

				editor.insertContent(content);
				editor.notificationManager.open({
					text: 'Kotak Donasi berhasil ditambahkan',
					type: 'success',
					timeout: 2000
				});
			}
		});

		// ─── Button: Pengumuman ───────────────────────────
		editor.addButton('alkautsar_pengumuman', {
			title: 'Tambah Pengumuman Penting',
			icon: 'blockquote', // pakai icon bawaan
			onclick: function() {
				var content =
					'<div class="akp-pengumuman">' +
						'<h3>Pengumuman Penting</h3>' +
						'<p>Tuliskan isi pengumuman di sini. Contoh: "Diberitahukan kepada seluruh jamaah, bahwa mulai tanggal 1 Ramadhan 1446 H, Tarawih berjamaah akan dimulai pukul 19.30 WIB setelah Isya."</p>' +
					'</div><p>&nbsp;</p>';

				editor.insertContent(content);
				editor.notificationManager.open({
					text: 'Pengumuman berhasil ditambahkan',
					type: 'success',
					timeout: 2000
				});
			}
		});

		// ─── Style toolbar buttons supaya terlihat seperti shortcut ──
		editor.on('init', function() {
			var iframe = editor.getDoc();
			if (!iframe) return;

			var style = iframe.createElement('style');
			style.innerHTML = [
				'.mce-ico.mce-i-charmap[aria-label*="Donasi"]::before,',
				'.mce-ico.mce-i-charmap[aria-label*="Kotak"]::before {',
				'  content: "Rp";',
				'  font-family: inherit;',
				'  font-weight: bold;',
				'  font-size: 11px;',
				'  color: #B8901E;',
				'}',
				'.mce-ico.mce-i-blockquote[aria-label*="Pengumuman"]::before,',
				'.mce-ico.mce-i-blockquote[aria-label*="Tambah"]::before {',
				'  content: "!";',
				'  font-family: inherit;',
				'  font-weight: bold;',
				'  font-size: 14px;',
				'  color: #3B1E12;',
				'}'
			].join('\n');
			iframe.head.appendChild(style);
		});
	});
})();
