<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mb-10">
    <?php foreach ($project_statuses as $status) { ?>
        <a href="<?php echo site_url('clients/projects/' . $status['id']); ?>"
            class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100
            <?php if ($status['id'] == 1) { ?>
            " style="
               background-color: rgb(16,70,95, .20);
    border-color: rgb(16,70,95, 0.2);
    border-style: solid;
    border-width: 1px;
    color: rgb(27,120,163);
            "
            <?php } elseif ($status['id'] == 2) { ?>
            " style="background-color: rgba(101, 85, 16, 0.20);
border-color: rgba(101, 85, 16, 0.20);
border-style: solid;
border-width: 1px;
color: rgba(118, 99, 19, .84);
            "
            <?php } elseif ($status['id'] == 3) { ?>
            " style="
                background-color: rgba(0, 113, 53, .20);
border-color: rgba(0, 113, 53, .09);
border-style: solid;
border-width: 1px;
color: rgba(0, 113, 53);
            "
            <?php } ?>
        >
            <!-- Start : Widgets -->
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium">
                    <?php echo $status['name']; ?>
                </dt>
                <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600"
                    <?php if ($status['id'] == 1) { ?>
                        style="color: rgb(27,120,163);"
                    <?php } elseif ($status['id'] == 2) { ?>
                        style="color: rgba(118, 99, 19, .84);"
                    <?php } elseif ($status['id'] == 3) { ?>
                        style="color: rgb(22, 163, 74);"
                    <?php } ?>
                >
                    <?php echo total_rows(db_prefix() . 'projects', ['status' => $status['id'], 'clientid' => get_client_user_id()]); ?>
                </div>
            </div>
            <!-- End : Widgets -->
        </a>
    <?php } ?>
</dl>
