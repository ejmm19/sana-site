<?php

namespace Kaizencoders\Update_Urls;

use Kaizencoders\Update_Urls\Option;

/**
 * Plugin_Name
 *
 * @package   Update_Urls
 * @author    KaizenCoders <hello@kaizencoders.com>
 * @link      https://kaizencoders.com
 */

/**
 * Helper Class
 */
class Helper {

    /**
     * Whether given user is an administrator.
     *
     * @param \WP_User $user The given user.
     *
     * @return bool
     */
    public static function is_user_admin( \WP_User $user = null ) {
        if ( is_null( $user ) ) {
            $user = wp_get_current_user();
        }

        if ( ! $user instanceof WP_User ) {
            _doing_it_wrong( __METHOD__, 'To check if the user is admin is required a WP_User object.', '1.0.0' );
        }

        return is_multisite() ? user_can( $user, 'manage_network' ) : user_can( $user, 'manage_options' );
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron, cli or frontend.
     *
     * @return bool
     * @since 1.2
     *
     */
    public function request( $type ) {
        switch ( $type ) {
            case 'admin_backend':
                return $this->is_admin_backend();
            case 'ajax':
                return $this->is_ajax();
            case 'installing_wp':
                return $this->is_installing_wp();
            case 'rest':
                return $this->is_rest();
            case 'cron':
                return $this->is_cron();
            case 'frontend':
                return $this->is_frontend();
            case 'cli':
                return $this->is_cli();
            default:
                _doing_it_wrong( __METHOD__, esc_html( sprintf( 'Unknown request type: %s', $type ) ), '1.0.0' );

                return false;
        }
    }

    /**
     * Is installing WP
     *
     * @return boolean
     */
    public function is_installing_wp() {
        return defined( 'WP_INSTALLING' );
    }

    /**
     * Is admin
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_admin_backend() {
        return is_user_logged_in() && is_admin();
    }

    /**
     * Is ajax
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_ajax() {
        return ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || defined( 'DOING_AJAX' );
    }

    /**
     * Is rest
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_rest() {
        return defined( 'REST_REQUEST' );
    }

    /**
     * Is cron
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_cron() {
        return ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) || defined( 'DOING_CRON' );
    }

    /**
     * Is frontend
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_frontend() {
        return ( ! $this->is_admin_backend() || ! $this->is_ajax() ) && ! $this->is_cron() && ! $this->is_rest();
    }

    /**
     * Is cli
     *
     * @return boolean
     * @since 1.2
     *
     */
    public function is_cli() {
        return defined( 'WP_CLI' ) && WP_CLI;
    }

    /**
     * Define constant
     *
     * @param $name
     * @param $value
     *
     * @since 1.2
     */
    public static function maybe_define_constant( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Get current date time
     *
     * @return false|string
     */
    public static function get_current_date_time() {
        return gmdate( 'Y-m-d H:i:s' );
    }


    /**
     * Get current date time
     *
     * @return false|string
     *
     */
    public static function get_current_gmt_timestamp() {
        return strtotime( gmdate( 'Y-m-d H:i:s' ) );
    }

    /**
     * Get current date
     *
     * @return false|string
     *
     */
    public static function get_current_date() {
        return gmdate( 'Y-m-d' );
    }

    /**
     * Format date time
     *
     * @param $date
     *
     * @return string
     *
     * @since 1.2
     */
    public static function format_date_time( $date ) {
        $convert_date_format = get_option( 'date_format' );
        $convert_time_format = get_option( 'time_format' );

        $local_timestamp = ( $date !== '0000-00-00 00:00:00' ) ? date_i18n( "$convert_date_format $convert_time_format", strtotime( get_date_from_gmt( $date ) ) ) : '<i class="dashicons dashicons-es dashicons-minus"></i>';

        return $local_timestamp;
    }

    /**
     * Clean String or array using sanitize_text_field
     *
     * @param $variable Data to sanitize
     *
     * @return array|string
     *
     * @since 1.2
     */
    public static function clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }

    /**
     * Get IP
     *
     * @return mixed|string|void
     *
     * @since 1.2
     */
    public static function get_ip() {

        // Get real visitor IP behind CloudFlare network
        if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else {
            $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';
        }

        return $ip;
    }

    /**
     * Get GMT Offset
     *
     * @param bool $in_seconds
     * @param null $timestamp
     *
     * @return float|int
     *
     * @since 1.2
     */
    public static function get_gmt_offset( $in_seconds = false, $timestamp = null ) {

        $offset = get_option( 'gmt_offset' );

        if ( $offset == '' ) {
            $tzstring = get_option( 'timezone_string' );
            $current  = date_default_timezone_get();
            date_default_timezone_set( $tzstring );
            $offset = date( 'Z' ) / 3600;
            date_default_timezone_set( $current );
        }

        // check if timestamp has DST
        if ( ! is_null( $timestamp ) ) {
            $l = localtime( $timestamp, true );
            if ( $l['tm_isdst'] ) {
                $offset ++;
            }
        }

        return $in_seconds ? $offset * 3600 : (int) $offset;
    }

    /**
     * Insert $new in $array after $key
     *
     * @param $array
     * @param $key
     * @param $new
     *
     * @return array
     *
     * @since 1.2
     */
    public static function array_insert_after( $array, $key, $new ) {
        $keys  = array_keys( $array );
        $index = array_search( $key, $keys );
        $pos   = false === $index ? count( $array ) : $index + 1;

        return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
    }

	/**
	 * Insert a value or key/value pair before a specific key in an array.  If key doesn't exist, value is prepended
	 * to the beginning of the array.
	 *
	 * @param array $array
	 * @param string $key
	 * @param array $new
	 *
	 * @return array
	 *
	 * @since 1.2
	 */
	public static function array_insert_before( array $array, $key, array $new ) {
		$keys = array_keys( $array );
		$pos  = (int) array_search( $key, $keys );

		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

    /**
     * Insert $new in $array after $key
     *
     * @param $array
     *
     * @return boolean
     *
     * @since 1.2
     */
    public static function is_forechable( $array = array() ) {

        if ( ! is_array( $array ) ) {
            return false;
        }

        if ( empty( $array ) ) {
            return false;
        }

        if ( count( $array ) <= 0 ) {
            return false;
        }

        return true;

    }

    /**
     * Get current db version
     *
     * @since 1.2
     */
    public static function get_db_version() {
        return Option::get( 'db_version', null );
    }

	/**
	 * Get data from array
	 *
	 * @param array $array
	 * @param string $var
	 * @param string $default
	 * @param bool $clean
	 *
	 * @return array|string
	 *
	 * @since 1.2
	 */
	public static function get_data( $array = array(), $var = '', $default = '', $clean = false ) {

		if ( empty( $array ) ) {
			return $default;
		}

		if ( ! empty( $var ) || ( 0 === $var ) ) {
			if ( strpos( $var, '|' ) > 0 ) {
				$vars = array_map('trim', explode( '|', $var ));
				foreach ( $vars as $var ) {
					if ( isset( $array[ $var ] ) ) {
						$array = $array[ $var ];
					} else {
						return $default;
					}
				}

				return wp_unslash( $array );
			} else {
				$value = isset( $array[ $var ] ) ? wp_unslash( $array[ $var ] ) : $default;
			}
		} else {
			$value = wp_unslash( $array );
		}

		if ( $clean ) {
			$value = self::clean( $value );
		}

		return $value;
	}


	/**
	 * Get POST | GET data from $_REQUEST
	 *
	 * @param string $var
	 * @param string $default
	 * @param bool $clean
	 *
	 * @return array|string
	 *
	 * @since 1.2
	 */
	public static function get_request_data( $var = '', $default = '', $clean = true ) {
		return self::get_data( $_REQUEST, $var, $default, $clean );
	}

	/**
	 * Get POST data from $_POST
	 *
	 * @param string $var
	 * @param string $default
	 * @param bool $clean
	 *
	 * @return array|string
	 *
	 * @since 1.2
	 */
	public static function get_post_data( $var = '', $default = '', $clean = true ) {
		return self::get_data( $_POST, $var, $default, $clean );
	}

	/**
	 * Get Current Screen Id
	 *
	 * @return string
	 *
	 * @since 1.2
	 */
	public static function get_current_screen_id() {

		$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		if ( ! $current_screen instanceof \WP_Screen ) {
			return '';
		}

		$current_screen = get_current_screen();

		return ( $current_screen ? $current_screen->id : '' );
	}

	/**
	 * Get all Plugin admin screens
	 *
	 * @return array|mixed|void
	 *
	 * @since 1.2
	 */
	public static function get_plugin_admin_screens() {

		$screens = array(
			'tools_page_update-urls'
		);

		return apply_filters( 'kc_uu_admin_screens', $screens );
	}

	/**
	 * Is es admin screen?
	 *
	 * @param string $screen_id Admin screen id
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public static function is_plugin_admin_screen( $screen_id = '' ) {

		$current_screen_id = self::get_current_screen_id();

		// Check for specific admin screen id if passed.
		if ( ! empty( $screen_id ) ) {
			if ( $current_screen_id === $screen_id ) {
				return true;
			} else {
				return false;
			}
		}

		$plugin_admin_screens = self::get_plugin_admin_screens();

		if ( in_array( $current_screen_id, $plugin_admin_screens ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Replace into serialised data.
	 *
	 * @param $from
	 * @param $to
	 * @param $data
	 * @param $serialised
	 *
	 * @return array|mixed|object|string|string[]
	 *
	 * @since 1.2
	 */
	public static function replace_into_serialized_data( $from = '', $to = '', $data = '', $serialised = false ) {
		try {
			if ( false !== is_serialized( $data ) ) {
				$un_serialized = maybe_unserialize( $data );
				$data         = self::replace_into_serialized_data( $from, $to, $un_serialized, true );
			} elseif ( is_array( $data ) ) {
				$_tmp = array();
				foreach ( $data as $key => $value ) {
					$_tmp[ $key ] = self::replace_into_serialized_data( $from, $to, $value, false );
				}
				$data = $_tmp;
				unset( $_tmp );
			} else {
				if ( is_string( $data ) ) {
					$data = str_replace( $from, $to, $data );
				}
			}
			if ( $serialised ) {
				return maybe_serialize( $data );
			}
		} catch ( Exception $error ) {

		}

		return $data;
	}

	/**
	 * Update URLs
	 *
	 * @param $options
	 * @param $oldurl
	 * @param $newurl
	 *
	 * @return array
	 *
	 * @since 1.2
	 */
	public static function update_urls( $options, $oldurl, $newurl ) {
		global $wpdb;

		$results = array();
		$queries = array(
			'content'     => array(
				"UPDATE $wpdb->posts SET post_content = replace(post_content, %s, %s)",
				__( 'Content Items (Posts, Pages, Custom Post Types, Revisions)', 'update-urls' )
			),
			'excerpts'    => array(
				"UPDATE $wpdb->posts SET post_excerpt = replace(post_excerpt, %s, %s)",
				__( 'Excerpts', 'update-urls' )
			),
			'attachments' => array(
				"UPDATE $wpdb->posts SET guid = replace(guid, %s, %s) WHERE post_type = 'attachment'",
				__( 'Attachments', 'update-urls' )
			),
			'links'       => array(
				"UPDATE $wpdb->links SET link_url = replace(link_url, %s, %s)",
				__( 'Links', 'update-urls' )
			),
			'custom'      => array(
				"UPDATE $wpdb->postmeta SET meta_value = replace(meta_value, %s, %s)",
				__( 'Custom Fields', 'update-urls' )
			),
			'guids'       => array(
				"UPDATE $wpdb->posts SET guid = replace(guid, %s, %s)",
				__( 'GUIDs', 'update-urls' )
			),
		);

		foreach ( $options as $option ) {
			if ( 'custom' === $option ) {
				$n         = 0;
				$row_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta" );
				$page_size = 10000;
				$pages     = ceil( $row_count / $page_size );

				for ( $page = 0; $page < $pages; $page ++ ) {
					$current_row = 0;
					$start       = $page * $page_size;
					$end         = $start + $page_size;
					$pmquery     = "SELECT * FROM $wpdb->postmeta WHERE meta_value <> ''";
					$items       = $wpdb->get_results( $pmquery );
					foreach ( $items as $item ) {
						$value = $item->meta_value;
						if ( trim( $value ) == '' ) {
							continue;
						}

						$edited = self::replace_into_serialized_data( $oldurl, $newurl, $value );

						if ( $edited != $value ) {
							$fix = $wpdb->query( "UPDATE $wpdb->postmeta SET meta_value = '" . $edited . "' WHERE meta_id = " . $item->meta_id );
							if ( $fix ) {
								$n ++;
							}
						}
					}
				}
				$results[ $option ] = array( $n, $queries[ $option ][1] );
			} else {
				$result             = $wpdb->query( $wpdb->prepare( $queries[ $option ][0], $oldurl, $newurl ) );
				$results[ $option ] = array( $result, $queries[ $option ][1] );
			}
		}

		return $results;
	}

}