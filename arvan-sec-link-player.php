<?php
if (!defined('ABSPATH')) {
	die();
}
?>
<style> .r1_iframe_embed {
		position: relative;
		overflow: hidden;
		width: 100%;
		height: auto;
		padding-top: 56.25%;
	}

	.r1_iframe_embed iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border: 0;
	} </style>
<div class="r1_iframe_embed">
	<iframe
		src="<?php echo esc_url($player_url) ?>"
		frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
		allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
</div>
