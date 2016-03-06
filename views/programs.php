<div id="ymas" class="wrap" ng-controller="ProgramsController">
	<h1><?php _e('Affiliate', 'ymas');?> <small>&gt; <?php _e('Programs', 'ymas');?></small></h1>
	<h2>
		<?php _e('Approved programs', 'ymas');?> <i class="fa fa-spin fa-spinner"></i>
	</h2>
	<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th><a ng-click="sortBy('name')"><?php _e('Program', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('network')"><?php _e('Network', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('category')"><?php _e('Category', 'ymas'); ?></a></th>
			<th class="url-column"><a ng-click="sortBy('tracking_url')"><?php _e('Tracking URL', 'ymas'); ?></a></th>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="row in programs | orderBy:sortType:sortReverse">
			<td>{{ row.name }}</td>
			<td>{{ row.network }}</td>
			<td>{{ row.category }}</td>
			<td class="url-column">
				<input type="text" disabled ng-model="row.tracking_url">
			</td>
		</tr>
	</tbody>
	</table>
</div>
<script type="text/javascript">
affiliatePro.controller('ProgramsController', ['$scope', function($scope) {

	$scope.sortType     = 'name'; // set the default sort type
	$scope.sortReverse  = false;  // set the default sort order

	$scope.sortBy = function( sortType ) {
		
		if ($scope.sortType == sortType)
			$scope.sortReverse = $scope.sortReverse === true ? false : true;
		else {
			$scope.sortType 	= sortType;
			$scope.sortReverse  = false;
		}
	}

	jQuery(document).ready(function($) {
		var data = {'action': 'programs_list'};
		jQuery.post(ajaxurl, data, function(response) {

			if (response.status == 'ok') {
				$scope.$apply(function () {
					$scope.programs = response.results;
				});
			}

			jQuery('.fa-spinner').fadeOut();
		});
	});

}]);
</script>