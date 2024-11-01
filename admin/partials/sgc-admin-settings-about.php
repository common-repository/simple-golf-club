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

<div class="sgc-settings about">
    <div class="heading">
        <table>
            <tr>
                <td><h2>Simple Golf Club</h2></td>
                <td><?php _e('v', SGC_TEXTDOMAIN) ?><?php _e(SGC_VERSION) ?></td>
            </tr>
            <tr>
                <td colspan="2"><a href="https://simplegolfclub.com/" target="_blank"><?php _e('SimpleGolfClub.com', SGC_TEXTDOMAIN) ?></a></td>
            </tr>
            <tr>
                <td colspan="2"><a href="https://gitlab.com/mlinton/simplegolfclub/" target="_blank"><?php _e('SimpleGolfClub on GitLab', SGC_TEXTDOMAIN) ?></a></td>
            </tr>
            <tr>
                <td colspan="2"><a href="https://gitlab.com/mlinton/" target="_blank"><?php _e('Matthew Linton', SGC_TEXTDOMAIN) ?></a></td>
            </tr>
        </table>
    </div>

    <div class="description">
        <p>
            <?php _e('Simple Golf Club provides an easy way you to coordinate golf outings for your friends or for large groups of people.<br>
            Create teams, add players, schedule events, and track player scores using a simple interface.<br>
            For more information, check out the <a href="https://simplegolfclub.com/" target="_blank">SGC Website</a>.<br>
            For more information and to contact the developers, checkout <a href="https://gitlab.com/mlinton/simplegolfclub/" target="_blank">GitLab</a>.', SGC_TEXTDOMAIN) ?>
        </p>
    </div>

    <div class="license">
        <h2>License</h2>
        <p>
            <?php _e('This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License 
            as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.
            This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
            of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
            You should have received a copy of the GNU General Public License along with this program. If not, 
            see <a href="https://www.gnu.org/licenses/" target="_blank">https://www.gnu.org/licenses/</a>.', SGC_TEXTDOMAIN) ?> 
        </p>
    </div>
</div>
