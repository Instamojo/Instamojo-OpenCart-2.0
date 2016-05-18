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

      $name = substr(trim((html_entity_decode($order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'))), 0, 20);
      $email = substr($order_info['email'], 0, 75);
      $phone = substr(html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'), 0, 20);
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

      if(version_compare(VERSION, '2.2.0.0', '<')) {
          return $this->load->view('default/template/payment/instamojo.tpl', $method_data);
      } else {
        return $this->load->view('payment/instamojo.tpl', $method_data);
      }

    }
  }
  
  public function callback(){

    if (isset($this->request->get['payment_id'])){
      $payment_id = $this->request->get['payment_id'];
      $this->logger->write("Callback called with payment ID: $payment_id");
      $data = $this->_getcurlInfo($payment_id);
      $payment_status = $data['payment']['status'];
      $this->logger->write("Response from server is $payment_status.");

      if($payment_status === "Credit" || $payment_status === "Failed" || $payment_status === "Initiated"){
        $custom_field = $this->config->get('instamojo_custom_field');
        $tr_ord_id = $data['payment']['custom_fields'][$custom_field]['value'];
        $time_order_id = explode('-',  $tr_ord_id);
        $order_id = $time_order_id[1];
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        if($order_info){

          if($payment_status == "Credit"){
              $this->logger->write("Payment for $payment_id was credited.");
              $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('instamojo_order_status_id'), "Payment successful for instamojo payment ID: $payment_id", true);
              $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
            }
            else if($payment_status == "Failed"){
              $this->logger->write("Payment for $payment_id failed.");
              $this->model_checkout_order->addOrderHistory($order_id, 10, "Payment failed for instamojo payment ID: $payment_id", true);
              $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));

            }
            else if($payment_status == "Initiated"){
              $this->logger->write("Payment for $payment_id failed(initiated).");
              $this->model_checkout_order->addOrderHistory($order_id, 10, "Payment initiated for instamojo payment ID but never completed: $payment_id", true);
              $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
            }
        }

      }
      else{
        $this->logger->write("Payment for $payment_id was not credited.");
        $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
      } 
    } else {
      $this->logger->write("Callback called with no payment ID.");
      $this->response->redirect($this->config->get('config_url'));
    }
  
  }


  private function _getcurlInfo($payment_id){

    $this->logger->write("Get the required field for payment ID: $payment_id");
    $cUrl = 'https://www.instamojo.com/api/1.1/payments/' . $payment_id . '/';
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
