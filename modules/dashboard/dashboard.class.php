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
	 * Options 
	 * Create admin menu options 
	*/
	public function Options() {
		
		$this->panelSalesOverview();
		$this->panelSalesLatestWeek();

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