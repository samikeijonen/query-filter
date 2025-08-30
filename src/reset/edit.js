import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { label } = attributes;

	return (
		<button { ...useBlockProps( { className: 'query-filter__button' } ) }>
			<RichText
				tagName="span"
				value={ label }
				onChange={ ( newLabel ) =>
					setAttributes( { label: newLabel } )
				}
				placeholder={ __( 'Reset filters', 'query-filter' ) }
			/>
		</button>
	);
}
