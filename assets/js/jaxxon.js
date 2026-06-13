(function () {
	'use strict';

	document.documentElement.classList.add('av-js');

	var header = document.querySelector('.site-header');
	if (header) {
		window.addEventListener('scroll', function () {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		});
	}

	initProductPage();

	var nav = document.querySelector('.av-catalog-nav');
	if (!nav) {
		return;
	}

	initHeaderLocale(nav);

	var drawer = nav.querySelector('#av-catalog-drawer');
	var toggle = nav.querySelector('.av-catalog-nav__toggle');
	var closeBtn = nav.querySelector('.av-catalog-drawer__close');
	var shopItem = nav.querySelector('.av-catalog-nav__shop');
	var shopTrigger = nav.querySelector('.av-catalog-nav__shop-trigger');
	var mega = nav.querySelector('#av-shop-mega');
	var accordion = nav.querySelector('.av-catalog-drawer__accordion');
	var accordionTrigger = nav.querySelector('.av-catalog-drawer__accordion-trigger');
	var accordionPanel = nav.querySelector('.av-catalog-drawer__sub');

	function setExpanded(button, expanded) {
		if (button) {
			button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
		}
	}

	function openDrawer() {
		if (!drawer) {
			return;
		}
		drawer.hidden = false;
		document.body.classList.add('av-nav-open');
		setExpanded(toggle, true);
	}

	function closeDrawer() {
		if (!drawer) {
			return;
		}
		drawer.hidden = true;
		document.body.classList.remove('av-nav-open');
		setExpanded(toggle, false);
	}

	function closeMega() {
		if (!mega) {
			return;
		}
		mega.hidden = true;
		if (shopItem) {
			shopItem.classList.remove('is-open');
		}
		setExpanded(shopTrigger, false);
	}

	function syncMegaTop() {
		if (!mega || !header) {
			return;
		}
		document.documentElement.style.setProperty(
			'--av-mega-top',
			Math.round(header.getBoundingClientRect().bottom) + 'px'
		);
	}

	function openMega() {
		if (!mega) {
			return;
		}
		syncMegaTop();
		mega.hidden = false;
		if (shopItem) {
			shopItem.classList.add('is-open');
		}
		setExpanded(shopTrigger, true);
	}

	if (toggle) {
		toggle.addEventListener('click', function () {
			if (drawer && drawer.hidden) {
				openDrawer();
			} else {
				closeDrawer();
			}
		});
	}

	if (closeBtn) {
		closeBtn.addEventListener('click', closeDrawer);
	}

	if (drawer) {
		drawer.addEventListener('click', function (event) {
			if (event.target === drawer) {
				closeDrawer();
			}
		});
	}

	if (shopTrigger && mega) {
		var megaCloseTimer = null;
		var desktopMega = window.matchMedia('(min-width: 769px)');

		function cancelMegaClose() {
			if (megaCloseTimer) {
				clearTimeout(megaCloseTimer);
				megaCloseTimer = null;
			}
		}

		function scheduleMegaClose() {
			cancelMegaClose();
			megaCloseTimer = window.setTimeout(closeMega, 220);
		}

		shopTrigger.addEventListener('click', function (event) {
			event.preventDefault();
			if (mega.hidden) {
				openMega();
			} else {
				closeMega();
			}
		});

		document.addEventListener('click', function (event) {
			if (shopItem.contains(event.target) || mega.contains(event.target)) {
				return;
			}
			closeMega();
		});

		shopItem.addEventListener('mouseenter', function () {
			if (desktopMega.matches) {
				cancelMegaClose();
				openMega();
			}
		});

		shopItem.addEventListener('mouseleave', function () {
			if (desktopMega.matches) {
				scheduleMegaClose();
			}
		});

		mega.addEventListener('mouseenter', function () {
			if (desktopMega.matches) {
				cancelMegaClose();
				openMega();
			}
		});

		mega.addEventListener('mouseleave', function () {
			if (desktopMega.matches) {
				scheduleMegaClose();
			}
		});

		window.addEventListener('scroll', function () {
			if (!mega.hidden) {
				syncMegaTop();
			}
		}, { passive: true });

		window.addEventListener('resize', function () {
			if (!mega.hidden) {
				syncMegaTop();
			}
		});
	}

	if (accordionTrigger && accordionPanel && accordion) {
		accordionTrigger.addEventListener('click', function () {
			var expanded = accordionTrigger.getAttribute('aria-expanded') === 'true';
			accordionPanel.hidden = expanded;
			setExpanded(accordionTrigger, !expanded);
			accordion.classList.toggle('is-open', !expanded);
		});
	}

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeDrawer();
			closeMega();
		}
	});

	document.querySelectorAll('[data-av-category-rail], [data-av-featured-collections]').forEach(function (rail) {
		var track = rail.querySelector('.av-category-rail__track') || rail;
		var isDown = false;
		var startX = 0;
		var scrollLeft = 0;

		rail.addEventListener('mousedown', function (event) {
			isDown = true;
			startX = event.pageX - track.offsetLeft;
			scrollLeft = track.scrollLeft;
		});

		['mouseleave', 'mouseup'].forEach(function (name) {
			rail.addEventListener(name, function () {
				isDown = false;
			});
		});

		rail.addEventListener('mousemove', function (event) {
			if (!isDown) {
				return;
			}
			event.preventDefault();
			var walk = (event.pageX - track.offsetLeft - startX) * 1.2;
			track.scrollLeft = scrollLeft - walk;
		});
	});

	function initHeaderLocale(nav) {
		var wraps = nav.querySelectorAll('.av-header-utilities__locale-wrap');
		if (!wraps.length) {
			return;
		}

		function closeMenu(wrap) {
			var menu = wrap.querySelector('.av-header-utilities__locale-menu');
			var btn = wrap.querySelector('.av-header-utilities__locale-btn');
			if (!menu || !btn) {
				return;
			}
			menu.hidden = true;
			btn.setAttribute('aria-expanded', 'false');
		}

		function closeAllMenus(exceptWrap) {
			wraps.forEach(function (wrap) {
				if (wrap !== exceptWrap) {
					closeMenu(wrap);
				}
			});
		}

		function applyCountry(country) {
			wraps.forEach(function (wrap) {
				var btn = wrap.querySelector('.av-header-utilities__locale-btn');
				var options = wrap.querySelectorAll('.av-header-utilities__locale-option');
				var flagHost = btn ? btn.querySelector('.av-header-utilities__flag') : null;
				var activeOption = wrap.querySelector('.av-header-utilities__locale-option[data-country="' + country + '"]');
				var flag = activeOption ? activeOption.querySelector('.av-header-utilities__flag') : null;

				if (btn) {
					btn.setAttribute('data-country', country);
				}
				if (flag && flagHost) {
					flagHost.className = flag.className;
					flagHost.innerHTML = flag.innerHTML;
				}
				options.forEach(function (opt) {
					opt.classList.toggle('is-active', opt.getAttribute('data-country') === country);
				});
			});

			try {
				window.localStorage.setItem('av-header-country', country);
			} catch (err) {
				// Ignore storage errors.
			}
		}

		wraps.forEach(function (wrap) {
			var btn = wrap.querySelector('.av-header-utilities__locale-btn');
			var menu = wrap.querySelector('.av-header-utilities__locale-menu');
			var options = wrap.querySelectorAll('.av-header-utilities__locale-option');

			if (!btn || !menu) {
				return;
			}

			btn.addEventListener('click', function () {
				if (menu.hidden) {
					closeAllMenus(wrap);
					menu.hidden = false;
					btn.setAttribute('aria-expanded', 'true');
				} else {
					closeMenu(wrap);
				}
			});

			options.forEach(function (option) {
				option.addEventListener('click', function () {
					applyCountry(option.getAttribute('data-country'));
					closeAllMenus(null);
				});
			});
		});

		document.addEventListener('click', function (event) {
			wraps.forEach(function (wrap) {
				if (!wrap.contains(event.target)) {
					closeMenu(wrap);
				}
			});
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key) {
				closeAllMenus(null);
			}
		});

		try {
			var saved = window.localStorage.getItem('av-header-country');
			if (saved) {
				applyCountry(saved);
			}
		} catch (err) {
			// Ignore storage errors.
		}
	}

	function initProductPage() {
		var pdp = document.querySelector('.av-pdp');
		if (!pdp) {
			return;
		}

		pdp.querySelectorAll('.av-pdp__swatch').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var option = btn.closest('.av-pdp__option');
				if (!option) {
					return;
				}
				var select = option.querySelector('select');
				if (!select) {
					return;
				}
				select.value = btn.getAttribute('data-value');
				select.dispatchEvent(new Event('change', { bubbles: true }));
				option.querySelectorAll('.av-pdp__swatch').forEach(function (swatch) {
					swatch.classList.toggle('is-selected', swatch === btn);
				});
			});
		});

		document.addEventListener('woocommerce_variation_has_changed', function () {
			pdp.querySelectorAll('.av-pdp__option').forEach(function (option) {
				var select = option.querySelector('select');
				if (!select) {
					return;
				}
				option.querySelectorAll('.av-pdp__swatch').forEach(function (swatch) {
					swatch.classList.toggle('is-selected', swatch.getAttribute('data-value') === select.value);
				});
			});
		});

		initPdpStockBadge(pdp);
		initPdpVariationPrice(pdp);
		initPdpGalleryVideo(pdp);
		initPdpGalleryFrame(pdp);
		initPdpQuantityStepper(pdp);
	}

	function initPdpGalleryFrame(pdp) {
		var gallery = pdp.querySelector('.woocommerce-product-gallery');
		if (!gallery) {
			return;
		}

		function applyPortraitFrame() {
			var viewport = gallery.querySelector('.flex-viewport');
			if (!viewport || !viewport.offsetWidth) {
				return;
			}
			var targetHeight = Math.round(viewport.offsetWidth * 1.25);
			viewport.style.height = targetHeight + 'px';
			gallery.querySelectorAll('.woocommerce-product-gallery__image').forEach(function (slide) {
				slide.style.height = targetHeight + 'px';
				var link = slide.querySelector('a');
				if (link) {
					link.style.height = '100%';
				}
			});
		}

		applyPortraitFrame();
		window.addEventListener('resize', applyPortraitFrame);

		if (window.jQuery) {
			window.jQuery(gallery).on('woocommerce_gallery_init_slider', applyPortraitFrame);
			window.jQuery(gallery).on('woocommerce_gallery_after_slide', applyPortraitFrame);
		}

		window.setTimeout(applyPortraitFrame, 120);
		window.setTimeout(applyPortraitFrame, 600);
	}

	function initPdpQuantityStepper(pdp) {
		var wraps = pdp.querySelectorAll('.quantity');
		wraps.forEach(function (wrap) {
			if (wrap.classList.contains('av-pdp__qty-stepper--ready')) {
				return;
			}

			var input = wrap.querySelector('input.qty');
			if (!input) {
				return;
			}

			wrap.classList.add('av-pdp__qty-stepper', 'av-pdp__qty-stepper--ready');

			var minus = document.createElement('button');
			minus.type = 'button';
			minus.className = 'av-pdp__qty-btn av-pdp__qty-btn--minus';
			minus.setAttribute('aria-label', 'Decrease quantity');
			minus.textContent = '\u2212';

			var plus = document.createElement('button');
			plus.type = 'button';
			plus.className = 'av-pdp__qty-btn av-pdp__qty-btn--plus';
			plus.setAttribute('aria-label', 'Increase quantity');
			plus.textContent = '+';

			wrap.insertBefore(minus, input);
			wrap.appendChild(plus);

			function getMin() {
				return input.min !== '' ? Number(input.min) : 1;
			}

			function getMax() {
				return input.max !== '' ? Number(input.max) : Infinity;
			}

			function getVal() {
				return Number(input.value) || getMin();
			}

			function syncButtons() {
				var val = getVal();
				minus.disabled = val <= getMin();
				plus.disabled = val >= getMax();
			}

			function setVal(next) {
				var min = getMin();
				var max = getMax();
				var val = Math.max(min, Math.min(max, next));
				input.value = String(val);
				input.dispatchEvent(new Event('change', { bubbles: true }));
				syncButtons();
			}

			minus.addEventListener('click', function () {
				setVal(getVal() - 1);
			});

			plus.addEventListener('click', function () {
				setVal(getVal() + 1);
			});

			input.addEventListener('change', function () {
				setVal(getVal());
			});

			syncButtons();
		});

		if (!window.jQuery) {
			return;
		}

		var form = pdp.querySelector('form.variations_form');
		if (!form) {
			return;
		}

		window.jQuery(form).on('found_variation', function (event, variation) {
			var input = pdp.querySelector('.quantity input.qty');
			if (!input || !variation) {
				return;
			}
			if (typeof variation.min_qty !== 'undefined') {
				input.min = variation.min_qty;
			}
			if (typeof variation.max_qty !== 'undefined' && variation.max_qty) {
				input.max = variation.max_qty;
			} else {
				input.removeAttribute('max');
			}
			var wrap = input.closest('.quantity');
			if (wrap && wrap.classList.contains('av-pdp__qty-stepper--ready')) {
				var val = Number(input.value) || 1;
				var max = input.max !== '' ? Number(input.max) : val;
				if (val > max) {
					input.value = String(max);
				}
				input.dispatchEvent(new Event('change', { bubbles: true }));
			}
		});
	}

	function initPdpGalleryVideo(pdp) {
		var config = window.asheravaPdpGalleryVideo;
		if (!config || !config.url) {
			return;
		}

		function mountPdpGalleryVideo() {
			var gallery = pdp.querySelector('.woocommerce-product-gallery');
			if (!gallery) {
				return false;
			}

			var thumbs = gallery.querySelector('.flex-control-thumbs');
			var wrapper = gallery.querySelector('.woocommerce-product-gallery__wrapper');
			if (!thumbs || !wrapper) {
				return false;
			}

			if (thumbs.querySelector('.av-pdp__gallery-video-thumb')) {
				pdp.dataset.avGalleryVideoReady = '1';
				return true;
			}

			if (pdp.dataset.avGalleryVideoReady === '1') {
				pdp.dataset.avGalleryVideoReady = '';
			}

		var poster = config.poster || '';
		var slide = document.createElement('div');
		slide.className = 'woocommerce-product-gallery__image av-pdp__gallery-video-slide';
		slide.setAttribute('data-thumb', poster);
		slide.hidden = true;
		slide.innerHTML = buildGalleryVideoMarkup(config.url, poster);
		wrapper.appendChild(slide);

		var thumb = document.createElement('li');
		thumb.className = 'av-pdp__gallery-video-thumb';
		thumb.innerHTML =
			'<button type="button" class="av-pdp__gallery-video-thumb-btn" aria-label="' +
			(config.i18nPlay || 'Play product video') +
			'">' +
			(poster ? '<img src="' + poster + '" alt="" loading="lazy" decoding="async" />' : '<span class="av-pdp__gallery-video-thumb-fallback"></span>') +
			'<span class="av-pdp__gallery-video-play" aria-hidden="true"></span>' +
			'</button>';
		thumbs.appendChild(thumb);

		var imageSlides = wrapper.querySelectorAll('.woocommerce-product-gallery__image:not(.av-pdp__gallery-video-slide)');
		var imageThumbs = thumbs.querySelectorAll('li:not(.av-pdp__gallery-video-thumb)');
		var videoEl = slide.querySelector('video');
		var iframeEl = slide.querySelector('iframe');

		function pauseVideo() {
			if (videoEl) {
				videoEl.pause();
			}
			if (iframeEl && iframeEl.src) {
				iframeEl.dataset.src = iframeEl.src;
				iframeEl.src = 'about:blank';
			}
		}

		function showVideo() {
			imageSlides.forEach(function (item) {
				item.style.display = 'none';
			});
			slide.hidden = false;
			slide.style.display = 'block';
			imageThumbs.forEach(function (item) {
				item.classList.remove('flex-active');
			});
			thumb.classList.add('flex-active');
			if (videoEl) {
				videoEl.play().catch(function () {});
			}
			if (iframeEl && iframeEl.dataset.src && iframeEl.src === 'about:blank') {
				iframeEl.src = iframeEl.dataset.src;
			}
		}

		function showImage(index) {
			pauseVideo();
			slide.hidden = true;
			slide.style.display = 'none';
			imageSlides.forEach(function (item, i) {
				item.style.display = i === index ? 'block' : 'none';
			});
			thumb.classList.remove('flex-active');
			imageThumbs.forEach(function (item, i) {
				item.classList.toggle('flex-active', i === index);
			});
		}

		thumb.querySelector('button').addEventListener('click', function (event) {
			event.preventDefault();
			showVideo();
		});

		imageThumbs.forEach(function (item, index) {
			item.addEventListener('click', function () {
				showImage(index);
			});
		});

			pdp.dataset.avGalleryVideoReady = '1';
			return true;
		}

		if (mountPdpGalleryVideo()) {
			return;
		}

		var gallery = pdp.querySelector('.woocommerce-product-gallery');
		if (gallery && window.jQuery) {
			window.jQuery(gallery).on('woocommerce_gallery_init_slider', mountPdpGalleryVideo);
		}

		window.setTimeout(mountPdpGalleryVideo, 120);
		window.setTimeout(mountPdpGalleryVideo, 600);
	}

	function buildGalleryVideoMarkup(url, poster) {
		var youtubeId = extractYoutubeId(url);
		if (youtubeId) {
			return (
				'<div class="av-pdp__video-embed">' +
				'<iframe src="https://www.youtube.com/embed/' +
				youtubeId +
				'?rel=0&playsinline=1" title="Product video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>' +
				'</div>'
			);
		}

		var vimeoId = extractVimeoId(url);
		if (vimeoId) {
			return (
				'<div class="av-pdp__video-embed">' +
				'<iframe src="https://player.vimeo.com/video/' +
				vimeoId +
				'?title=0&byline=0&portrait=0" title="Product video" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>' +
				'</div>'
			);
		}

		return (
			'<video class="av-pdp__video-native" controls playsinline preload="metadata"' +
			(poster ? ' poster="' + poster + '"' : '') +
			'><source src="' +
			url +
			'" type="video/mp4" /></video>'
		);
	}

	function extractYoutubeId(url) {
		var match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/);
		return match ? match[1] : '';
	}

	function extractVimeoId(url) {
		var match = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
		return match ? match[1] : '';
	}

	function initPdpVariationPrice(pdp) {
		var buybox = pdp.querySelector('.av-pdp__buybox');
		var priceEl = buybox ? buybox.querySelector(':scope > .price .av-pdp__price-display') : null;
		var form = pdp.querySelector('form.variations_form');

		if (!priceEl || !form || !window.jQuery) {
			return;
		}

		var defaultHtml = priceEl.innerHTML;
		var $form = window.jQuery(form);

		$form.on('found_variation', function (event, variation) {
			if (!variation || !variation.price_html) {
				return;
			}
			var tmp = document.createElement('div');
			tmp.innerHTML = variation.price_html;
			var inner = tmp.querySelector('.av-pdp__price-display');
			if (inner) {
				priceEl.innerHTML = inner.innerHTML;
				return;
			}
			var amount = tmp.querySelector('.woocommerce-Price-amount');
			priceEl.innerHTML = amount ? amount.outerHTML : variation.price_html;
		});

		$form.on('reset_data hide_variation', function () {
			priceEl.innerHTML = defaultHtml;
		});
	}

	function shouldShowLowStockBadge(stock, threshold) {
		if (!stock || !stock.in_stock) {
			return false;
		}
		if (stock.qty === null || typeof stock.qty === 'undefined') {
			return false;
		}
		var qty = Number(stock.qty);
		return qty > 0 && qty <= threshold;
	}

	function updateStockBadge(pdp, stock, config) {
		var badge = pdp.querySelector('#av-pdp-stock-badge');
		if (!badge) {
			return;
		}
		var span = badge.querySelector('.av-pdp__stock');
		var threshold = Number(config.lowStockThreshold) || 10;
		if (!shouldShowLowStockBadge(stock, threshold) || !span) {
			badge.setAttribute('hidden', 'hidden');
			return;
		}
		var template = config.i18nOnlyLeft || 'Only %d left!';
		span.className = 'av-pdp__stock av-pdp__stock--low';
		span.textContent = template.replace('%d', String(stock.qty));
		badge.removeAttribute('hidden');
	}

	function initPdpStockBadge(pdp) {
		var config = window.asheravaPdpStock;
		if (!config) {
			return;
		}

		if (!config.isVariable) {
			updateStockBadge(pdp, config.simple, config);
			return;
		}

		var form = pdp.querySelector('form.variations_form');
		if (!form || !window.jQuery) {
			return;
		}

		var $form = window.jQuery(form);
		var map = config.variations || {};

		updateStockBadge(pdp, null, config);

		$form.on('found_variation', function (event, variation) {
			var stock = map[variation.variation_id];
			if (!stock && variation.is_in_stock) {
				stock = {
					in_stock: true,
					qty: typeof variation.max_qty !== 'undefined' ? variation.max_qty : null
				};
			}
			updateStockBadge(pdp, stock, config);
		});

		$form.on('reset_data hide_variation', function () {
			updateStockBadge(pdp, null, config);
		});
	}
})();
