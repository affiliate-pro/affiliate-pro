<div id="ymas" class="wrap">
	<h1>Affiliate <small>&gt; Links</small></h1>
	<?php 	
	if ($ymas->adtraction->isConfigured())
		require_once('links-adtraction.php');	

	if ($ymas->adrecord->isConfigured())
		require_once('links-adrecord.php');
	?> 
</div>