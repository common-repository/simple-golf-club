<?php
/**
 * Events list shortcode card
 */
?>

<div class="sgc-sc-events-card">
<?php if( !empty($eventslist['data']) ) : ?>
    <table>
        <thead>
            <tr>
                <th class="date"><?php _e('Date', SGC_TEXTDOMAIN) ?></th>
                <th class="time"><?php _e('Time', SGC_TEXTDOMAIN) ?></th>
                <th class="name"><?php _e('Event', SGC_TEXTDOMAIN) ?></th>
                <th class="name"><?php _e('Tee', SGC_TEXTDOMAIN) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $eventslist['data'] as $event ) : ?>
            <tr>
                <td class="date"><?php echo date( 'M d, Y', strtotime($event['time']) ) ?></td>
                <td class="time"><?php echo date( 'g:i A', strtotime($event['time']) ) ?></td>
                <td class="name"><a href="<?php echo esc_url($event['URL']) ?>" target="_blank"><?php echo esc_html($event['name']) ?></a></td>
                <td class="name"><?php echo esc_html($event['tee']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Events scheduled', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>