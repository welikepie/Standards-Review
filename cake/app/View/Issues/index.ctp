<?php App::import('Vendor', 'markdown'); ?>
<h1>Issue Index</h1>
<div id="issues">
<?php foreach($issues as &$issue) { ?>
	<article>
		<h2><?php echo($issue['title']); ?></h2>
		<p>Made by <?php echo($issue['author']); ?> on <?php echo($issue['created']); ?></p>
		<div class="description">
			<?php echo(Markdown($issue['description'])); ?>
		</div>
		<p class="status_<?php echo($issue['state']['state_id']); ?>"><?php echo($issue['state']['name']); ?></p>
		<p>Last edit by <?php echo($issue['revisionist']); ?> on <?php echo($issue['revised']); ?></p>
		<?php echo($this->Html->link(
			'View Solutions',
			'/issues/' . $issue['issue_id'] . '/solutions'
		)); ?>
	</article>
<?php } ?>
</div>