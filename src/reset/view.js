import { store, withSyncEvent } from '@wordpress/interactivity';

// Register the store
const { state } = store( 'query-filter', {
	actions: {
		// `event.preventDefault()` requires synchronous event access.
		preventNavigation: withSyncEvent( ( event ) => {
			event.preventDefault();
		} ),
		*navigateReset( e ) {
			// Set search value to empty string
			state.searchValue = '';

			// Get the current URL and remove all query parameters
			const currentURL = new URL( window.location.href );
			currentURL.search = '';

			// Navigate to the updated URL
			const { actions } = yield import(
				'@wordpress/interactivity-router'
			);
			yield actions.navigate( currentURL.toString() );
		},
	},
} );
