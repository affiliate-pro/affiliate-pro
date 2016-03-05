<h2>Adtraction</h2>
<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th class="lang-column"></th>
			<th class="logo-column">Program</th>
			<th>Coupon Code</th>
			<th>Offer Terms</th>
			<th>Offer Description</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach( $coupons as $coupon ) : 
	if (!isset($coupon->offerCoupon) OR empty($coupon->offerCoupon))
		continue;
	?>
		<tr>
			<td class="lang-column">
				<img src="https://cdn1.iconfinder.com/data/icons/famfamfam_flag_icons/<?php echo strtolower(str_replace('NO', 'bv', $coupon->market)); ?>.png" alt="">
			</td>
			<td class="logo-column"><img class="table-logo" src="<?php echo $coupon->logoURL; ?>"></td>
			<td><input type="text" disabled value="<?php echo $coupon->offerCoupon; ?>"></td>
			<td><?php if(isset($coupon->offerTerms)) echo $coupon->offerTerms; ?></td>
			<td><?php echo $coupon->offerDescription; ?></td>
		</tr>
	<?php endforeach; 

	if (count($coupons) == 0):
	?>
		<td colspan="5">Sorry, but you have no active coupon codes for this channel.</td>
	<?php endif; ?>
	</tbody>
</table>