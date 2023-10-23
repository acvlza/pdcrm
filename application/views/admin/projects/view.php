<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php echo form_hidden('project_id', $project->id) ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <div class="row">
                        <div class="col-md-7 project-heading">
                            <div class="tw-flex tw-flex-wrap tw-items-center">
                                <!-- Start : Project Name -->
                                <h2 class="project-name" style="margin: 0 20px 0 10px; color: #9ba1c4;"><?php echo $project->name; ?></h2>
                               
                                <!-- End : Project Name -->
                                <div class="visible-xs">
                                    <div class="clearfix"></div>
                                </div>
                                <!-- Start : Right Menu -->
                                <div class="tw-items-center ltr:tw-space-x-2 tw-inline-flex">
                                    <div class="tw-flex -tw-space-x-1">
                                        <?php foreach ($members as $member) { ?>
                                        <span class="tw-group tw-relative"
                                            data-title="<?php echo get_staff_full_name($member['staff_id']) . (has_permission('projects', '', 'create') || $member['staff_id'] == get_staff_user_id() ? ' - ' . _l('total_logged_hours_by_staff') . ': ' . seconds_to_time_format($member['total_logged_time']) : ''); ?>"
                                            data-toggle="tooltip">
                                            <?php if (has_permission('projects', '', 'edit')) { ?>
                                            <a href="<?php echo admin_url('projects/remove_team_member/' . $project->id . '/' . $member['staff_id']); ?>"
                                                class="_delete group-hover:tw-inline-flex tw-hidden tw-rounded-full tw-absolute tw-items-center tw-justify-center tw-bg-neutral-300/50 tw-h-7 tw-w-7 tw-cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="tw-w-4 tw-h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                            <?php } ?>
                                            <?php echo staff_profile_image($member['staff_id'], ['tw-inline-block tw-h-7 tw-w-7 tw-rounded-full tw-ring-2 tw-ring-white', '']); ?>
                                        </span>
                                        <?php } ?>
                                    </div>
                                    <a href="#" data-target="#add-edit-members" data-toggle="modal"
                                        class="tw-mt-1.5 rtl:tw-mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </a>
                                </div>

                                <?php
                                        if ($project_status['name'] == 'In Progress') {
                                            $labelStyles = 'label label-primary';
                                        } elseif ($project_status['name'] == 'For Review') {
                                            $labelStyles = 'label label-warning';
                                        } elseif ($project_status['name'] == 'Done') {
                                            $labelStyles = 'label label-success';
                                        } else {
                                            // Handle the case if none of the specified values match
                                            $labelStyles = ''; // Set a default value or handle it accordingly
                                        }
                                    ?>

                                    <span class="<?php echo $labelStyles; ?>"><?php echo $project_status['name']; ?></span>


                                
                                <!-- End : Right Menu -->
                            </div>
                        </div>
                        <div class="col-md-5 text-right">
                            <?php if (has_permission('tasks', '', 'create')) { ?>
                            <a href="#"
                                onclick="new_task_from_relation(undefined,'project',<?php echo $project->id; ?>); return false;"
                                class="btn btn-primary">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('new_task'); ?>
                            </a>
                            <?php } ?>
                            
                            <?php
                           $project_pin_tooltip = _l('pin_project');
                           if (total_rows(db_prefix() . 'pinned_projects', ['staff_id' => get_staff_user_id(), 'project_id' => $project->id]) > 0) {
                               $project_pin_tooltip = _l('unpin_project');
                           }
                           ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <?php echo _l('more'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right width200 project-actions">
                                    <li>
                                        <a href="<?php echo admin_url('projects/pin_action/' . $project->id); ?>">
                                            <?php echo $project_pin_tooltip; ?>
                                        </a>
                                    </li>
                                    <?php if (has_permission('projects', '', 'edit')) { ?>
                                    <li>
                                        <a href="<?php echo admin_url('projects/project/' . $project->id); ?>">
                                            <?php echo _l('edit_project'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if (has_permission('projects', '', 'create')) { ?>
                                    <li>
                                        <a href="#" onclick="copy_project(); return false;">
                                            <?php echo _l('copy_project'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if (has_permission('projects', '', 'create') || has_permission('projects', '', 'edit')) { ?>
                                    <li class="divider"></li>
                                    <?php foreach ($statuses as $status) {
                               if ($status['id'] == $project->status) {
                                   continue;
                               } ?>
                                    <li>
                                        <a href="#" data-name="<?php echo _l('project_status_' . $status['id']); ?>"
                                            onclick="project_mark_as_modal(<?php echo $status['id']; ?>,<?php echo $project->id; ?>, this); return false;"><?php echo _l('project_mark_as', $status['name']); ?></a>
                                    </li>
                                    <?php
                           } ?>
                                    <?php } ?>
                                    <li class="divider"></li>
                                    <?php if (has_permission('projects', '', 'create')) { ?>
                                    <li>
                                        <a href="<?php echo admin_url('projects/export_project_data/' . $project->id); ?>"
                                            target="_blank"><?php echo _l('export_project_data'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <?php if (is_admin()) { ?>
                                    <li>
                                        <a href="<?php echo admin_url('projects/view_project_as_client/' . $project->id . '/' . $project->clientid); ?>"
                                            target="_blank"><?php echo _l('project_view_as_client'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <?php if (has_permission('projects', '', 'delete')) { ?>
                                    <li>
                                        <a href="<?php echo admin_url('projects/delete/' . $project->id); ?>"
                                            class="_delete">
                                            <span class="text-danger"><?php echo _l('delete_project'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start : Project Tabs -->
                <div class="project-menu-panel tw-my-5">
                    <?php hooks()->do_action('before_render_project_view', $project->id); ?>
                    <?php $this->load->view('admin/projects/project_tabs'); ?>
                </div>
                <?php
               if ((has_permission('projects', '', 'create') || has_permission('projects', '', 'edit'))
                 && $project->status == 1
                 && $this->projects_model->timers_started_for_project($project->id)
                 && $tab['slug'] != 'project_milestones') {
                   ?>
                <div class="alert alert-warning project-no-started-timers-found mbot15">
                    <?php echo _l('project_not_started_status_tasks_timers_found'); ?>
                </div>
                <?php
               } ?>
                <?php
               if ($project->deadline && date('Y-m-d') > $project->deadline
                && $project->status == 2
                && $tab['slug'] != 'project_milestones') {
                   ?>
                <div class="alert alert-warning bold project-due-notice mbot15">
                    <?php echo _l('project_due_notice', floor((abs(time() - strtotime($project->deadline))) / (60 * 60 * 24))); ?>
                </div>
                <?php
               } ?>
                <?php
               if (!has_contact_permission('projects', get_primary_contact_user_id($project->clientid))
                 && total_rows(db_prefix() . 'contacts', ['userid' => $project->clientid]) > 0
                 && $tab['slug'] != 'project_milestones') {
                   ?>
                <div class="alert alert-warning project-permissions-warning mbot15">
                    <?php echo _l('project_customer_permission_warning'); ?>
                </div>
                <?php
               } ?>

                <?php $this->load->view(($tab ? $tab['view'] : 'admin/projects/project_overview')); ?>

            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="add-edit-members" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('projects/add_edit_members/' . $project->id)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('project_members'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
            $selected = [];
            foreach ($members as $member) {
                array_push($selected, $member['staff_id']);
            }
           echo render_select('project_members[]', $staff, ['staffid', ['firstname', 'lastname']], 'project_members', $selected, ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
           ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary" autocomplete="off"
                    data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php if (isset($discussion)) {
               echo form_hidden('discussion_id', $discussion->id);
               echo form_hidden('discussion_user_profile_image_url', $discussion_user_profile_image_url);
               echo form_hidden('current_user_is_admin', $current_user_is_admin);
           }
   echo form_hidden('project_percent', $percent);
   ?>
<div id="invoice_project"></div>
<div id="pre_invoice_project"></div>
<?php $this->load->view('admin/projects/milestone'); ?>
<?php $this->load->view('admin/projects/copy_settings'); ?>
<?php $this->load->view('admin/projects/_mark_tasks_finished'); ?>
<?php init_tail(); ?>
<!-- For invoices table -->

<?php
$httpstr = explode("://", APP_BASE_URL)[0];
$script_url = $httpstr."://".$_SERVER['SERVER_NAME']."/";
?>
<script src="<?php echo $script_url .'assets/js/packery.pkgd.js';?>"></script>
<script src="<?php echo $script_url .'assets/js/draggabilly.pkgd.js';?>"></script>



<script>
taskid = '<?php echo $this->input->get('taskid'); ?>';
</script>
<script>



function Reset(){
localStorage.removeItem('dragPositions_' + <?php echo $project->id;?>);
localStorage.removeItem('dragPositions2_' + <?php echo $project->id;?>);
location.reload(); 
}

function Reload(){
location.reload(); 
}
function selectCategory(obj){
var id = obj.id;
var value = obj.value;
document.cookie = id + "=" + value; 
Reload();
console.log(igetCookie(id));

}

function saveCategories(){
var ids = document.getElementById('custom_categories').id;
var value = document.getElementById('custom_categories').value;
document.cookie = ids + "=" + value; 
localStorage.setItem(ids,value);



$.post("<?php echo admin_url('video_library/cats'); ?>", {
categories: value,
}, function(result){
	//alert(result);
if(result == 'success'){
Reload();
console.log(ids);
console.log(igetCookie(ids));
}else{
alert('An error occured');
//$('#ModalError').modal('show');
}
});

}


function igetCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
function setDivNumbers(){

var jsonArray = localStorage.getItem('dragPositions_' + <?php echo $project->id;?>);
var obj = JSON.parse(jsonArray);
if(obj == null){return;}	
//var attr = obj.attr; //client prop is an array
for(var i = 0; i < obj.length; i++){
 //console.log((i+1) + ' ' + obj[i].attr);
var divattr = obj[i].attr; 
document.getElementsByClassName("bottom-right")[divattr-1].innerHTML = i+1;

}
}

function setDivNumbers2(){

  
var jsonArray = localStorage.getItem('dragPositions2_' + <?php echo $project->id;?>);
var obj = JSON.parse(jsonArray);
if(obj == null){return;}	
//console.log(obj);return;
//var attr = obj.attr; //client prop is an array
for(var i = 0; i < obj.length; i++){
var num = parseInt(obj[i].attr) + parseInt(4);  
console.log((i+1) + ' ' + num);
//var divattr = obj[i].attr; 
document.getElementById("grid-number-"+num).innerHTML = i+1;
//document.getElementsByClassName("bottom-right")[divattr-1].innerHTML = i+1;

}
}

$(document).ready(function () {

 setDivNumbers();
 setDivNumbers2();   
 

});

//localStorage.removeItem("dragPositions"); 
// add Packery.prototype methods

// get JSON-friendly data for items positions
Packery.prototype.getShiftPositions = function (attrName) {
  attrName = attrName || 'id';
  var _this = this;
  return this.items.map(function (item) {
    return {
      attr: item.element.getAttribute(attrName),
      x: item.rect.x / _this.packer.width };

  });
};

/*
Packery.prototype.mergeSortSpaces = function() { // remove redundant spaces 
Packery.mergeRects( this.spaces ); 
this.spaces.sort( this.sorter );
}*/
/*
Packer.prototype.addSpace = function( rect ) { 
this.spaces.push( rect ); this.mergeSortSpaces();
}; // remove element from DOM 

Packery.Item.prototype.removeElem = function() {
this.element.parentNode.removeChild( this.element ); this.layout.packer.addSpace( this.rect ); this.emitEvent( 'remove', [ this ] );
};*/





Packery.prototype.initShiftLayout = function (positions, attr) {
  if (!positions) {
    // if no initial positions, run packery layout
    this.layout();
    return;
  }
  // parse string to JSON
  if (typeof positions == 'string') {
    try {
      positions = JSON.parse(positions);
    } catch (error) {
      console.error('JSON parse error: ' + error);
      this.layout();
      return;
    }
  }

  attr = attr || 'id'; // default to id attribute
  this._resetLayout();
  // set item order and horizontal position from saved positions
  this.items = positions.map(function (itemPosition) {
    var selector = '[' + attr + '="' + itemPosition.attr + '"]';
    var itemElem = this.element.querySelector(selector);
    var item = this.getItem(itemElem);
    item.rect.x = itemPosition.x * this.packer.width;
    return item;
  }, this);
  this.shiftLayout();
};

// -----------------------------//

// init Packery
var $grid = $('.grid').packery({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  initLayout: false, // disable initial layout
  gutter: 10

});

// get saved dragged positions
var initPositions = localStorage.getItem('dragPositions_' + <?php echo $project->id;?>);
// init layout with saved positions
$grid.packery('initShiftLayout', initPositions, 'data-item-id');

// make draggable
$grid.find('.grid-item').each(function (i, itemElem) {

  var draggie = new Draggabilly(itemElem);
  $grid.packery('bindDraggabillyEvents', draggie);
// $(document).ready(function () { 
// dragItemToPage(draggie, $('#grid'), $('#grid2'));
//});
});
// save drag positions on event
$grid.on('dragItemPositioned', function () {
  // save drag positions
  $grid.packery('mergeSortSpaces', this);
  var positions = $grid.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers();

});

$grid.on( 'dragEnd', function( event, pointer ) {
$grid.packery('mergeSortSpaces', this);
setDivNumbers();

//  $grid.packery('bindDraggabillyEvents', this);
//  var d = $(this).removeClass('grid').addClass('grid2');  
//console.log(d);

});


 $grid.packery( 'on', 'dragItemPositioned', function( pckryInstance, draggedItem ) {
        setTimeout(function(){
            $grid.packery();
		$grid.packery('mergeSortSpaces', this);	
        var positions = $grid.packery('getShiftPositions', 'data-item-id');
	
  localStorage.setItem('dragPositions_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers();
        },100); 
    });


// -----------------------------//
Packery.prototype.initShiftLayout2 = function (positions, attr) {
  if (!positions) {
    // if no initial positions, run packery layout
    this.layout();
    return;
  }
  // parse string to JSON
  if (typeof positions == 'string') {
    try {
      positions = JSON.parse(positions);
    } catch (error) {
      console.error('JSON parse error: ' + error);
      this.layout();
      return;
    }
  }

  attr = attr || 'id'; // default to id attribute
  this._resetLayout();
  // set item order and horizontal position from saved positions
  this.items = positions.map(function (itemPosition) {
    var selector = '[' + attr + '="' + itemPosition.attr + '"]';
    var itemElem = this.element.querySelector(selector);
    var item = this.getItem(itemElem);
    item.rect.x = itemPosition.x * this.packer.width;
    return item;
  }, this);
  this.shiftLayout();
};


// init Packery
var $grid2 = $('.grid2').packery({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  initLayout: false, // disable initial layout
  gutter: 10,
  horizontal: true
 
 });
 


//var positions = $grid2.packery('getShiftPositions', 'data-item-id');
//localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  //setDivNumbers2();

// get saved dragged positions
var initPositions2 = localStorage.getItem('dragPositions2_' + <?php echo $project->id;?>);
// init layout with saved positions
$grid2.packery('initShiftLayout2', initPositions2, 'data-item-id');

// make draggable
$grid2.find('.grid-item').each(function (i, itemElem) {
	
var draggie2 = new Draggabilly(itemElem, {
  axis:'x'
});

/*  var draggie2 = new Draggabilly(itemElem);*/
  
  $grid2.packery('bindDraggabillyEvents', draggie2);
 
}); 

// save drag positions on event
$grid2.on('dragItemPositioned', function () {
  // save drag positions
//setTimeout(function(){
         //   $grid2.packery();
		//$grid2.packery('mergeSortSpaces', this);	
        var positions = $grid2.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers2();
     //   },100); 
});

/*
$grid2.on( 'dragEnd', function( event, pointer ) {
setTimeout(function(){
            $grid2.packery();
		$grid2.packery('mergeSortSpaces', this);	
        var positions = $grid2.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers2();
        },100); 
 
});

 $grid2.packery( 'on', 'dragItemPositioned', function( pckryInstance, draggedItem ) {
        setTimeout(function(){
            $grid2.packery();
		$grid2.packery('mergeSortSpaces', this);	
        var positions = $grid2.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers2();
        },100); 
    });
*/

  //$grid.packery('bindDraggabillyEvents', this);
  
//var d = $(this).removeClass('grid2').addClass('grid');  
//console.log(d);



function toBin(file_id) {
  //alert(file_id);return;
var id = 'bin_item_'+ file_id;
var value = 'true';
document.cookie = id + "=" + value; 
$('#grid-item-'+file_id).detach().appendTo($('#grid2'));

//localStorage.removeItem('dragPositions2_' + <?php echo $project->id;?>);
//location.reload(); 
Reset();
return;
//console.log(getCookie(id)); 
  
  
  
  
  
    // Move item to new container
    $(draggie.element).detach().appendTo(id_to);
    $(id_from).packery( 'reloadItems' );
    $(id_to).packery( 'reloadItems' );

    // Rebind draggie events
    $(id_from).packery( 'unbindDraggabillyEvents', draggie );
    $(id_to).packery( 'bindDraggabillyEvents', draggie );
    $(id_to).packery( 'stamp', draggie.element );
    var item = $(id_to).packery( 'getItem', draggie.element );
    if ( item ) {
        item.dragStart();
    }
}

function fromBin(file_id) {
  //alert(file_id);return;
var id = 'bin_item_'+ file_id;
var value = 'false';
document.cookie = id + "=" + value; 
$('#grid-item-'+file_id).detach().appendTo($('#grid'));
localStorage.removeItem('dragPositions2_' + <?php echo $project->id;?>);
//var positions = $grid2.packery('getShiftPositions', 'data-item-id');
//  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
//location.reload(); 
Reset();
return;
}





var gantt_data = {};
<?php if (isset($gantt_data)) { ?>
gantt_data = <?php echo json_encode($gantt_data); ?>;
<?php } ?>
var discussion_id = $('input[name="discussion_id"]').val();
var discussion_user_profile_image_url = $('input[name="discussion_user_profile_image_url"]').val();
var current_user_is_admin = $('input[name="current_user_is_admin"]').val();
var project_id = $('input[name="project_id"]').val();
if (typeof(discussion_id) != 'undefined') {
    discussion_comments('#discussion-comments', discussion_id, 'regular');
}
$(function() {
    var project_progress_color =
        '<?php echo hooks()->apply_filters('admin_project_progress_color', '#84c529'); ?>';
    var circle = $('.project-progress').circleProgress({
        fill: {
            gradient: [project_progress_color, project_progress_color]
        }
    }).on('circle-animation-progress', function(event, progress, stepValue) {
        $(this).find('strong.project-percent').html(parseInt(100 * stepValue) + '<i>%</i>');
    });
});

function discussion_comments(selector, discussion_id, discussion_type) {
    var defaults = _get_jquery_comments_default_config(
        <?php echo json_encode(get_project_discussions_language_array()); ?>);
    var options = {
        // https://github.com/Viima/jquery-comments/pull/169
        wysiwyg_editor: {
            opts: {
                enable: true,
                is_html: true,
                container_id: 'editor-container',
                comment_index: 0,
            },
            init: function(textarea, content) {
                var comment_index = textarea.data('comment_index');
                var editorConfig = _simple_editor_config();
                editorConfig.setup = function(ed) {
                    textarea.data('wysiwyg_editor', ed);

                    ed.on('change', function() {
                        var value = ed.getContent();
                        if (value !== ed._lastChange) {
                            ed._lastChange = value;
                            textarea.trigger('change');
                        }
                    });

                    ed.on('keyup', function() {
                        var value = ed.getContent();
                        if (value !== ed._lastChange) {
                            ed._lastChange = value;
                            textarea.trigger('change');
                        }
                    });

                    ed.on('Focus', function(e) {
                        setTimeout(function() {
                            textarea.trigger('click');
                        }, 500)
                    });

                    ed.on('init', function() {
                        if (content) ed.setContent(content);

                        if ($('#mention-autocomplete-css').length === 0) {
                            $('<link>').appendTo('head').attr({
                                id: 'mention-autocomplete-css',
                                type: 'text/css',
                                rel: 'stylesheet',
                                href: site_url +
                                    'assets/plugins/tinymce/plugins/mention/autocomplete.css'
                            });
                        }

                        if ($('#mention-css').length === 0) {
                            $('<link>').appendTo('head').attr({
                                type: 'text/css',
                                id: 'mention-css',
                                rel: 'stylesheet',
                                href: site_url +
                                    'assets/plugins/tinymce/plugins/mention/rte-content.css'
                            });
                        }
                    })
                }

                editorConfig.toolbar = editorConfig.toolbar.replace('alignright', 'alignright strikethrough')
                editorConfig.plugins[0] += ' mention';
                editorConfig.content_style = 'span.mention {\
                     background-color: #eeeeee;\
                     padding: 3px;\
                  }';
                var projectUserMentions = [];
                editorConfig.mentions = {
                    source: function(query, process, delimiter) {
                        if (projectUserMentions.length < 1) {
                            $.getJSON(admin_url + 'projects/get_staff_names_for_mentions/' + project_id,
                                function(data) {
                                    projectUserMentions = data;
                                    process(data)
                                });
                        } else {
                            process(projectUserMentions)
                        }
                    },
                    insert: function(item) {
                        return '<span class="mention" contenteditable="false" data-mention-id="' + item
                            .id + '">@' +
                            item.name + '</span>&nbsp;';
                    }
                };

                var containerId = this.get_container_id(comment_index);
                tinyMCE.remove('#' + containerId);

                setTimeout(function() {
                    init_editor('#' + containerId, editorConfig)
                }, 100)
            },
            get_container: function(textarea) {
                if (!textarea.data('comment_index')) {
                    textarea.data('comment_index', ++this.opts.comment_index);
                }

                return $('<div/>', {
                    'id': this.get_container_id(this.opts.comment_index)
                });
            },
            get_contents: function(editor) {
                return editor.getContent();
            },
            on_post_comment: function(editor, evt) {
                editor.setContent('');
            },
            get_container_id: function(comment_index) {
                var container_id = this.opts.container_id;
                if (comment_index) container_id = container_id + "-" + comment_index;
                return container_id;
            }
        },
        currentUserIsAdmin: current_user_is_admin,
        getComments: function(success, error) {
            $.get(admin_url + 'projects/get_discussion_comments/' + discussion_id + '/' + discussion_type,
                function(response) {
                    success(response);
                }, 'json');
        },
        postComment: function(commentJSON, success, error) {
            $.ajax({
                type: 'post',
                url: admin_url + 'projects/add_discussion_comment/' + discussion_id + '/' +
                    discussion_type,
                data: commentJSON,
                success: function(comment) {
                    comment = JSON.parse(comment);
                    success(comment)
                },
                error: error
            });
        },
        putComment: function(commentJSON, success, error) {
            $.ajax({
                type: 'post',
                url: admin_url + 'projects/update_discussion_comment',
                data: commentJSON,
                success: function(comment) {
                    comment = JSON.parse(comment);
                    success(comment)
                },
                error: error
            });
        },
        deleteComment: function(commentJSON, success, error) {
            $.ajax({
                type: 'post',
                url: admin_url + 'projects/delete_discussion_comment/' + commentJSON.id,
                success: success,
                error: error
            });
        },
        uploadAttachments: function(commentArray, success, error) {
            var responses = 0;
            var successfulUploads = [];
            var serverResponded = function() {
                responses++;
                // Check if all requests have finished
                if (responses == commentArray.length) {
                    // Case: all failed
                    if (successfulUploads.length == 0) {
                        error();
                        // Case: some succeeded
                    } else {
                        successfulUploads = JSON.parse(successfulUploads);
                        success(successfulUploads)
                    }
                }
            }
            $(commentArray).each(function(index, commentJSON) {
                // Create form data
                var formData = new FormData();
                if (commentJSON.file.size && commentJSON.file.size > app
                    .max_php_ini_upload_size_bytes) {
                    alert_float('danger', "<?php echo _l('file_exceeds_max_filesize'); ?>");
                    serverResponded();
                } else {
                    $(Object.keys(commentJSON)).each(function(index, key) {
                        var value = commentJSON[key];
                        if (value) formData.append(key, value);
                    });

                    if (typeof(csrfData) !== 'undefined') {
                        formData.append(csrfData['token_name'], csrfData['hash']);
                    }
                    $.ajax({
                        url: admin_url + 'projects/add_discussion_comment/' + discussion_id +
                            '/' + discussion_type,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(commentJSON) {
                            successfulUploads.push(commentJSON);
                            serverResponded();
                        },
                        error: function(data) {
                            var error = JSON.parse(data.responseText);
                            alert_float('danger', error.message);
                            serverResponded();
                        },
                    });
                }
            });
        }
    }
    var settings = $.extend({}, defaults, options);
    $(selector).comments(settings);
}
</script>
</body>
</html>



