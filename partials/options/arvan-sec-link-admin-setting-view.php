<?php
if (!defined('ABSPATH')) {
	die();
}
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
		<?php

		settings_fields('arvan-sec-link');

		do_settings_sections('arvan-sec-link');

		submit_button(__('ذخیره تنظیمات', 'arvan-sec-link'));
		?>
    </form>

</div>

<div class="wrap">
    <p>
        نمونه کد کوتاه:
        <Br><textarea style="width: 80%; direction: ltr !important; text-align: left !important;" readonly>[arv_sec_link video_id="fefe-efefs-vdzxv324" exp_time="NUMBER in minuts"]</textarea>
        <br>exp_time is optional
        <br>video_id is required
    </p>
</div>