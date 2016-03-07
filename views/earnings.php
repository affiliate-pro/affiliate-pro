<div id="ymas" class="wrap" ng-controller="EarningsController">
	<h1><?php _e('Earnings', 'ymas');?></h1>
	<h2>
		<?php _e('Latest transactions', 'ymas');?> <i class="fa fa-spin fa-spinner"></i>
	</h2>
	<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th><a ng-click="sortBy($event, 'name')"><?php _e('Program', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy($event, 'network')"><?php _e('Network', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy($event, 'transaction')"><?php _e('Transaction', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy($event, 'click_date')"><?php _e('Click', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy($event, 'event_date')"><?php _e('Event', 'ymas'); ?><i class="fa fa-arrow-up pull-right"></i></a></th>
			<th><a ng-click="sortBy($event, 'commission')"><?php _e('Commission', 'ymas'); ?></a></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php _e('Program', 'ymas'); ?></th>
			<th><?php _e('Network', 'ymas'); ?></th>
			<th><?php _e('Transaction', 'ymas'); ?></th>
			<th><?php _e('Click', 'ymas'); ?></th>
			<th><?php _e('Event', 'ymas'); ?></th>
			<th><?php _e('Commission', 'ymas'); ?></th>
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

	$scope.sortBy = function( event, sortType ) {
		
		jQuery('a.active').removeClass('up');	
		jQuery('a.active').removeClass('down');	
		jQuery('a.active').removeClass('active');	

		if ($scope.sortType == sortType) {
			$scope.sortReverse = $scope.sortReverse === true ? false : true;
			angular.element(event.target).addClass('active');
			
			if ($scope.sortReverse === true) {
				angular.element(event.target).addClass('up');
			} else {
				angular.element(event.target).addClass('down');
			}

		} else {
			$scope.sortType 	= sortType;
			$scope.sortReverse  = true;
			angular.element(event.target).addClass('active');
			angular.element(event.target).addClass('up');
		}

		$scope.refreshSortArrows();
	}

	$scope.refreshSortArrows = function() {
		jQuery('a i.fa.pull-right').remove();	
		jQuery('a.active.up').append(jQuery('<i class="fa fa-arrow-up pull-right"></i>'));	
		jQuery('a.active.down').append(jQuery('<i class="fa fa-arrow-down pull-right"></i>'));	
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