<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin. 
 *
 * @link              http://mbmti.ir
 * @since             2.0.8
 * @package           MBM_IPAK
 *
 * @wordpress-plugin
 * Plugin Name:       ایپک ( حسابداری )
 * Plugin URI:        http://mbmti.ir
 * Description:       سیستم حسابداری ایپک
 * Version:           2.0.8
 * Author:            ایپک
 * Author URI:        http://mbmti.ir/our-team
 * Text Domain:       mbm-ipak
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) die;

/* General Definition
******************************/
define('MBM_IPAK_VERSION', '2.0.8');

define('MBM_IPAK_BASE', plugin_dir_path(__FILE__));
define('MBM_IPAK_URI', plugin_dir_url(__FILE__));
define('MBM_IPAK_FILE', __FILE__);
define('MBM_IPAK_Include', MBM_IPAK_BASE . 'include/');
define('MBM_IPAK_View', MBM_IPAK_BASE . 'view/');
$ViewData = [];


require MBM_IPAK_Include . 'lib/jdf.php';
require MBM_IPAK_Include . 'lib/tools.php';
require MBM_IPAK_Include . 'sql_scripts.php';
require MBM_IPAK_Include . 'model.php';
require MBM_IPAK_Include . 'models.php';
require MBM_IPAK_Include . 'base_class.php';
require MBM_IPAK_Include . 'entity.php';
require MBM_IPAK_Include . 'shared.php';
require MBM_IPAK_Include . 'ajax.php';
require MBM_IPAK_Include . 'setting.php';
require MBM_IPAK_Include . 'core.php';
require MBM_IPAK_Include . 'home.php';
require MBM_IPAK_Include . 'woocommerce.php';


foreach (glob(MBM_IPAK_Include . "hooks/*.php") as $filename) {
    include $filename;
}

$MBM_Ipak_Core;
function MBM_Ipak_Core()
{
}
global $MBM_Ipak_Core;
$MBM_Ipak_Core = new MBM_Ipak_Core();


//add_action("init", "MBM_Ipak_Core",10);

add_action('init', 'do_export_csv',11);

function do_export_csv()
{

    $sql = get_option("mbm_ipak_export_sql");

    $data = [];

    if (strlen($sql) < 10) {
        return;
    }

    global $wpdb;
    $data = $wpdb->get_results($sql, 'ARRAY_A');
    if(is_array($data))
    {
        update_option("mbm_ipak_export_sql", "");

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="wp.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputs($file, "\xEF\xBB\xBF"); // UTF-8
    
    
        foreach ($data as $item) {
    
            fputcsv($file, $item);
        }

        exit();
    }

}
