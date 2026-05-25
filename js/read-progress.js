(function () {
	'use strict';
	var bar = document.querySelector('.mvp-read-progress-bar');
	if (!bar) {
		return;
	}
	function tick() {
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		var doc = document.documentElement;
		var height = doc.scrollHeight - window.innerHeight;
		if (height <= 0) {
			bar.style.width = '0%';
			return;
		}
		var pct = (scrollTop / height) * 100;
		pct = Math.min(100, Math.max(0, pct));
		bar.style.width = pct + '%';
	}
	window.addEventListener('scroll', tick, { passive: true });
	window.addEventListener('resize', tick);
	window.addEventListener('load', tick);
	tick();
})();
