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
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO " . $wpdb->prefix. "core_standards values("", %s , %s)' , $title , $url ));
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
					$wpdb->get_results( $wpdb->prepare( 'INSERT INTO " . $wpdb->prefix. "standard_notation values("", %s, %s, %s, "", %s)' , $parent , $notation , $description , $url ));
				}
			}
			
		} catch(Exception $e) {
			// Log any error during import process
			debug_log($e->getMessage());
		}
		// Log Finished Import
		debug_log("OER Standards Importer: Finished Bulk Import of Standards");
		// Get Standard Notation
		echo "<b>Standards Are Successfully Imported.</b>";
	}
}
?>
<div id="col-container">
	<div id="col-standards-left">
		<div class="oer_imprtrwpr">
			<div class="oer_hdng">
			Standards Import
		    </div>
			<div class="oer_pargrph">
				Import requires an XML file in the format used by ASN for Common Core State Standards. You can download the CCSS in XML format here: <a href="http://asn.jesandco.org/resources/ASNJurisdiction/CCSS" target="_blank" >http://asn.jesandco.org/resources/ASNJurisdiction/CCSS</a>
			</div>
		    <form method="post" enctype="multipart/form-data">
			<div class="fields">
			    <input type="file" name="standards_import"/>
			    <input type="hidden" value="" name="standards_import" />
			    <input type="submit" name="" value="Import" class="button button-primary"/>
			</div>
		    </form>
		</div>
	</div>
	<div id="col-standards-right">
		<table class="wp-list-table wp-standards-list widefat fixed pages">
			<thead>
				<tr>
					<th>Id</th>
					<th>Standard Title</th>
					<th>Url</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$standards_notation = $wpdb->get_results("select * from " . $wpdb->prefix. "sub_standards");
				if (!empty($standards_notation)):
				foreach($standards_notation as $notation){
				?>
				<tr>
					<td><?php echo $notation->id; ?></td>
					<td><?php echo $notation->standard_title; ?></td>
					<td>
						<?php echo "<a href='".$notation->url."' target='_blank'>".$notation->url."</a>"; ?>
					</td>
				</tr>
				<?php } ?>
				<?php endif; ?>
				<input type="hidden" id="currntspan">
			</tbody>
		</table>
	</div>
</div>
