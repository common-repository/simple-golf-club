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

<input type="hidden" id="sgc_javascript" value="sgc_event">

<!-- #### Event Data -->
<div class="sgc-container">
    <table class="formtable">
        <tbody>
            <tr>
                <th><label for="sgc_event_date"><?= __('Date', SGC_TEXTDOMAIN) ?></label></th>
                <td>
                    <input type="text" name="sgc_event_date" id="sgc_eventdate" 
                           placeholder="<?= __('Date...', SGC_TEXTDOMAIN) ?>" 
                           value="<?php
                           if ($event['date']) {
                               echo esc_attr($event['date']);
                           }
                           ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="sgc_event_time"><?= __('Time', SGC_TEXTDOMAIN) ?></label></th>
                <td>
                    <input type="text" name="sgc_event_time" id="sgc_eventtime" 
                           placeholder="<?= __('Time...', SGC_TEXTDOMAIN) ?>" 
                           value="<?php
                           if ($event['time']) {
                           echo esc_attr($event['time']);
                           }
                           ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="sgc_event_location_select"><?= __('Location', SGC_TEXTDOMAIN) ?></label></th>
                <td>
                    <a href="#TB_inline?width=600&height=550&inlineId=sgc_event_select_location" 
                       class="thickbox" id="sgc_selected_locations_summary" 
                       name="<?= __('Select a Location...', SGC_TEXTDOMAIN) ?>">
                        <input type="text" id="sgc_selected_summary" 
                               class="sgc-event-location-select" 
                               placeholder="<?= __('Select a Location...', SGC_TEXTDOMAIN) ?>" 
                               value="<?= esc_attr( $event['locations'][0]['name'] ) ?>"/>
                    </a>
                </td>
            </tr>
            <tr>
                <th><label for="sgc_event_tee"><?= __('Tee', SGC_TEXTDOMAIN) ?></label></th>
                <td>
                    <select name="sgc_event_tee" id="sgc_event_tee" class="sgc-event-tee">
                        <option value=""><?= __('Any Tee', SGC_TEXTDOMAIN) ?></option>
                        <?php if ($event['tees']) : foreach ($event['tees'] as $tee) : ?>
                            <option value="<?= esc_attr($tee->color) ?>" 
                                <?php selected($tee->color, $event['tee']); ?>>
                                <?= esc_html($tee->color) ?> (<?= esc_html($tee->difficulty) ?>) 
                                <?= ($tee->isdefault ? '&ast;' : '') ?></option>
                        <?php endforeach;endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="sgc_event_team_select"><?= __('Team', SGC_TEXTDOMAIN) ?></label></th>
                <td>
                    <a href="#TB_inline?width=600&height=550&inlineId=sgc_event_select_team" 
                       class="thickbox" id="sgc_selected_teams_summary" 
                       name="<?= __('Select a Team...', SGC_TEXTDOMAIN) ?>">
                        <input type="text" id="sgc_selected_summary" 
                               class="sgc-event-team-select" 
                               placeholder="<?= __('Select a Team...', SGC_TEXTDOMAIN) ?>" 
                               value="<?= esc_attr( $event['teams'][0]['name'] ) ?>"/>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="sgc-table-top"><label for="sgc_event_managegroups">
                        <?= __('Groups', SGC_TEXTDOMAIN) ?></th>
                <td>
                    <div class="sgc-container">
                        <a href="#TB_inline?width=600&height=550&inlineId=sgc_event_manage_groups" 
                           class="thickbox button" id="sgc_event_manage_button" 
                           name="<?= __('Manage Groups', SGC_TEXTDOMAIN) ?>">
                            <?= __('Manage Groups', SGC_TEXTDOMAIN) ?></a>
                    </div>
                    <div id="sgc_event_groups_summary"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- #### Select Event Location -->
<div id="sgc_event_select_location" style="display:none;">
    <div class="container" id="sgc_event_manage_locations_selectsearch" style="padding: 6px">
        <div style="position: absolute; right:64px;top:35px;">
            <a href="" class="button-primary thickbox" id="sgc_event_location_save">
            <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-select-items" style="float:right;margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Event Location", SGC_TEXTDOMAIN); ?>
            </div>
            <!-- SELECTED LOCATION LIST MAIN -->
            <div id="sgc_selected_main" class="sgc-select-items-body"></div>
        </div>
        <div class="sgc-select-items" style="margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Available Locations", SGC_TEXTDOMAIN); ?>
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
        <textarea name="sgc_event_locations" id="sgc_selected_items" style="display: none">
            <?= esc_html(json_encode($event['locations'])) ?></textarea>
    </div>
</div>

<!-- #### Select Event Team -->
<div id="sgc_event_select_team" style="display:none;">
    <div class="container" id="sgc_event_manage_teams_selectsearch" style="padding: 6px">
        <div style="position: absolute; right:64px;top:35px;">
            <a href="" class="button-primary thickbox" id="sgc_event_team_save">
            <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <div class="sgc-select-items" style="float:right;margin-top:16px;">
            <div class="sgc-select-items-header">
                <?= __("Selected Team", SGC_TEXTDOMAIN); ?>
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
        <textarea name="sgc_event_teams" id="sgc_selected_items" style="display: none">
            <?= esc_html(json_encode($event['teams'])) ?></textarea>
    </div>
</div>

<!-- #### Manage Groups -->
<div id="sgc_event_manage_groups" style="display:none;">
    <div class="container" style="padding: 6px">
        <div style="float: right">
            <a href="" class="button-primary thickbox" style="position: absolute; right:64px;top:35px;" id="sgc_event_group_save">
                <?= __('Done', SGC_TEXTDOMAIN) ?></a>
        </div>
        <a href="" class="button-secondary thickbox" id="sgc_event_group_add" >
            <?= __('Add Group', SGC_TEXTDOMAIN) ?></a>
        <div id="sgc_event_group_display" class="sgc-event-group-list"></div>
    </div>
    <textarea name="sgc_event_group_list" id="sgc_event_group_list" style="display: none">
        <?= esc_html($event['groups']) ?></textarea>
</div>
