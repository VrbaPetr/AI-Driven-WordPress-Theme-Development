document.addEventListener( 'alpine:init', function () {
	'use strict';

	window.Alpine.data( 'portfolioFilter', function () {
		return {
			activeFilter: 'all',
			page: 1,
			hasMore: false,
			isLoading: false,

			init() {
				this.hasMore = this.$el.dataset.hasMore === 'true';
			},

			setFilter( slug ) {
				if ( this.activeFilter === slug ) {
					return;
				}

				this.activeFilter = slug;
				this.page         = 1;
				this.hasMore      = true;

				// Remove AJAX-appended cards so page count resets cleanly.
				this.$refs.grid.querySelectorAll( '[data-ajax-card]' ).forEach( function ( card ) {
					card.remove();
				} );
			},

			async loadMore() {
				if ( this.isLoading || ! this.hasMore ) {
					return;
				}

				const ajaxUrl = this.$el.dataset.ajaxUrl;
				const nonce   = this.$el.dataset.nonce;
				const perPage = this.$el.dataset.perPage;

				this.isLoading = true;
				this.page++;

				try {
					const formData = new FormData();
					formData.append( 'action',   'ai_driven_load_portfolio' );
					formData.append( 'nonce',    nonce );
					formData.append( 'paged',    this.page );
					formData.append( 'per_page', perPage );
					formData.append( 'category', this.activeFilter === 'all' ? '' : this.activeFilter );

					const response = await fetch( ajaxUrl, { method: 'POST', body: formData } );

					if ( ! response.ok ) {
						throw new Error( 'Request failed: ' + response.status );
					}

					const json = await response.json();

					if ( ! json.success ) {
						throw new Error( ( json.data && json.data.message ) || 'Request unsuccessful.' );
					}

					if ( json.data.html ) {
						const temp = document.createElement( 'ul' );
						temp.innerHTML = json.data.html;

						Array.from( temp.children ).forEach( ( card ) => {
							card.setAttribute( 'data-ajax-card', '1' );
							this.$refs.grid.appendChild( card );
						} );
					}

					this.hasMore = json.data.has_more;
				} catch ( err ) {
					this.page--;
					// eslint-disable-next-line no-console
					console.error( 'Portfolio load-more error:', err );
				} finally {
					this.isLoading = false;
				}
			},
		};
	} );
} );
