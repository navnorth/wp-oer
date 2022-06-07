<!-- Modal -->
<?php global $post; ?>
<div class="modal fade" id="standardModal" tabindex="-1" role="dialog" aria-labelledby="standardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="standardModalLabel"><?php esc_html_e('Add Standard',OER_SLUG); ?></h5>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php esc_html_e('Close',OER_SLUG); ?></span></button>
      </div>
      <div id="standards-list" class="modal-body">
        <div class="search-bar">
          <input type="text" name="searchStandard" class="search-standard-text form-control">
          <button class="search_std_btn" data-postid="<?php echo esc_attr($post->ID); ?>"><span class='dashicons dashicons-search'></span></button>
        </div>
        <div id="oer_standards_list">
        <?php
        if (function_exists('was_selectable_admin_standards')){
          was_selectable_admin_standards($post->ID);
        }
        ?>
        </div>
        <div id="oer_search_results_list">
          <button class="search_close_btn" alt="Clear Search"><span class='dashicons dashicons-no'></span></button>
          <div class="search_results_list">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSaveStandards" class="btn btn-default btn-sm btn-primary" data-postid="<?php echo esc_attr($post->ID); ?>" data-dismiss="modal"><?php esc_html_e('Select',OER_SLUG); ?></button>
      </div>
    </div>
  </div>
</div>