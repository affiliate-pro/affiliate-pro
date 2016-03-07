<div id="ymas" class="wrap" ng-controller="ProgramsController">
	<h1><?php _e('Programs', 'ymas');?></h1>
	<h2>
		<?php _e('Approved programs', 'ymas');?> <i class="fa fa-spin fa-spinner"></i>
	</h2>
	<table class="wp-list-table widefat fixed striped" cellspacing="0">
	<thead>
		<tr>
			<th><a ng-click="sortBy($event, 'name')"><?php _e('Program', 'ymas'); ?><i class="fa fa-arrow-down pull-right"></i></a></th>
			<th><a ng-click="sortBy($event, 'network')"><?php _e('Network', 'ymas'); ?></a></th>
			<th><a ng-click="sortBy($event, 'category')"><?php _e('Category', 'ymas'); ?></a></th>
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
		<tr ng-hide="programs.length">
			<td colspan="5"><?php _e('Sorry, but you have no programs for this channel yet.', 'ymas'); ?></td>
		</tr>
	</tbody>
	</table>
</div>
<script type="text/javascript">
affiliatePro.controller('ProgramsController', ['$scope', function($scope) {

	$scope.sortType     = 'name'; // set the default sort type
	$scope.sortReverse  = false;  // set the default sort order

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
			$scope.sortReverse  = false;
			angular.element(event.target).addClass('active');
			angular.element(event.target).addClass('down');
		}

		$scope.refreshSortArrows();
	}

	$scope.refreshSortArrows = function() {
		jQuery('a i.fa.pull-right').remove();	
		jQuery('a.active.up').append(jQuery('<i class="fa fa-arrow-up pull-right"></i>'));	
		jQuery('a.active.down').append(jQuery('<i class="fa fa-arrow-down pull-right"></i>'));	
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