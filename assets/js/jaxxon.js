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
	initLegacyOmnisendSuppressor();
	initAsheravaSignupPopup();

	var nav = document.querySelector('.av-catalog-nav');
	if (!nav) {
		return;
	}

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

	function initProductPage() {
		var pdp = document.querySelector('.av-pdp');
		if (!pdp) {
			return;
		}

		initQuantityControls(pdp);
		cleanVariationRows(pdp);
		cleanVariationRows(document);
		watchVariationRows(pdp);
		watchVariationRows(document);

		window.setTimeout(function () {
			cleanVariationRows(pdp);
			cleanVariationRows(document);
		}, 120);

		window.setTimeout(function () {
			cleanVariationRows(pdp);
			cleanVariationRows(document);
		}, 650);

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
			cleanVariationRows(pdp);
			cleanVariationRows(document);

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
	}

	function compactText(text) {
		return String(text || '').replace(/\s+/g, ' ').trim();
	}

	function normalizedLengthLabel(text) {
		var source = compactText(text);
		var hasUnit = /(?:inch|inches|in|")/i.test(source);
		var match = source.match(/(\d+(?:\.\d+)?)/);

		if (!match) {
			return '';
		}

		return hasUnit || /^\d+(?:\.\d+)?$/.test(source) ? match[1] + '"' : '';
	}

	function setTextIfChanged(node, text) {
		if (node && node.textContent !== text) {
			node.textContent = text;
		}
	}

	function normalizeLengthOptionNode(node) {
		var normalized = normalizedLengthLabel(node.getAttribute('data-title') || node.getAttribute('title') || node.getAttribute('aria-label') || node.textContent);

		if (!normalized) {
			return;
		}

		['data-title', 'title', 'aria-label'].forEach(function (name) {
			node.setAttribute(name, normalized);
		});

		if (!node.children.length || node.classList.contains('variable-item-span') || node.classList.contains('av-pdp__swatch-text')) {
			setTextIfChanged(node, normalized);
		}
	}

	function normalizeLengthChoice(choice) {
		var normalized = normalizedLengthLabel(choice.getAttribute('data-title') || choice.getAttribute('title') || choice.getAttribute('aria-label') || choice.textContent);
		var target;

		if (!normalized) {
			return;
		}

		['data-title', 'title', 'aria-label'].forEach(function (name) {
			choice.setAttribute(name, normalized);
		});

		target = choice.querySelector('.variable-item-span, .variable-item-span-button, .variable-item-button, .av-pdp__swatch-text') || choice;
		setTextIfChanged(target, normalized);
	}

	function variationRowLabelElement(row) {
		return row.querySelector('th.label label, label') || row.querySelector('th.label');
	}

	function variationRowSelect(row) {
		return row.querySelector('select[name*="attribute_"], select');
	}

	function variationRowLabelText(row) {
		var label = variationRowLabelElement(row);

		return label ? compactText(label.textContent).toLowerCase() : '';
	}

	function variationRowAttributeName(row) {
		var select = variationRowSelect(row);
		var values = [];

		values.push(row.getAttribute('data-attribute_name') || '');
		values.push(row.getAttribute('data-attribute-name') || '');
		values.push(row.className || '');

		row.querySelectorAll('[class], [data-attribute], [data-attribute_name], [data-attribute-name], [data-wvstooltip], [data-title], [title], [aria-label]').forEach(function (node) {
			values.push(node.className || '');
			values.push(node.getAttribute('data-attribute') || '');
			values.push(node.getAttribute('data-attribute_name') || '');
			values.push(node.getAttribute('data-attribute-name') || '');
			values.push(node.getAttribute('data-wvstooltip') || '');
			values.push(node.getAttribute('data-title') || '');
			values.push(node.getAttribute('title') || '');
			values.push(node.getAttribute('aria-label') || '');
		});

		if (select) {
			values.push(select.getAttribute('data-attribute_name') || '');
			values.push(select.getAttribute('data-attribute-name') || '');
			values.push(select.name || '');
			values.push(select.id || '');
		}

		return values.join(' ').toLowerCase();
	}

	function variationRowIsLength(row) {
		var select = variationRowSelect(row);
		var name = select && select.name ? select.name.toLowerCase() : '';
		var id = select && select.id ? select.id.toLowerCase() : '';
		var attributeName = variationRowAttributeName(row);
		var labelText = variationRowLabelText(row);

		return name.indexOf('length') !== -1 || id.indexOf('length') !== -1 || attributeName.indexOf('length') !== -1 || labelText.indexOf('length') === 0;
	}

	function variationRowIsSize(row) {
		var select = variationRowSelect(row);
		var name = select && select.name ? select.name.toLowerCase() : '';
		var id = select && select.id ? select.id.toLowerCase() : '';
		var attributeName = variationRowAttributeName(row);
		var labelText = variationRowLabelText(row);
		var marker = row.querySelector('[data-av-hide-variation-row]');

		return marker || name.indexOf('size') !== -1 || id.indexOf('size') !== -1 || /\b(?:attribute_)?pa_size\b|\b(?:attribute_)?size\b/.test(attributeName) || labelText.indexOf('size') === 0;
	}

	function clearSelectedVariationLabels(row) {
		row.querySelectorAll('.woo-selected-variation-item-name, .woo-variation-selected-item-name, .wvs-selected-variation-item-name, .selected-value').forEach(function (item) {
			setTextIfChanged(item, '');
			item.setAttribute('aria-hidden', 'true');
			item.style.setProperty('display', 'none', 'important');
		});
	}

	function normalizeLengthVariationRows(scope) {
		scope.querySelectorAll('table.variations tr').forEach(function (row) {
			var label = variationRowLabelElement(row);

			if (!variationRowIsLength(row)) {
				return;
			}

			row.classList.add('av-pdp__variation-row--length');

			if (label) {
				setTextIfChanged(label, 'Length');
			}

			clearSelectedVariationLabels(row);

			row.querySelectorAll('.av-pdp__swatch, .variable-item, .button-variable-item, .variable-item-button').forEach(function (item) {
				normalizeLengthChoice(item);
			});

			row.querySelectorAll('.av-pdp__swatch-text, .variable-item-span, .variable-item-span-button, .variable-item-contents span, .button-variable-item span').forEach(function (item) {
				normalizeLengthOptionNode(item);
			});
		});
	}

	function variationOptionNumber(option) {
		if (!option) {
			return '';
		}

		var source = [option.value, option.textContent].join(' ');
		var match = source.match(/(\d+(?:\.\d+)?)/);

		return match ? match[1] : '';
	}

	function selectMatchingHiddenSize(sizeSelect, lengthSelect) {
		if (!sizeSelect) {
			return;
		}

		var previousValue = sizeSelect.value;
		var target = '';
		if (lengthSelect && lengthSelect.value) {
			target = variationOptionNumber(lengthSelect.options[lengthSelect.selectedIndex]);
		}

		var fallback = '';
		Array.prototype.some.call(sizeSelect.options || [], function (option) {
			if (!option.value) {
				return false;
			}

			if (!fallback) {
				fallback = option.value;
			}

			if (target && variationOptionNumber(option) === target) {
				sizeSelect.value = option.value;
				return true;
			}

			return false;
		});

		if (!sizeSelect.value && fallback) {
			sizeSelect.value = fallback;
		}

		if (sizeSelect.value && sizeSelect.value !== previousValue) {
			sizeSelect.dispatchEvent(new Event('change', { bubbles: true }));
		}
	}

	function findLengthSelect(scope) {
		return scope.querySelector('table.variations select[name*="length"], table.variations select[id*="length"], table.variations [data-attribute_name*="length"] select, table.variations [data-attribute-name*="length"] select');
	}

	function hideVariationRow(row, select, lengthSelect) {
		if (!row) {
			return;
		}

		selectMatchingHiddenSize(select || variationRowSelect(row), lengthSelect);
		row.classList.add('av-pdp__variation-row--hidden', 'av-pdp__variation-row--legacy-size');
		row.setAttribute('aria-hidden', 'true');
		row.hidden = true;
		row.style.setProperty('display', 'none', 'important');
		row.querySelectorAll('.variable-items-wrapper, .button-variable-items-wrapper, .woo-variation-items-wrapper, .av-pdp__swatches').forEach(function (item) {
			item.setAttribute('aria-hidden', 'true');
			item.style.setProperty('display', 'none', 'important');
		});
	}

	function hideDuplicateVariationRows(scope) {
		var lengthSelect = findLengthSelect(scope);

		scope.querySelectorAll('table.variations tr').forEach(function (row) {
			var select = variationRowSelect(row);

			if (!variationRowIsSize(row) || variationRowIsLength(row)) {
				return;
			}

			hideVariationRow(row, select, lengthSelect);
		});

		scope.querySelectorAll('table.variations select[name*="size"], table.variations select[id*="size"], table.variations [data-attribute_name*="size"], table.variations [data-attribute-name*="size"], table.variations [class*="attribute_pa_size"], table.variations [class*="attribute_size"]').forEach(function (node) {
			var row = node.closest('tr');

			if (!row || variationRowIsLength(row)) {
				return;
			}

			hideVariationRow(row, variationRowSelect(row), lengthSelect);
		});

		if (lengthSelect && lengthSelect.dataset.avLengthSyncReady !== '1') {
			lengthSelect.dataset.avLengthSyncReady = '1';
			lengthSelect.addEventListener('change', function () {
				hideDuplicateVariationRows(scope);
			});
		}
	}

	function cleanVariationRows(scope) {
		normalizeLengthVariationRows(scope);
		hideDuplicateVariationRows(scope);
	}

	function watchVariationRows(scope) {
		var table = scope.querySelector('table.variations');

		if (!table || table.dataset.avVariationCleanupReady === '1' || typeof window.MutationObserver === 'undefined') {
			return;
		}

		table.dataset.avVariationCleanupReady = '1';
		new window.MutationObserver(function () {
			window.requestAnimationFrame(function () {
				cleanVariationRows(scope);
			});
		}).observe(table, {
			childList: true,
			subtree: true,
			characterData: true
		});
	}

	function initQuantityControls(scope) {
		scope.querySelectorAll('.quantity input.qty').forEach(function (input) {
			var quantity = input.closest('.quantity');

			if (!quantity || input.dataset.avQuantityReady === '1') {
				return;
			}

			input.dataset.avQuantityReady = '1';
			quantity.classList.add('av-quantity');

			var minus = document.createElement('button');
			var plus = document.createElement('button');

			minus.type = 'button';
			minus.className = 'av-quantity__button av-quantity__button--minus';
			minus.setAttribute('aria-label', 'Decrease quantity');
			minus.innerHTML = '<svg class="av-icon av-icon--minus" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 12h12"></path></svg>';

			plus.type = 'button';
			plus.className = 'av-quantity__button av-quantity__button--plus';
			plus.setAttribute('aria-label', 'Increase quantity');
			plus.innerHTML = '<svg class="av-icon av-icon--plus" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 6v12"></path><path d="M6 12h12"></path></svg>';

			quantity.insertBefore(minus, input);
			quantity.appendChild(plus);

			function getNumber(attribute, fallback) {
				var value = parseFloat(input.getAttribute(attribute));
				return Number.isFinite(value) ? value : fallback;
			}

			function getStep() {
				var step = parseFloat(input.getAttribute('step'));
				return Number.isFinite(step) && step > 0 ? step : 1;
			}

			function formatValue(value, step) {
				return Number.isInteger(step) ? String(Math.round(value)) : String(parseFloat(value.toFixed(3)));
			}

			function syncButtons() {
				var min = getNumber('min', 1);
				var max = getNumber('max', Infinity);
				var current = parseFloat(input.value);

				if (!Number.isFinite(current)) {
					current = min;
				}

				minus.disabled = current <= min;
				plus.disabled = current >= max;
			}

			function updateQuantity(direction) {
				var step = getStep();
				var min = getNumber('min', 1);
				var max = getNumber('max', Infinity);
				var current = parseFloat(input.value);
				var next;

				if (!Number.isFinite(current)) {
					current = min;
				}

				next = current + direction * step;
				next = Math.max(min, Math.min(max, next));
				input.value = formatValue(next, step);
				input.dispatchEvent(new Event('input', { bubbles: true }));
				input.dispatchEvent(new Event('change', { bubbles: true }));
				syncButtons();
			}

			minus.addEventListener('click', function () {
				updateQuantity(-1);
			});

			plus.addEventListener('click', function () {
				updateQuantity(1);
			});

			input.addEventListener('input', syncButtons);
			input.addEventListener('change', syncButtons);
			syncButtons();
		});
	}

	function initLegacyOmnisendSuppressor() {
		var legacyPhrases = [
			'GET 10% OFF YOUR FIRST ORDER',
			'AND BE THE FIRST TO HEAR ABOUT OUR NEW PRODUCT DROPS',
			'POWERED BY OMNISEND'
		];
		var scheduled = false;
		var observer = null;

		function hasLegacyCopy(element) {
			var text = (element.textContent || '').replace(/\s+/g, ' ').toUpperCase();
			return legacyPhrases.some(function (phrase) {
				return text.indexOf(phrase) !== -1;
			});
		}

		function findPopupRoot(element) {
			var root = element;
			var current = element;
			var depth = 0;

			while (current && current !== document.body && depth < 12) {
				var style = window.getComputedStyle(current);
				var rect = current.getBoundingClientRect();
				var zIndex = parseInt(style.zIndex, 10) || 0;
				var largeOverlay = rect.width > 320 && rect.height > 240;

				if ((style.position === 'fixed' || style.position === 'absolute') && (largeOverlay || zIndex > 900)) {
					root = current;
				}

				current = current.parentElement;
				depth += 1;
			}

			return root;
		}

		function suppressLegacyPopup() {
			scheduled = false;

			document.querySelectorAll('body div, body section, body aside, body form').forEach(function (element) {
				if (!hasLegacyCopy(element)) {
					return;
				}

				var root = findPopupRoot(element);
				root.style.setProperty('display', 'none', 'important');
				root.style.setProperty('visibility', 'hidden', 'important');
				root.style.setProperty('pointer-events', 'none', 'important');
				root.setAttribute('aria-hidden', 'true');
				document.documentElement.classList.remove('omnisend-popup-open');
				document.body.classList.remove('omnisend-popup-open');
				document.body.style.removeProperty('overflow');
			});
		}

		function scheduleSuppress() {
			if (scheduled) {
				return;
			}

			scheduled = true;
			window.setTimeout(suppressLegacyPopup, 80);
		}

		scheduleSuppress();
		window.setTimeout(scheduleSuppress, 800);
		window.setTimeout(scheduleSuppress, 2200);

		observer = new MutationObserver(scheduleSuppress);
		observer.observe(document.documentElement, { childList: true, subtree: true });
	}

	function initAsheravaSignupPopup() {
		var popup = document.querySelector('[data-av-signup-popup]');
		var config = window.asheravaSignupPopup || {};

		if (!popup) {
			if (!initAsheravaSignupPopup.waiting) {
				initAsheravaSignupPopup.waiting = true;
				window.setTimeout(initAsheravaSignupPopup, 300);
				window.setTimeout(initAsheravaSignupPopup, 1200);
				document.addEventListener('DOMContentLoaded', initAsheravaSignupPopup, { once: true });
			}
			return;
		}

		if (initAsheravaSignupPopup.initialized || !config.ajaxUrl) {
			return;
		}
		initAsheravaSignupPopup.initialized = true;

		var form = popup.querySelector('[data-av-signup-form]');
		var message = popup.querySelector('[data-av-signup-message]');
		var email = form ? form.querySelector('input[name="email"]') : null;
		var submit = form ? form.querySelector('button[type="submit"]') : null;
		var closedKey = 'asheravaSignupPopupClosed';
		var joinedKey = 'asheravaSignupPopupJoined';
		var isForced = !!config.force;

		function hasStored(key) {
			try {
				return window.localStorage.getItem(key) === '1';
			} catch (error) {
				return false;
			}
		}

		function store(key) {
			try {
				window.localStorage.setItem(key, '1');
			} catch (error) {
				// Ignore storage restrictions.
			}
		}

		function openPopup() {
			if (!isForced && (hasStored(closedKey) || hasStored(joinedKey))) {
				return;
			}

			popup.hidden = false;
			window.setTimeout(function () {
				popup.classList.add('is-visible');
				document.body.classList.add('av-signup-popup-open');
				if (email && !window.matchMedia('(max-width: 640px)').matches) {
					email.focus({ preventScroll: true });
				}
			}, 30);
		}

		function closePopup() {
			popup.classList.remove('is-visible');
			document.body.classList.remove('av-signup-popup-open');
			store(closedKey);
			window.setTimeout(function () {
				popup.hidden = true;
			}, 240);
		}

		popup.querySelectorAll('[data-av-signup-close]').forEach(function (button) {
			button.addEventListener('click', closePopup);
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && popup.classList.contains('is-visible')) {
				closePopup();
			}
		});

		if (form) {
			form.addEventListener('submit', function (event) {
				event.preventDefault();

				if (!email || !email.value) {
					if (message) {
						message.textContent = 'Please enter your email address.';
						message.classList.add('is-error');
					}
					return;
				}

				var data = new URLSearchParams();
				data.set('action', 'asherava_signup_popup');
				data.set('nonce', config.nonce || '');
				data.set('email', email.value);
				data.set('company', form.querySelector('input[name="company"]').value || '');

				if (submit) {
					submit.disabled = true;
					submit.textContent = 'Joining...';
				}

				fetch(config.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: data.toString()
				})
					.then(function (response) {
						return response.json();
					})
					.then(function (payload) {
						if (!payload || !payload.success) {
							throw new Error(payload && payload.data && payload.data.message ? payload.data.message : 'Please try again.');
						}

						popup.classList.add('is-complete');
						store(joinedKey);
						if (message) {
							message.textContent = payload.data.message || 'Welcome to Asherava. Use code WELCOME10 at checkout.';
							message.classList.remove('is-error');
						}
						if (submit) {
							submit.textContent = 'WELCOME10';
						}
					})
					.catch(function (error) {
						if (message) {
							message.textContent = error.message || 'Please try again.';
							message.classList.add('is-error');
						}
						if (submit) {
							submit.disabled = false;
							submit.textContent = 'Get WELCOME10';
						}
					});
			});
		}

		window.setTimeout(openPopup, isForced ? 300 : 3500);
	}
})();
