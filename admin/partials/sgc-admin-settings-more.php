<?php
/**
 * Provide extended settings and options for SGC configuration
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.6.22
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
            <th><label for="sgc_disable_theme_nag"><?= __('Disable Theme Nag', SGC_TEXTDOMAIN) ?></label></th>
            <td>
                <input type="checkbox" name="sgc_disable_theme_nag" value="True"
                       <?php checked(get_option('sgc_disable_theme_nag'), 'True'); ?> > 
                       <?= __('Yes', SGC_TEXTDOMAIN) ?>
            </td>
            <td><?= __('Disable the Notification that reminds you to choose a theme that supports Simple Golf Club '
                    , SGC_TEXTDOMAIN) ?></td>
        </tr>
    </table>

    <?php submit_button(); ?>
</form>
