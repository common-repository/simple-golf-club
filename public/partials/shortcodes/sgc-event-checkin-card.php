<?php
/**
 * Event groups listing shortcode card
 */
?>

<div class="sgc-event-checkin-card" id="event_player_checkin" event_id="<?php echo $eventid ?>">
<?php if( !empty($playerslist['data']) ) : ?>
    <table>
        <thead>
            <tr>
                <th><?php _e('Player', SGC_TEXTDOMAIN) ?></th>
                <th><?php _e('Attending?', SGC_TEXTDOMAIN) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $playerslist['data'] as $player ) : ?>
            <tr>
                <td class="name"><a href="<?php echo esc_url($player['URL']) ?>" target="_blank">
                    <?php echo esc_html($player['name']) ?></a></td>
                <td class="checkin">
                    <?php
                        $rest_url = get_option('siteurl') 
                                    . '/wp-json/simplegolfclub/v1/event/checkin/' 
                                    . $eventid . '/' . $player['ID'] 
                                    . '?email=' . $player['email']
                                    . '&phone=' . $player['phone'];
                    ?>
                    <a href="<?php echo esc_url($rest_url) ?>" 
                        id="player_checkin_<?php echo esc_attr($player['ID']) ?>" 
                        sgc_tc_player_id="<?php echo esc_attr($player['ID']) ?>"
                        sgc_tc_player_email="<?php echo esc_attr($player['email']) ?>"
                        sgc_tc_player_phone="<?php echo esc_attr($player['phone']) ?>"
                        >
                            <?php ( $player['checkedin'] === 'true' ) ? _e('Yes', SGC_TEXTDOMAIN) : _e('No', SGC_TEXTDOMAIN); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Players to checkin', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>