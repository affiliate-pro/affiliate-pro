<div ng-controller="OverviewLatestWeek" class="graph_container">
<div class="latest_week_loading loading_container" style="text-align: center;">
	<i class="fa fa-spinner fa-spin"></i>
</div>
<canvas 
	id="sales_latest_week" 
	class="chart chart-line" 
	chart-data="data"
  	chart-labels="labels" 
  	chart-series="series"
  	chart-options="options"
  >
</canvas>
</div>
<script type="text/javascript">
affiliatePro.controller("OverviewLatestWeek", function ($scope) {
	
	$scope.series = ['<?php _e('Sales', 'ymas'); ?>'];
	$scope.labels = [];

	$scope.options = {
        responsive: true,
        maintainAspectRatio: false,
        scaleShowGridLines: true,
		pointDot: true,
		showScale: true,
		showTooltips: true
    };

	$scope.data = [
		[]
	];

	jQuery(window).on('load', function($) {
		var data = {'action': 'dashboard_lastweek'};
		jQuery.post(ajaxurl, data, function(response) {

			if (response.status == 'ok') {

				jQuery('.latest_week_loading').hide();

				$scope.$apply(function () {
					$scope.labels = response.labels;
					$scope.data = response.data;
				});

			}

		});
	});
});
</script>