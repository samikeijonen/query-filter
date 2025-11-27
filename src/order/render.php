<?php
/**
 * Render the Order Filter block.
 *
 * @package query-filter
 */

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wp-block-query-filter',
	)
);

$order_options  = $attributes['orderOptions'] ?? array();
$default_option = $attributes['defaultOption'] ?? 'newest';
$label          = $attributes['label'] ?? '';
$show_label     = $attributes['showLabel'] ?? true;

// Generate a unique ID for this filter
$filter_id = 'query-order-filter-' . uniqid();

// Create parameter name based on query ID
if ( $block->context['query']['inherit'] ) {
	$param_name       = 'query-orderby';
	$order_param_name = 'query-order';
} else {
	$query_id         = $block->context['queryId'] ?? 0;
	$param_name       = sprintf( 'query-%d-orderby', $query_id );
	$order_param_name = sprintf( 'query-%d-order', $query_id );
}

// Check if there's an active orderby selection in URL
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_orderby = isset( $_GET[ $param_name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $param_name ] ) ) : '';
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_order = isset( $_GET[ $order_param_name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $order_param_name ] ) ) : '';

// If we have orderby/order in URL, find the matching option
$active_option = '';
if ( $current_orderby ) {
	foreach ( $order_options as $option ) {
		if ( $option['value']['orderby'] === $current_orderby ) {
			// If order is specified, it must also match
			if ( isset( $option['value']['order'] ) && $current_order ) {
				if ( $option['value']['order'] === $current_order ) {
					$active_option = sanitize_title( $option['label'] );
					break;
				}
			} elseif ( empty( $option['value']['order'] ) && empty( $current_order ) ) {
				$active_option = sanitize_title( $option['label'] );
				break;
			}
		}
	}
}

// If no active option was found from URL, use default
if ( empty( $active_option ) ) {
	$active_option = $default_option;
}

// Create data attribute for options to access in JavaScript
$options_json = wp_json_encode( $order_options );

?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	data-options="<?php echo esc_attr( $options_json ); ?>"
	data-wp-interactive="query-filter"
	data-wp-context='{"defaultOrder": "<?php echo esc_attr( $active_option ); ?>"}'>
	<label class="query-filter__label<?php echo $show_label ? '' : ' screen-reader-text'; ?>" for="<?php echo esc_attr( $filter_id ); ?>">
		<?php echo esc_html( $label ? $label : __( 'Order by', 'seravo' ) ); ?>
	</label>

	<select
		id="<?php echo esc_attr( $filter_id ); ?>"
		class="query-filter__select"
		name="<?php echo esc_attr( $param_name ); ?>"
		data-wp-on--change="actions.navigateOrder"
	>
		<?php foreach ( $order_options as $option ) : ?>
			<?php
			$value = $option['slug'];

			$opt_orderby = isset( $option['value']['orderby'] ) ? $option['value']['orderby'] : '';

			$opt_order = isset( $option['value']['order'] ) ? $option['value']['order'] : '';

			// Derive display label from attributes based on known slugs; fall back to option label.
			$display_label = $option['label'];
			switch ( $value ) {
				case 'newest':
					if ( ! empty( $attributes['labelNewest'] ) ) {
						$display_label = $attributes['labelNewest'];
					}
					break;
				case 'oldest':
					if ( ! empty( $attributes['labelOldest'] ) ) {
						$display_label = $attributes['labelOldest'];
					}
					break;
				case 'alphabetical-a-z':
					if ( ! empty( $attributes['labelTitleAsc'] ) ) {
						$display_label = $attributes['labelTitleAsc'];
					}
					break;
				case 'alphabetical-z-a':
					if ( ! empty( $attributes['labelTitleDesc'] ) ) {
						$display_label = $attributes['labelTitleDesc'];
					}
					break;
				case 'random':
					if ( ! empty( $attributes['labelRandom'] ) ) {
						$display_label = $attributes['labelRandom'];
					}
					break;
			}

			// Check if this option matches current URL parameters
			$is_selected = false;
			if ( $current_orderby === $opt_orderby ) {
				// If order is specified in option, it must match URL order (or both empty)
				if ( ! empty( $opt_order ) ) {
					$is_selected = ( $current_order === $opt_order );
				} else {
					$is_selected = empty( $current_order );
				}
			}
			// If no URL params, check if this is the default
			if ( empty( $current_orderby ) && $value === $active_option ) {
				$is_selected = true;
			}
			?>
			<option value="<?php echo esc_attr( $value ); ?>" data-orderby="<?php echo esc_attr( $opt_orderby ); ?>" data-order="<?php echo esc_attr( $opt_order ); ?>" <?php selected( $is_selected ); ?>><?php echo esc_html( $display_label ); ?></option>
		<?php endforeach; ?>
	</select>
</div>
