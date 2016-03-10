<div ng-controller="OverviewSalesContainer" class="graph_container">
<div class="sales_overview_loading loading_container">
	<i class="fa fa-spinner fa-spin"></i>
</div>
<canvas 
	id="sales_overview" 
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

	$scope.labels = [];

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

	$scope.data = [];

	jQuery(window).on('load', function($) {
		var data = {'action': 'dashboard_sales_overview'};
		jQuery.post(ajaxurl, data, function(response) {

			if (response.status == 'ok') {
				jQuery('.sales_overview_loading').hide();

				$scope.$apply(function () {
					$scope.labels = response.labels;
					$scope.data = response.data;
				});

			}

		});
	});

});
</script>