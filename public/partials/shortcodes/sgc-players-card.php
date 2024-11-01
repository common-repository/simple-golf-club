<?php
/**
 * Players list shortcode card
 */
?>


<div class="sgc-sc-players-card">
<?php if( !empty($playerslist['data']) ) : ?>
    <ul>
        <?php foreach( $playerslist['data'] as $player ) : ?>
        <li>
            <a href="<?php echo esc_url($player['URL']) ?>" target="_blank"><?php echo esc_html($player['name']) ?></a>
        </li>

        <?php endforeach; ?>
    </ul>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Players listed', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>