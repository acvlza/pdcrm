<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mb-10">
    <?php foreach ($project_statuses as $status) { ?>
        <a href="<?php echo site_url('clients/projects/' . $status['id']); ?>"
            class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100
            <?php if ($status['id'] == 1) { ?>
            " style="
                background-color: rgb(239, 246, 255);
                border-color: rgb(191, 219, 254);
                border-style: solid;
                border-width: 1px;
                color: rgb(37, 99, 235);
            "
            <?php } elseif ($status['id'] == 2) { ?>
            " style="
                background-color: rgb(254, 252, 232);
                border-color: rgb(254, 240, 138);
                border-style: solid;
                border-width: 1px;
                color: rgb(202, 138, 4);
            "
            <?php } elseif ($status['id'] == 3) { ?>
            " style="
                background-color: rgb(240, 253, 244);
                border-color: rgb(187, 247, 208);
                border-style: solid;
                border-width: 1px;
                color: rgb(22, 163, 74);
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
                        style="color: rgb(37, 99, 235);"
                    <?php } elseif ($status['id'] == 2) { ?>
                        style="color: rgb(202, 138, 4);"
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
