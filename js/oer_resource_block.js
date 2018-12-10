var __  = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var SelectControl = wp.components.SelectControl;
var Component = wp.element.Component;
var elem = wp.element.createElement;

class mySelectResource extends Component{
    
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
        
        this.getOptions = this.getOptions.bind(this);
        
        this.getOptions();
    }
    
    getOptions(){
        return( new wp.api.collections.Posts() ).fetch({ data: { type: 'resource' } }).then( ( posts ) => {
            if (posts && 0!==this.state.selectedResource) {
                const post = posts.find( (item) => { return item.id == this.state.selectedResource });
                this.setState( {post, posts} );
            } else {
                this.setState( {posts} );
            }
        });
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
        
        return [
            !! this.props.isSelected && (
                <InspectorControls key='inspector'>
                    <SelectControl value={ this.props.attributes.selectedResource } label={ __('Select a Resource') } options={ options } />
                </InspectorControls>        
            ),
            output
        ]
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
    edit: mySelectResource,
    save: function( props ) {
        return elem( 'p', props.attributes.content, 'Saved Embed Resource' );
    }
} );