<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper" >
    <div class="row" >
        <div class="col-md-3">
            <div class="mbot30">
                <div class="contract-html-logo">
                    <?php echo get_dark_company_logo(); ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="top" data-sticky data-sticky-class="preview-sticky-header">
        <div class="container preview-sticky-container">
            <div class="sm:tw-flex sm:tw-justify-between -tw-mx-4">
                <div class="sm:tw-self-end tw-inline-flex">
                    
                    <?php if (!($contract->signed == 0 && $contract->marked_as_signed == 0)) { ?>
                    <span
                        class="label label-success -tw-mt-1 tw-self-start tw-ml-4 content-view-status contract-html-is-signed">
                        APPROVED
                    </span>
                    <?php } ?>
                </div>

				

				
                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0">
                    <?php if (is_client_logged_in() && has_contact_permission('contracts')) { ?>
                    <a href="<?php echo site_url('clients/project/'.$contract->project_id.'?group=project_overview'); ?>"
                        class="btn btn-default action-button go-to-portal">
                        Back to Project
                    </a>
                    <?php } ?>
                    
                    <?php echo form_hidden('action', 'contract_pdf'); ?>
                    <?php echo form_close(); ?>
                    <?php if ($contract->signed == 0 && $contract->marked_as_signed == 0) { ?>
                    
                   <?php echo form_open($this->uri->uri_string()) ; ?>
                   <button type="submit" id="approve_action" class="btn btn-success action-button"><i class="fa-solid"></i>APPROVE</button>
                   <?php echo form_hidden('action', 'approve_contract'); ?>
                   <?php echo form_close(); ?>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 contract-left">
        <div class="panel_s tw-mt-6 sm:tw-mt-8">
            <div class="panel-body tc-content contract-html-content">
                <table>
                    <tr> 
                        <td>
                            <b><h2 class="tw-my-0 tw-font-semibold contract-html-subject"><?php echo $contract->subject; ?> </h4></b>
                        </td>
                    </tr>
                </table>
                <hr style="background-color: #323761; border-color: #323761;">
                <table >
                    <tr> 
                        <td>
                            <b><h3 class="tw-my-0 tw-font-semibold contract-html-subject">Project Details</h3></b>
                        </td>
                    </tr>

                    <tr> 
                        <td><b>Project Name:</b></td>
                        <td><?php echo get_project_name_by_id($contract->project_id); ?></td>
                        <td width="30%"></td>
                        <td><b>Document ID:</b></td>
                        <td><?php echo $contract->id; ?></td>
                    </tr>
                    <tr> 
                        <td><b>Client Name:</b></td>
                        <td><?php echo get_customer_name_by_project_id($contract->project_id); ?></td>
                        <td width="30%"></td>
                        <td><b>Date Accepted:</b></td>
                        <td><?php echo _l('date_signed'); ?></td>
                    </tr>
                    <tr> 
                        <td><b>Portier Digital Contact:</b></td>
                        <td><?php echo get_staff_full_name($estimate->sale_agent); ?></td>
                        <td width="30%">
                        <td><b>Project Schedule:</b> </td>
                        <td><?php echo date('F d, Y', strtotime(get_project_startdate($contract->project_id))); ?> - <?php echo date('F d, Y', strtotime(get_project_deadline($contract->project_id))); ?></td>
                    </tr>
                    <tr> 
                        <td></td>
                        <td></td>
                        <td width="30%">
                        <td><b>Project Cost:</b> </td>
                        <td><?php echo app_format_money($contract->contract_value, get_base_currency()); ?></td>
                    </tr>
                    
                </table>
                <hr style="background-color: #323761; border-color: #323761;">
                 <table>
                    <tr> <td><b>Project Description</b></td> </tr>
                    <tr> <td><?php echo get_project_description($contract->project_id); ?> </td> </tr>
                </table>
                <hr style="background-color: #323761; border-color: #323761;">

                <table class="table dt-table" data-order-col="0" data-order-type="asc">
                    <thead>
                        <tr>
                            <th width="20%"><?php echo _l('milestone_name'); ?></th>
                            <th width="20%">Tasks</th>
                            <th>Requirements</th>
                            <th>Final Deliverables</th>
                            <th width="10%">Approval</th>
                            <th style="min-width:140px;">Schedule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo get_project_milestone_schedule_inscope($contract->project_id); ?>


                         
                    </tbody>
                </table>


            </div>
    </div>

    </div>

    <div class="col-md-12 contract-right">
    <div class="inner tw-mt-8 contract-html-tabs panel-body tc-content contract-html-content">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="discussion"> <!-- Made always active -->
                
                <!-- Display Existing Comments First -->
                <div class="clearfix"></div>
                <?php
                $comment_html = '';
                foreach ($comments as $comment) {
                    $comment_html .= '<div class="contract_comment mtop10 mbot20" data-commentid="' . $comment['id'] . '">';
                    if ($comment['staffid'] != 0) {
                        $comment_html .= staff_profile_image($comment['staffid'], [
                            'staff-profile-image-small',
                            'media-object img-circle pull-left mright10',
                        ]);
                    }
                    $comment_html .= '<div class="media-body valign-middle">';
                    $comment_html .= '<div class="mtop5">';
                    $comment_html .= '<b>';
                    if ($comment['staffid'] != 0) {
                        $comment_html .= get_staff_full_name($comment['staffid']);
                    } else {
                        $comment_html .= _l('is_customer_indicator');
                    }
                    $comment_html .= '</b>';
                    $comment_html .= ' - <small class="mtop10 text-muted">' . time_ago($comment['dateadded']) . '</small>';
                    $comment_html .= '</div>';
                    $comment_html .= '<br />';
                    $comment_html .= check_for_links($comment['content']) . '<br />';
                    $comment_html .= '</div>';
                    $comment_html .= '</div>';
                }
                echo $comment_html;
                ?>
                
                <!-- Place Comment Form Here -->
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="contract-comment">
                    <textarea name="content" rows="4" class="form-control"></textarea>
                    <button type="submit" class="btn btn-primary mtop10 pull-right" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('proposal_add_comment'); ?></button>
                    <?php echo form_hidden('action', 'contract_comment'); ?>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

</div>
<?php
   get_template_part('identity_confirmation_form', ['formData' => form_hidden('action', 'sign_contract')]);
   ?>
<script>
$(function() {
    new Sticky('[data-sticky]');
    $(".contract-left table").wrap("<div class='table-responsive'></div>");
    // Create lightbox for contract content images
    $('.contract-html-content img').wrap(function() {
        return '<a href="' + $(this).attr('src') + '" data-lightbox="contract"></a>';
    });
})
</script>