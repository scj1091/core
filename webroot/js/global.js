/**
 * Define namespace
 *
 * @var object
 */
if (CORE == undefined) {
	var CORE = {};
}

/**
 * The plugin we are in, if any. This makes `core-update-url` aware that we are
 * in a plugin in order to append `/page:1` for routed index pages.
 *
 * @var string
 */
CORE.plugin = '';

/**
 * Convenience method for updating an element's update container with html. If
 * `html` is not defined, a new request will be made
 *
 * @param Element element The element calling the update
 * @param string html The html to update the container with
 * @return object The Ajax object
 */
CORE.update = function(element, html) {
	if (html === undefined) {
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
	var container = $(element).closest('[data-core-update-url]'); //The element with data-core-update-url contains the update from the given url

	// use user defined options if defined
	var useOptions = {
		url: null,
		update: false,
		success: function() {},
		context: container
	};

	useOptions = $.extend(useOptions, options || {});

	if (useOptions.url == null) {
		useOptions.url = container.data('core-update-url'); //If url not set, use the url in the element
	}

	if (useOptions.update !== false) {
		container.data('core-update-url', useOptions.url) //Update the container element with the loaded url
		var success = useOptions.success;
		useOptions.success = function(data) { //On success, set container's html contents to ajax results
			container.html(data);
			success(data); //call user-specified success function
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
 */
CORE.ajaxPagination = function() {
	$('a[href*="page:"]')
		.off('click')
		.on('click', function() {
			CORE.request(this, {
				url: this.href,
				update: true
			});
			return false;
		});
	$('.pagination select[name="data[jump]"]')
		.off('change')
		.on('change', function() {
			// update update url
			var container = $(this).closest('[data-core-update-url]');
			var url = container.data('core-update-url');
			if (!url.match(/page:/)) {
				url = url.split('/');
				url.push('page:1');
				url = url.join('/');
			}
			url = url.replace(/page:\d+/, 'page:'+$(this).val());
			CORE.request($(this), {url: url, update: true});
		});
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
 * Creates a "loading" overlay on top of the container
 *
 * @param Element container The container that has loading content
 */
CORE.setLoading = function(container) { //set loading gif, note this doesn't use jquery-ui tabs
	container = $(container);
	if (!container.data('core-update-url')) {
		return;
	}
	container
		.append('<div class="loading"></div>')
		.css({
			'min-height': '50px'
		});
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
		},
		beforeSend: function(x, s) {
			// don't show the loading indicator on background requests
			if (s.update !== undefined && s.update == false) {
				return;
			}
			// if the XHR's context is not set correctly, the loading spinner
			// will not show
			CORE.setLoading(this);
		}
	});
}

/**
 * These are all items that should be initialized on a new page or when a modal
 * opens
 */
CORE.initUI = function() {
	$('.equal-height:visible > div').equalHeights(); //used to display receipts and household members as equal height tiles
	// hide flash message
	$('div[id^=flash], div#authMessage').appendTo('#wrapper').hide().delay(100).slideDown().delay(5000).slideUp(function() { $(this).remove(); }); 
	// attach tabbed behavior
	CORE.attachTabbedBehavior();
	// attach modal behavior
	CORE.attachModalBehavior();
	// tooltips
	CORE.attachTooltipBehavior();
	// ajax links
	CORE.attachAjaxBehavior();
	// form elements
	CORE.initFormUI();
	// pagination
	CORE.ajaxPagination();
}

// extend `$.data()` to update the dom as well
var origDataFn = $.fn.data; //could we just use $.attr() instead of redefining $.data()?
$.fn.data = function() {
	if (arguments[0] == 'core-update-url' && arguments[1] != undefined) {
		if (arguments[1] !== '' && !arguments[1].match(/page:/)) {
			var parsed = document.createElement('a'); //not quite sure what this magic does
			parsed.href = arguments[1];
			var path = parsed.pathname.replace(/^\/|\/$/g, ''); //strip leading/tailing slashes
			var pieces = path.split('/');
			if (pieces.length == 1 || (CORE.plugin !== '' && pieces.length == 2)) {
				pieces.push('index');
			}
			pieces.push('page:1');
			arguments[1] = '/'+pieces.join('/');
		}
		$(this).attr('data-core-update-url', arguments[1]);
	}
	return origDataFn.apply(this, arguments);
}