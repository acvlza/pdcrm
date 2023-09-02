<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<nav class="navbar navbar-default header">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#theme-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php get_dark_company_logo('', 'navbar-brand logo'); ?>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="theme-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="customers-nav-item-edit-profile">
                    <a href="<?php echo site_url('clients/Projects'); ?>"> Projects </a>
                </li>
                <li class="customers-nav-item-edit-profile">
                    <a href="#"> Meetings </a>
                </li>
                <li class="customers-nav-item-edit-profile">
                    <a href=""> Support </a>
                </li>
             
                <?php hooks()->do_action('customers_navigation_end'); ?>
                <?php if (is_client_logged_in()) { ?>
                <li class="dropdown customers-nav-item-profile">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        <img src="<?php echo contact_profile_image_url($contact->id, 'thumb'); ?>" data-toggle="tooltip"
                            data-title="<?php echo html_escape($contact->firstname . ' ' . $contact->lastname); ?>"
                            data-placement="bottom" class="client-profile-image-small">
                    </a>
                    <ul class="dropdown-menu animated fadeIn">
                        <li class="customers-nav-item-edit-profile">
                            <a href="<?php echo site_url('clients/profile'); ?>">
                                <?php echo _l('clients_nav_profile'); ?>
                            </a>
                        </li>
                        <?php if ($contact->is_primary == 1) { ?>
                        <?php if (can_loggged_in_user_manage_contacts()) { ?>
                        <li class="customers-nav-item-edit-profile">
                            <a href="<?php echo site_url('contacts'); ?>">
                                <?php echo _l('clients_nav_contacts'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="customers-nav-item-company-info">
                            <a href="<?php echo site_url('clients/company'); ?>">
                                <?php echo _l('client_company_info'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (can_logged_in_contact_update_credit_card()) { ?>
                        <li class="customers-nav-item-stripe-card">
                            <a href="<?php echo site_url('clients/credit_card'); ?>">
                                <?php echo _l('credit_card'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        
                        <?php if (!is_language_disabled()) {
                         ?>
                        
                        <?php
                     } ?>
                        <li class="customers-nav-item-logout">
                            <a href="<?php echo site_url('authentication/logout'); ?>">
                                <?php echo _l('clients_nav_logout'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php hooks()->do_action('customers_navigation_after_profile'); ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
