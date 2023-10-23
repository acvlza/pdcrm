<?php
defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php init_head(); ?> 
<?php require_once(__DIR__ .'/css.php');?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('scheduled_meetings', '', 'create') || is_admin()) { ?>
                <a href="<?php echo admin_url('zoom_meeting_manager/index/createMeeting'); ?>"
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
                					<h4><?php echo _l('scheduled_meetings_upcoming'); ?> </h4>
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
initDataTable('.table-meetings_list', admin_url+"scheduled_meetings/upcoming_meetings_table/", undefined, undefined,undefined,[2,'Desc']);
</script>

</body>

</html>