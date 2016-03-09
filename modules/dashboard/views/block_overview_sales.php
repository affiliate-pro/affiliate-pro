<div ng-controller="OverviewSalesContainer">
<canvas 
	id="chart_overview_salesLatestMonth" 
	class="chart chart-bar" 
	chart-data="data"
  	chart-labels="labels" 
  	chart-series="series"
  	chart-options="options"
  >
</canvas>
</div>
<script type="text/javascript">
affiliatePro.controller("OverviewSalesContainer", function ($scope) {

	$scope.labels = ['Adrecord', 'Adtraction','','','','','','','','',''];
	$scope.series = [
	'<?php _e('Today', 'ymas'); ?>',
	'<?php _e('Last week', 'ymas'); ?>', 
	'<?php _e('Last month', 'ymas'); ?>'];
	$scope.options = {
        responsive: true,
        maintainAspectRatio: false,
        scaleShowGridLines: true,
		pointDot: true,
		showScale: true,
		showTooltips: true
    };
	$scope.data = [
	[3, 1,0,0,0,0,0,0,0,0,0],
	[7, 10,0,0,0,0,0,0,0,0,0],
	[7, 10,0,0,0,0,0,0,0,0,0]
	];
});
</script>