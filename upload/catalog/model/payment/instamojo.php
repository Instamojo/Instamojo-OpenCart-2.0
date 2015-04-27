<?php
class ModelPaymentInstamojo extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/instamojo');
    $this->load->model('setting/setting');

    $checkout_label = $this->config->get('instamojo_checkout_label');

    $method_data = array(
      'code'     => 'instamojo',
      'title'    =>  !empty($checkout_label) ? $checkout_label : 'Pay using Instamojo',
      'terms'      => '',
      'sort_order' => $this->config->get('custom_sort_order')
    );
  
    return $method_data;
  }
}