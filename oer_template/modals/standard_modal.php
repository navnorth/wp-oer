<!-- Modal -->
<div class="modal fade" id="standardModal" tabindex="-1" role="dialog" aria-labelledby="standardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="standardModalLabel">Add Standard</h4>
      </div>
      <div id="standards-list" class="modal-body">
        <div class="search-bar">
          <input type="text" name="searchStandard" class="search-standard-text form-control">
          <button class="search_std_btn"><span class='dashicons dashicons-search'></span></button>
        </div>
        <?php
        if (function_exists('was_selectable_admin_standards')){
          global $post;
          was_selectable_admin_standards($post->ID);
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSaveStandards" class="btn btn-default btn-sm" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>