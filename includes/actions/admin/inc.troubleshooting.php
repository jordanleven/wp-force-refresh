<?php
/**
 * Functions to help troubleshoot installations of Force Refresh.
 *
 * @package ForceRefresh
 */

namespace JordanLeven\Plugins\ForceRefresh;

if ( ! function_exists( 'plugins_api' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
}

/**
 * Function to get the latest version of Force Refresh from the WordPress repository.
 *
 * @return  mixed Either null (if unable to get the version) or a string.
 */
function get_latest_plugin_version() {
    $args = array(
        'slug'   => WP_FORCE_REFRESH_REPOSITORY_SLUG,
        'fields' => array( 'version' => true ),
    );

    $call_api = plugins_api( 'plugin_information', $args );

    if ( ! is_wp_error( $call_api ) && ! empty( $call_api->version ) ) {
        return $call_api->version;
    }

    return null;
}

/**
 * Function to get an object of troubleshooting info that we'll localize and make available
 * to the troubleshooting page.
 *
 * @return  array  An object of troubleshooting info.
 */
function get_troubleshooting_info() {
    $force_refresh_plugin_data = get_plugin_data( get_main_plugin_file() );

    return array(
        'versions' => array(
            array(
                'label'           => 'PHP',
                'version'         => (string) phpversion(),
                'versionRequired' => (string) $force_refresh_plugin_data['RequiresPHP'],
            ),
            array(
                'label'           => 'WordPress',
                'version'         => (string) get_bloginfo( 'version' ),
                'versionRequired' => (string) $force_refresh_plugin_data['RequiresWP'],
            ),
            array(
                'label'           => 'Force Refresh',
                'version'         => (string) $force_refresh_plugin_data['Version'],
                'versionRequired' => get_latest_plugin_version(),
            ),
        ),
    );
}
