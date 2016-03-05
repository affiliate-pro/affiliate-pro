<h2>Adtraction</h2>
<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th>Program</th>
			<th>Transaction</th>
			<th>Click</th>
			<th>Event</th>
			<th>Comission</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach( $adtraction_transactions as $transaction ) : ?>
		<tr>
			<td><?php echo $transaction->programName; ?></td>
			<td><?php echo $transaction->transactionName; ?></td>
			<td><?php echo date("d M Y H:i", strtotime($transaction->clickDate)); ?></td>
			<td><?php echo date("d M Y H:i", strtotime($transaction->transactionDate)); ?></td>
			<td><?php echo $transaction->commission; ?> <?php echo $transaction->currency; ?></td>
		</tr>
	<?php endforeach; 

	if (count($adtraction_transactions) == 0):
	?>
		<td colspan="5">Sorry, but you have no transactions for this channel yet.</td>
	<?php endif; ?>
	</tbody>
</table>