<?php
/**
 * Event groups listing shortcode card
 */
?>

<div class="sgc-event-groups-card">
<?php if( !empty($groups['data']) ) : ?>
    <?php foreach( $groups['data'] as $group ) : ?>
        <div class="group">
            <ul>
                <li>
                    <span class="group-name"><?php echo esc_html($group['name']) ?></span>
                    <ul>
                        <?php foreach( $group['players'] as $player ) : ?>
                        <li><a href="<?php echo esc_url($player['URL']) ?>" target="_blank"><?php echo esc_html($player['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php endforeach; ?>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Groups created', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>