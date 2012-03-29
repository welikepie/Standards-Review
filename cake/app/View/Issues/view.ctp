<?php App::import('Vendor', 'markdown'); ?>
<div id="issue" data-id="<?php echo($issue["issue_id"]); ?>">
	<h1><?php echo($issue['title']); ?></h1>
	<p class="created">Created by <?php echo($issue['author']); ?> on <?php echo($issue['created']); ?></p>
	<? if (isset($issue['source'])) { ?><p class="source"><b>Source:</b> <a href="<?php echo($issue['source']); ?>"><?php echo($issue['source']); ?></a></p><?php } ?>
	<table>
		<caption>References</caption>
		<tbody>
		<?php if(count($issue['references']['normal'])) {
		      foreach($issue['references']['normal'] as $name => &$ref) { ?>
			<tr>
				<th scope="row"><?php echo($name); ?>:</th>
				<td><?php echo($this->Html->link($ref, $ref, array("rel" => "external"))); ?></td>
			</tr>
		<?php } } ?>
		</tbody>
	</table>
	<?php if (isset($issue['references']['special']['demo'])) {
	
		// Check if URL points to JSFiddler
		$url = parse_url($issue['references']['special']['demo']);
		if (strtolower($url["host"]) === "jsfiddle.net") {
			$url = $issue['references']['special']['demo'];
			$url .= ( $url[strlen($url)-1] === "/" ? "" : "/" ) . "embedded/result,html,js,css";
			
			?><p class="demo"><b>Demo:</b></p><iframe src="<?php echo($url); ?>" class="demo" allowfullscreen="allowfullscreen"></iframe><?php
		} else {
			?><p class="demo"><b>Demo:</b> <?php echo($this->Html->link($issue['references']['special']['demo'], $issue['references']['special']['demo'], array("rel" => "external"))); ?></p><?php
		}
	
	} ?>
	<div class="description">
		<?php echo(Markdown($issue['description'])); ?>
	</div>
	<p class="status_<?php echo($issue['state']['state_id']); ?>"><?php echo($issue['state']['name']); ?></p>
	<p class="revised">Last edit by <?php echo($issue['revisionist']); ?> on <?php echo($issue['revised']); ?></p>
</div>
<div id="solutions">
	<h1>Solutions</h1>
<?php foreach($solutions as &$solution) { ?>
	<article>
		<h2><?php echo($solution['title']); ?></h2>
		<p>Made by <?php echo($solution['author']); ?> on <?php echo($solution['created']); ?></p>
		<div class="description">
			<?php echo(Markdown($solution['description'])); ?>
		</div>
		<p class="status_<?php echo($solution['state']['state_id']); ?>"><?php echo($solution['state']['name']); ?></p>
		<p>Last edit by <?php echo($solution['revisionist']); ?> on <?php echo($solution['revised']); ?></p>
		<?php echo($this->Html->link(
			'Read Solution',
			'/issues/' . $issue['issue_id'] . '/solutions/' . $solution['solution_id']
		)); ?>
	</article>
<?php } ?>
</div>