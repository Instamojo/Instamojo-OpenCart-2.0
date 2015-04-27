<?php
class ControllerPaymentInstamojo extends Controller {

  private $logger;

  public function __construct($arg)
  {
    $this->logger= new Log('imojo.log');
    parent::__construct($arg);
  }

  public function index() {

    $this->language->load('payment/instamojo');
    $method_data['button_confirm'] = $this->language->get('button_confirm');
    $method_data['action'] = $this->config->get('instamojo_payment_link');
  
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
  
    if ($order_info) {
      $api_key = $this->config->get('instamojo_api_key');
      $auth_token = $this->config->get('instamojo_auth_token');
      $private_salt = $this->config->get('instamojo_private_salt');
      $custom_field_name = 'data_' . $this->config->get('instamojo_custom_field');

      $this->logger->write("$api_key | $auth_token | $private_salt | $custom_field_name");

      $name = substr(trim((html_entity_decode($order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8'))), 0, 20);
      $email = substr($order_info['email'], 0, 75);
      $phone = substr(ltrim(html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'), '+'), 0, 20);
      $amount = $this->currency->format($order_info['total'], $order_info['currency_code'] , false, false);
      $orderid = date('His') . '-' . $this->session->data['order_id'];

      $this->logger->write("$name | $email | $phone | $amount | $orderid");

      $data = Array();
      $data['data_name'] = $name;
      $data['data_email'] = $email;
      $data['data_amount'] = $amount;
      $data['data_phone'] = $phone;
      $data[$custom_field_name] = $orderid;

      $this->logger->write("Data before sorting: " .  print_r($data, true));

      $ver = explode('.', phpversion());
      $major = (int) $ver[0];
      $minor = (int) $ver[1];

      if($major >= 5 and $minor >= 4){
          ksort($data, SORT_STRING | SORT_FLAG_CASE);
      }
      else{
          uksort($data, 'strcasecmp');
      }

      $this->logger->write("Data after sorting: " .  print_r($data, true));

      $str = hash_hmac("sha1", implode("|", $data), $private_salt);

      $this->logger->write("Signature is: $str");
      $data['data_sign'] = $str;
      $method_data = array_merge($method_data, $data);
      $method_data['custom_field_name'] = $custom_field_name;
      $method_data['custom_field'] = $orderid;

      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/instamojo.tpl')){
        $template = $this->config->get('config_template') . '/template/payment/instamojo.tpl';
      } else {
        $template = 'default/template/payment/instamojo.tpl';
      }
      return $this->load->view($template, $method_data);
    }
  }
  
  public function callback(){

    if (isset($this->request->get['payment_id'])){
      $payment_id = $this->request->get['payment_id'];
      $this->logger->write("Callback called with payment ID: $payment_id");
      $data = $this->_getcurlInfo($payment_id);
      $payment_status = $data['payment']['status'];
      $this->logger->write("Response from server is $payment_status.");

      if($payment_status === "Credit"){
        $this->logger->write("Payment for $payment_id was credited.");
        $custom_field = $this->config->get('instamojo_custom_field');
        $tr_ord_id = $data['payment']['custom_fields'][$custom_field]['value'];
        $time_order_id = explode('-',  $tr_ord_id);
        $order_id = $time_order_id[1];
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info) {
          $this->model_checkout_order->addOrderHistory($order_id, 2);
          $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
        }
        else{
          
          $this->logger->write("Couldn't find any order for Payment ID $payment_id. Please contact the site admin for enquiries.");
          $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
        }

      }
      else{
        $this->logger->write("Payment for $payment_id was not credited.");
        $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
        //$this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
        //$method_data['insta_error'] = "Either $payment_id is is an invalid Payment ID or the payment for it was unsuccessful. Please contact the site admin for enquiries.";

      } 
    } else {
      $this->logger->write("Callback called with no payment ID.");
      $this->response->redirect($this->config->get('config_url'));
      //die('Illegal Access');
    }
  
  }


  private function _getcurlInfo($payment_id){

    $this->logger->write("Get the required field for payment ID: $payment_id");
    $cUrl = 'https://www.instamojo.com/api/1.1/payments/' . $payment_id;
    $api_key = $this->config->get('instamojo_api_key');
    $auth_token = $this->config->get('instamojo_auth_token');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $cUrl);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-Key:$api_key",
                                               "X-Auth-Token:$auth_token"));
    $response = curl_exec($ch);
    $error_number = curl_errno($ch);
    $error_message = curl_error($ch);
    curl_close($ch); 
    $this->logger->write("Curl error no: $error_number || Curl error message: $error_message");
    $response_obj = json_decode($response, true);
    if($response_obj['success'] == false) {
        $message = json_encode($response_obj['message']);
        $this->logger->write("Payment for this ID($payment_id) was not successful: $message");
        return Array('payment' => Array('status' => $message));
        
    }
    if(empty($response_obj) || is_null($response_obj)){
        return Array('payment' => Array('status' => 'No response from the server.'));
    }
    else{
        return $response_obj;
    }
}

}
?>