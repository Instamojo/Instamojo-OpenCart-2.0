<?php
class ModelPaymentInstamojo extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/instamojo');
    $this->load->model('setting/setting');

    $checkout_label = $this->config->get('instamojo_checkout_label');
    
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
    
    if (!$this->config->get('instamojo_geo_zone_id')) {
      $status = true;
    } elseif ($query->num_rows) {
      $status = true;
    } else {
      $status = false;
    }

    if($status) {
      $method_data = array(
        'code'     => 'instamojo',
        'title'    => !empty($checkout_label) ? $checkout_label : $this->language->get('text_title'),
        'terms'      => '',
        'sort_order' => $this->config->get('instamojo_sort_order')
      );
    }  
  
    return $method_data;
  }
}