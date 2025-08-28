import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ToggleControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const { orderOptions, defaultOption, label, showLabel, labelNewest, labelOldest, labelTitleAsc, labelTitleDesc, labelRandom } = attributes;

	// Helper to resolve display label from slug
	const getOptionLabel = (slug, fallback) => {
	switch (slug) {
		case 'newest':
			return labelNewest || fallback;
		case 'oldest':
			return labelOldest || fallback;
		case 'alphabetical-a-z':
			return labelTitleAsc || fallback;
		case 'alphabetical-z-a':
			return labelTitleDesc || fallback;
		case 'random':
			return labelRandom || fallback;
		default:
			return fallback;
	}
	};

	// Create options for default selection
	const orderOptionChoices = orderOptions.map((option) => {
		return {
			label: option.label,
			value: option.slug,
		};
	});

	return (
		<div {...useBlockProps( { className: 'wp-block-query-filter' } )}>
			<InspectorControls>
				<PanelBody title={__('Order Settings', 'query-filter')}>
					<SelectControl
						label={__('Default Order', 'query-filter')}
						value={defaultOption}
						options={orderOptionChoices}
						onChange={(value) => setAttributes({ defaultOption: value })}
					/>

					<TextControl
						label={__('Label', 'query-filter')}
						value={label}
						onChange={(value) => setAttributes({ label: value })}
						placeholder={__('Order by', 'query-filter')}
					/>

					<ToggleControl
						label={__('Show Label', 'query-filter')}
						checked={showLabel}
						onChange={(value) => setAttributes({ showLabel: value })}
					/>

					<TextControl
						label={__('Option Label Newest', 'query-filter')}
						value={labelNewest}
						onChange={(value) => setAttributes({ labelNewest: value })}
					/>

					<TextControl
						label={__('Option Label Oldest', 'query-filter')}
						value={labelOldest}
						onChange={(value) => setAttributes({ labelOldest: value })}
					/>

					<TextControl
						label={__('Option Label Alphabetical A-Z', 'query-filter')}
						value={labelTitleAsc}
						onChange={(value) => setAttributes({ labelTitleAsc: value })}
					/>

					<TextControl
						label={__('Label (Alphabetical Z-A)', 'query-filter')}
						value={labelTitleDesc}
						onChange={(value) => setAttributes({ labelTitleDesc: value })}
					/>

					<TextControl
						label={__('Option Label Random', 'query-filter')}
						value={labelRandom}
						onChange={(value) => setAttributes({ labelRandom: value })}
					/>


				</PanelBody>
			</InspectorControls>

			<>
				{showLabel && (
					<label className="wp-block-query-filter-order__label wp-block-query-filter__label">
						{label || __('Order by', 'query-filter')}
					</label>
				)}
				<select>
					{orderOptions.map((option, index) => {
						const optionValue = option.slug;
						const displayLabel = getOptionLabel(option.slug, option.label);
						return (
							<option key={index} value={optionValue} selected={optionValue === defaultOption}>
								{displayLabel}
							</option>
						);
					})}
				</select>
			</>
		</div>
	);
}
