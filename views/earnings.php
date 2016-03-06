<div id="ymas" class="wrap" ng-controller="EarningsController">
	<h1><?php _e('Affiliate', 'ymas');?> <small>&gt; <?php _e('Earnings', 'ymas');?></small></h1>
	<h2>
		<?php _e('Latest transactions', 'ymas');?> <i class="fa fa-spin fa-spinner"></i>
	</h2>
	<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th><a ng-click="sortBy('name')"><?php _e('Program', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('network')"><?php _e('Network', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('transaction')"><?php _e('Transaction', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('click_date')"><?php _e('Click', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('event_date')"><?php _e('Event', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('commission')"><?php _e('Commission', 'ymas'); ?></a></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><a ng-click="sortBy('name')"><?php _e('Program', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('network')"><?php _e('Network', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('transaction')"><?php _e('Transaction', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('click_date')"><?php _e('Click', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('event_date')"><?php _e('Event', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy('commission')"><?php _e('Commission', 'ymas'); ?></a></th>
		</tr>
	</tfoot>
	<tbody>
		<tr ng-repeat="row in transactions | orderBy:sortType:sortReverse">
			<td>{{ row.name }}</td>
			<td>{{ row.network }}</td>
			<td>{{ row.transaction }}</td>
			<td>{{ row.click_date }}</td>
			<td>{{ row.event_date }}</td>
			<td>{{ row.commission }} {{ row.currency }}</td>
		</tr>
		<tr ng-hide="transactions.length">
			<td colspan="5"><?php _e('Sorry, but you have no transactions for this channel yet.', 'ymas'); ?></td>
		</tr>
	</tbody>
	</table>
</div>
<script type="text/javascript">
affiliatePro.controller('EarningsController', ['$scope', function($scope) {

	$scope.sortType     = 'event_date'; // set the default sort type
	$scope.sortReverse  = true;  // set the default sort order

	$scope.sortBy = function( sortType ) {
		
		if ($scope.sortType == sortType)
			$scope.sortReverse = $scope.sortReverse === true ? false : true;
		else {
			$scope.sortType 	= sortType;
			$scope.sortReverse  = true;
		}
	}

	jQuery(document).ready(function($) {
		var data = {'action': 'earnings_list'};
		jQuery.post(ajaxurl, data, function(response) {

			console.log(response);
			
			if (response.status == 'ok') {
				$scope.$apply(function () {
					$scope.transactions = response.results;
				});
			}

			jQuery('.fa-spinner').fadeOut();
		});
	});
}]);
</script>