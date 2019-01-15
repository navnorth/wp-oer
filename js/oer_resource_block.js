var __  = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var SelectControl = wp.components.SelectControl;
var Component = wp.element.Component;
var CheckboxControl = wp.components.CheckboxControl;
var TextControl = wp.components.TextControl;
var elem = wp.element.createElement;

class mySelectResource extends Component{
    
    static getInitialState ( selectedResource ) {
        return {
            posts: [],
            subjectAreas: [],
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
        
        this.onChangeAlignment = this.onChangeAlignment.bind(this);
        
        this.onChangeShowTitle = this.onChangeShowTitle.bind(this);
        
        this.onChangeShowDescription = this.onChangeShowDescription.bind(this);
        
        this.onChangeShowSubjects = this.onChangeShowSubjects.bind(this);
        
        this.onChangeShowGradeLevels = this.onChangeShowGradeLevels.bind(this);
        
        this.onChangeShowThumbnail = this.onChangeShowThumbnail.bind(this);
        
        this.onChangeWidth = this.onChangeWidth.bind(this);
        
        this.onChangeWithBorder = this.onChangeWithBorder.bind(this);
    }
    
    onChangeSelectResource( value ) {
        var subject;
        var subjects = [];
        
        const post = this.state.posts.find( ( item ) => { return item.id == parseInt( value ) } );
        const subs = post['resource-subject-area'];
                
        for (i=0;i<subs.length;i++) {
            subject = new wp.api.models.ResourceSubjectArea({ id: subs[i] }).fetch().then( ( subs ) => {
                subjects.push(subs);
            } );
        }
        
        this.setState( { selectedResource: parseInt(value), post } );
        
        this.props.setAttributes( {
            selectedResource: parseInt(value),
            title: post.title.rendered,
            content: post.content.rendered,
            link: post.link,
            subjectAreas: subjects,
            gradeLevels: post.oer_grade,
            featuredImage: post.fimg_url,
            resourceUrl: post.oer_resourceurl
        } );
    }
    
    onChangeAlignment( value ) {
        this.props.setAttributes({
            alignment: value
        });
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
    
    onChangeShowThumbnail ( checked ) {
        this.props.setAttributes( { showThumbnail: checked } );
    }
    
    onChangeWidth ( value ) {
        this.props.setAttributes( { blockWidth: value } );
    }
    
    onChangeWithBorder ( checked ) {
        this.props.setAttributes( { withBorder: checked } );
    }
    
    getOptions(){
        var resources = new wp.api.collections.Resource();
        
        return resources.fetch().then( ( posts ) => {
            if (posts && 0!==this.state.selectedResource) {
                const post = posts.find( (item) => { return item.id == this.state.selectedResource });
                this.setState( { post, posts } );
            } else {
                this.setState( { posts } );
            }
        });
    }
    
    render() {
        
        let options = [ { value:0, label: __('Select a resource') } ];
        let aOptions = [ { value: 'none', label:__('Select Alignment') }, { value:'left', label: __('Left') }, { value:'center', label: __('Center') }, { value:'right', label:__('Right') } ];
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
                { this.props.showDescription===true && (<p dangerouslySetInnerHTML={ { __html: this.state.post.content.rendered } }></p>)}
            </div>;
            this.props.className += ' has-post';
        } else {
            this.props.className += ' no-post';
        }
        
        return [
             !! this.props.isSelected && (
                <InspectorControls key='inspector'>
                    <SelectControl onChange={this.onChangeSelectResource} value={ this.props.attributes.selectedResource } label={ __('Resource:') } options={ options } />
                    <SelectControl onChange={this.onChangeAlignment} value={ this.props.attributes.alignment } label={ __('Alignment:') } options={ aOptions } />
                    <TextControl onChange={ this.onChangeWidth } value={ this.props.attributes.blockWidth } label={ __('Width in pixels(optional)') } />
                    <CheckboxControl
                        id="oerShowThumbnail"
                        label={__('Show Thumbnail') }
                        checked={ this.props.attributes.showThumbnail }
                        onChange={ this.onChangeShowThumbnail } />
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
                    <CheckboxControl
                        id="oerWithBorder"
                        label={__('Show Block with Border') }
                        checked={ this.props.attributes.withBorder }
                        onChange={ this.onChangeWithBorder } />
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
        },
        showThumbnail: {
            type: 'boolean'  
        },
        subjectAreas: {
            type: 'array'
        },
        gradeLevels: {
            type: 'string'
        },
        featuredImage: {
            type: 'string',
            selector: 'img'
        },
        resourceUrl: {
            type: 'string'
        },
        alignment: {
            type: 'string',
            default: 'none'
        },
        blockWidth: {
            type: 'integer'
        },
        withBorder: {
            type: 'boolean'
        }
    },
    edit: mySelectResource,
    save: function( props ) {
        var wImage = false;
        var imgClass = "col-md-12";
        var wSubjects = false;
        var aCenter = false;
        var aLign = "none";
        var width = "auto";
        var border = "none";
        if (props.attributes.alignment=="center") {
            aCenter = true;
        } else {
            aLign = props.attributes.alignment;
        }
        if (props.attributes.showThumbnail===true && props.attributes.featuredImage!==""){
            wImage = true;
            imgClass = "col-md-7";
        }
        if (props.attributes.showSubjectAreas===true && props.attributes.subjectAreas.length>0)
            wSubjects = true;
        if (props.attributes.blockWidth!=="") {
            width = props.attributes.blockWidth + "px";
        }
        if (props.attributes.withBorder===true) {
            border = "1px solid #cdcdcd";
        }
        const listItems = props.attributes.subjectAreas.map((d) => <li key={d.name}><span><a href={d.link}>{d.name}</a></span></li>);
    return (   
        <div className={ props.className } style={{ textAlign: aCenter==true ? 'center': 'auto' }}>
          <div className="post" style={{ float: aLign, textAlign:'left', width:width, overflow:'hidden', border: border }}>
            { props.attributes.showTitle===true && (<a href={ props.attributes.link }><h2 dangerouslySetInnerHTML={ { __html: props.attributes.title } }></h2></a>)}
            { (wImage) && (<div className="col-md-5"><a href={props.attributes.resourceUrl}><img src={props.attributes.featuredImage} /></a></div>)}
            <div className={imgClass}>
            { props.attributes.showDescription===true && (<div dangerouslySetInnerHTML={ { __html: props.attributes.content } }></div>)}
            </div>
            { wSubjects && (<div class="row resource-block-subjects oer-rsrcctgries tagcloud"><ul>{listItems}</ul></div>)}
            { props.attributes.showGradeLevels===true && (<div dangerouslySetInnerHTML={ { __html: '<strong>Grade Levels</strong> : ' + props.attributes.gradeLevels } }></div>)}
          </div>
        </div>
      );
    }
} );