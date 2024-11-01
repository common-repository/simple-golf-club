<?php
/**
 * Provide UI for upcoming events widget
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.0.0
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/admin/partials
 */
?>

<div class="sgc-container">
    <div class="sgc-widget-events">
        <h3><?= __('Past Events', SGC_TEXTDOMAIN) ?></h3>
        
        <?php if (empty($events_past)) : ?>
            <h3><?= __('No Past Events', SGC_TEXTDOMAIN) ?></h3>
        <?php else : ?>
            <ul>
                <?php 
                    $event_date_previous = '';
                    foreach ($events_past as $event) : 
                ?>
                    <li class="sgc-widget-event-item">
                    <?php 
                        $event_timestamp = strtotime( get_post_meta($event->ID, 'sgc_event_timestamp', true) );
                        date_default_timezone_set( get_option('timezone_string') );
                        $event_date = date( 'F j Y', $event_timestamp );
                        $event_time = date( 'g:i A', $event_timestamp );
                        
                        if ( $event_date != $event_date_previous ) :
                    ?>
                        <span class="sgc-widget-event-date"><?= esc_html( $event_date ) ?></span>
                    <?php endif; ?>
                        <span class="sgc-widget-event-link"><?= esc_html( $event_time ) ?> : <a href="<?= esc_url(get_the_permalink($event->ID)) ?>" target="_widget">
                            <?= esc_html($event->post_title) ?></a>
                            <small><a href="<?= get_edit_post_link($event->ID) ?>"><?= __('Edit', SGC_TEXTDOMAIN) ?></a></small>
                        </span>
                    </li>
                <?php 
                    $event_date_previous = $event_date;
                    endforeach;
                ?>
            </ul>
        <?php endif; ?>
    </div>
    
    <div class="sgc-widget-events" style="float: right;">
        <h3><?= __('Upcoming Events', SGC_TEXTDOMAIN) ?></h3>
            
        <?php if (empty($events_upcoming)) : ?>
            <h3><?= __('No Upcoming Events', SGC_TEXTDOMAIN) ?></h3>
        <?php else : ?>
            <ul>
                <?php 
                    $event_date_previous = '';
                    foreach ($events_upcoming as $event) : 
                        
                ?>
                    <li class="sgc-widget-event-item">
                    <?php
                        $event_timestamp = strtotime( get_post_meta($event->ID, 'sgc_event_timestamp', true) );
                        date_default_timezone_set( get_option('timezone_string') );
                        $event_date = date( 'F j Y', $event_timestamp );
                        $event_time = date( 'g:i A', $event_timestamp );
                        
                        if ( $event_date != $event_date_previous ) :
                    ?>
                        <span class="sgc-widget-event-date"><?= esc_html( $event_date ) ?></span>
                    <?php endif; ?>
                        <span class="sgc-widget-event-link"><?= esc_html( $event_time ) ?> <a href="<?= esc_url(get_the_permalink($event->ID)) ?>" target="_widget">
                            <?= esc_html($event->post_title) ?></a>
                            <small><a href="<?= get_edit_post_link($event->ID) ?>"><?= __('Edit', SGC_TEXTDOMAIN) ?></a></small>
                        </span>
                    </li>
                <?php 
                    $event_date_previous = $event_date;
                    endforeach; 
                ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div class="sgc-container-clear"></div>
