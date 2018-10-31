<?php


if ( ! function_exists( 'ywca_admin_menu' ) ) {
	/**
	 * Create new admin page and include required scripts and styles
	 */
	function ywca_admin_menu() {
		$page_hook_suffix = add_submenu_page( 'yith_plugin_panel', esc_html__( 'ACF Compare', 'surveys-plugin' ), esc_html__( 'ACF Compare', 'surveys-plugin' ), 'manage_options', 'ywca', 'ywca_admin_page' ); 
		add_action( 'admin_print_scripts-' . $page_hook_suffix, 'ywca_enqueue_admin_scripts');
	}
}
add_action( 'admin_menu', 'ywca_admin_menu' );



if ( ! function_exists( 'ywca_plugin_settings' ) ) {
	/**
	 * Register new setting
	 */
	function ywca_plugin_settings() {
		register_setting( 'ywca_settings', 'ywca_settings' );
	}
}
add_action( 'admin_init', 'ywca_plugin_settings' );



if ( ! function_exists( 'ywca_enqueue_admin_scripts' ) ) {
	/**
	 * Register scripts and styles
	 */
	function ywca_enqueue_admin_scripts() {
		wp_enqueue_style( 'ywca-admin-style',   YWCA_DIR_URL . '/assets/css/styles.css', YWCA_VERSION );
		wp_enqueue_script( 'ywca-admin-script', YWCA_DIR_URL . '/assets/js/scripts.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), YWCA_VERSION, true );
	}
}



if ( ! function_exists( 'ywca_admin_page' ) ) {
	/**
	 * Admin page options form
	 */
	function ywca_admin_page() {
		$ywca_settings = get_option( 'ywca_settings', array() );
		$groups = acf_get_field_groups( array( 'post_type' => 'product' ) );

		$en_fields  = array();
		$dis_fields = array();

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				$fields = ywca_filter_fields( acf_get_fields( $group['key'] ) );
				if ( ! empty( $fields ) ) {
					foreach ( $fields as $key => $field ) {
						if ( in_array( $field['key'], $ywca_settings ) ) {
							$en_fields[ array_search( $field['key'], $ywca_settings ) ] = $field;
						} else {
							$dis_fields[] = $field;
						}
					}
				}
			}
		}
		ksort( $en_fields );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Choose ACF fields', 'ywca-plugin' ); ?></h2>
			<form method="post" action="options.php" novalidate="novalidate" class="ywca-column-1">

				<?php settings_fields( 'ywca_settings' ); ?>
				<?php do_settings_sections( 'ywca_settings' ); ?>

				<div class="ywca-field-sorter">
					
					<div class="ywca-modules">
						<h3><?php esc_html_e( 'Enabled', 'ywca-plugin' ); ?></h3>
						<ul class="ywca-enabled">
							<?php if ( ! empty( $en_fields ) ): ?>
								<?php foreach ( $en_fields as $en_field ) { ?>
									<li>
										<input type="hidden" name="ywca_settings[]" value="<?php echo esc_attr( $en_field['key'] ); ?>" />
										<label><?php echo esc_html( $en_field['label'] ); ?></label>
									</li>
								<?php } ?>
							<?php endif ?>
						</ul>
					</div>

					<div class="ywca-modules">
						<h3><?php esc_html_e( 'Disabled', 'ywca-plugin' ); ?></h3>
						<ul class="ywca-disabled">
							<?php if ( ! empty( $dis_fields ) ): ?>
								<?php foreach ( $dis_fields as $dis_field ) { ?>
									<li>
										<input type="hidden" name="disabled[]" value="<?php echo esc_attr( $dis_field['key'] ); ?>" />
										<label><?php echo esc_html( $dis_field['label'] ); ?></label>
									</li>
								<?php } ?>
							<?php endif ?>						
						</ul>
					</div>

					<?php submit_button(); ?>
				</div>
			</form>
			<div class="ywca-column-2">
				<h2><?php esc_html_e( 'Need help with customization?', 'ywca-plugin' ); ?></h2>
				<ul class="ywca-services">
					<li><?php esc_html_e( 'themes customizations;', 'ywca-plugin' ); ?></li>
					<li><?php esc_html_e( 'plugins customizations;', 'ywca-plugin' ); ?></li>
					<li><?php esc_html_e( 'custom development.', 'ywca-plugin' ); ?></li>
				</ul>
				<h3><?php esc_html_e( 'Contacts:', 'ywca-plugin' ); ?></h3>
				<ul>
					<li>
						<i aria-hidden="true" class="dashicons dashicons-external"></i>
						<a href="<?php echo esc_url('htts://thewpdev.org/'); ?>"><?php esc_html_e( 'My portfolio', 'ywca-plugin' ); ?></a>
					</li>
					<li>
						<i aria-hidden="true" class="dashicons dashicons-external"></i>
						<a href="<?php echo esc_url('https://upwork.com/fl/olezhyk5/'); ?>"><?php esc_html_e( 'UpWork profile', 'ywca-plugin' ); ?></a>
					</li>
					<li>
						<i aria-hidden="true" class="dashicons dashicons-external"></i>
						<a href="mailto:olezhyk5@gmail.com"><?php esc_html_e( 'Email', 'ywca-plugin' ); ?></a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'ywca_filter_fields' ) ) {
	/**
	 * Filter fields, allow only several types
	 *
	 * @param array $fields 
	 * @return array
	 */
	function ywca_filter_fields( $fields ) {
		$supported_fields = array(
			'button_group',
			'checkbox',
			'date_picker',
			'date_time_picker',
			'email',
			'link',
			'number',
			'page_link',
			'radio',
			'range',
			'select',
			'taxonomy',
			'text',
			'textarea',
			'time_picker',
			'url',
			'wysiwyg',
		);

		$fields_list = array();
		foreach ( $fields as $field ) {
			if ( in_array( $field['type'], $supported_fields ) ) {
				$fields_list[] = $field;
			}
		}
		return $fields_list;
	}
}



if ( ! function_exists( 'ywca_yith_woocompare_filter_table_fields' ) ) {
	/**
	 * Register new column(title) in the compare table
	 *
	 * @param array $fields 
	 * @param array $products 
	 * @return array
	 */
	function ywca_yith_woocompare_filter_table_fields( $fields, $products ) {
		$ywca_settings = get_option( 'ywca_settings', array() );
		if ( ! empty( $ywca_settings ) ) {
			foreach ( $ywca_settings as $field ) {
				$field_data = get_field_object( $field );
				$fields[ $field ] = $field_data['label'];
			}
		}

		return $fields;
	}
}
add_filter( 'yith_woocompare_filter_table_fields', 'ywca_yith_woocompare_filter_table_fields', 10, 2 );



if ( ! function_exists( 'ywca_fields_values' ) ) {
	/**
	 * Add actition to view new fields value
	 */
	function ywca_fields_values() {
		$ywca_settings = get_option( 'ywca_settings', array() );
		if ( ! empty( $ywca_settings ) ) {
			foreach( $ywca_settings as $key => $field ) {
				add_action( 'yith_woocompare_field_' . $field, function( $product ) use ( $field ) {
					$product->fields[ $field ] = ywca_get_field_value( $field, $product->get_id() );
				});
			}
		}
	}
}
add_action( 'plugins_loaded', 'ywca_fields_values' );



if ( ! function_exists( 'ywca_get_field_value' ) ) {
	/**
	 * Get proguct field value, format it and return
	 *
	 * @param string $field 
	 * @param int $post_id 
	 * @return string
	 */
	function ywca_get_field_value( $field, $post_id ) {
		$field_value = get_field( $field, $post_id ) ?: '-';
		$field_data  = get_field_object( $field );

		switch ( $field_data['type'] ) {
			case 'radio':
			case 'button':
				if ( is_array( $field_value ) ) {
					$value = $field_value['value'];
				} else {
					$value = $field_value;
				}
				break;
			case 'select':
				if ( ! $field_data['return_format'] ) {
					if ( is_array( $field_value ) ) {
						$value = $field_value['value'];
					} else {
						$value = $field_value;
					}
				} else {
					if ( ! is_array( $field_value ) ) {
						$value = $field_value;
					} else {
						$values = array();
						foreach ( $field_value as $f_value ) {
							if ( is_array( $f_value ) ) {
								$values[] = $f_value['value'];
							} else {
								$values[] = $f_value;
							}
						}
						$value = implode( ', ', $values );
					}
				}
				break;

			case 'checkbox':
				if ( is_array( $field_value ) ) {
					$values = array();
					foreach ( $field_value as $key => $f_value ) {
						if ( is_array( $f_value ) ) {
							$values[] = $f_value['value'];
						} else {
							$values[] = $f_value;
						}
					}
					$value = implode( ', ', $values );
				} else {
					$value = $field_value;
				}
				break;
				
			case 'date_picker':
			case 'date_time_picker':
			case 'number':
			case 'time_picker':
			case 'email':
			case 'text':
			case 'textarea':
			case 'range':
			case 'wysiwyg':
				$value = $field_value;
				break;
				
			case 'link':
				if ( $field_data['return_format'] == 'url' ) {
					$value = '<a href="' . esc_url( $field_value ) . '">' . $field_value . '</a>';
				} else {
					$target = ! empty( $field_value['target'] ) ? 'target="_blank"' : '';
					$value  = '<a href="' . esc_url( $field_value['url'] ) . '" ' . $target . '>' . $field_value['title'] . '</a>';
				}
				break;
				
			case 'page_link':
			case 'url':
				$value = '<a href="' . esc_url( $field_value ) . '">' . $field_value . '</a>';
				break;

			case 'taxonomy':
				$ids = array();
				$values = array();

				// Single value
				if ( $field_data['field_type'] == 'radio' || $field_data['field_type'] == 'select' ) {
					if ( is_object( $field_value ) ) {
						$ids[] = $field_value->term_id;
					} else {
						$ids[] = $field_value;
					}
				}

				// Multiple values
				if ( $field_data['field_type'] == 'checkbox' || $field_data['field_type'] == 'multi_select' ) {
					foreach ( $field_value as $f_value) {
						if ( is_object( $f_value ) ) {
							$ids[] = $f_value->term_id;
						} else {
							$ids[] = $f_value;
						}
					}
				}

				foreach ( $ids as $key => $id ) {
					$term = get_term( $id, $field_data['taxonomy'] );
					$values[] = $term->name;
				}

				$value = implode( ', ', $values );
				break;
			
			default:
				$value = $field_value;
				break;
		}

		return $value;
	}
}