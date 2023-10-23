<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
* { box-sizing: border-box; }

/* force scrollbar */
html { }

body { font-family: sans-serif; }

/* ---- grid ---- */

.grid {
  max-width: 1300px;
  background: rgba(93, 91, 133, 0.56);
  overflow-x: hidden; 
  overflow-y: scroll;
  height: 400px;
  max-height: 400px;
  padding:5px;
}

/* clear fix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}


/* ---- .grid-item ---- */

.grid-sizer,
.grid-item {
  width: 14%;
}

.grid .grid-item {
  padding-bottom: 14%; /* hack for proportional sizing */
  float: left;
  background-position: center center;
  background-size: cover;
  
}


/* ---- grid2 ---- */

.grid2 {

 min-width:100% !important;
 max-width:100% !important;
  background: rgba(93, 91, 133, 0.56);
overflow-x: scroll; 
  /*overflow-y: hidden;*/ 
  height: 200px;
  padding:5px;
}



/* clear fix */



/* ---- .grid-item ---- */

.grid-sizer,
.grid-item {
  width: 14%;
}

.grid2 .grid-item {
  padding-bottom: 14%; /* hack for proportional sizing */
 
  background-position: center center;
  background-size: cover;

}




.packery-drop-placeholder {
  border: 3px dotted #333;
  background: hsla(0, 0%, 0%, 0.3);
}

.grid-item.is-dragging,
.grid-item.is-positioning-post-drag {
  z-index: 2;
}
/* Bottom left text */
.bottom-left {
  position: absolute;
  bottom: 7px;
  left: 5px;
  border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;
}

/* Top left text */
.top-left {
  position: absolute;
  top: 4px;
  left: 15px;
  color:#CD2323;
}

/* Top right text */
.top-right {
    position: absolute;
    top: 1px;
    background: rgb(0, 0, 0);
    background-color: rgba(93, 91, 133, 0.40);
    color: #f1f1f1;
    width: 100%;
    padding: 5px 7px;
    text-align: right;
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;
  
  
}



/* Bottom right text */
.bottom-right {
  position: absolute;
  bottom: 8px;
  right: 8px;
}

/* Centered text */
.centered {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.red-label{


}

.no-display{
  visibility:hidden;
  width:0px;
  height:0px;
  
}

#project-files-upload{
margin-top:30px !important; 
  
}

.fa-trash{
	color:red;
}
</style>


<?php $custom_categories = isset($_COOKIE["custom_categories"]) ? $_COOKIE["custom_categories"] : '';?>
<h5 style="font-weight:600; color: #4F5786!important; padding-bottom: 5px; margin-left:20px;"> Category Filter</h5> 
<?php $viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';?>
<div class="form-group col-md-4" app-field-wrapper="file_category">
<select name="file_category" id="file_category" class="form-control" onchange="selectCategory(this)">
<option value="">Show All</option>
<?php
$cats= explode(',',$custom_categories);
foreach($cats as $cat){?>
<option value="<?php echo trim($cat);?>"<?php if($viewcat==trim($cat)){echo ' selected';}?>><?php echo trim($cat);?></option>
<?php }?>
</select></div>

<div class="form-group col-md-6" app-field-wrapper="custom_categories">
    <input 
        type="text" 
        name="custom_categories" 
        id="custom_categories" 
        class="form-control" 
        value="<?php echo $custom_categories; ?>" 
        placeholder="Enter custom categories here and separate it by comma. Ex: Intro, Outro">
</div>


  <div class="form-group col-md-2">
 
    <button class="btn btn-default btn-block" onclick="saveCategories()">
     Save
    </button>
  </div>

<div class="clearfix"></div>
<hr style="height: 2px; background-color: #4F5786; border: none; opacity: 0.5;">

<?php if ($project->settings->upload_files == 1) { ?>
  <?php echo form_open_multipart(site_url('clients/project/' . $project->id), ['class' => 'dropzone mbot15', 'id' => 'project-files-upload']); ?>
  <input type="file" name="file" multiple class="hide"/>
  <?php echo form_close(); ?>
  <div class="pull-right mbot20">
   <button class="gpicker" data-on-pick="projectFileGoogleDriveSave">
    <i class="fa-brands fa-google" aria-hidden="true"></i>
    <?php echo _l('choose_from_google_drive'); ?>
  </button>
  <div id="dropbox-chooser-project-files"></div>
</div>
<?php } ?>

<hr style="width: 100%; height: 2px; background-color: #4F5786; border: none; opacity: 0.5;">

           
<h3 style="font-weight:600; color: #4F5786!important; padding-bottom: 20px;"> Main Bin</h3> 


<div style="width:100%;margin:auto;">
<div class="grid which" id="grid">
<div class="grid-sizer"></div>

<?php 
$c =1;$b=1;
$key=0;
foreach ($files as $key=>$file) {
$pos = ($file['id'] & 1) ? 'even' : 'odd';  
$bin_item = isset($_COOKIE["bin_item_".$file['id']]) ? $_COOKIE["bin_item_".$file['id']] :'false';    
$key=$key+1;
if (isset($_COOKIE["file_priority_".$file['id']])) {
$pvalue = $_COOKIE["file_priority_".$file['id']];
if($pvalue == 'Low'){
$color='grey';
}else if($pvalue == 'Medium'){
$color='orange';
}else if($pvalue == 'High'){
$color='red';
}else{
$color='';
$pvalue ='';
}
$priority = '<span style="background-color:'.$color.';color:white;padding:3px 5px;border-radius:5px;font-size:10px;font-weight:bold;">'.strtoupper($pvalue).'</span>';
}else{
$priority ='';
}


if (isset($_COOKIE["file_category_".$file['id']])) {
$cvalue = $_COOKIE["file_category_".$file['id']];
$viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';
}else{
$cvalue='';
$viewcat ='';
}

if($cvalue != $viewcat && $viewcat !=''){continue;}

$path = get_upload_path_by_type('project') . $project->id . '/' . $file['file_name']; ?>
<?php if (!is_image(PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name'])) {
if ( $bin_item != 'true' ){
$httpstr = explode("://", APP_BASE_URL)[0];
$placeholder = $httpstr."://".$_SERVER['SERVER_NAME']."/assets/images/placeholder.jpg";?>  <div class="gutter-sizer"></div>

<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo $placeholder;?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>
<?php if (get_option('allow_contact_to_delete_files') == 1) { ?>
<?php if ($file['contact_id'] == get_contact_user_id()) { ?>
<a href="<?php echo site_url('clients/delete_file/' . $file['id'] . '/project'); ?>"><i class="fa-solid fa-x"></i></a>
<?php } ?>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php //echo $file['subject']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:12px;font-weight:normal;color:white;background-color: rgba(93, 91, 133, 0.60);;;border-radius:30%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="toBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/down.png'); ?>">
</span>
</div><?php
}
}else{
if ( $bin_item != 'true' ) {
?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo project_file_url($file, false);?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>
<?php if (get_option('allow_contact_to_delete_files') == 1) { ?>
<?php if ($file['contact_id'] == get_contact_user_id()) { ?>
<a href="<?php echo site_url('clients/delete_file/' . $file['id'] . '/project'); ?>"><i class="fa-solid fa-x"></i></a>
<?php } ?>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php //echo $file['subject']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="toBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/down.png'); ?>">
</span>
</div>
<?php


}
}
$c++;




}

?>

</div>
</div>
<div class="clearfix"></div>
<hr style="height: 2px; background-color: #4F5786; border: none; opacity: 0.5;">

<h3 style="font-weight:600; color: #4F5786!important; padding-bottom: 20px;"> Ordered Bin</h3> 

 <div class="clearfix"></div>
<div style="width:100%;margin:auto;">
<div class="grid2" id="grid2">
<div class="grid-sizer"></div>


<?php 
$c =1;$b=1;
$keys=0;
foreach ($files as $key=>$file) {
$pos = ($file['id'] & 1) ? 'even' : 'odd';  
$bin_item = isset($_COOKIE["bin_item_".$file['id']]) ? $_COOKIE["bin_item_".$file['id']] :'false';  

if (isset($_COOKIE["file_priority_".$file['id']])) {
$pvalue = $_COOKIE["file_priority_".$file['id']];
if($pvalue == 'Low'){
$color='grey';
}else if($pvalue == 'Medium'){
$color='orange';
}else if($pvalue == 'High'){
$color='red';
}else{
$color='';
$pvalue ='';
}
$priority = '<span style="background-color:'.$color.';color:white;padding:3px 5px;border-radius:5px;font-size:10px;font-weight:bold;">'.strtoupper($pvalue).'</span>';
}else{
$priority ='';
}


if (isset($_COOKIE["file_category_".$file['id']])) {
$cvalue = $_COOKIE["file_category_".$file['id']];
$viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';
}else{
$cvalue='';
$viewcat ='';
}

if($cvalue != $viewcat && $viewcat !=''){continue;}

$path = get_upload_path_by_type('project') . $project->id . '/' . $file['file_name']; ?>
<?php if (!is_image(PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name'])) {
if ( $bin_item == 'true' ){
$keys++;  
$httpstr = explode("://", APP_BASE_URL)[0];
$placeholder = $httpstr."://".$_SERVER['SERVER_NAME']."/assets/images/placeholder.jpg";?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo $placeholder;?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>
<?php if (get_option('allow_contact_to_delete_files') == 1) { ?>
<?php if ($file['contact_id'] == get_contact_user_id()) { ?>
<a href="<?php echo site_url('clients/delete_file/' . $file['id'] . '/project'); ?>"><i class="fa-solid fa-x"></i></a>
<?php } ?>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php echo $file['type']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="fromBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/up.png'); ?>">
</span>
</div><?php
}
}else{
if ( $bin_item == 'true' ){
  $keys++;
?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo project_file_url($file, false);?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>
<?php if (get_option('allow_contact_to_delete_files') == 1) { ?>
<?php if ($file['contact_id'] == get_contact_user_id()) { ?>
<a href="<?php echo site_url('clients/delete_file/' . $file['id'] . '/project'); ?>"><i class="fa-solid fa-x"></i></a>
<?php } ?>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php echo $file['type']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right bottom-right-bin" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $keys; ?></div>
<span onclick="fromBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/up.png'); ?>">
</span>
</div>
<?php

}
}
$c++;




}

?>

</div>
</div>

<div class="clearfix"></div>
<hr style="height: 2px; background-color: #4F5786; border: none; opacity: 0.5;">

<div class="pull-right mbot20">
    <button class="btn btn-default" onclick="Reset();">
      Reset File Order
    </button>
  </div>





















<div id="project_file_data"></div>

<?php

$httpstr = explode("://", APP_BASE_URL)[0];
$script_url = $httpstr."://".$_SERVER['SERVER_NAME']."/";
?>

<script src="<?php echo $script_url .'assets/js/jquery.min.js';?>"></script>
<script src="<?php echo $script_url .'assets/js/packery.pkgd.js';?>"></script>
<script src="<?php echo $script_url .'assets/js/draggabilly.pkgd.js';?>"></script>
<script id="rendered-js" >
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
    </script>
