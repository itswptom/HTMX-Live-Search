// JS to build the Block on the backend and give you access to it within the Block Editor.
( function( blocks, element ) {
    var el = element.createElement;

    blocks.registerBlockType( 'tdr/htmx-live-search', {
        title: 'HTMX Live Search',
        icon: 'search',
        category: 'widgets',

        edit: function() {
            return el(
                'div',
                {},
                'HTMX Live Search Block Placeholder' // Placeholder content for the block editor. My expertise only stretches so far XD.
            );
        },

        save: function() {
            // Use dynamic rendering for the save function, so PHP handles the frontend.
            return null;
        },
	// Enqueue the required HTMX script
    	dependencies: ['htmx-script'],
    } );
} )( window.wp.blocks, window.wp.element );
