(function () {
	'use strict';

	document.documentElement.classList.add('av-js');

	var header = document.querySelector('.site-header');
	if (header) {
		window.addEventListener('scroll', function () {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		});
	}

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

	function openMega() {
		if (!mega) {
			return;
		}
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
		shopTrigger.addEventListener('click', function (event) {
			event.preventDefault();
			if (mega.hidden) {
				openMega();
			} else {
				closeMega();
			}
		});

		document.addEventListener('click', function (event) {
			if (!shopItem.contains(event.target)) {
				closeMega();
			}
		});

		shopItem.addEventListener('mouseenter', function () {
			if (window.matchMedia('(min-width: 769px)').matches) {
				openMega();
			}
		});

		shopItem.addEventListener('mouseleave', function () {
			if (window.matchMedia('(min-width: 769px)').matches) {
				closeMega();
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
})();
