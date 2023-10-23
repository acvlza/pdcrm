<script>
var segment = $(location).attr("href").split("/").pop();	
alert(segment);
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
Reload();
console.log(ids);
console.log(igetCookie(ids));

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

document.addEventListener('DOMContentLoaded', () => {

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
  initLayout: false // disable initial layout

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
  var positions = $grid.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers();

});

$grid.on( 'dragEnd', function( event, pointer ) {
$grid.packery('mergeSortSpaces', this);

//  $grid.packery('bindDraggabillyEvents', this);
//  var d = $(this).removeClass('grid').addClass('grid2');  
//console.log(d);

});


 $grid.packery( 'on', 'dragItemPositioned', function( pckryInstance, draggedItem ) {
        setTimeout(function(){
            $grid.packery();
        var positions = $grid.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers();
        },100); 
    });


// -----------------------------//

// init Packery
var $grid2 = $('.grid2').packery({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  initLayout: false // disable initial layout
});

//var positions = $grid2.packery('getShiftPositions', 'data-item-id');
//localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  //setDivNumbers2();

// get saved dragged positions
var initPositions2 = localStorage.getItem('dragPositions2_' + <?php echo $project->id;?>);
// init layout with saved positions
$grid2.packery('initShiftLayout', initPositions2, 'data-item-id');

// make draggable
$grid2.find('.grid-item').each(function (i, itemElem) {

  var draggie2 = new Draggabilly(itemElem);
  $grid2.packery('bindDraggabillyEvents', draggie2);
 
}); 

// save drag positions on event
$grid2.on('dragItemPositioned', function () {
  // save drag positions
  var positions = $grid2.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers2();
 console.log('after drag');
});


$grid2.on( 'dragEnd', function( event, pointer ) {
  $('.bottom-right-bin').each(function (i) {

//$(this).html(i+1);
 
});

 $grid2.packery( 'on', 'dragItemPositioned', function( pckryInstance, draggedItem ) {
        setTimeout(function(){
            $grid2.packery();
        var positions = $grid2.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions2_' + <?php echo $project->id;?>, JSON.stringify(positions));
  setDivNumbers2();
        },100); 
    });


  //$grid.packery('bindDraggabillyEvents', this);
  
//var d = $(this).removeClass('grid2').addClass('grid');  
//console.log(d);

});

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