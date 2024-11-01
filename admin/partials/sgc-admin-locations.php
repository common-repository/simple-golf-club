<?php
/**
 * Provide ui for entering in date/time
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

<?php add_thickbox(); ?> 

<input type="hidden" id="sgc_javascript" value="sgc_location">

<!-- #### Location Data -->
<div class="sgc-container">
    <a href="#TB_inline?width=600&height=550&inlineId=sgc_location_tee" 
       class="thickbox button" id="sgc_location_tee_add" name="
        <?= __('Add Tee', SGC_TEXTDOMAIN) ?>"><?= __('Add Tee', SGC_TEXTDOMAIN) ?></a>
    <div id="sgc_location_tee_summary"></div>
    <textarea name="sgc_location_tees" id="sgc_location_tees" style="display: none">
        <?= esc_html($location['tees']) ?></textarea>
</div>

<!-- #### Manage Location Tee -->
<div id="sgc_location_tee" style="display:none;">
    <div class="container" style="padding: 6px">
        <input type="hidden" name="sgc_location_tee_index" 
               id="sgc_location_tee_index" value="-1">
        <div class="sgc-container" style="float: right">
            <a href="" class="button thickbox" id="sgc_location_tee_copy">
                <?= __('Copy', SGC_TEXTDOMAIN) ?></a>
            <a href="" class="button-primary thickbox" id="sgc_location_tee_save">
                <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-location-tee">
            <div class="sgc-location-tee-info">
                <label for="sgc_location_tee_color" class="sgc-location-tee-info">
                        <?= __('Color', SGC_TEXTDOMAIN) ?></label>
                <input type="text" name="sgc_location_tee_color" 
                       id="sgc_location_tee_color">
                <label for="sgc_location_tee_difficulty" class="sgc-location-tee-info">
                        <?= __('Difficulty', SGC_TEXTDOMAIN) ?></label>
                <input type="text" name="sgc_location_tee_difficulty" 
                        id="sgc_location_tee_difficulty">
            </div>
            <div class="sgc-location-tee-data">
                <label for="sgc_location_par" class="sgc-location-tee-data"><?= __('Par', SGC_TEXTDOMAIN) ?></label>
                <input type="number" class="sgc-location-par" name="sgc_location_tee_avgpar" id="sgc_location_tee_avgpar">
                <label for="sgc_location_rating" class="sgc-location-tee-data"><?= __('Rating', SGC_TEXTDOMAIN) ?></label>
                <input type="number" class="sgc-location-rating" name="sgc_location_tee_avgrating" id="sgc_location_tee_avgrating">
            </div>
            <div class="sgc-location-tee-data">
                <label for="sgc_location_isdefault" class="sgc-location-tee-data" style="display:inline;padding-top:1.1em;"><?= __('Default', SGC_TEXTDOMAIN) ?></label>
                <input type="checkbox" class="sgc-location-isdefault" name="sgc_location_tee_isdefault" id="sgc_location_tee_isdefault">
                <label for="sgc_location_slope" class="sgc-location-tee-data"><?= __('Slope', SGC_TEXTDOMAIN) ?></label>
                <input type="number" class="sgc-location-slope" name="sgc_location_tee_avgslope" id="sgc_location_tee_avgslope">
            </div>
        </div>
        <div class="sgc-container"></div>
        <div class="sgc-location-tee">
            <h1 class="sgc-location-tee"><?= __('Front Nine', SGC_TEXTDOMAIN) ?></h1>
            <table class="sgc-location-tee-holes">
                <tbody>
                    <tr>
                        <th class="sgc-location-hole"><?= __('Hole', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                            <th class="scg-location-hole-number"><?= $i ?></th>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-par"><?= __('Par', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                            <td class="sgc-location-par"><input class="sgc-location-tee"
                                type="number" name="sgc_location_tee_par"></td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-rating"><?= __('Rating', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                            <td class="sgc-location-rating"><input class="sgc-location-tee"
                                type="number" name="sgc_location_tee_rating"></td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-length"><?= __('Length', SGC_TEXTDOMAIN) ?> 
                            (<?= esc_html($default_units) ?>)</th>
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                            <td class="sgc-location-length"><input class="sgc-location-tee"
                                type="number" name="sgc_location_tee_length"></td>
                        <?php endfor; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sgc_container">
            <h1 class="sgc-location-tee"><?= __('Back Nine', SGC_TEXTDOMAIN) ?></h1>
            <table class="sgc-location-tee-holes">
                <tbody>
                    <tr>
                        <th class="sgc-location-hole"><?= __('Hole', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 10; $i <= 18; $i++): ?>
                            <th class="scg-location-hole-number"><?= $i ?></th>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-par"><?= __('Par', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 10; $i <= 18; $i++): ?>
                            <td class="sgc-location-par"><input class="sgc-location-tee"
                                type="number" name="sgc_location_tee_par"></td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-rating"><?= __('Rating', SGC_TEXTDOMAIN) ?></th>
                        <?php for ($i = 10; $i <= 18; $i++): ?>
                            <td class="sgc-location-rating"><input class="sgc-location-tee"
                                type="number" name="sgc_location_tee_rating"></td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th class="sgc-location-length"><?= __('Length', SGC_TEXTDOMAIN) ?> 
                            (<?= esc_html($default_units) ?>)</th>
                        <?php for ($i = 10; $i <= 18; $i++): ?>
                            <td class="sgc-location-length"><input class="sgc-location-tee" 
                                type="number" name="sgc_location_tee_length"></td>
                            <?php endfor; ?>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
