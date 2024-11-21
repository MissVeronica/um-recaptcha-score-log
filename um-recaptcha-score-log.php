<?php
/**
 * Plugin Name:       Ultimate Member - reCAPTCHA V3 Score Log
 * Description:       Extension to Ultimate Member for logging of the UM Google reCAPTCHA V3 Scores to a file with .CSV format.
 * Version:           1.1.0
 * Requires PHP:      7.4
 * Author:            Miss Veronica
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI:        https://github.com/MissVeronica?tab=repositories
 * Plugin URI:        https://github.com/MissVeronica/um-recaptcha-score-log
 * Update URI:        https://github.com/MissVeronica/um-recaptcha-score-log
 * Text Domain:       um-recaptcha
 * Domain Path:       /languages
 * Requires Plugins:  UM Google reCAPTCHA
 * UM version:        2.9.1
 * reCAPTCHA version: 2.3.8 or later
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'UM' ) ) return;

class UM_reCAPTCHA_Score_Log {

    public $log_file_path = '/uploads/ultimatemember/um-recaptcha-score/';
    public $log_file_name = 'g-recaptcha-v3-score-log.csv';
    public $g_response    = false;
    public $csv_file_hdrs = array(
                                    'Time',
                                    'Google',
                                    'UM setting',
                                    'UM form',
                                    'UM limit',
                                    'success',
                                    'action',
                                    'WP page',
                                    'UM form',
                                    'UM mode',
                                    'User domain',
                                    'User IP',
                                    'Country',
                                    'Browser',
                                    'Platform',
                                    'username',
                                    'error message'
                                );

    function __construct() {

        define( 'Plugin_Basename_RSL', plugin_basename( __FILE__ ));

        if ( UM()->options()->get( 'g_recaptcha_status' ) == 1 ) {

            $version = UM()->options()->get( 'g_recaptcha_version' );
            if ( UM()->options()->get( 'g_recaptcha_score_log' ) == 1 && $version == 'v3' ) {

                add_action( 'um_recaptcha_api_response',     array( $this, 'um_g_recaptcha_save_g_response' ), 10, 1 );
                add_filter( 'um_recaptcha_score_validation', array( $this, 'um_g_recaptcha_score_log' ), 10, 3 );
            }
        }

        add_filter( 'um_settings_structure', array( $this, 'add_settings_score_log' ), 100, 1 );
        add_filter( 'plugin_action_links_' . Plugin_Basename_RSL, array( $this, 'plugin_settings_link' ), 10, 1 );
    }

    public function plugin_settings_link( $links ) {

        if ( class_exists( 'UM_ReCAPTCHA' ) && version_compare( UM_RECAPTCHA_VERSION, '2.3.8' ) != -1 ) {

            $url = get_admin_url() . 'admin.php?page=um_options&tab=extensions&section=recaptcha';
            $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings' ) . '</a>'; 
        }

        return $links;
    }

    public function um_g_recaptcha_save_g_response( $response ) {

        if ( is_array( $response )) {

            $this->g_response = $response;
        }
    }

    public function um_g_recaptcha_score_log( $score, $args, $form_data ) {

        if ( ! empty( $this->g_response ) && is_array( $this->g_response )) {

            $g_result = json_decode( $this->g_response['body'] );

            $trace = array();

            $trace[] = date_i18n( 'Y/m/d H:i:s', current_time( 'timestamp' ));
            $trace[] = ( isset( $g_result->score )) ? $this->convert_decimals( sanitize_text_field( $g_result->score )) : '';

            $um_score = UM()->options()->get( 'g_reCAPTCHA_score' );
            $trace[] = ( isset( $um_score )) ? $this->convert_decimals( $um_score ) : '';

            $trace[] = ( isset( $form_data['g_recaptcha_score'] )) ? $this->convert_decimals( $form_data['g_recaptcha_score'] ) : '';
            $trace[] = ( isset( $score )) ? $this->convert_decimals( $score ) : '';

            $trace[] = ( isset( $g_result->success ) && $g_result->success == 1 ) ? 'yes' : 'no';
            $trace[] = ( isset( $g_result->action )) ? sanitize_text_field( $g_result->action ) : '';

            if ( isset( $args['form_id'] ) && $args['form_id'] == 'um_password_id' ) {

                $trace[] = 'password_reset';
                $trace[] = '';
                $trace[] = ( isset( $args['mode'] )) ? $args['mode'] : '';

            } else {

                $trace[] = ( isset( $_REQUEST['_wp_http_referer'] )) ? sanitize_text_field( str_replace( get_site_url(), '...', str_replace( array( '%3A', '%2F' ), array( ':', '/' ), $_REQUEST['_wp_http_referer'] ))) : basename( $_SERVER['PHP_SELF'] );
                $trace[] = ( isset( $form_data['form_id'] )) ? sanitize_text_field( $form_data['form_id'] ) : '';
                $trace[] = ( isset( $form_data['mode'] )) ? $form_data['mode'] : '';
            }

            $remote_ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );

            if ( UM()->options()->get( 'g_recaptcha_score_log_host' ) == 1 ) {

                $remote_host = array_map( 'sanitize_text_field', explode( '.', gethostbyaddr( $remote_ip )));

                if ( is_array( $remote_host ) && count( $remote_host ) > 1 ) {

                    $tld = array_pop( $remote_host );

                    if ( ! is_numeric( $tld )) {
                        $domain  = array_pop( $remote_host );
                        $trace[] = $domain . '.' . $tld;

                    } else {
                        $trace[] = $remote_host . '.' . $tld;
                    }

                } else {
                    $trace[] = ( UM()->options()->get( 'g_recaptcha_score_log_ip' ) == 1 ) ? $remote_ip : '';
                }

            } else {
                $trace[] = '';
            }

            $trace[] = ( UM()->options()->get( 'g_recaptcha_score_log_ip' ) == 1 ) ? $remote_ip : '';

            $geo_plugin = class_exists( 'CF_Geoplugin' );
            $trace[] = ( $geo_plugin ) ? do_shortcode( '[cfgeo return="country"]' ) : '';
            $trace[] = ( $geo_plugin ) ? do_shortcode( '[cfgeo return="browser"]' ) . do_shortcode( '[cfgeo return="browser_version"]' ) : '';
            $trace[] = ( $geo_plugin ) ? do_shortcode( '[cfgeo return="platform"]' ) : '';

            $username = '';
            if ( UM()->options()->get( 'g_recaptcha_score_log_username' ) == 1 ) {

                if ( isset( $args['form_id'] )) {

                    if ( $args['form_id'] == 'um_password_id' && isset( $args['username_b'] )) {
                        $username = sanitize_text_field( $args['username_b'] );

                    } else {

                        if ( isset( $args['username'] )) {
                            $username = sanitize_text_field( $args['username'] );
                        }

                        if ( isset( $args['user_login'] )) {
                            $username = sanitize_text_field( $args['user_login'] );
                        }
                    }

                } else {

                    if ( isset( $_REQUEST['log'] )) {
                        $username = sanitize_text_field( $_REQUEST['log'] );
                    }
                }
            }

            $trace[] = $username;

            if ( empty( $_POST['g-recaptcha-response'] ) ) {
                $trace[] = 'Please confirm you are not a robot.';
            }

            if ( isset( $g_result->score ) && $g_result->score < (float) $score ) {
                $trace[] = 'It is very likely a bot.';
            }

            if ( isset( $g_result->{'error-codes'} ) && ! $g_result->success ) {

                $error_codes = UM()->ReCAPTCHA()->error_codes_list();
                foreach ( $g_result->{'error-codes'} as $key => $error_code ) {

                    $error = array_key_exists( $error_code, $error_codes ) ? $error_codes[ $error_code ] : sprintf( __( 'Undefined error. Key: %s', 'um-recaptcha' ), $error_code );
                    $trace[] = strip_tags( $error );
                }
            }

            $this->write_to_log_file( $trace );
        }

        return $score;
    }

    public function convert_decimals( $number ) {

        if ( empty( $number )) {
            $number = '';

        } else {

            if ( UM()->options()->get( 'g_recaptcha_score_log_decimal' ) == 1 ) {

                $number = str_replace( '.', ',', $number );
            }
        }

        return $number;
    }

    public function write_to_log_file( $trace ) {

        $delimiter = sanitize_text_field( UM()->options()->get( 'g_recaptcha_score_log_delimiter' ));

        switch( $delimiter ) {
                                case 'semicolon':   $delimiter = ';'; break;
                                case 'tab':         $delimiter = chr(9); break;
                                default:            $delimiter = chr(9);
                            }

        $path = WP_CONTENT_DIR . $this->log_file_path;
        $file = $path . $this->log_file_name;

        if ( ! file_exists( $file )) {

            wp_mkdir_p( dirname( $file ) );

            file_put_contents( $file, implode( $delimiter, array_map( 'esc_attr', $this->csv_file_hdrs )) . "\r\n" );
        }

        if ( ! file_exists( $path . '.htaccess' )) {

            $htaccess = array(  '<Files "' . $this->log_file_name . '">',
                                'Order Allow,Deny',
                                'Deny from all',
                                '</Files>'
                            );

            file_put_contents( $path . '.htaccess', implode( "\r\n", $htaccess ) . "\r\n" );
        }

        file_put_contents( $file, implode( $delimiter, array_map( 'esc_attr', $trace )) . "\r\n", FILE_APPEND );
    }

    public function add_settings_score_log( $settings ) {

        if ( class_exists( 'UM_ReCAPTCHA' ) && version_compare( UM_RECAPTCHA_VERSION, '2.3.8' ) != -1 ) {

            if ( isset( $settings['extensions']['sections']['recaptcha'] )) {

                $prefix = '&nbsp; * &nbsp;';

                $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_header',
                            'type'           => 'header',
                            'label'          => esc_html__( 'reCAPTCHA Score Log CSV file settings', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_version', '=', 'v3' ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log',
                            'type'           => 'checkbox',
                            'label'          => $prefix . esc_html__( 'Enable/Disable', 'um-recaptcha' ),
                            'checkbox_label' => esc_html__( 'Tick to enable reCAPTCHA score logging to a CSV file.', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_version', '=', 'v3' ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_host',
                            'type'           => 'checkbox',
                            'label'          => $prefix . esc_html__( 'User host', 'um-recaptcha' ),
                            'checkbox_label' => esc_html__( 'Tick for including User host domain name.', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_score_log', '=', 1 ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_ip',
                            'type'           => 'checkbox',
                            'label'          => $prefix . esc_html__( 'User IP address', 'um-recaptcha' ),
                            'checkbox_label' => esc_html__( 'Tick for including User IP address.', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_score_log', '=', 1 ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_username',
                            'type'           => 'checkbox',
                            'label'          => $prefix . esc_html__( 'Username', 'um-recaptcha' ),
                            'checkbox_label' => esc_html__( 'Tick for including username.', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_score_log', '=', 1 ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_decimal',
                            'type'           => 'checkbox',
                            'label'          => $prefix . esc_html__( 'Decimal numbers with comma', 'um-recaptcha' ),
                            'checkbox_label' => esc_html__( 'Tick for converting dot to comma in decimal numbers if required for the spreadsheet calculations.', 'um-recaptcha' ),
                            'conditional'    => array( 'g_recaptcha_score_log', '=', 1 ),
                        );

                    $settings['extensions']['sections']['recaptcha']['fields'][] = array(

                            'id'             => 'g_recaptcha_score_log_delimiter',
                            'type'           => 'select',
                            'label'          => $prefix . esc_html__( 'CSV file delimiter', 'um-recaptcha' ),
                            'description'    => esc_html__( 'Select the field delimiter to be used. Default is Tab.', 'um-recaptcha' ),
                            'options'        => array( 'tab' => 'Tab', 'semicolon' => 'Semicolon' ),
                            'size'           => 'small',
                            'conditional'    => array( 'g_recaptcha_score_log', '=', 1 ),
                        );
            }
        }

        return $settings;
    }


}

new UM_reCAPTCHA_Score_Log();

