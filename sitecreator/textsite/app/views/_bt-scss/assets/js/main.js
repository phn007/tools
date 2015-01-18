/**!
 * MASONRY SECTION
 * -------------------------------------------------------------------------------------------
 */
var $loadingIndicator;

$( function() {
	var $container = $('#topic-thumbs');
	$loadingIndicator = $('#loading-indicator');

	// hide initial items
	var $initialItems = $container.find('.item').hide();

	var $container = $container.masonry({
		itemSelector: 'no-item',
		columnWidth: '.grid-sizer'
	})

	// set proper itemSelector
	.masonry( 'option', { itemSelector: '.item' })
	.masonryImagesReveal( $initialItems );
});

// reveals all items after all item images have been loaded
$.fn.masonryImagesReveal = function( $items ) {
	var msnry = this.data('masonry');
	var itemSelector = msnry.options.itemSelector;
	$loadingIndicator.show();

	// hide by default
	$items.hide();

	// append to container
	this.append( $items );
	$items.imagesLoaded( function() {
		// un-hide items
		$items.show();
		$loadingIndicator.hide();
		// reveal all of them
		msnry.appended( $items );
	});
	return this;
};

/**!
 * ECHOJS SECTION
 * -------------------------------------------------------------------------------------------
 */
window.echo = (function (window, document) {
	'use strict';

	//Constructor function
	var Echo = function (elem) {
		this.elem = elem;
		this.render();
		this.listen();
	};

	//Images for echoing
	var echoStore = [];

	//Element in viewport logic
	var scrolledIntoView = function (element) {
		var coords = element.getBoundingClientRect();
		return ((coords.top >= 0 && coords.left >= 0 && coords.top) <= (window.innerHeight || document.documentElement.clientHeight));
	};

	//Changing src attr logic
	var echoSrc = function (img, callback) {
		img.src = img.getAttribute('data-echo');
		if (callback) {
			callback();
		}
	};

	//Remove loaded item from array
	var removeEcho = function (element, index) {
		if (echoStore.indexOf(element) !== -1) {
			echoStore.splice(index, 1);
		}
	};

	//Echo the images and callbacks
	var echoImages = function () {
		for (var i = 0; i < echoStore.length; i++) {
			var self = echoStore[i];
			if (scrolledIntoView(self)) {
				echoSrc(self, removeEcho(self, i));
			}
		}
	};

	//Prototypal setup
	Echo.prototype = {
		init : function () {
			echoStore.push(this.elem);
		},
		render : function () {
			if (document.addEventListener) {
				document.addEventListener('DOMContentLoaded', echoImages, false);
			} else {
				window.onload = echoImages;
			}
		},
		listen : function () {
			window.onscroll = echoImages;
		}
	};

	//Initiate the plugin
	var lazyImgs = document.querySelectorAll('img[data-echo]');
	for (var i = 0; i < lazyImgs.length; i++) {
		new Echo(lazyImgs[i]).init();
	}
})(window, document);