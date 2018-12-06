const { registerBlockType } = window.wp.blocks;
const { __ } = window.wp.i18n;

registerBlockType( 'wp-oer-plugin/oer-resource-block', {
    title: __( 'OER Resource' ),
    category: 'embed',
    icon: 'dashicons-media-document',
    keywords: [
        __( 'OER' ),
        __( 'Resource' ),
        __( 'History' )
    ],
    attributes: {},
    edit: props => {},
    save: props => {}
} );