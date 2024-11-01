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

<input type="hidden" id="sgc_javascript" value="sgc_scorecard">

<!-- Scorecard Data -->
<div class="sgc-container">
    <table class="formtable">
        <tbody>
            <tr>
                <td>
                    <a href="#TB_inline?width=600&height=550&inlineId=sgc_scorecard_select_event" 
                       class="thickbox" id="sgc_selected_events_summary" 
                       name="<?= __('Select an Event...', SGC_TEXTDOMAIN) ?>">
                        <input type="text" id="sgc_selected_summary" 
                               class="sgc-scorecard-event-select" 
                               placeholder="<?= __('Select an Event...', SGC_TEXTDOMAIN) ?>" 
                               value="<?php echo ( count($sc['event']) > 0 ) ? $sc['event']['name'] : '' ?>">
                    </a>
                </td>
                <td>
                    <select name="sgc_scorecard_player" id="sgc_scorecard_player">
                        <option value="-1"><?= __('Select Player...', SGC_TEXTDOMAIN) ?></option>
                        <?php foreach ($sc['event']['teams'] as $team) : ?>
                            <?php foreach ($team['players'] as $player) : ?>
                                <option value="<?php esc_attr_e($player['ID']) ?>" 
                                    <?php selected($sc['player']['ID'], $player['ID']); ?>>
                                    <?php esc_html_e($player['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="sgc_scorecard_tee" id="sgc_scorecard_tee">
                        <option value="-1"><?= __('Any Tee', SGC_TEXTDOMAIN) ?></option>
                        <?php foreach ($sc['event']['location']['tees'] as $tee) : ?>
                            <option value="<?php esc_attr_e($tee['color']) ?>" 
                                <?php selected($tee['isdefault'], '1', true); ?>>
                                <?php esc_html_e($tee['color']) ?> (<?php esc_html_e($tee['difficulty']) ?>) 
                                <?php echo ($tee['isdefault'] == '1') ? '*' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="sgc-scorecard">
        <thead>
            <tr>
                <th class="sgc-scorecard"><?= __('Hole', SGC_TEXTDOMAIN) ?></th>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <th class="sgc-scorecard"><?= $i ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="sgc-scorecard"><?= __('Strokes', SGC_TEXTDOMAIN) ?></th>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <td class="sgc-scorecard-hole">
                        <input type="number" class="sgc-scorecard-hole" name="sgc_scorecard_hole" 
                               placeholder="0" value="<?php esc_html_e($sc['strokes'][$i-1]) ?>"/>
                    </td>
                <?php endfor; ?>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="sgc-scorecard">
        <thead>
            <tr>
                <th class="sgc-scorecard"><?= __('Hole', SGC_TEXTDOMAIN) ?></th>
                <?php for ($i = 10; $i <= 18; $i++): ?>
                    <th class="sgc-scorecard"><?= $i ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="sgc-scorecard"><?= __('Strokes', SGC_TEXTDOMAIN) ?></th>
                <?php for ($i = 10; $i <= 18; $i++): ?>
                    <td class="sgc-scorecard-hole">
                        <input type="number" class="sgc-scorecard-hole" name="sgc_scorecard_hole" 
                               placeholder="0" value="<?php esc_html_e($sc['strokes'][$i-1]) ?>"/>
                    </td>
                <?php endfor; ?>
            </tr>
        </tbody>
    </table>
    <br>

    <table class="sgc-scorecard-stats">
        <tbody class="sgc-scorecard-stats">
            <tr>
                <td class="sgc-scorecard-stats"><label for="sgc_scorecard_greens" 
                    class="sgc-scorecard-stats"><?= __('Greens in Regulation', SGC_TEXTDOMAIN) ?></label></td>
                <td class="sgc-scorecard-stats"><input type="number" class="sgc-scorecard-stats" 
                    id="sgc_scorecard_greens" name="sgc_scorecard_greens" 
                    value="<?= esc_attr($sc['greens']) ?>"></td>
                <td class="sgc-scorecard-stats"><label for="sgc_scorecard_fairways" 
                    class="sgc-scorecard-stats"><?= __('Fairways Hit', SGC_TEXTDOMAIN) ?></label></td>
                <td class="sgc-scorecard-stats"><input type="number" class="sgc-scorecard-stats" 
                    id="sgc_scorecard_fairways" name="sgc_scorecard_fairways" 
                    value="<?= esc_attr($sc['fairways']) ?>"></td>
                <td class="sgc-scorecard-stats"><label for="sgc_scorecard_putts" 
                    class="sgc-scorecard-stats"><?= __('Putts', SGC_TEXTDOMAIN) ?></label></td>
                <td class="sgc-scorecard-stats"><input type="number" class="sgc-scorecard-stats" 
                    id="sgc_scorecard_putts" name="sgc_scorecard_putts" 
                    value="<?= esc_attr($sc['putts']) ?>"></td>
            </tr>
        </tbody>
    </table>
    
    <input type="hidden" id="sgc_scorecard_selected_player" name="sgc_scorecard_selected_player" 
           value="<?= esc_attr($sc['player']['ID']) ?>">
    <input type="hidden" id="sgc_scorecard_selected_tee" name="sgc_scorecard_selected_tee" 
           value="<?= esc_attr($sc['tee']) ?>">
    <input type="hidden" id="sgc_scorecard_timestamp" name="sgc_scorecard_timestamp" 
           value="<?= esc_attr($sc['event']['date']) ?>">
    <textarea id="sgc_scorecard_strokes" name="sgc_scorecard_strokes" 
              style="display: none"><?php esc_html_e(json_encode($sc['strokes'])) ?></textarea>
</div>

<!-- #### Select Event Location -->
<div id="sgc_scorecard_select_event" style="display:none;">
    <div class="container" id="sgc_scorecard_manage_events_selectsearch" style="padding: 6px">
        <div style="position: absolute; right:64px;top:35px;">
            <a href="" class="button-primary thickbox" id="sgc_scorecard_event_save">
            <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-select-items" style="float:right;margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Scorecard Event", SGC_TEXTDOMAIN); ?>
            </div>
            <!-- SELECTED LOCATION LIST MAIN -->
            <div id="sgc_selected_main" class="sgc-select-items-body"></div>
        </div>
        <div class="sgc-select-items" style="margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Available Events", SGC_TEXTDOMAIN); ?>
            </div>
            <div class="sgc-select-items-body">
                <div id="sgc_select_search" class="sgc-select-add-search">
                    <input type="text" id="sgc_search_input" class="sgc-select-search-box">
                    <a href="" id="sgc_search_button" class="sgc-select-search-button">
                        <?= __('Search', SGC_TEXTDOMAIN) ?></a>
                </div>
                <!-- SEARCH RESULTS -->
                <div id="sgc_search_result"></div>
                <div id="sgc_search_nav_info" class="sgc-search-navigation">
                    <div class="sgc-select-page-nav">
                        <table id="sgc_select_page_navigation" class="sgc-select-page-navigation"><tr>
                            <td class="sgc-select-page-navigation">
                                <a href="" id="sgc_page_nav_start">&lt;&lt;</a></td>
                            <td class="sgc-select-page-navigation">
                                <a href="" id="sgc_page_nav_previous">&lt; <?= __('Prev', SGC_TEXTDOMAIN) ?></a></td>
                            <td class="sgc-select-page-navigation">|</td>
                            <td class="sgc-select-page-navigation">
                                <a href="" id="sgc_page_nav_next"><?= __('Next', SGC_TEXTDOMAIN) ?> &gt;</a></td>
                            <td class="sgc-select-page-navigation">
                                <a href="" id="sgc_page_nav_end">&gt;&gt;</a>
                            </td>
                        </tr></table>
                    </div>
                    <div class="sgc-select-page-nav" style="float:right;">
                        <span id="sgc_search_page_info" class="sgc-select-page-nav-info"></span>
                    </div>
                </div>
            </div>
        </div>
        <textarea name="sgc_scorecard_events" id="sgc_selected_items" style="display: none">
            <?php 
            // build event for select search
            esc_html_e(json_encode( [array(
                'ID' => $sc['event']['ID'],
                'name' => $sc['event']['name'],
                'URL' => $sc['event']['URL'],
                'delete' => false
            )]));
            ?>
        </textarea>
    </div>
</div>
