<?php

	App::import('Vendor', 'markdown');
	if(!$is_ajax) {
	
		$this->Html->script('yepnope', array('inline' => false));
		$this->Html->scriptStart(array('inline' => false)); ?>
		
		yepnope({
			load: [ "/standards-review/cake/js/jquery-1.7.2.min.js",
			        "/standards-review/cake/js/solution-load.js" ],
			complete: function() { alert("Test"); solution_ajax(); }
		});
		
		<?php $this->Html->scriptEnd();
		echo($this->requestAction('/issues/' . $issue_id, array('return')));
	
	}

?>
<div id="solution">
	<h1><?php echo($solution['title']); ?></h1>
	<p>Created by <?php echo($solution['author']); ?> on <?php echo($solution['created']); ?></p>
	<? if (isset($solution['source'])) { ?><p>Source: <a href="<?php echo($solution['source']); ?>"><?php echo($solution['source']); ?></a></p><?php } ?>
	<? if (isset($solution['references']['special']['demo'])) { ?><p>Demo: <a href="<?php echo($solution['references']['special']['demo']); ?>"><?php echo($issue['references']['special']['demo']); ?></a></p><?php } ?>
	<table>
		<caption>References</caption>
		<tbody>
		<?php if(count($solution['references']['normal'])) {
		      foreach($solution['references']['normal'] as $name => &$ref) { ?>
			<tr>
				<td><?php echo($name); ?>:</td>
				<td><?php echo($ref); ?></td>
			</tr>
		<?php } } ?>
		</tbody>
	</table>
	<div class="description">
		<?php echo(Markdown($solution['description'])); ?>
	</div>
	<p class="status_<?php echo($solution['state']['state_id']); ?>"><?php echo($solution['state']['name']); ?></p>
	<p>Last edit by <?php echo($solution['revisionist']); ?> on <?php echo($solution['revised']); ?></p>
	<div id="disqus">
		<p>DISQUS GOES HERE</p>
	</div>
</div>