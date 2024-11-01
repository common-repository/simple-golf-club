<?php
/**
 * Event groups listing shortcode card
 */
?>

<div class="sgc-event-scorecard-card">
<?php if( !empty($scorecard['data']) ) : ?>
    <?php $total_strokes = 0; ?>

        <div class="header">
            <span class="player"><a href="<?php echo esc_html($scorecard['data']['player_url']) ?>"><?php echo esc_html($scorecard['data']['player_name']) ?></a></span>
            <span class="event"><a href="<?php echo esc_html($scorecard['data']['event_url']) ?>"><?php echo esc_html($scorecard['data']['event_name']) ?></a></span>
            <span class="tee"><?php echo esc_html($scorecard['data']['tee_color']) ?> (<?php echo esc_html($scorecard['data']['tee_difficulty']) ?>)</span>
        </div>
        
        <div class="content">
            <div class="tees">
                <div class="frontnine">
                    <table>
                        <caption><?php _e('Front Nine', SGC_TEXTDOMAIN) ?></caption>
                        <tbody>
                            <tr class="hole">
                                <th><?php _e('Hole', SGC_TEXTDOMAIN) ?></th>
                                <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <th><?php echo $i ?></th>
                                <?php endfor; ?>
                            </tr>
                            <tr class="strokes">
                                <th><?php _e('Strokes', SGC_TEXTDOMAIN) ?></th>
                                <?php for ($i = 0; $i < 9; $i++): ?>
                                    <td><?php echo esc_html($scorecard['data']['strokes'][$i]) ?></td>
                                    <?php $total_strokes += $scorecard['data']['strokes'][$i] ?>
                                <?php endfor; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="backnine">
                    <table>
                        <caption><?php _e('Back Nine', SGC_TEXTDOMAIN) ?></caption>
                        <tbody>
                            <tr class="hole">
                                <th><?php _e('Hole', SGC_TEXTDOMAIN) ?></th>
                                <?php for ($i = 10; $i <= 18; $i++): ?>
                                    <th><?php echo $i ?></th>
                                <?php endfor; ?>
                            </tr>
                            <tr class="strokes">
                                <th><?php _e('Strokes', SGC_TEXTDOMAIN) ?></th>
                                <?php for ($i = 9; $i < 18; $i++): ?>
                                    <td><?php echo esc_html($scorecard['data']['strokes'][$i]) ?></td>
                                    <?php $total_strokes += $scorecard['data']['strokes'][$i] ?>
                                <?php endfor; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="stats">
                <span class="greens"><?php _e('Greens', SGC_TEXTDOMAIN) ?>: <?php echo esc_html($scorecard['data']['greens']) ?></span>
                <span class="fairways"><?php _e('Fairways', SGC_TEXTDOMAIN) ?>: <?php echo esc_html($scorecard['data']['fairways']) ?></span>
                <span class="putts"><?php _e('Putts', SGC_TEXTDOMAIN) ?>: <?php echo esc_html($scorecard['data']['putts']) ?></span>
                <span class="total"><?php _e('Total', SGC_TEXTDOMAIN) ?>: <?php echo esc_html($total_strokes) ?></span>
            </div>
            
        </div>


<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Scorecard available', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>