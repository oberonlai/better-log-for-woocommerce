<?php

namespace DWPLOG;

defined( 'ABSPATH' ) || exit;

class LOG {
	/**
	 * Instance
	 */
	private static $instance;


	/**
	 * Initialize class and add hooks
	 *
	 * @return void
	 */
	public static function init(): void {
		$class = self::get_instance();
		add_action( 'rest_api_init', array( $class, 'register_api_route' ) );
	}

	/**
	 * Returns the single instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_api_route( $request ) {
		register_rest_route(
			'dwp/v1',
			'/log',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_log' ),
				'permission_callback' => function () {
					return true;
				},
			)
		);
	}

	/**
	 * Generate log
	 *
	 * @return array
	 */
	public function get_log(): array {
		$logs   = array_reverse( \WC_Log_Handler_File::get_log_files() );
		$output = array();
		$files  = array( 'fatal-errors', 'woocommerce-notify' );
		foreach ( $logs as $key => $log ) {
			$pattern = '/\d{4}-\d{2}-\d{2}/';
			if ( preg_match( $pattern, $key, $matches ) ) {
				$date = $matches[0];
			}
			foreach ( $files as $file ) {
				if ( str_contains( $log, $file ) ) {
					$log_contents = file_get_contents( ABSPATH . '/wp-content/uploads/wc-logs/' . $log );

					$parts = preg_split( $pattern, $log_contents, - 1, PREG_SPLIT_DELIM_CAPTURE );
					$parts = array_filter( $parts );
					$parts = array_reverse( $parts );

					foreach ( $parts as $part ) {
						$local = $this->switch_localtime( $part );

						$output[ $file ][ $date ][ $local['local'] ] = $local['log'];
					}
				}
			}
		}

		return $output;
	}

	/**
	 * Switch to local time
	 *
	 * @param string $log Log text.
	 *
	 * @return array
	 */
	public function switch_localtime( string $log ): array {
		$timezone   = new \DateTimeZone( get_option( 'timezone_string' ) );
		$time_part  = substr( $log, 0, 15 );
		$local_time = '';

		if ( substr( $time_part, 0, 1 ) === 'T' ) {
			$date       = new \DateTime( $time_part, $timezone );
			$local_time = $date->setTimezone( $timezone )->format( 'H:i:s' );
		}

		return array(
			'local' => $local_time,
			'log'   => preg_replace( '/T\d{2}:\d{2}:\d{2}\+00:00/', '', $log ),
		);

	}
}

LOG::init();
