const { __ } = wp.i18n;
const { registerBlockType, InspectorControls } = wp.blocks;
const { SelectControl } = wp.components;
const { Component } = wp.element;
var elem = wp.element.createElement;

class selectResource extends Component {
    
    static getInitialState ( selectedResource ) {
        return {
            posts: [],
            selectedPost: selectedResource,
            post: {}
        }
    }
    
    constructor() {
        super( ...arguments );
        
        this.state = this.constructor.getInitialState( this.props.attributes.selectedResource );
    }
    
    render() {
        
        let options = [ { value:0, label: __('Select a resource') } ];
        
        return [
            !! this.props.isSelected && ( <InspectorControls key='inspector'>
                <SelectControl 
                // Selected value.
                value={ this.props.attributes.selectedResource } 
                label={ __( 'Select a Resource' ) } 
                options={ options } />
              </InspectorControls>
            ), 
            'Load Resource Placeholder'
        ];
        
    }
}

registerBlockType( 'wp-oer-plugin/oer-resource-block', {
    title: __( 'OER Resource' ),
    category: 'widgets',
    icon: {
        foreground: '#121212',
        src: 'media-document'
    },
    keywords: [
        __( 'OER' ),
        __( 'Resource' ),
        __( 'History' )
    ],
    attributes: {
        selectedResource: {
            type: 'number',
            default: 0
        }
    },
    edit: selectResource,
    save: function( props ) {
        return elem( 'p', props.attributes.resource, 'Saved Embed Resource' );
    }
} );