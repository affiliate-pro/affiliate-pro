<h2>Adtraction</h2>
<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th class="lang-column"></th>
			<th class="logo-column">Program</th>
			<th class="category-column">Category</th>
			<!--th>Program</th -->
			<th class="compensation-column">Compensations</th>
			<th class="url-column">Tracking URL</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	foreach( $adtraction_programs as $program ) : 

		//$affiliate_link = $program->trackingURL;
		$affiliate_link = site_url() . '/' . $ymas->slug . '/' . sanitize_title($program->programName);
	?>
		<tr>
			<td class="lang-column">
				<img src="https://cdn1.iconfinder.com/data/icons/famfamfam_flag_icons/<?php echo strtolower(str_replace('NO', 'bv', $program->market)); ?>.png" alt="">
			</td>
			<td class="logo-column"><img class="table-logo" src="<?php echo $program->logoURL; ?>"></td>
			<td class="category-column">
				<?php echo $program->category; ?>
			</td>
			<td class="compensation-column">
				<table class="compensation-table">
				<?php foreach ( $program->compensations as $compensation ) : ?>
					<tr>
						<td><?php echo $compensation->name; ?></td>
						<td class="right"><?php echo $compensation->value; ?> <?php echo $compensation->type; ?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			</td>
			<td class="url-column">
				<input type="text" disabled value="<?php echo $affiliate_link; ?>">
				<input type="text" disabled value="<?php echo $program->trackingURL; ?>">
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>