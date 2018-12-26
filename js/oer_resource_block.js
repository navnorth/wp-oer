var __  = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var SelectControl = wp.components.SelectControl;
var Component = wp.element.Component;
var CheckboxControl = wp.components.CheckboxControl;
var elem = wp.element.createElement;

class mySelectResource extends Component{
    
    static getInitialState ( selectedResource ) {
        return {
            posts: [],
            selectedResource: selectedResource,
            post: {},
        }
    }
    
    constructor() {
        super( ...arguments );
        
        this.state = this.constructor.getInitialState( this.props.attributes.selectedResource );
        
        this.getOptions = this.getOptions.bind(this);
        
        this.getOptions();
        
        this.onChangeSelectResource = this.onChangeSelectResource.bind(this);
        
        this.onChangeShowTitle = this.onChangeShowTitle.bind(this);
        
        this.onChangeShowDescription = this.onChangeShowDescription.bind(this);
        
        this.onChangeShowSubjects = this.onChangeShowSubjects.bind(this);
        
        this.onChangeShowGradeLevels = this.onChangeShowGradeLevels.bind(this);
    }
    
    onChangeSelectResource( value ) {
        const post = this.state.posts.find( ( item ) => { return item.id == parseInt( value ) } );
        
        this.setState( { selectedResource: parseInt(value), post } );
        
        this.props.setAttributes( {
            selectedResource: parseInt(value),
            title: post.title.rendered,
            content: post.content.rendered,
            link: post.link,
        } );
    }
    
    onChangeShowTitle( checked ) {
        this.props.setAttributes( { showTitle: checked } );
    }
    
    onChangeShowDescription( checked ) {
        this.props.setAttributes( { showDescription: checked } );
    }
    
    onChangeShowSubjects( checked ) {
        this.props.setAttributes( { showSubjectAreas: checked } );
    }
    
    onChangeShowGradeLevels ( checked ) {
        this.props.setAttributes( { showGradeLevels: checked } );
    }
    
    getOptions(){
        var resources = new wp.api.collections.Resource();
        
        return resources.fetch().then( ( posts ) => {
            if (posts && 0!==this.state.selectedResource) {
                const post = posts.find( (item) => { return item.id == this.state.selectedResource });
                this.setState( { post, posts} );
            } else {
                this.setState( { posts } );
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
        
        if (this.state.post.hasOwnProperty('title')) {
            output = <div className="post">
                <h2 dangerouslySetInnerHTML={ { __html: this.state.post.title.rendered } }></h2>
                <p dangerouslySetInnerHTML={ { __html: this.state.post.content.rendered } }></p>
            </div>;
            this.props.className += ' has-post';
        } else {
            this.props.className += ' no-post';
        }
        
        return [
             !! this.props.isSelected && (
                <InspectorControls key='inspector'>
                    <SelectControl onChange={this.onChangeSelectResource} value={ this.props.attributes.selectedResource } label={ __('Resource:') } options={ options } />
                    <CheckboxControl
                        id="oerShowTitle"
                        label={__('Show Title') }
                        checked={ this.props.attributes.showTitle }
                        onChange={ this.onChangeShowTitle } />
                    <CheckboxControl
                        id="oerShowDesc"
                        label={__('Show Description') }
                        checked={ this.props.attributes.showDescription }
                        onChange={ this.onChangeShowDescription } />
                    <CheckboxControl
                        id="oerShowSubjects"
                        label={__('Show Subject Areas') }
                        checked={ this.props.attributes.showSubjectAreas }
                        onChange={ this.onChangeShowSubjects } />
                    <CheckboxControl
                        id="oerShowGradeLevels"
                        label={__('Show Grade Levels') }
                        checked={ this.props.attributes.showGradeLevels }
                        onChange={ this.onChangeShowGradeLevels } />
                </InspectorControls>
            ),
            <div className={this.props.className}>{output}</div>
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
            type: 'string',
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
        },
        showTitle: {
            type: 'boolean'
        },
        showDescription: {
            type: 'boolean'
        },
        showSubjectAreas: {
            type: 'boolean'
        },
        showGradeLevels: {
            type: 'boolean'
        }
    },
    edit: mySelectResource,
    save: function( props ) {
    return (
        <div className={ props.className }>
          <div className="post">
            <a href={ props.attributes.link }><h2 dangerouslySetInnerHTML={ { __html: props.attributes.title } }></h2></a>
            <p dangerouslySetInnerHTML={ { __html: props.attributes.content } }></p>
          </div>
        </div>
      );
    }
} );