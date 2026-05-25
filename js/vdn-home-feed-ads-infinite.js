/**
 * After infinite scroll appends stories, insert AdSense rows every N stories (matches PHP).
 * Fills ONLY `li.vdn-home-feed-ad` units. Markup sets `data-adf-triggered="0"` so AdFusion’s global
 * AdSense loop (which often runs at the wrong size on mobile) skips these; we push + mark done here.
 *
 * @package Vicksburg_Daily_News
 */
(function () {
	'use strict';

	var feedAdIo = null;

	function getCfg() {
		return window.vdnHomeFeedAds || null;
	}

	function pushFeedAdSenseSlots() {
		var nodes = document.querySelectorAll(
			'li.vdn-home-feed-ad ins.adsbygoogle[data-vdn-home-feed="1"]:not([data-vdn-feed-pushed])'
		);
		var i;
		var n = nodes.length;
		if (!n) {
			return;
		}
		for (i = 0; i < n; i++) {
			try {
				(window.adsbygoogle = window.adsbygoogle || []).push({});
				nodes[i].setAttribute('data-vdn-feed-pushed', '1');
				nodes[i].setAttribute('data-adf-triggered', '1');
			} catch (e) {
				// Duplicate / blocked — try again on next scheduled pass.
			}
		}
	}

	function scheduleFeedPush() {
		requestAnimationFrame(pushFeedAdSenseSlots);
		window.setTimeout(pushFeedAdSenseSlots, 400);
		window.setTimeout(pushFeedAdSenseSlots, 1200);
		window.setTimeout(pushFeedAdSenseSlots, 3500);
	}

	function ensureFeedAdIntersectionObserver() {
		if (feedAdIo || typeof IntersectionObserver !== 'function') {
			return;
		}
		feedAdIo = new IntersectionObserver(
			function (entries) {
				var j;
				for (j = 0; j < entries.length; j++) {
					if (entries[j].isIntersecting) {
						pushFeedAdSenseSlots();
						return;
					}
				}
			},
			{ root: null, rootMargin: '320px 0px', threshold: 0.01 }
		);
	}

	function observeFeedAdSlots() {
		ensureFeedAdIntersectionObserver();
		if (!feedAdIo) {
			return;
		}
		var list = document.querySelectorAll(
			'li.vdn-home-feed-ad ins.adsbygoogle[data-vdn-home-feed="1"]:not([data-vdn-feed-io])'
		);
		var i;
		for (i = 0; i < list.length; i++) {
			list[i].setAttribute('data-vdn-feed-io', '1');
			feedAdIo.observe(list[i]);
		}
	}

	function pickSlotForPlacement(cfg, storyIndex, every) {
		var placement = Math.floor(storyIndex / every);
		if (!placement || placement < 1) {
			return cfg.slot || '';
		}
		var slots = cfg.slots;
		if (slots && slots.length) {
			return String(slots[(placement - 1) % slots.length] || '');
		}
		return cfg.slot || '';
	}

	function buildAdLi(cfg, slotId) {
		var layout = cfg.layout === 'row' ? 'row' : 'col';
		var itemClass =
			layout === 'col'
				? 'relative vdn-home-feed-ad vdn-home-feed-ad--col'
				: 'relative vdn-home-feed-ad vdn-home-feed-ad--row';
		var li = document.createElement('li');
		li.className = itemClass;
		li.setAttribute('role', 'presentation');
		li.setAttribute('aria-hidden', 'true');
		var sr = document.createElement('span');
		sr.className = 'screen-reader-text';
		sr.textContent = cfg.label || 'Advertisement';
		li.appendChild(sr);
		var wrap = document.createElement('div');
		wrap.className = 'vdn-home-feed-adfusion-google vdn-google-fallback-ad';
		wrap.style.textAlign = 'center';
		wrap.style.margin = '15px 0';
		wrap.style.clear = 'both';
		var ins = document.createElement('ins');
		ins.className = 'adsbygoogle';
		ins.style.display = 'block';
		ins.setAttribute('data-vdn-home-feed', '1');
		ins.setAttribute('data-adf-triggered', '0');
		ins.setAttribute('data-ad-client', cfg.client);
		ins.setAttribute('data-ad-slot', slotId);
		ins.setAttribute('data-ad-format', 'auto');
		ins.setAttribute('data-full-width-responsive', 'true');
		wrap.appendChild(ins);
		li.appendChild(wrap);
		return li;
	}

	function syncList(ul) {
		var cfg = getCfg();
		if (!cfg || !cfg.client) {
			return;
		}
		var hasSlotList = cfg.slots && cfg.slots.length;
		if (!hasSlotList && !cfg.slot) {
			return;
		}
		var every = parseInt(cfg.every, 10);
		if (isNaN(every) || every < 1) {
			every = 10;
		}
		var storyIndex = 0;
		var node = ul.firstElementChild;
		while (node) {
			if (node.matches && node.matches('li.infinite-post')) {
				storyIndex++;
				if (storyIndex % every === 0) {
					var slotNow = pickSlotForPlacement(cfg, storyIndex, every);
					if (slotNow) {
						var nxt = node.nextElementSibling;
						if (!nxt || !nxt.classList.contains('vdn-home-feed-ad')) {
							var adLi = buildAdLi(cfg, slotNow);
							if (node.after) {
								node.after(adLi);
							} else {
								ul.insertBefore(adLi, nxt);
							}
						}
					}
				}
			}
			node = node.nextElementSibling;
		}
	}

	function syncAll() {
		var cfg = getCfg();
		if (!cfg) {
			return;
		}
		var lists = document.querySelectorAll('ul.infinite-content');
		var i;
		for (i = 0; i < lists.length; i++) {
			syncList(lists[i]);
		}
		observeFeedAdSlots();
		scheduleFeedPush();
	}

	function init() {
		var cfg = getCfg();
		if (!cfg) {
			return;
		}
		syncAll();
		window.addEventListener('load', scheduleFeedPush);
		window.addEventListener('pageshow', function (ev) {
			if (ev && ev.persisted) {
				scheduleFeedPush();
			}
		});
		var rt = null;
		window.addEventListener('resize', function () {
			if (rt) {
				window.clearTimeout(rt);
			}
			rt = window.setTimeout(function () {
				rt = null;
				pushFeedAdSenseSlots();
			}, 280);
		});

		var lists = document.querySelectorAll('ul.infinite-content');
		var i;
		var t = null;
		function debounced() {
			if (t) {
				window.clearTimeout(t);
			}
			t = window.setTimeout(function () {
				t = null;
				syncAll();
			}, 120);
		}
		for (i = 0; i < lists.length; i++) {
			if (typeof MutationObserver === 'function') {
				var mo = new MutationObserver(debounced);
				mo.observe(lists[i], { childList: true });
			}
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
