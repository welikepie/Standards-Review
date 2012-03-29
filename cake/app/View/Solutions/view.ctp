<?php

	App::import('Vendor', 'markdown');
	if(!$is_ajax) {
	
		$this->Html->scriptStart(array('inline' => false, 'block' => 'script'));
		?>
		var disqus_developer = 1;
		var disqus_shortname = 'standardsreview';
		var disqus_identifier = 'issue-<?php echo($issue_id); ?>-solution-<?php echo($solution['solution_id']); ?>';
		<?php
		$this->Html->scriptEnd();
		
		$this->Html->script(array(
			'jquery-1.7.2.min',
			'solution-load',
			'http://standardsreview.disqus.com/embed.js'
		), array(
			'inline' => false,
			'block' => 'script-async'
		));
		
		$this->Html->scriptStart(array('inline' => false, 'block' => 'script-async'));
		?>
		solution_switcher(null, [function(ins) {
			if (typeof DISQUS !== "undefined") {
			
				DISQUS.reset({
					reload: true,
					config: function () {  
						this.page.identifier = "issue-" + $("div#issue").attr("data-id") + "-solution-" + ins.getAttribute("data-id");
						this.page.url = window.location.href;
					}
				});
			
			}
		}]);
		<?php
		$this->Html->scriptEnd();
		
		echo($this->requestAction('/issues/' . $issue_id, array('return')));
	
	}

?>
<div id="solution" data-id="<?php echo($solution["solution_id"]); ?>">
	<h1><?php echo($solution['title']); ?></h1>
	<p class="created">Created by <?php echo($solution['author']); ?> on <?php echo($solution['created']); ?></p>
	<? if (isset($solution['source'])) { ?><p class="source"><b>Source:</b> <a href="<?php echo($solution['source']); ?>"><?php echo($solution['source']); ?></a></p><?php } ?>
	<table>
		<caption>References</caption>
		<tbody>
		<?php if(count($solution['references']['normal'])) {
		      foreach($solution['references']['normal'] as $name => &$ref) { ?>
			<tr>
				<th scope="row"><?php echo($name); ?>:</th>
				<td><?php echo($this->Html->link($ref, $ref, array("rel" => "external"))); ?></td>
			</tr>
		<?php } } ?>
		</tbody>
	</table>
	<?php if (isset($solution['references']['special']['demo'])) {
	
		// Check if URL points to JSFiddler
		$url = parse_url($solution['references']['special']['demo']);
		if (strtolower($url["host"]) === "jsfiddle.net") {
			$url = $solution['references']['special']['demo'];
			$url .= ( $url[strlen($url)-1] === "/" ? "" : "/" ) . "embedded/result,html,js,css";
			
			?><p class="demo"><b>Demo:</b></p><iframe src="<?php echo($url); ?>" class="demo" allowfullscreen="allowfullscreen"></iframe><?php
		} else {
			?><p class="demo"><b>Demo:</b> <?php echo($this->Html->link($solution['references']['special']['demo'], $solution['references']['special']['demo'], array("rel" => "external"))); ?></p><?php
		}
	
	} ?>
	<div class="description">
		<?php echo(Markdown($solution['description'])); ?>
	</div>
	<p class="status_<?php echo($solution['state']['state_id']); ?>"><?php echo($solution['state']['name']); ?></p>
	<p class="revised">Last edit by <?php echo($solution['revisionist']); ?> on <?php echo($solution['revised']); ?></p>
</div>
<div id="disqus_thread"></div>