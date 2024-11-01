<?php
/**
 * Event Info shortcode card
 */
?>

<div class="sgc-event-info-card">
    <div class="content">
        <ul>
            <li class="event">
                <a href="<?php echo esc_url($eventinfo['data']['URL']) ?>" target="_blank"><?php echo esc_html($eventinfo['data']['name']) ?></a>
                <ul>
                    <li class="date">
                        <span><?php echo date( 'M d, Y', strtotime($eventinfo['data']['time']) ) ?></span>
                        <span><?php echo date( 'g:i A', strtotime($eventinfo['data']['time']) ) ?></span>
                    </li>
                    <li class="location"><a href="<?php echo esc_url($eventinfo['data']['location_url']) ?>" target="_blank"><?php echo esc_html($eventinfo['data']['location_name']) ?></a></li>
                    <li class="team"><a href="<?php echo esc_url($eventinfo['data']['team_url']) ?>" target="_blank"><?php echo esc_html($eventinfo['data']['team_name']) ?></a></li>
                    <li class="tee"><?php echo esc_html($eventinfo['data']['tee']) ?></li>
                </ul>
            </li>
            
        </ul>
    </div>
</div>