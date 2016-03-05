<h2>Adrecord</h2>
<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th class="lang-column"></th>
			<th class="logo-column">Program</th>
			<th class="category-column">Category</th>
			<th class="compensation-column">Compensations</th>
			<th class="url-column">Tracking URL</th>
			<th class="clicks-column">Clicks (unique)</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach( $adrecord_programs as $row ) : 
		$affiliate_link = site_url() . '/' . $ymas->slug . '/' . sanitize_title($row->program->name);
		$tracking_url = $this->adrecord->tracking_url($row->program->id);
		$program_info = $this->adrecord->api->program($row->program->id);
	?>
		<tr>
			<td class="lang-column">
				
			</td>
			<td class="logo-column"><?php echo $row->program->name; ?></td>
			<td class="category-column"><?php echo $program_info->category; ?></td>
			<td class="compensation-column">
				<table class="compensation-table">
				<?php foreach ( $program_info->commissions as $commission ) : ?>
					<tr>
						<td><?php echo $commission->type; ?> - <?php echo $commission->name; ?></td>
						<td class="right"><?php echo $commission->commission; ?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			</td>
			<td class="url-column">
				<input type="text" disabled value="<?php echo $affiliate_link; ?>">
				<input type="text" disabled value="<?php echo $tracking_url; ?>">
			</td>
			<td class="clicks">
				<?php echo $row->clicks->total; ?> (<?php echo $row->clicks->unique; ?>)
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>