<?php
/**
 * Plugin Name: ArvanCloud Video Secure Link
 * Plugin URI: http://shalior.ir
 * Description: gets secure linked videos from arvan cloud
 * Version: 1.0.0
 * Author: Shalior
 * Author URI: http://www.shalior.ir
 */

if (!defined('ABSPATH')) {
	die();
}

define('ARVAN_SEC_LINK_PATH', plugin_dir_path(__FILE__));


//version
function shalior_arvan_sec_link_shortcodes_init()
{
	function shalior_arvan_sec_link($atts = [])
	{

		//shortcode sample = [arv_sec_link video_id="eeefesf" exp_time="NUMBER in minuts"]

		$video_id = isset($atts['video_id']) ? $atts['video_id'] : NULL;
		$exp_time = isset($atts['exp_time']) ? $atts['exp_time'] : 1440; //24h as default
		if ($video_id == NULL || !is_numeric($exp_time))
			return 'Invalid shortcode input';


		$api_key = esc_html(get_option('arvan_sec_link_options')['arv_api_key']);
		$base_url = "https://napi.arvancloud.com/vod/2.0/videos/";
		if (empty($api_key))
			return 'Error: no Apikey';


		$user_ip_address = shalior_get_ip_address();
		$sec_exp_time = (int)$exp_time * 60 + time();
		$video_end_point = $base_url . esc_html($video_id)
			. "?secure_ip="
			. $user_ip_address
			. '&secure_expire_time='
			. $sec_exp_time;

		$headers = [
			'Authorization' => $api_key,
			'accept' => 'application/json',
		];

		$response = wp_remote_get($video_end_point, [
			'headers' => $headers,
		]);

		if ($response instanceof WP_Error)
			return 'Error';

		if ($response['response']['code'] != 200)
			return 'Error!';

		$player_url = json_decode($response['body'], TRUE)['data']['player_url'];

		ob_start();
		include_once plugin_dir_path(__FILE__). '/arvan-sec-link-player.php';
		return ob_get_clean();

	}

	add_shortcode('arv_sec_link', 'shalior_arvan_sec_link');
}

add_action('init', 'shalior_arvan_sec_link_shortcodes_init');

//helpers
function shalior_get_ip_address()
{
	foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
		if (array_key_exists($key, $_SERVER) === TRUE) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip); // just to be safe

				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE) {
					return $ip;
				}
			}
		}
	}
}

if (!function_exists('write_log')) {

	function write_log($log)
	{
		if (TRUE === WP_DEBUG) {
			if (is_array($log) || is_object($log)) {
				error_log(print_r($log, TRUE));
			} else {
				error_log($log);
			}
		}
	}

}

require_once ARVAN_SEC_LINK_PATH . 'class-arvan-sec-link-options.php';