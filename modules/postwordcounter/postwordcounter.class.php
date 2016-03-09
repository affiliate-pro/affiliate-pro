<?php
namespace YoungMedia\Affiliate\Modules;


/**
 * Postwordcounter
 * Count words in post and output the result in admin post/page columns.
*/
class Postwordcounter extends Module {

	public $name = 'Word counter';
	public $slug = 'postwordcounter';


	public function Init() {

		if (!$this->isEnabledOption('displayWordcounter'))
			return;

		/* Backend Table Columns THEAD */
		add_filter('manage_posts_columns', array($this, 'manage_posts_columns'));
		add_filter('manage_pages_columns', array($this, 'manage_posts_columns'));

		/* Backend Table Columns TBODY */
		add_action('manage_posts_custom_column', array($this, 'manage_posts_custom_column'), 10, 2);
		add_action('manage_pages_custom_column', array($this, 'manage_posts_custom_column'), 10, 2);
	}

	/**
	 * Options 
	 * Create admin menu options 
	*/
	public function Options() {

		global $ymas;
		
		$ymas->admin_settings_tab->createOption( array(
		    'name' => $this->name,
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_tab->createOption( array(
			'name' => __('Enable Counter', 'ymas'),
			'id' => $this->slug . '_enabled_displayWordcounter',
			'type' => 'checkbox',
			'default' => false,
			'desc' => __('Check this box to enable word counter', 'ymas') . '<br><p><small>' . __('Display amount of post/page words','ymas') . '</small></p>',
		));

		$ymas->admin_settings_tab->createOption( array(
			'name' => __('Min Words', 'ymas'),
			'id' => $this->slug . '_min_words',
			'type' => 'text',
			'default' => 250,
			'desc' => __('Recommended minimum amount of words per article/page', 'ymas'),
		));

		$ymas->admin_settings_tab->createOption( array(
		    'type' => 'save',
		));
	}

	public function manage_posts_columns($defaults) {

		$tmp_date = $defaults['date'];
		unset($defaults['date']);

		$defaults['word_count'] = __( 'Word Count', 'youngmedia_affiliate' );
		$defaults['date'] = $tmp_date;

		return $defaults;

	}

	public function manage_posts_custom_column($column_name, $post_ID) {

	    if ($column_name == 'word_count') {

			$post = get_post($post_ID);
			$word_count = str_word_count($post->post_content);

			if ($word_count < $this->getOption('min_words'))
				echo '<span style="color:red">' . $word_count . '</span>';
			else
				echo $word_count;

	    }
	}

}