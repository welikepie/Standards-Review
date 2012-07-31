var scripts = (function() {

	// Shim for outerHTML missing in older browsers
	var outerHTML = function(el) {
		if (typeof el.outerHTML !== "undefined") {
			return el.outerHTML;
		} else {
			var t = $(this);
			t.wrap(document.createElement('div'));
			var html = $(t.get(0).parentNode).html();
			t.unwrap();
			return html;
		}
	};

	// History API is required for smooth solution switching
	// and optional for pagination. Verify it's available.
	var history_api = (typeof window.history.pushState === 'function');
	
	/* Utility function used to swap the old element with the
	 * new one. Optionally applies some positioning magic to avoid
	 * the element jumping.
	 */
	var swap_locked = 0; // Our "semaphore" for keeping track of 
	var swap = function(old_element, new_element, duration, fixed, post) {
	
		fixed = !!fixed;
		if (!post) { post = []; }
		
		// If the transition is to be fixed, inject styles to utilize it.
		if (fixed) {
		
			// Add the style with absolute positioning
			if (swap_locked <= 0) {
				$('<style id="__swaplock__">' +
					'div.__swapfixed__ { position: relative; }' +
					'div.__swapfixed__ > * { position: absolute !important; }' +
				'</style>').appendTo('head');
			}
			
			// Increate the semaphore value
			swap_locked += 1;
			
			// Wrap the old element...
			var wrapper = $('<div class="__swapfixed__"></div>');
			$(old_element).wrap(wrapper.get(0));
			
			// ...and fix the dimensions of the wrapper
			wrapper.width(wrapper.width()).height(wrapper.height());
		
		}
		
		$(old_element).fadeOut(duration / 2, function() {
		
			var n = $(new_element)
				.insertAfter(old_element)
				.hide();
			$(old_element).remove();
			n.fadeIn(duration / 2, function() {
			
				if (fixed) {
				
					// If transition was to be fixed,
					// unwrap the new element
					n.unwrap();
					
					// Decrease the semaphore value
					swap_locked -= 1;
					
					// If semaphore is zeroed, remove the swap styles
					if (swap_locked <= 0) {
						$('style#__swaplock__').remove();
					}
				
				}
				
				for (var i = 0; i < post.length; i++)
					post[i](this);
				
			});
		
		});
	
	};

	/* AJAX PAGINATORS
	 * These scripts are used on general issue pages (in order to
	 * paginate through the issues) and on issue detail pages (in order
	 * to paginate through the proposed solutions).
	 *
	 */
	
	var paginator = {
		'page_selector': 'div#page:first',
		'link_selector': 'div#paginator a',
		'current_class': 'current'
	};
	
	var paginate = function(page, link, curr) {
	
		// Save the arguments for usage
		if (page) { paginator.page_selector = page; }
		if (link) { paginator.link_selector = link; }
		if (curr) { paginator.current_class = curr; }
	
		// If we're utilising History API, the current
		// state of the pagination should be saved.
		if (history_api) {
		
			// Get the page number, current content and replace current state
			var num = $(paginator.link_selector + ' .current:first').text();
			var content = outerHTML($(paginator.page_selector).get(0));
			window.history.replaceState({'number': num, 'content': content}, null, window.location.href);
			
			// Assign the event handler to 'popstate' to swap the pages
			$(window).bind('popstate', function(ev) {
		
				if ( (typeof ev.originalEvent.state !== 'undefined') &&
				     ev.originalEvent.state ) {
				
					var state = ev.originalEvent.state;
				
					$(paginator.link_selector).removeClass(paginator.current_class);
					$(paginator.link_selector).filter(":contains('" + state.num + "')").addClass(paginator.current_class);
					
					swap($(paginator.page_selector).get(0), state.content, 600, false);
				
				}
			
			});
		
		}
		
		// Generate the click event for the paginator links to swap pages
		$(paginator.link_selector).click(function(ev) {
		
			ev.preventDefault();
			
			if (this.href !== window.location.href) {
		
				// AJAX query the website
				$.ajax({
				
					url: this.href,
					type: 'GET',
					dataType: 'html',
					context: this,
					success: function(data) {
					
						// If History API is available, add new history state
						if (history_api) {
						
							var num = $(this).text();
							window.history.pushState({'number': num, 'content': data}, null, this.href);
						
						}
					
						// Change the location of current class
						$(paginator.link_selector).removeClass(paginator.current_class);
						$(this).addClass(paginator.current_class);
						
						// Swap new element for an old one
						swap($(paginator.page_selector).get(0), data, 600, false);
					
					}
				
				});
			
			}
		
		});
	
	};
	
	var solution_loader = {
		'solution_selector': 'div#solution:first',
		'link_selector': 'div#solutions a',
		'insert_point': 'div#paginator',
		'path_match': /issues\/[0-9]+\/solutions\/[0-9]+/,
		'post_hooks': []
	};
	
	var load_solution = function(sol, link, ins, path, post) {
	
		console.log("log");
	
		// This functionality requires History API to work
		if (history_api) {
	
			// Save the arguments for usage
			if (sol) { solution_loader.solution_selector = sol; }
			if (link) { solution_loader.link_selector = link; }
			if (ins) { solution_loader.insert_point = ins; }
			if (path) { solution_loader.path_match = path; }
			if (post) { solution_loader.post_hooks = post; }
			
			// If the state is present, save it
			if ($(solution_loader.solution_selector).length) {
				var content = outerHTML($(solution_loader.solution_selector).get(0));
				console.log("Attempting to store current state: ", content);
				window.history.replaceState(content, null, window.location.href);
			}

			// Assign the event handler to 'popstate' to swap the pages
			$(window).bind('popstate', function(ev) {
			
				console.log("Popstate: ", ev.originalEvent);
		
				if ( (typeof ev.originalEvent.state !== 'undefined') &&
				     ev.originalEvent.state ) {
					 
					console.log("Swap, will ya?");
				
					var state = ev.originalEvent.state;
					swap($(solution_loader.solution_selector), state, 600, false, solution_loader.post_hooks);
				
				}
			
			});
			
			// Bind the click event
			$(solution_loader.link_selector)
				.filter(function() { return !!this.href.match(solution_loader.path_match); })
				.click(function(ev) {
				
					ev.preventDefault();
					
					console.log("HTML: ", this.href);
				
					$.ajax({
						'url': this.href,
						'dataType': 'html',
						'type': 'GET',
						'context': this,
						'success': function(data) {
						
							
							
							// Push the new state into the history
							window.history.pushState(data, null, this.href);
							
							if ($(solution_loader.solution_selector).length) {
							
								console.log("Replacement");
								swap(
									$(solution_loader.solution_selector),
									data, 600, false, solution_loader.post_hooks
								);
								
							} else {
							
								console.log("Insertion");
								var t = $(data)
									.insertAfter(solution_loader.insert_point)
									.hide().fadeIn(300, function() {
									
										if (!$('div#disqus_thread').length) { $(this).after('<div id="disqus_thread"></div>'); }
										for (var i = 0; i < solution_loader.post_hooks; i++) { solution_loader.post_hooks[i](this); }
									
									});
							
							}
							
						}
					});
				
				});
			
		
		}
	
	}
	
	return {
		'paginate': paginate,
		'load_solution': load_solution
	};

})();