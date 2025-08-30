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

$label = $attributes['label'] ?? __( 'Reset filters', 'query-filter' );

// Generate a unique ID for this filter
$filter_id = 'query-reset-filter-' . uniqid();

// Create parameter name based on query ID
if ( $block->context['query']['inherit'] ) {
	$query_id = null;
} else {
	$query_id = $block->context['queryId'] ?? 0;
}

?>

<button
	<?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	data-wp-interactive="query-filter"
	data-wp-context='<?php echo wp_json_encode( array( 'queryId' => $query_id ) ); ?>'
	id="<?php echo esc_attr( $filter_id ); ?>"
	class="query-filter__button"
	data-wp-on--click="actions.navigateReset"
>
	<?php echo esc_html( $label ); ?>
</button>
