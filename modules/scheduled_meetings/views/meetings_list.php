<?php
defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php init_head(); ?> 
<?php require_once(__DIR__ .'/css.php');?>
<div id="wrapper"> 
    <div class="content">
        <div class="row">
            <div class="col-md-12">
			
<div class="widget relative" id="widget-clientdash_helper" data-name="Account Overview" style="margin-bottom:10px;padding:20px 10px 20px;">
<div class="row">
<a href="<?php echo admin_url('scheduled_meetings/new_meetings');?>">
      	<div style="margin-bottom:5px !important;"  class="col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">
            <div style="padding:10px 10px 20px;background-color:#white !important;border-radius: 5px; border: 1px solid green;min-height:104.16px;" class="top_stats_wrapper">
                    <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex tw-items-center text-neutral-500 tw-truncate">
                    <span style="color:green;" class="tw-truncate">New Meeting Requests</span>
                    </div>
                </div>
                <div style="text-align:center;font-size:20px;font-weight:bold;color:green;margin:15px 0 0 0;">
                <span style="color:green;"><?php echo $new_meetings;?></span>
            </div>
        </div>		
		</div>
</a>		
		
<a href="<?php echo admin_url('scheduled_meetings/upcoming');?>">
      	<div style="margin-bottom:5px !important;"  class="col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">
            <div style="padding:10px 10px 20px;background-color:#white !important;border-radius: 5px; border: 1px solid orange;min-height:104.16px;" class="top_stats_wrapper">
                    <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex tw-items-center text-neutral-500 tw-truncate">
                    <span style="color:orange;" class="tw-truncate">Upcoming Meetings</span>
                    </div>
                </div>
                <div style="text-align:center;font-size:20px;font-weight:bold;color:orange;margin:15px 0 0 0;">
                <span style="color:orange;"><?php echo $upcoming_meetings;?></span>
            </div>
        </div>		
		</div>
</a>

<a href="<?php echo admin_url('scheduled_meetings/rescheduled');?>">
      	<div style="margin-bottom:5px !important;"  class="col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">
            <div style="padding:10px 10px 20px;background-color:#white !important;border-radius: 5px; border: 1px solid blue;min-height:104.16px;" class="top_stats_wrapper">
                    <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex tw-items-center text-neutral-500 tw-truncate">
                    <span style="color:blue;" class="tw-truncate">Reschedule Requests</span>
                    </div>
                </div>
                <div style="text-align:center;font-size:20px;font-weight:bold;color:blue;margin:15px 0 0 0;">
                <span style="color:blue;"><?php echo $rescheduled_meetings;?></span>
            </div>
        </div>		
		</div>
</a>		

<a href="<?php echo admin_url('scheduled_meetings/cancelled');?>">
      	<div style="margin-bottom:5px !important;"  class="col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">
            <div style="padding:10px 10px 20px;background-color:#white !important;border-radius: 5px; border: 1px solid red;min-height:104.16px;" class="top_stats_wrapper">
                    <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex tw-items-center text-neutral-500 tw-truncate">
                    <span style="color:red;" class="tw-truncate">Cancelled Meetings</span>
                    </div>
                </div>
                <div style="text-align:center;font-size:20px;font-weight:bold;color:red;margin:15px 0 0 0;">
                <span style="color:red;"><?php echo $cancelled_meetings;?></span>
            </div>
        </div>		
		</div>
</a>


<div class="clearfix"></div>
      
</div>
</div>			
			
			
			
			
                <?php if (has_permission('scheduled_meetings', '', 'create') || is_admin()) { ?>
                <a href="<?php echo admin_url('scheduled_meetings/create_new_meeting'); ?>"
                    class="btn btn-primary tw-mb-2 sm:tw-mb-4">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?php echo _l('scheduled_meetings_create_new_meeting'); ?>
                </a>
                <?php } ?>
              
                <div class="panel_s">
                	<div class="panel-body">
                		<div class="_buttons">
                			<div class="row">
                				<div class="col-md-6">
                					<h4><?php echo _l('scheduled_meetings_dashboard'); ?> </h4>
                				</div>

                			</div>
                		</div>
                		<div class="clearfix"></div>
                		<hr class="hr-panel-heading" />
                		<div class="clearfix"></div>
<?php render_datatable([
'#',
_l('scheduled_meetings_date'),
_l('scheduled_meetings_topic_name'),
_l('scheduled_meetings_category'),
_l('scheduled_meetings_status'),
_l('scheduled_meetings_action'),
], 'meetings_list');
?>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
initDataTable('.table-meetings_list', admin_url+"scheduled_meetings/meetings_table/", undefined, undefined,undefined,[2,'Desc']);
</script>

</body>

</html>