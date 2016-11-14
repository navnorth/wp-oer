<?php
	
$time = time();
$date = date($time);

//Set Maximum Excution Time
ini_set('max_execution_time', 0);
set_time_limit(0);


//Standards Bulk Import
if(isset($_POST['standards_import']))
{
	// Log start of import process
	debug_log("OER Standards Importer: Start Bulk Import of Standards");
		
	global $wpdb;
	if( isset($_FILES['standards_import']) && $_FILES['standards_import']['size'] != 0 )
	{
		try {
			
		
			$filename = $_FILES['standards_import']['name']."-".$date;
	
			if ($_FILES["standards_import"]["error"] > 0)
			{
				echo "Error: " . $_FILES["standards_import"]["error"] . "<br>";
			}
			else
			{
				//Upload File
				"Upload: " . $_FILES["standards_import"]["name"] . "<br>";
				"Type: " . $_FILES["standards_import"]["type"] . "<br>";
				"Size: " . ($_FILES["standards_import"]["size"] / 1024) . " kB<br>";
				"stored in:" .move_uploaded_file($_FILES["standards_import"]["tmp_name"],OER_PATH."upload/".$filename) ;
			}
	
	
			$file = OER_PATH."upload/".$filename;
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
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "core_standards where standard_name = %s" , $title ));
				if(empty($results))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'core_standards values("", %s , %s)' , $title , $url ));
				}
			}
			// Get Core Standard
	
			// Get Sub Standard
			foreach($xml_arr as $key => $data)
			{
				$url = $key;
				$ischild = $data['ischild'];
				$title = $data['title'];
				$parent = '';
	
				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "core_standards where standard_url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = "core_standards-".$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "sub_standards where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'sub_standards-'.$rsltset_sec[0]->id;
					}
				}
	
				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "sub_standards where parent_id = %s && url = %s" , $parent , $url ));
				if(empty($res))
				{
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'sub_standards values("", %s, %s, %s)' , $parent , $title , $url ));
				}
			}
			// Get Sub Standard
	
			// Get Standard Notation
			foreach($standard_notation as $st_key => $st_data)
			{
				$url = $st_key;
				$ischild = $st_data['ischild'];
				$notation = $st_data['title'];
				$description = $st_data['description'];
				$parent = '';
	
				$rsltset = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "sub_standards where url=%s" , $ischild ));
				if(!empty($rsltset))
				{
					$parent = 'sub_standards-'.$rsltset[0]->id;
				}
				else
				{
					$rsltset_sec = $wpdb->get_results( $wpdb->prepare( "select id from " . $wpdb->prefix. "standard_notation where url=%s" , $ischild ));
					if(!empty($rsltset_sec))
					{
						$parent = 'standard_notation-'.$rsltset_sec[0]->id;
					}
				}
	
				$res = $wpdb->get_results( $wpdb->prepare( "SELECT id from " . $wpdb->prefix. "standard_notation where standard_notation = %s && parent_id = %s && url = %s" , $notation , $parent , $url ));
				if(empty($res))
				{
					//$description = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($description))
					$description = mysql_real_escape_string($description);
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix. 'standard_notation values("", %s, %s, %s, "", %s)' , $parent , $notation , $description , $url ));
				}
			}
			
		} catch(Exception $e) {
			// Log any error during import process
			debug_log($e->getMessage());
		}
		// Log Finished Import
		debug_log("OER Standards Importer: Finished Bulk Import of Standards");
		// Get Standard Notation
		?>
		<b><?php _e("Standards Are Successfully Imported.", OER_SLUG); ?></b>;
		<?php
	}
}
?>
<div id="col-container" class="oer_imprtrwpr">
	<form method="post" action="options.php">
		<fieldset>
			<legend><div class="oer_heading"><?php _e("Import Academic Standards", OER_SLUG); ?></div></legend>
			<div class="oer-import-row">
				<div class="row-left">
					<?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis nunc tempor, maximus nulla nec, consectetur dolor.", OER_SLUG); ?>
				</div>
				<div class="row-right alignRight">
					<a href="http://asn.jesandco.org/resources/ASNJurisdiction/CCSS" target="_blank"><?php _e("ASN Standards Info", OER_SLUG); ?></a>
				</div>
			</div>
			<div class="oer-import-row">
				<div class="row-left">
					<div class="fields">
						<?php settings_fields("oer_import_standards"); ?>
						<?php do_settings_sections("import_standards_section"); ?>
						<input type="hidden" value="" name="standards_import" />
					</div>
				</div>
				<div class="row-right">
					<div class="fields alignRight">
						<input type="submit" name="" value="<?php _e("Import", OER_SLUG); ?>" class="button button-primary"/>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
