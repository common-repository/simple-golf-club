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

<input type="hidden" id="sgc_javascript" value="sgc_player">

<!-- #### Player Data -->
<div class="sgc-container">
    <table class="formtable">
        <tbody>
            <tr>
                <th><label for="sgc_player_handicap"><?= __('Handicap', SGC_TEXTDOMAIN) ?></label></th>
                <td><input type="number" name="sgc_player_handicap" id="sgc_player_handicap" 
                        value="<?= esc_attr($player['handicap']) ?>" /></td>
            </tr>
            <tr>
                <th><label for="sgc_player_phone"><?= __('Phone', SGC_TEXTDOMAIN) ?></label></th>
                <td><input type="tel" name="sgc_player_phone" id="sgc_player_phone" 
                        value="<?= esc_attr($player['phone']) ?>" /></td>
            </tr>
            <tr>
                <th><label for="sgc_player_email"><?= __('Email', SGC_TEXTDOMAIN) ?></label></th>
                <td><input type="email" name="sgc_player_email" id="sgc_player_email" 
                        value="<?= esc_attr($player['email']) ?>"></td>
            </tr>
            <tr>
                <th class="sgc-table-top"><label for="sgc_player_team"><?= __('Teams', SGC_TEXTDOMAIN) ?>
                    </label></th>
                <td>
                    <div class="sgc-container">
                    <a href="#TB_inline?width=600&height=550&inlineId=sgc_player_manage_teams" 
                       class="thickbox button" id="sgc_team_manage_button" 
                       name="<?= __('Manage Teams', SGC_TEXTDOMAIN) ?>">
                        <?= __('Manage Teams', SGC_TEXTDOMAIN) ?></a>
                    </div>
                    <!-- SELECTED TEAMS LIST SUMMARY -->
                    <div id="sgc_selected_teams_summary">
                        <div id="sgc_selected_summary"></div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- #### Manage Player Teams -->
<div id="sgc_player_manage_teams" style="display:none;">
    <div class="container" id="sgc_player_manage_teams_selectsearch" style="padding: 6px">
        <div style="position: absolute; right:64px;top:35px;">
            <a href="" class="button-primary thickbox" id="sgc_player_team_save">
            <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-select-items" style="float:right;margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Player Teams", SGC_TEXTDOMAIN); ?>
            </div>
            <!-- SELECTED TEAMS LIST MAIN -->
            <div id="sgc_selected_main" class="sgc-select-items-body"></div>
        </div>
        <div class="sgc-select-items" style="margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Available Teams", SGC_TEXTDOMAIN); ?>
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
        <textarea name="sgc_player_teams" id="sgc_selected_items" style="display: none">
            <?= esc_html(json_encode($player['teamlist'])) ?></textarea>
    </div>
</div>
