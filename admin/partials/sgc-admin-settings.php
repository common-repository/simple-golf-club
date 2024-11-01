<?php
/**
 * Provide About info for SGC configuration
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.0.0
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/admin/partials
 */

$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>

<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="?page=simplegolfclub-manage_options" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">General</a>
      <a href="?page=simplegolfclub-manage_options&tab=shortcodes" class="nav-tab <?php if($tab==='shortcodes'):?>nav-tab-active<?php endif; ?>">Shortcodes</a>
      <a href="?page=simplegolfclub-manage_options&tab=about" class="nav-tab <?php if($tab==='about'):?>nav-tab-active<?php endif; ?>">About</a>
    </nav>

    <div class="tab-content">
    <?php switch($tab) :
      case 'about':
        include_once( 'sgc-admin-settings-about.php' );
        break;
      case 'shortcodes':
        include_once( 'sgc-admin-settings-shortcodes.php' );
        break;
      default:
        include_once( 'sgc-admin-settings-general.php' );
        break;
    endswitch; ?>
    </div>
  </div>
