<?php
/**
 * Provide a list of players for the team
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

<input type="hidden" id="sgc_javascript" value="sgc_team">

<!-- #### Team Data -->
<div class="sgc-container">
    <a href="#TB_inline?width=600&height=550&inlineId=sgc_team_manage_players" 
       class="thickbox button" id="sgc_event_manage_button" 
       name="<?= __('Manage Players', SGC_TEXTDOMAIN) ?>">
        <?= __('Manage Players', SGC_TEXTDOMAIN) ?></a>
    <!-- SELECTED TEAMS LIST SUMMARY -->
    <div id="sgc_selected_players_summary">
        <div id="sgc_selected_summary"></div>
    </div>

<!-- #### Manage Team Players -->
<div id="sgc_team_manage_players" style="display:none;">
    <div class="container" id="sgc_team_manage_players_selectsearch" style="padding: 6px">
        <div style="position: absolute; right:64px;top:35px;">
            <a href="" class="button-primary thickbox" id="sgc_team_player_save">
            <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-select-items" style="float:right;margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Team Players", SGC_TEXTDOMAIN); ?>
            </div>
            <!-- SELECTED PLAYERS LIST MAIN -->
            <div id="sgc_selected_main" class="sgc-select-items-body"></div>
        </div>
        <div class="sgc-select-items" style="margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Available Players", SGC_TEXTDOMAIN); ?>
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
        <textarea name="sgc_team_players" id="sgc_selected_items" style="display: none">
            <?= esc_html(json_encode($team['playerlist'])) ?></textarea>
    </div>
</div>
