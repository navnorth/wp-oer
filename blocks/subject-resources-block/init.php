<?php
/** Subject Resources block version 2.0 **/
function wp_oer_register_resources_block(){
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/my-oer-block" block first.'
		);
	}

	// Main Script for the Block
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'wp-oer-resources-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'wp-oer-resources-block', OER_SLUG );

	// Backend styles
	$editor_css = 'build/index.css';
	wp_register_style(
		'wp-oer-resources-block-editor-style',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	// Frontend styles
	$style_css = 'build/style-index.css';
	wp_register_style(
		'wp-oer-resources-block-frontend-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'wp-oer-plugin/wp-oer-resources-block', array(
		'editor_script' => 'wp-oer-resources-block-editor',
		'editor_style'  => 'wp-oer-resources-block-editor-style',
		'style'         => 'wp-oer-resources-block-frontend-style',
		'render_callback' => 'wp_oer_display_subject_resources'
	) );
}
add_action( 'init', 'wp_oer_register_resources_block' );

function register_api_routes(){
	register_rest_route(
		'oer/v2',
		'subjects',
		array(  'methods'=>'GET',
			'callback'=>'wp_oer_get_subject_areas',
			'permission_callback' => function(){
				return current_user_can('edit_posts');
			}
		)
	);
}
add_action( 'rest_api_init' , 'register_api_routes' );

function wp_oer_display_subject_resources( $attributes ){
	if (!empty($attributes))
		extract($attributes);

	$sort_display = "Date";
	switch($sort){
		case "modified":
			$sort_display = 'Date Updated';
			break;
		case "date":
			$sort_display = 'Date Added';
			break;
		case "title":
			$sort_display = 'Title a-z';
			break;
	}

	$heading = '<div class="oer-snglrsrchdng">';
	$heading .= '	Browse '.$displayCount.' resources';
	$heading .= '	<div class="sort-box">';
	$heading .= '		<span class="sortoption">'.$sort_display.'</span>';
	$heading .= '		<span class="sort-resources" title="Sort resources" tabindex="0" role="button"><i class="fa fa-sort" aria-hidden="true"></i></span>';
	$heading .= '		<div class="sort-options">';
	$heading .= '			<ul class="sortList">';
	$heading .= '				<li value="modified" '.($sort=='modified'?'class="selected"':'').'>Date Updated</li>';
	$heading .= '				<li value="date" '.($sort=='date'?'class="selected"':'').'>Date Added</li>';
	$heading .= '				<li value="title" '.($sort=='title'?'class="selected"':'').'>Title a-z</li>';
	$heading .= '			</ul>';
	$heading .= '		</div>';
	$heading .= '		<div class="components-base-control sort-selectbox">';
	$heading .= '			<div class="components-base-control__field">';
	$heading .= '				<select id="inspector-select-control-1" class="components-select-control__input">';
	$heading .= '					<option value="modified" '.selected($sort,'modified',false).'>Date Updated</option>';
	$heading .= '					<option value="date" '.selected($sort,'date',false).'>Date Added</option>';
	$heading .= '					<option value="title" '.selected($sort,'title',false).'>Title a-z</option>';
	$heading .= '				</select>';
	$heading .= '			</div>';
	$heading .= '		</div>';
	$heading .= '	</div>';
	$heading .= '</div>';

	$html = '<div class="oer-subject-resources-list">';
	$html .= $heading;

	if (is_array($selectedSubjectResources)){
		foreach ($selectedSubjectResources as $subject){
			$html .= '<div class="post oer-snglrsrc">';
			$html .= '	<a href="'.$subject['link'].'" class="oer-resource-link">';
			$html .= '		<div class="oer-snglimglft">';
			$html .= '			<img src="'.$subject['fimg_url'].'">';
			$html .= '		</div>';
			$html .= '	</a>';
			$html .= '	<div class="oer-snglttldscrght">';
			$html .= '		<div class="ttl">';
			$html .= '			<a href="'.$subject['link'].'">'.$subject['title']['rendered'].'</a>';
			$html .= '		</div>';
			$html .= '		<div class="post-meta">';
			$html .= '			<span class="post-meta-box post-meta-grades">';
			$html .= '				<strong>Grades: </strong>'.$subject['oer_grade'];
			$html .= '			</span>';
			$html .= '			<span class="post-meta-box post-meta-domain">';
			$html .= '				<strong>Domain: </strong><a href="'.$subject['oer_resourceurl'].'">'.$subject['domain'].'</a>';
			$html .= '			</span>';
			$html .= '		</div>';
			$html .= '		<div class="desc">';
			$html .= '			<div>';
			$html .=  $subject['content']['rendered'];
			$html .= '			</div>';
			$html .= '		</div>';
			$html .= '		<div class="tagcloud">';
			if (is_array($subject['subject_details'])){
				foreach($subject['subject_details'] as $subj){
					$html .= '			<span><a href="'.$subj['link'].'">'.$subj['name'].'</a></span>';
				}
			}
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		}
	}
	$html .= '</div>';

	return $html;
}

function wp_oer_get_subject_areas() {
	$subjects_areas = array();

	// Get Top Level Subject Areas
	$subject_args = array(
		'taxonomy' => 'resource-subject-area',
		'hide_empty' => false,
		'parent' => 0,
		'number' => 0
	);

	$subject_query = new WP_Term_Query($subject_args);

	if (!empty($subject_query->terms)){
		$index = 0;
		foreach($subject_query->terms as $subject) {
			$subject_areas[$index]['term_id'] = $subject->term_id;
			$subject_areas[$index]['name'] = $subject->name;
			$subject_areas[$index]['type'] = 'parent';
			$subject_areas[$index]['parent'] = $subject->parent;
			$subject_areas[$index]['slug'] = $subject->slug;
			$subject_areas[$index]['count'] = $subject->count;
			$index++;

			// Get Child Subject Areas
			$child_subject_args = array(
				'taxonomy' => 'resource-subject-area',
				'parent' => $subject->term_id,
				'hide_empty' => false,
				'number' => 0
			);

			$child_subject_query = new WP_Term_Query($child_subject_args);
			if (!empty($child_subject_query->terms)) {
				foreach($child_subject_query->terms as $childSubject){
					$subject_areas[$index]['term_id'] = $childSubject->term_id;
					$subject_areas[$index]['name'] = $childSubject->name;
					$subject_areas[$index]['type'] = 'child';
					$subject_areas[$index]['parent'] = $childSubject->parent;
					$subject_areas[$index]['slug'] = $childSubject->slug;
					$subject_areas[$index]['count'] = $childSubject->count;
					$index++;
				}
			}
		}
	}
	return $subject_areas;
}
