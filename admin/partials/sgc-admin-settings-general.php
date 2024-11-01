<?php
/**
 * Provide general settings and options for SGC configuration
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

<form method="post" action="options.php">
    <?php settings_fields($this->plugin_name . '_settings'); ?>
    <?php do_settings_sections($this->plugin_name . '_settings'); ?>
    <table class="form-table">
        <tr>
            <th><label for="sgc_default_team"><?= __('Default Team', SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <select name="sgc_default_team" id="sgc_default_team">
                    <option value=""></option>
                    <?php foreach ($teams as $team) : ?>
                        <option value="<?= esc_attr($team->ID) ?>" 
                            <?php selected($team->ID, get_option('sgc_default_team')); ?>>
                            <?= esc_html($team->post_title) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?= __('The default team that will automatically be selected when '
                    . 'adding new players and events.', SGC_TEXTDOMAIN) ?></td>
        </tr>
        <tr>
            <th><label for="sgc_default_location"><?= __('Default Location', SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <select name="sgc_default_location" id="sgc_default_location">
                    <option value=""></option>
                    <?php foreach ($locations as $location) : ?>
                        <option value="<?= esc_attr($location->ID) ?>" 
                            <?php selected($location->ID, get_option('sgc_default_location')); ?>>
                            <?= esc_html($location->post_title) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?= __('The default location that will automatically be selected '
                    . 'when adding events.', SGC_TEXTDOMAIN) ?></td>
        </tr>
        <tr>
            <th><label for="sgc_default_units"><?= __('Units', SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <select name="sgc_default_units" id="sgc_default_units">
                    <option value="imperial" <?php selected('imperial', get_option('sgc_default_units')); ?>>
                        <?= __('Imperial (ft/yd)', SGC_TEXTDOMAIN); ?></option>
                    <option value="metric" <?php selected('metric', get_option('sgc_default_units')); ?>>
                        <?= __('Metric (cm/m)', SGC_TEXTDOMAIN); ?></option>
                </select>
            </td>
            <td><?= __('Units to be used for all measurements.', SGC_TEXTDOMAIN) ?></td>
        </tr>
        <tr>
            <th><label for="sgc_default_numevents"><?= __('Number of Dashboard Events', SGC_TEXTDOMAIN) ?>
                </label></th>
            <td>
                <select name="sgc_default_numevents" id="sgc_default_numevents">
                    <option value=""></option>
                    <?php foreach (range(1, 10) as $i) : ?>
                        <option value="<?= $i ?>" <?php selected($i, 
                            get_option('sgc_default_numevents')); ?>>
                            <?= $i ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?= __('Maximum number of events to display for the Upcoming '
                    . 'and Past Events dashboard widget.', SGC_TEXTDOMAIN) ?> </td>
        </tr>
        <tr>
            <th><label for="sgc_display_personal"><?= __('Enable Posts on Homepage', 
                    SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <input type="checkbox" name="sgc_display_post_sgc_location" value="True"
                       <?php checked(get_option('sgc_display_post_sgc_location'), 'True'); ?> > 
                       <?= __('Locations', SGC_TEXTDOMAIN) ?><br>
                <input type="checkbox" name="sgc_display_post_sgc_team" value="True"
                       <?php checked(get_option('sgc_display_post_sgc_team'), 'True'); ?> > 
                       <?= __('Teams', SGC_TEXTDOMAIN) ?><br>
                <input type="checkbox" name="sgc_display_post_sgc_player" value="True"
                       <?php checked(get_option('sgc_display_post_sgc_player'), 'True'); ?> > 
                       <?= __('Players', SGC_TEXTDOMAIN) ?><br>
                <input type="checkbox" name="sgc_display_post_sgc_event" value="True"
                       <?php checked(get_option('sgc_display_post_sgc_event'), 'True'); ?> > 
                       <?= __('Events', SGC_TEXTDOMAIN) ?><br>
                <input type="checkbox" name="sgc_display_post_sgc_scorecard" value="True"
                       <?php checked(get_option('sgc_display_post_sgc_scorecard'), 'True'); ?> > 
                       <?= __('Score Cards', SGC_TEXTDOMAIN) ?>
            </td>
            <td><?= __('Add Simple golf club posts to the Home page feed.', SGC_TEXTDOMAIN) ?></td>
        </tr>
        <tr>
            <th><label for="sgc_display_personal"><?= __('Display Personal Info.', 
                    SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <input type="checkbox" name="sgc_display_personal" value="True"
                       <?php checked(get_option('sgc_display_personal'), 'True'); ?> > 
                       <?= __('Yes', SGC_TEXTDOMAIN) ?>
            </td>
            <td><?= __('Should Simple Golf Club display personal information for '
                    . 'players in the publicly accessible site? '
                    . '(Emails, Phone Numbers, etc...).', SGC_TEXTDOMAIN) ?></td>
        </tr>
        <tr>
            <th><label for="sgc_require_id"><?= __('Require ID', 
                    SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <input type="checkbox" name="sgc_require_id" value="True"
                       <?php checked(get_option('sgc_require_id'), 'True'); ?> > 
                       <?= __('Yes', SGC_TEXTDOMAIN) ?>
            </td>
            <td><?= __('Should Simple Golf Club require identification when updating '
                    . 'info through the public interface? (e.g. toggling checked-in status). '
                    . 'When enabled, SGC will check against the players email and then phone number. '
                    . 'If both are missing from the player\'s profile the changes will fail. '
                    . 'It is recommended that you un-check "Display Personal Info" (Above) '
                    . 'when enabling this feature.', SGC_TEXTDOMAIN) ?></td>
        </tr>
    </table>

    <?php submit_button(); ?>
</form>
