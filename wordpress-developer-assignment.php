<?php
/**
 * Plugin Name: WordPress Developer Assignment
 * Description: A builder-free, responsive WordPress landing page implemented as a custom plugin with editable CMS content.
 * Version: 1.0.0
 * Author: Deepa T L
 * License: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) exit;

define('WDA_VERSION', '1.0.0');
define('WDA_PATH', plugin_dir_path(__FILE__));
define('WDA_URL', plugin_dir_url(__FILE__));

require_once WDA_PATH . 'includes/class-wda-admin.php';
require_once WDA_PATH . 'includes/class-wda-plugin.php';

WDA_Plugin::instance();
WDA_Admin::instance();
