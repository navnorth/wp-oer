<!-- Modal -->
<?php global $post; ?>
<div class="modal fade" id="relatedResourcesModal" tabindex="-1" role="dialog" aria-labelledby="relatedResourcesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="standardModalLabel">Add Related Resource</h4>
      </div>
      <div id="standards-list" class="modal-body">
        <div class="search-bar">
          <input type="text" name="searchRelatedResources" class="search-related-resources-text form-control">
          <button class="search_std_btn" data-postid="<?php echo $post->ID; ?>"><span class='dashicons dashicons-search'></span></button>
        </div>
        <div id="oer_related_resources_list">
          <?php
          if (function_exists('get_resources_for_related')){
            $oer_related_resource = get_post_meta($post->ID, 'oer_related_resource', true);
            $_res_array = explode(',',$oer_related_resource);

            $_resources = get_resources_for_related();
            ?><ul><?php
            foreach($_resources as $_res){
              $_chk = ''; $_icon = ''; $_sel = '';
              if (in_array($_res->ID, $_res_array)):
                $_chk = 'checked';  $_sel = 'selected';
              endif;
              ?>
                <li data_name="<?php echo strtolower($_res->post_title); ?>">
                  <label rid="<?php echo $_res->ID; ?>" class="<?php echo $_sel; ?>">
                    <input class="relatedResourceNode <?php echo $_sel; ?>" name="relatedResourceNode" type="checkbox" data_name="<?php echo $_res->post_title; ?>" value="<?php echo $_res->ID; ?>" <?php echo $_chk; ?> />&nbsp;<?php echo $_res->post_title; ?>
                    <span class="relatedResourceSelectorImage dashicons dashicons-yes"></span>
                  </label>
                </li>
              <?php
            }
            ?></ul><?php
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
        <button type="button" id="btnAddRelatedResources" class="btn btn-default btn-sm" data-postid="<?php echo $post->ID; ?>" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>
