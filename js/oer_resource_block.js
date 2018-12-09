const { __ } = wp.i18n;
const { registerBlockType, InspectorControls } = wp.blocks;
const { SelectControl } = wp.components;
const { Component } = wp.element;
var elem = wp.element.createElement;

class selectResource extends Component {
    
    static getInitialState ( selectedResource ) {
        return {
            posts: [],
            selectedResource: selectedResource,
            post: {}
        }
    }
    
    constructor() {
        super( ...arguments );
        
        this.state = this.constructor.getInitialState( this.props.attributes.selectedResource );
    }
    
    render() {
        
        let options = [ { value:0, label: __('Select a resource') } ];
        let output = __( 'Loading Resources' );
        
        if (this.state.posts.length > 0) {
            const loading = __( 'We have %d resources. Choose one.' );
            output = loading.replace( '%d', this.state.posts.length );
            this.state.posts.forEach((post) => {
                options.push({ value:post.id, label:post.title.rendered });
            });
        } else {
            output = __( 'No resource found. Please create some first.' );
        }
        
        return (
                <InspectorControls key='inspector'>
                    <SelectControl
                        label={__('Select a Resource')}
                    />
                </InspectorControls>
        )
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
        content: {
            type: 'array',
            source: 'children',
            selector: 'p'
        },
        title: {
            type: 'string',
            selector: 'h2'
        },
        link: {
            type: 'string',
            selector: 'a'
        },
        selectedResource: {
            type: 'number',
            default: 0
        }
    },
    edit: selectResource,
    save: function( props ) {
        return elem( 'p', props.attributes.content, 'Saved Embed Resource' );
    }
} );