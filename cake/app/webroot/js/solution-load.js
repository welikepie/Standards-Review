/* HISTORY API SUPPORT
 * Verify that the scripts will only be deployed when the History API is supported.
 */

var solution_switcher;
if ((typeof window.history.pushState !== "undefined") && (window.history.pushState)) {

	solution_switcher = (function() {
	
		var pre_hooks = [];
		var post_hooks = [];
		var link_selector = "a";
		var display_selector = "div#solution";
		var path_match = /issues\/[0-9]+\/solutions\/[0-9]+/;
		
		var switching = function(data) {
		
			var deleted = $(display_selector).filter(":first");
			for (var i = 0; i < pre_hooks.length; i++) { pre_hooks[i](deleted.get(0)); }
			
			deleted.fadeOut(300, function() {
				var el = $(data).get(0);
				$(el).insertAfter(deleted).fadeIn(300, function() {
					console.log("Element: ", this);
					console.log("Post-hooks: ", post_hooks);
					for (var i = 0; i < post_hooks.length; i++) { post_hooks[i](this); }
				});
				deleted.remove();
			});
		
		};
		
		var init = function(pre, post, link_select, display_select, path) {
		
			if (pre) { pre_hooks = pre; }
			if (post) { post_hooks = post; }
			if (link_select) { link_selector = link_select; }
			if (display_select) { display_selector = display_select; }
			if (path) { path_match = path; }
			
			// Replace the current history state
			var stateHtml = $(display_selector).filter(":first");
			stateHtml.wrap("<div></div>");
			var state = $(stateHtml.get(0).parentNode).html();
			console.log("Current state: ", state);
			stateHtml.unwrap();
			window.history.replaceState(state, null, window.location.href);
			
			$(link_selector).live('click', function(ev) {
			
				// See if the custom action is applicable
				if (this.href.match(path_match)) {
					ev.preventDefault();
					
					// Verify if the URL is different from current one
					if (this.href !== location.href) {
						var that = this;
						$.ajax({
							url: this.href,
							dataType: 'html',
							type: 'GET',
							success: function(data) {
								history.pushState(data, null, that.href);
								switching(data);
							}
						});
					}
					
				}
			
			});
			
			$(window).bind("popstate", function(ev) {
				console.log("Event: ", ev);
				switching(ev.originalEvent.state);
			});
		
		};
		
		return init;
	
	})();

}
/*function solution_ajax() {

	var links = $("a")
		.filter(function() { return this.href.match(/issues\/[0-9]+\/solutions\/[0-9]+/i); });
	
	links.each(function() {
	
		$(this).click(function(ev) {
			ev.preventDefault();
			$.ajax({
			
				'url': this.href,
				'dataType': 'html',
				'type': 'GET',
				'success': function(data) {
					$("div#solution").fadeOut(500, function() {
						var temp = this;
						$(data).hide().insertAfter(temp).fadeIn(500, function() {
							$(temp).remove();
							alert("Does it work?");
							if (typeof DISQUS !== "undefined") {
							
								DISQUS.reset({
								  reload: true,
								  config: function () {  
									this.page.identifier = "newidentifier";  
									this.page.url = window.location.href;
								  }
								});
							
							}
						});
					});
				}
			
			});
		});
	
	});
	
};*/