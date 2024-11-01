<?php
/**
 * Teams list shortcode card
 */
?>

<div class="sgc-player-teams-card">
<?php if( !empty($teamslist['data']) ) : ?>
    <ul>
    <?php foreach( $teamslist['data'] as $team ) : ?>
    <li>
        <a href="<?php echo esc_url($team['URL']) ?>" target="_blank"><?php echo esc_html($team['name']) ?></a>
    </li>
    <?php endforeach; ?>
    </ul>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Teams found', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>