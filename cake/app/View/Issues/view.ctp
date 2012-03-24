<?php App::import('Vendor', 'markdown'); ?>
<div id="issue">
	<h1><?php echo($issue['title']); ?></h1>
	<p>Created by <?php echo($issue['author']); ?> on <?php echo($issue['created']); ?></p>
	<? if (isset($issue['source'])) { ?><p>Source: <a href="<?php echo($issue['source']); ?>"><?php echo($issue['source']); ?></a></p><?php } ?>
	<? if (isset($issue['references']['special']['demo'])) { ?><p>Demo: <a href="<?php echo($issue['references']['special']['demo']); ?>"><?php echo($issue['references']['special']['demo']); ?></a></p><?php } ?>
	<table>
		<caption>References</caption>
		<tbody>
		<?php if(count($issue['references']['normal'])) {
		      foreach($issue['references']['normal'] as $name => &$ref) { ?>
			<tr>
				<td><?php echo($name); ?>:</td>
				<td><?php echo($ref); ?></td>
			</tr>
		<?php } } ?>
		</tbody>
	</table>
	<div class="description">
		<?php echo(Markdown($issue['description'])); ?>
	</div>
	<p class="status_<?php echo($issue['state']['state_id']); ?>"><?php echo($issue['state']['name']); ?></p>
	<p>Last edit by <?php echo($issue['revisionist']); ?> on <?php echo($issue['revised']); ?></p>
</div>
<div id="solutions">
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