<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<table class="table dt-table table-milestones" data-order-col="0" data-order-type="asc">
    <thead>
        <tr>
            <th class="hidden"></th>
            <th width="20%"><?php echo _l('milestone_name'); ?></th>
            <th width="10%"><?php echo _l('milestone_start_date'); ?></th>
            <th width="10%"><?php echo _l('milestone_due_date'); ?></th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach($milestones as $milestone){ ?>
            <tr>
                <td class="hide" data-order="<?php echo $milestone['milestone_order']; ?>"></td>
                <td><?php echo $milestone['name']; ?></td>
                
                <td data-order="<?php echo $milestone['start_date']; ?>"><?php echo _d($milestone['start_date']); ?></td>
                <td data-order="<?php echo $milestone['due_date']; ?>"><?php echo _d($milestone['due_date']); ?></td>
                
            </tr>
        <?php } ?>
    </tbody>
</table>
