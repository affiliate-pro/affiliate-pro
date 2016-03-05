<h2>Adrecord</h2>
<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th>Program</th>
			<th>Transaction</th>
			<th>Click</th>
			<th>Event</th>
			<th>Commission</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	foreach( $adrecord_transactions as $transaction ) : ?>
		<tr>
			<td><?php echo $transaction->program->name; ?></td>
			<td><?php echo $transaction->type; ?> - <?php echo $transaction->commissionName; ?></td>
			<td><?php echo date("d M Y H:i", strtotime($transaction->click)); ?></td>
			<td><?php echo date("d M Y H:i", strtotime($transaction->changes[0]->date)); ?></td>
			<td><?php echo $transaction->commission / 100; ?></td>
		</tr>
	<?php endforeach; 

	if (count($adrecord_transactions) == 0):
	?>
		<td colspan="5">Sorry, but you have no transactions for this channel yet.</td>
	<?php endif; ?>
	</tbody>
</table>