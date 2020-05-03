<?php
if (!defined('ABSPATH')) {
	die();
}
?>
<input style="padding: .3em;direction: ltr !important; text-align: left !important; width: 40em"
       type="text"
       id="<?php echo esc_attr($args['label_for']); ?>"
       name="arvan_sec_link_options[<?php echo esc_attr($args['label_for']); ?>]"
       value="<?php echo isset($option[$args['label_for']]) ? $option[$args['label_for']] : 5; ?>">
