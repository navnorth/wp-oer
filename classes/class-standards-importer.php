<?php

class oer_standards_importer {
    private $_debug;
    private $_sample_dir =  OER_PATH . "/samples/";
    
    function __construct(){
        
    }
    
    
    function download_standard($url){
        global $_debug;

        $filename = basename($url);
        
	$ch = curl_init ($url);

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$raw=curl_exec($ch);
	curl_close ($ch);

        $path = $this->_sample_dir;
        
	if(!file_exists($path))
	{
		mkdir($path, 0777, true);
		debug_log("OER : create samples directory");
	}

	if(!file_exists($file = $path.$filename))
	{
		debug_log("OER : start downloading ". $url ." to local");

		$fp = fopen($file,'wb');
		fwrite($fp, $raw);
		fclose($fp);

		debug_log("OER : end of download");
	}
	return $file;
    }
    
    /** Import Standards **/
    function import_standard($file){
	global $wpdb;

	$time = time();
	$date = date($time);

	//Set Maximum Excution Time
	ini_set('max_execution_time', 0);
	set_time_limit(0);

	// Log start of import process
	debug_log("OER Standards Importer: Start Bulk Import of Standards");

	if( isset($file) )
	{
            try {

                    $filedetails = pathinfo($file);

                    $filename = $filedetails['filename']."-".$date;

                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace = FALSE;
                    $doc->load( $file );

                    $StandardDocuments = $doc->getElementsByTagName('StandardDocument');
                    
                    $xml_arr = array();
                    $m = 0;
                    foreach( $StandardDocuments as $StandardDocument)
                    {
                            $url = $StandardDocuments->item($m)->getAttribute('rdf:about');
                            $titles = $StandardDocuments->item($m)->getElementsByTagName('title');
                            $core_standard[$url]['title'] = $titles->item($m)->nodeValue;
                    }

                    $Statements = $doc->getElementsByTagName('Statement');
                    $i = 0;
                    foreach( $Statements as $Statement)
                    {
                            $statementNotations = $Statements->item($i)->getElementsByTagName('statementNotation');
                            if($statementNotations->length == 1)
                            {
                                    $url = $Statements->item($i)->getAttribute('rdf:about');
                                    $isChildOfs = $Statements->item($i)->getElementsByTagName('isChildOf');
                                    $descriptions = $Statements->item($i)->getElementsByTagName('description');
                                    for($j = 0; $j < sizeof($statementNotations); $j++)
                                    {
                                            $standard_notation[$url]['ischild'] = $isChildOfs->item($j)->getAttribute('rdf:resource');
                                            $standard_notation[$url]['title'] = $statementNotations->item($j)->nodeValue;
                                            $standard_notation[$url]['description'] = $descriptions->item($j)->nodeValue;
                                    }
                            }
                            else
                            {
                                    $descriptions = $Statements->item($i)->getElementsByTagName('description');
                                    $url = $Statements->item($i)->getAttribute('rdf:about');
                                    $isChildOfs = $Statements->item($i)->getElementsByTagName('isChildOf');
                                    $k = 0;
                                    foreach( $descriptions as $description)
                                    {
                                            $xml_arr[$url]['ischild'] = $isChildOfs->item($k)->getAttribute('rdf:resource');
                                            $xml_arr[$url]['title'] = $descriptions->item($k)->nodeValue;
                                            $k++;
                                    }
                            }
                            $i++;
                    }

                    // Get Core Standard
                    foreach($core_standard as $cskey => $csdata)
                    {
                            $url = $cskey;
                            $title = $csdata['title'];
                            $results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_core_standards where standard_name = %s" , $title ));
                            if(empty($results))
                            {
                                    $wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_core_standards values("", %s , %s)' , $title , $url ));
                            }
                    }
                    // Get Core Standard

                    // Get Sub Standard
                    foreach($xml_arr as $key => $data)
                    {
                            $url = esc_url_raw($key);
                            $ischild = $data['ischild'];
                            $title = sanitize_text_field($data['title']);
                            $parent = '';

                            $rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_core_standards where standard_url=%s" , $ischild ));
                            if(!empty($rsltset))
                            {
                                    $parent = "core_standards-".$rsltset[0]->id;
                            }
                            else
                            {
                                    $rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_sub_standards where url=%s" , $ischild ));
                                    if(!empty($rsltset_sec))
                                    {
                                            $parent = 'sub_standards-'.$rsltset_sec[0]->id;
                                    }
                            }

                            $res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s && url = %s" , $parent , $url ));
                            if(empty($res))
                            {
                                    $wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_sub_standards values("", %s, %s, %s)' , $parent , $title , $url ));
                            }
                    }
                    // Get Sub Standard

                    // Get Standard Notation
                    foreach($standard_notation as $st_key => $st_data)
                    {
                            $url = esc_url_raw($st_key);
                            $ischild = $st_data['ischild'];
                            $notation = sanitize_text_field($st_data['title']);
                            $description = sanitize_text_field($st_data['description']);
                            $parent = '';

                            $rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_sub_standards where url=%s" , $ischild ));
                            if(!empty($rsltset))
                            {
                                    $parent = 'sub_standards-'.$rsltset[0]->id;
                            }
                            else
                            {
                                    $rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "oer_standard_notation where url=%s" , $ischild ));
                                    if(!empty($rsltset_sec))
                                    {
                                            $parent = 'standard_notation-'.$rsltset_sec[0]->id;
                                    }
                            }

                            $res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "oer_standard_notation where standard_notation = %s && parent_id = %s && url = %s" , $notation , $parent , $url ));
                            if(empty($res))
                            {
                                    //$description = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($description))
                                    $description = esc_sql($description);
                                    $wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'oer_standard_notation values("", %s, %s, %s, "", %s)' , $parent , $notation , $description , $url ));
                            }
                    }

            } catch(Exception $e) {
                    $response = array(
                                      'message' => $e->getMessage(),
                                      'type' => 'error'
                                      );
                    // Log any error during import process
                    debug_log($e->getMessage());
                    return $response;
            }
            // Log Finished Import
            debug_log("OER Standards Importer: Finished Bulk Import of Standards");
            // Get Standard Notation
            $response = array(
                    'message' => 'successful',
                    'type' => 'success'
            );
            return $response;
	}
    }
}


?>