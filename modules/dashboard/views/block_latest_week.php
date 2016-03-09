<div ng-controller="OverviewLatestWeek">
<div class="latest_week_loading" style="text-align: center;">
	<i class="fa fa-spinner fa-spin"></i>
</div>
<canvas 
	id="sales_latest_week" 
	class="chart chart-line" 
	chart-data="data"
  	chart-labels="labels" 
  	chart-series="series"
  	chart-options="options"
  	style="display:none"
  >
</canvas>
</div>
<script type="text/javascript">
affiliatePro.controller("OverviewLatestWeek", function ($scope) {
	
	$scope.series = ['<?php _e('Försäljning', 'ymas'); ?>'];
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

	jQuery(document).ready(function($) {
		var data = {'action': 'dashboard_lastweek'};
		jQuery.post(ajaxurl, data, function(response) {

			console.log(response);

			if (response.status == 'ok') {

				jQuery('.latest_week_loading').hide();
				jQuery('#sales_latest_week').fadeIn();

				$scope.$apply(function () {
					$scope.labels = response.labels;
					$scope.data = response.data;
				});

			}

		});
	});
});
</script>