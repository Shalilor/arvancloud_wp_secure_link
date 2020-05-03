<?php

if (!defined('ABSPATH')) {
	die();
}

/**
 * handles options of the plugin
 *
 * @author     Shalior <contact@shalior.ir>
 */
final class Arvan_Sec_Link_Settings
{

	private static $instance;
	public $page_slug = 'arvan-sec-link';
	private $options;

	/**
	 * Sets up needed actions/filters for the admin options to initialize.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct()
	{

		if (!is_admin()) {
			return;
		}
		$this->load_settings();
		$this->options = get_option('arvan_sec_link_options', [
			'arv_api_key' => 'Apikey xxxxx-xxxx-x-x',
		]);

	}

	public function load_settings()
	{
		add_action('admin_init', [$this, 'settings_init']);
		add_action('admin_menu', [$this, 'add_options_page']);

		//get options
	}

	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  1.0.0
	 * @access public
	 */
	public static function get_instance()
	{

		if (!self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @param $args
	 */
	public function field_arv_api_key($args)
	{
		$option = $this->options;
		//include html
		include_once ARVAN_SEC_LINK_PATH . '/partials/options/field_arv_api_key.php';
	}

	public function section_1($args)
	{
		?>
        <a target="_blank" href="https://www.arvancloud.com/fa/products/video-platform"
           id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Video Platform', 'arvan-sec-link'); ?></a>
		<?php
	}

	public function settings_init()
	{

		$args = [
			'description' => '',
			'sanitize_callback' => [$this, 'sanitize_callback'],
			'show_in_rest' => FALSE,
		];
		register_setting($this->page_slug, 'arvan_sec_link_options', $args);

		add_settings_section(
			'arvan_sec_link_section_1',
			__('تنظیمات', 'arvan-sec-link'),
			[$this, 'section_1'],
			$this->page_slug
		);

		//code length field
		add_settings_field(
			'arvan_sec_link_arv_api_key',
			// use $args' label_for to populate the id inside the callback
			__('کلید Api', 'arvan-sec-link'),
			[$this, 'field_arv_api_key'],
			$this->page_slug,
			'arvan_sec_link_section_1',
			[
				'label_for' => 'arv_api_key',
				'class' => 'wrc_row',
			]
		);

	}

	public function sanitize_callback($data)
	{

		$have_err = FALSE;

		//validate api key
		if (strpos($data['arv_api_key'], 'Apikey') === FALSE) {
			add_settings_error($this->page_slug, $this->page_slug, __('باید با کلمه Apikey وارد کنید.', 'arv_sec_link'));
			$have_err = TRUE;
		}

		if (!$have_err) {
			//sanitize
			$data['arv_api_key'] = sanitize_text_field($data['arv_api_key']);

			return $data;
		}

		return $this->options;
	}

	//menu methods
	public function add_options_page()
	{
		add_options_page(
			'تنظیمات لینک امن آروان کلود',
			'لینک امن آروان کلود',
			'manage_options',
			$this->page_slug,
			[$this, 'get_options_page_html']
		);
	}

	public function get_options_page_html()
	{
		if (!current_user_can('manage_options')) {
			return;
		}
		//settings_errors( $this->page_slug );
		require_once ARVAN_SEC_LINK_PATH . '/partials/options/arvan-sec-link-admin-setting-view.php';
	}

}

Arvan_Sec_Link_Settings::get_instance();