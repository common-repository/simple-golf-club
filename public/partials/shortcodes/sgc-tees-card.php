<?php
/**
 * Tees list shortcode card
 */
?>

<div class="sgc-sc-tees-card">
<?php if( !empty($teeslist['data']) ) : ?>
    <div class="tabs">
        <?php
        foreach ($teeslist  ['data'] as $index => $tee) :
            ?>
            <div class="tab">
                <input type="radio" class="tab-switch" id="tab-<?php echo $index ?>" name="css-tabs" checked>
                <label for="tab-<?php echo $index ?>" class="tab-label"><?php echo esc_html($tee->color) ?> (<?php echo esc_html($tee->difficulty) ?>)</label>
                <div class="tab-content">
                    
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
                                    <tr class="par">
                                        <th><?php _e('Par', SGC_TEXTDOMAIN) ?></th>
                                        <?php for ($i = 0; $i < 9; $i++): ?>
                                            <td><?php echo esc_html($tee->par[$i]) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr class="rating">
                                        <th><?php _e('Rating', SGC_TEXTDOMAIN) ?></th>
                                        <?php for ($i = 0; $i < 9; $i++): ?>
                                            <td><?php echo esc_html($tee->rating[$i]) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr class="length">
                                        <th><?php echo $units ?></th>
                                        <?php for ($i = 0; $i < 9; $i++): ?>
                                            <td><?php echo esc_html($tee->length[$i]) ?></td>
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
                                    <tr class="par">
                                        <th><?php _e('Par', SGC_TEXTDOMAIN) ?></th>
                                        <?php for ($i = 9; $i < 18; $i++): ?>
                                            <td><?php echo esc_html($tee->par[$i]) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr class="rating">
                                        <th><?php _e('Rating', SGC_TEXTDOMAIN) ?></th>
                                        <?php for ($i = 9; $i < 18; $i++): ?>
                                            <td><?php echo esc_html($tee->rating[$i]) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr class="length">
                                        <th><?php echo $units ?></th>
                                        <?php for ($i = 9; $i < 18; $i++): ?>
                                            <td><?php echo esc_html($tee->length[$i]) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="stats">
                        <ul>
                            <li class="par"><?php _e('Par:', SGC_TEXTDOMAIN) ?> <?php echo esc_html($tee->average_par) ?></li>
                            <li class="rating"><?php _e('Rating:', SGC_TEXTDOMAIN) ?> <?php echo esc_html($tee->average_rating) ?></li>
                            <li class="slope"><?php _e('Slope:', SGC_TEXTDOMAIN) ?> <?php echo esc_html($tee->average_slope) ?></li>
                        </ul>
                    </div>
                    
                </div> 
            </div>
        <?php endforeach;  ?>
    </div>
<?php else : ?>
    <span class="sgc-sc-message"><?php _e('No Tees found', SGC_TEXTDOMAIN) ?></span>
<?php endif; ?>
</div>