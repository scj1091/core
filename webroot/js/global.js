/**
 * Define namespace
 *
 * @var object
 */
if (CORE == undefined) {
	var CORE = {};
}

/**
 * Registered updateable divs
 *
 * @var array Array containing an alias object, which contains a div and url
 */
CORE.updateables = [];

/**
 * Convenience method for updating an element's update container with html. If
 * `html` is not defined, a new request will be made
 *
 * @param Element element The element calling the update
 * @param string html The html to update the container with
 * @return object The Ajax object
 */
CORE.update = function(element, html) {
	if (typeof html === undefined) {
		CORE.request(element, {
			update: true
		});
	} else {
		var container = $(element).closest('[data-core-update-url]');
		container.html(html);
	}
}

/**
 * Wraps Ajax request. If a url is not defined, it will find the closest
 * `data-core-update-url` element to update.
 *
 * ### Extra options:
 * - `update` Boolean. Whether or not to update the container with the results
 *
 * @param Element element The element calling the request
 * @param object options Ajax options
 * @return object The Ajax object
 */
CORE.request = function(element, options) {
	// use user defined options if defined
	var useOptions = {
		url: null,
		update: false,
		success: function() {}
	};
	
	useOptions = $.extend(useOptions, options || {});
	
	var container = $(element).closest('[data-core-update-url]');
	
	if (useOptions.url == null) {
		useOptions.url = container.data('core-update-url');
	}
	
	container.data('core-update-url', useOptions.url)
	
	if (useOptions.update !== false) {
		var success = useOptions.success;
		useOptions.success = function(data) {
			container.html(data);
			success(data);
		}		
		delete useOptions.update;
	}

	if (useOptions.data != undefined) {
		useOptions.type = 'post';
	}
	return $.ajax(useOptions);
}

/**
 * Removes CakePHP pagination and replaces it with a request that replaces the
 * updateable, or the closest updateable parent
 *
 * @param string id The id of the div containing the pagination links
 */
CORE.updateablePagination = function(id) {
	$('a[href*="page:"]', $('#'+id))
		.off('click')
		.on('click', function() {
			console.log(this);
			CORE.request(this, {
				url: this.href,
				update: true
			});
			return false;
		});
}

/**
 * Registers a div as an "updateable"
 *
 * By registering a div as an updateable and linking it to a url,
 * we can call CORE.update(alias) to quickly update that div
 * without having to remember the url or div id. An alias can 
 * also update more than one div (pass the same alias to CORE.register
 * with a new div and url).
 *
 * @param string alias The quick reference alias name
 * @param string div The element div to update
 * @param string url The url to load
 * @return boolean
 * @see CORE.updateables
 */
CORE.register = function(alias, div, url) {
	if (alias == undefined || div == undefined || url == undefined) {
		return false;
	}

	if (CORE.updateables[alias] == undefined) {
		CORE.updateables[alias] = [];
	}
	
	// assume we want to retain any FilterPagination filters when closing
	// models on the first page
	if (!url.match(/page:/)) {
		url += '/page:1';
	}
	
	// if this exact one exists, don't duplicate
	CORE.updateables[alias][div] = url;
	
	return true;
}

/**
 * Unregisters an updateable and returns the updateable that was removed
 *
 * @param alias string The alias for the updateable
 * @return hash The updateable that was removed
 * @see CORE.register
 */
CORE.unregister = function(alias) {
	if (alias == undefined) {
		return false;
	}
	var old = CORE.updateables[alias];
	delete CORE.updateables[alias];
	return old;
}

/**
 * Extracts the flash message from a response and displays it
 * 
 * @param data string The html response
 * @return void
 */
CORE.showFlash = function(data) {
	var msg = $('div[id^=flash], div#authMessage', '<div>'+data+'</div>');
	$(msg).appendTo('#wrapper').hide().delay(100).slideDown().delay(5000).slideUp(function() { $(this).remove(); });
}

/**
 * Registers the alias with the `content` updateable's data, so if there's a
 * call to an undefined alias it will load it in content's div instead. Useful
 * for pages that may or may not be loaded in ajax windows.
 *
 * @param alias string The alias to check for
 * @return void;
 */
CORE.fallbackRegister = function(alias) {
	if (CORE.updateables[alias] == undefined) {
		CORE.updateables[alias] = CORE.updateables['content'];
	}
}

/**
 * Inits CORE js
 */
CORE.init = function() {	
	// init ui elements
	CORE.initUI();
	// init navigation
	CORE.initNavigation();
	// IE is too agressive in its caching
	$.ajaxSetup({
		cache: false,
		error: function(XMLHttpRequest) {
			if (XMLHttpRequest.status == '403') {
				redirect('/login');
			}
		}
	});
}

/**
 * These are all items that should be initialized on a new page or when a modal
 * opens
 */
CORE.initUI = function() {
	$('.equal-height:visible > div').equalHeights();
	// hide flash message
	$('div[id^=flash], div#authMessage').appendTo('#wrapper').hide().delay(100).slideDown().delay(5000).slideUp(function() { $(this).remove(); });
	// attach tabbed behavior
	CORE.attachTabbedBehavior();
	// attach modal behavior
	CORE.attachModalBehavior();
	// tooltips
	CORE.attachTooltipBehavior();
	// form elements
	CORE.initFormUI();
}

// extend `$.data()` to update the dom as well
var origDataFn = $.fn.data;
$.fn.data = function() {
	if (arguments[0] == 'core-update-url' && arguments[1] != undefined) {
		console.log(arguments);
		if (arguments[1] !== '' && !arguments[1].match(/page:/)) {
			arguments[1] += '/page:1';
		}
		$(this).attr('data-core-update-url', arguments[1]);
	}
	return origDataFn.apply(this, arguments);
}

CORE.register('content', 'content', location.href);