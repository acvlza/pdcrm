<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contract extends ClientsController
{
    public function index($id, $hash)
    {
        check_contract_restrictions($id, $hash);
        $contract = $this->contracts_model->get($id);

        if (!$contract) {
            show_404();
        }

        if (!is_client_logged_in()) {
            load_client_language($contract->client);
        }

        if ($this->input->post()) {
            $action = $this->input->post('action');

            switch ($action) {
            case 'contract_pdf':
                    $pdf = contract_pdf($contract);
                    $pdf->Output(slug_it($contract->subject . '-' . get_option('companyname')) . '.pdf', 'D');

                    break;
            case 'sign_contract':
                    process_digital_signature_image($this->input->post('signature', false), CONTRACTS_UPLOADS_FOLDER . $id);
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix().'contracts', array_merge(get_acceptance_info_array(), [
                        'signed' => 1,
                    ]));

                    // Notify contract creator that customer signed the contract
                    send_contract_signed_notification_to_staff($id);

                    set_alert('success', _l('document_signed_successfully'));
                    redirect($_SERVER['HTTP_REFERER']);

            break;
            
           case 'approve_contract':
                   // process_digital_signature_image($this->input->post('signature', false), CONTRACTS_UPLOADS_FOLDER . $id);
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix().'contracts', array_merge(get_acceptance_info_array(), ['signed' => 1, ]));

                    // Notify contract creator that customer signed the contract
                    //send_contract_signed_notification_to_staff($id);
					
					$this->db->where('id', $id);
				    $result = $this->db->get(db_prefix().'contracts')->row();
					$clientid = $result->client;
					$project_id = $result->project_id;
					$contract_value = $result->contract_value;
					$description = $result->subject;
					$long_description = $result->subject;
					
					$this->db->where('userid', $clientid);
					$result2 = $this->db->get(db_prefix().'clients')->row();
					$billing_street = $result2->billing_street;
					$billing_city = $result2->billing_city;
					$billing_state = $result2->billing_state;
					$billing_zip = $result2->billing_zip;
					$billing_street = $result2->billing_street;
					$billing_country = $result2->billing_country;
					
					$invoice_data = array(
					"clientid" => $clientid,
					"project_id" => $project_id,
					"billing_street" => $billing_street,
					"billing_city" => $billing_city,
					"billing_state" => $billing_state,
					"billing_zip" => $billing_zip,
					"billing_country" => $billing_country,
					"contract_value" => $contract_value,
					"description" => $description,
					"long_description" => '',
					);
					
                    $this->create_client_invoice($invoice_data);
                    set_alert('success', 'You successfully APPROVED this document!');
                    redirect($_SERVER['HTTP_REFERER']);

            break; 
            
            
            
             case 'contract_comment':
                    // comment is blank
                    if (!$this->input->post('content')) {
                        redirect($this->uri->uri_string());
                    }
                    $data                = $this->input->post();
                    $data['contract_id'] = $id;
                    $this->contracts_model->add_comment($data, true);
                    redirect($this->uri->uri_string() . '?tab=discussion');

                    break;
            }
        }

        $this->disableNavigation();
        $this->disableSubMenu();

        $data['title']     = $contract->subject;
        $data['contract']  = hooks()->apply_filters('contract_html_pdf_data', $contract);
        $data['bodyclass'] = 'contract contract-view';

        $data['identity_confirmation_enabled'] = true;
        $data['bodyclass'] .= ' identity-confirmation';
        $this->app_scripts->theme('sticky-js','assets/plugins/sticky/sticky.js');
        $data['comments'] = $this->contracts_model->get_comments($id);
        //add_views_tracking('proposal', $id);
        hooks()->do_action('contract_html_viewed', $id);
        $this->app_css->remove('reset-css','customers-area-default');
        $data                      = hooks()->apply_filters('contract_customers_area_view_data', $data);
        $this->data($data);
        no_index_customers_area();
        $this->view('contracthtml');
        $this->layout();
    }
	
public function create_client_invoice($data){

$due_date = "+7 days";
//$due_date = "+21 days";
//$due_date = "+30 days";

$date = new DateTime(date("Y-m-d"));
$date->modify($due_date);
$duedate = $date->format('Y-m-d');

$subtotal = $data['contract_value'];

$f = array(',','_');
$r = array(', ',' ');
//$package_modules = str_replace($f,$r,$package_result->package_modules);

$default_tax  = unserialize(get_option('default_tax'));

$this->load->model('taxes_model');
$taxes = $this->taxes_model->get();

$taxname = '';$total = '';
$status = 1;
if($subtotal != '0'){
$status = 1;
foreach ($taxes as $tax) {
if (is_array($default_tax)) {
if (in_array($tax['name'] . '|' . $tax['taxrate'], $default_tax)) {
$tax_id = $tax['id'];
$taxname = $tax['name'] . '|' . $tax['taxrate'];
$taxrate = $tax['taxrate'];
$total = $subtotal + ($subtotal * $taxrate / 100);
break;
}
}
}
}else{//price is zero
foreach ($taxes as $tax) {
if (is_array($default_tax)) {
if (in_array($tax['name'] . '|' . $tax['taxrate'], $default_tax)) {
$tax_id = $tax['id'];
$taxname = $tax['name'] . '|' . $tax['taxrate'];
$taxrate = $tax['taxrate'];
$total = $subtotal;
$status = 2;//paid because total is zero, maybe trial?
break;
}
}
}
}

if($taxname == ''){
$total = $subtotal;
}

$this->load->model('currencies_model');
$currencies = $this->currencies_model->get();    
$base_currency = $this->currencies_model->get_base_currency();

$staff     = $this->staff_model->get('', ['active' => 1]); 
$billable_tasks = [];
$number = get_option('next_invoice_number');

$newitems = Array(['order' => 1,'description' => $data['description'], 'long_description' => $data['long_description'],'qty' => 1,'unit' => '','rate' => $subtotal,'taxname' => [$taxname]]);//subtotal stays the same if discount

if($this->session->userdata("staff_user_id") ==''){
$sale_agent =1;
}else{
$sale_agent =$this->session->userdata("staff_user_id") ;
}

$this->load->model('payment_modes_model');
// $data['payment_modes']        = $this->payment_modes_model->get('', [], true);//get all no matter if selected


$allowed_payment_modes=[];
$this->load->model('payment_modes_model');
$payment_modes = $this->payment_modes_model->get('', ['expenses_only !=' => 1,]);

if(count($payment_modes) > 0){
foreach ($payment_modes as $key => $mode) {
$allowed_payment_modes[] =$mode['id'];
}
}


$invoice_data = array(
    "clientid" => $data['clientid'],
    "project_id" => $data['project_id'],
    "billing_street" => $data['billing_street'],
    "billing_city" => $data['billing_street'],
    "billing_state" => $data['billing_state'],
    "billing_zip" => $data['billing_zip'],
    "billing_country" => $data['billing_country'],
    "show_shipping_on_invoice" => 'on',
    "shipping_street" => '',
    "shipping_city" => '',
    "shipping_state" => '',
    "shipping_zip" => '',
    "number" => $number,
    "date" => date('Y-m-d'),
    "duedate" => $duedate,
    "tags" => '',
    "allowed_payment_modes" => $allowed_payment_modes,
    "currency" => $base_currency->id,
    "sale_agent" => $sale_agent,
    "recurring" => 0,//$data['invoice_frequency']) ? $data['invoice_frequency'] : $package_frequency,//get_option('super_admin_default_invoice_frequency'),
    "discount_type" => '',
    "repeat_every_custom" => 0,
    "repeat_type_custom" => 'day',
    "adminnote" => '',
    "item_select" => '',
    "show_quantity_as" => 1,
    "description" => '',
    "long_description" => '',
    "newitems" => $newitems,
    "quantity" => 1,
    "unit" => '',
    "rate" => '',
    "taxname" => $taxname,
    "subtotal" => $subtotal,
    "discount_percent" => 0,
    "discount_total" => 0,
    "adjustment" => 0,
    "total" => $total,
    "task_id" => 0,
    "expense_id" => 0,
    "status" => $status,
    "clientnote" => get_option('predefined_clientnote_invoice'),
    "terms" => get_option('predefined_terms_invoice')
    );
	//print_r($invoice_data);exit;
	
$this->load->model('invoices_model');
$invoice_id = $this->invoices_model->add($invoice_data);

if($invoice_id){


}

}	
	
	
	
	
}
