<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <?php if (count($gantt_data) > 0) { ?>
            <div class="form-group pull-right">
                <select class="selectpicker" name="gantt_view" id="gantt_view">
                    <option value="Day" selected><?php echo _l('gantt_view_day'); ?></option>
                    <option value="Week"><?php echo _l('gantt_view_week'); ?></option>
                    <option value="Month"><?php echo _l('gantt_view_month'); ?></option>
                    <option value="Year"><?php echo _l('gantt_view_year'); ?></option>
                </select>
            </div>
        <?php } else { ?>
            <p><?php echo _l('no_tasks_found'); ?></p>
        <?php } ?>

    </div>
</div>
<div class="clearfix"></div>
<div class="gantt-target"></div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.0/frappe-gantt.min.js'></script>

      <script id="rendered-js" >
 document.addEventListener('DOMContentLoaded', function() {

(function () {
  tasks.forEach(task => {
    task.children = gantt_chart.get_all_dependent_tasks(task.id);
    task.display = "";
    task.parent = "";
    task.collapsed = "";
  });
  let tasks_all = {};
  gantt_chart.tasks.forEach(item => {
    tasks_all[item.id] = item;
  });
  gantt_chart.tasks_all = tasks_all;
  gantt_chart.parents = [];
  gantt_chart.tasks_to_display = gantt_chart.tasks;
})();	 
	 
	 
  collapseAll(); 


  });

        var tasks = <?php echo json_encode($gantt_data); ?>;

       // if (gantt_data.length > 0) {
            var gantt_chart = new Gantt(".gantt-target", tasks, {
                view_modes: ['Day', 'Week', 'Month', 'Year'],
                view_mode: 'Day',
                date_format: 'YYYY-MM-DD',
                popup_trigger: 'click mouseover',
                on_date_change: function(data, start, end) {
                    if (typeof(data.task_id) != 'undefined') {
                        $.post(admin_url + 'tasks/gantt_date_update/' + data.task_id, {
							startdate: moment(start).format('YYYY-MM-DD'),
							duedate: moment(end).format('YYYY-MM-DD'),
                        });
                    }
                },
                on_click: function(task) {
					toggleBars(task);
                    if (typeof(task.task_id) != 'undefined') {
                        init_task_modal(task.task_id);
                    }
                }
            });
			
var selector = document.getElementById("gantt_view");

selector.addEventListener("change", function(el) {
let view = $(el.target).val();
gantt_chart.change_view_mode(view);
});
/*
           jQuery('body').on('mouseleave', '.grid-row', function() {
                gantt_chart.hide_popup();
            })*/

       // }

/*
jQuery('select[name$="gantt_view"').change(function(el) {
let view = $(el.target).val();
gantt_chart.change_view_mode(view);
})*/

	


function toggleBars(task) {
  let children = task.children;

  let index = gantt_chart.parents.indexOf(task.id);
  index === -1 ? gantt_chart.parents.push(task.id) : gantt_chart.parents.splice(index, 1);

  gantt_chart.tasks_to_display.map(item => {
    let indexChild = children.indexOf(item.id);
    if (indexChild !== -1) {
      if (!item.display && !item.parent) {
        item.display = "none";
        item.parent = task.id;
      } else if (!item.display && item.parent) {
        item.display = item.display;
        item.parent = item.parent;
      } else if (item.display && item.parent !== task.id) {
        item.display = item.display;
        item.parent = item.parent;
      } else {
        item.display = "";
        item.parent = "";
      }
    } else if (item.id === task.id) {
      item.collapsed = !item.collapsed ? true : "";
    } else if (item.id !== task.id) {
      item.collapsed = item.collapsed;
    }

    gantt_chart.tasks_all[item.id] = item;

    return item;
  });

  gantt_chart.refresh(gantt_chart.tasks_to_display.filter(task => !task.display));

  let check = gantt_chart.tasks_to_display.length !== gantt_chart.tasks.length;
  gantt_chart.parents = !check ? [] : gantt_chart.parents;

  toggleClassBars(check);
}

// add or remove class to element bar
function toggleClassBars(check) {
  document.querySelectorAll('.bar-wrapper').
  forEach(el => gantt_chart.parents.indexOf(el.dataset.id) !== -1 && check ?
  el.classList.add('parent') : el.classList.remove('parent'));

}

function collapseAll() {
  let tasks = gantt_chart.tasks_to_display ? gantt_chart.tasks_to_display : gantt_chart.tasks;
  tasks.map(task => {
    if (!task.collapsed) {
      toggleBars(task);
    }
  });
}

function expandBars(task) {
  let tasks = gantt_chart.tasks_to_display ? gantt_chart.tasks_to_display : gantt_chart.tasks;
  let tasks_to_display;

  if (!task) {
    tasks_to_display = tasks.map(item => {
      item.display = "";
      item.parent = "";
      item.collapsed = "";
      gantt_chart.tasks_all[item.id] = item;
      return item;
    });
    gantt_chart.parents = [];
  } else {
    let index = gantt_chart.parents.indexOf(task.id);
    if (task.collapsed && index !== -1)
    gantt_chart.parents.splice(index, 1);

    tasks_to_display = tasks.map(item => {
      if (task.children.indexOf(item.id) !== -1) {
        index = gantt_chart.parents.indexOf(item.id);
        if (index !== -1) gantt_chart.parents.splice(index, 1);

        item.display = "";
        item.parent = "";
        item.collapsed = "";
      } else if (task.id === item.id) {
        item.display = "";
        item.parent = "";
        item.collapsed = "";
      } else {
        item.display = item.display;
        item.parent = item.parent;
        item.collapsed = item.collapsed;
      }

      gantt_chart.tasks_all[item.id] = item;

      return item;
    });
  }

  gantt_chart.tasks_to_display = tasks_to_display;
  let check = gantt_chart.tasks_to_display.length !== gantt_chart.tasks.length;
  gantt_chart.refresh(tasks_to_display.filter(task => !task.display));

  toggleClassBars(check);
}

// mousedown event to capture gantt_chart property "bar_being_dragged" 
document.addEventListener("mousedown", handleMouseDown, false);

document.querySelector('.js-view').addEventListener('click', changeView, false);

function handleMouseDown(event) {
  if (!event.target.parentNode.classList.contains('bar-group')) return;

  let taskId = gantt_chart.bar_being_dragged;
  let task = gantt_chart.get_task(taskId);

  let children = task.children.
  map(item => gantt_chart.tasks_all[item]).
  filter(item => item.collapsed);

  if (task.collapsed) {
    expandBars(task);
  } else if (!task.collapsed && children.length >= 1) {
    children.forEach(item => expandBars(item));
  }
}

function changeView(event) {
  event.target.parentNode.childNodes.forEach(childNode => {
    let view = event.target.dataset.view;
    if (childNode.tagName === "BUTTON") {
      if (childNode.dataset.view === view) {
        gantt_chart.change_view_mode(view);
        childNode.classList.add("selected");
      } else {
        childNode.classList.remove("selected");
      }
    }
  });
}	
	
</script>
