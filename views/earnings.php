<div id="ymas" class="wrap">
	<h1>Affiliate <small>&gt; Earnings</small></h1>
	<?php 	
	if ($ymas->adtraction->isConfigured())
		require_once('earnings-adtraction.php');

	if ($ymas->adrecord->isConfigured())
		require_once('earnings-adrecord.php');
	?> 
</div>