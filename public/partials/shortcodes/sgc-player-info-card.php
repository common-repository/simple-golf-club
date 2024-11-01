<?php
/**
 * Player Info shortcode card (Single Player)
 */
?>

<div class="sgc-player-info-card">
<?php if( !empty($playerinfo['data']) ) : ?>
    <span class="name"><a href="<?php echo esc_url($playerinfo['data']['URL']) ?>" target="_blank"><?php echo esc_html($playerinfo['data']['name']) ?></a></span>
    <span class="phone"><a href="phone:<?php echo esc_attr($playerinfo['data']['phone']) ?>" target="_blank">
        <?php echo esc_html($playerinfo['data']['phone']) ?></a></span>
    <span class="email"><a href="mailto:<?php echo esc_attr($playerinfo['data']['email']) ?>" target="_blank">
        <?php echo esc_html($playerinfo['data']['email']) ?></a></span>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No information for this Player', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>