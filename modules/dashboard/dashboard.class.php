<?php
namespace YoungMedia\Affiliate\Modules;


/**
 * Dashboard Module
 * Creates a beutiful Dashboard ;)
*/
class Dashboard extends Module {

	public $name = 'Dashboard';
	public $slug = 'dashboard';

	/**
	 * Init 
	 * Runs on hook admin_init 
	*/
	public function admin_init() {
		
		add_action( 'wp_ajax_dashboard_lastweek', array(&$this, 'DashboardLastWeek') );
		add_action( 'wp_ajax_dashboard_sales_overview', array(&$this, 'DashboardSalesOverview') );

	}

	/**
	 * Options 
	 * Create admin menu options 
	*/
	public function Options() {
		
		$this->panelSalesOverview();
		$this->panelSalesLatestWeek();

	}

	public function DashboardLastWeek() {

		$ajax = new \YoungMedia\Affiliate\Ajax\Ajax();

		// Create dates and dates labels from last 7 days
		$dates = \YoungMedia\Affiliate\last_seven_days();
		$labels = \YoungMedia\Affiliate\last_seven_days_labels();

		// Get and group transactions by date
		$transactions = $ajax->CombineLists('transactions');
		$grouped_transactions = \YoungMedia\Affiliate\group_by_date($transactions, 'event_date');

		// Group results into weeks.
		$week = array();
		foreach ($dates as $day) {

			$week[$day] = array();
			if (isset($grouped_transactions[$day]))
				$week[$day] = $grouped_transactions[$day];
			
		}

		$sales = array();
		foreach ($week as $results) {
			$sales[] = count($results);
		}

		wp_send_json(array(
			'status' => 'ok',
			'data' => array($sales),
			'labels' => $labels,
		));
	}

	public function DashboardSalesOverview() {

		global $ymas;
		$ajax = new \YoungMedia\Affiliate\Ajax\Ajax();
		
		$labels = array('','','','','','','','','','');
		$today = array('','','','','','','','','','');
		$week = array('','','','','','','','','','');
		$month = array('','','','','','','','','','');

		// Get and group transactions by network
		$transactions = $ajax->CombineLists('transactions');
		$network_transactions = \YoungMedia\Affiliate\group_by_network($transactions);
		
		$i = -1;
		foreach ($ymas->modules as $module) {

			$module_name = strtolower($module);
			$module = $ymas->$module_name;

			if ($module->module_type == 'network' AND $module->isConfigured() === true) {

				$i++;

				// Create network labels and data			
				$labels[$i] = $module->name;

				if (!isset($network_transactions[$module->slug])) {
					$today[$i] = '';
					$week[$i] = 0;
					$month[$i] = 0;
					continue;
				}	

				$module_transactions = $network_transactions[$module->slug];

				$today[$i] = count(\YoungMedia\Affiliate\group_dates_by_range($module_transactions, 'today'));
				$week[$i] = count(\YoungMedia\Affiliate\group_dates_by_range($module_transactions, 'week'));
				$month[$i] = count(\YoungMedia\Affiliate\group_dates_by_range($module_transactions, 'month'));
			} 
		}

		wp_send_json(array(
			'status' => 'ok',
			'labels' => $labels,
			'data' => array($today,$week,$month)
		));
	}

	public function panelSalesOverview() {

		global $ymas;
		$panel = $ymas->admin_settings_dashboard_tab;

		$panel->createOption( array(
		    'name' => __('Sales overview', 'ymas'),
		    'type' => 'heading',
		));

		$panel->createOption( array(
		    'type' => 'custom',
		    'custom' => $this->view('block_overview_sales'),
		) );

	}

	public function panelSalesLatestWeek() {

		global $ymas;
		$panel = $ymas->admin_settings_dashboard_tab;

		$panel->createOption( array(
		    'name' => __('Last week\'s events', 'ymas'),
		    'type' => 'heading',
		));

		$panel->createOption( array(
		    'type' => 'custom',
		    'custom' => $this->view('block_latest_week'),
		) );

	}
}