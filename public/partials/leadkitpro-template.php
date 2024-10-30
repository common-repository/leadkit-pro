<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php echo $lcd_campaign->data->name; ?></title>
	<?php if ( ! $lcd_campaign->data->name ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
	<?php endif; ?>

	<?php wp_head(); ?>
	<?php
	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
	
	<!-- OG Tags -->
	<meta property="og:title" content="<?php echo $lcd_campaign->data->og_title; ?>" />
	<meta property="og:description" content="<?php echo $lcd_campaign->data->og_description; ?>" />
	<meta property="og:url" content="<?php echo get_permalink() ?>" />
	<meta property="og:image" content="<?php echo $lcd_campaign->data->og_image; ?>" />

</head>
<body id="lkpr_pub_page_id" <?php body_class(); ?>>
	<div id="lkpr-iframe-container">
    	<iframe src="<?php echo LKPR_APP_URL; ?>c/<?php echo $campaign_id; ?>" allowfullscreen mozallowfullscreen webkitallowfullscreen>Your browser doesn't support iFrames.</iframe>
    </div>
	<?php
	wp_footer();
	?>
</body>
</html>
