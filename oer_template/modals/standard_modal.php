<!-- Modal -->
<div class="modal fade" id="standardModal" tabindex="-1" role="dialog" aria-labelledby="standardModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="standardModalLabel">Add Standard</h4>
      </div>
      <div class="modal-body">
        <?php
        global $wpdb;
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results){
          echo "<ul>";
          foreach($results as $row){
            echo "<li>".$row['standard_name']."</li>";
          }
          echo "</ul>";
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>