<?php
class ControllerPaymentInstamojo extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/instamojo');
    $this->document->setTitle('Instamojo Payment Method Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $this->model_setting_setting->editSetting('instamojo', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
 
    $data['heading_title'] = $this->language->get('heading_title');
    $data['entry_text_instamojo_checkout_label'] = $this->language->get('instamojo_checkout_label');
    $data['entry_text_instamojo_api_key'] = $this->language->get('instamojo_api_key');
    $data['entry_text_instamojo_auth_token'] = $this->language->get('instamojo_auth_token');
    $data['entry_text_instamojo_private_salt'] = $this->language->get('instamojo_private_salt');
    $data['entry_text_instamojo_payment_link'] = $this->language->get('instamojo_payment_link');
    $data['entry_text_instamojo_custom_field'] = $this->language->get('instamojo_custom_field');
    $data['button_save'] = $this->language->get('text_button_save');
    $data['button_cancel'] = $this->language->get('text_button_cancel');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_edit'] = $this->language->get('text_edit');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    
 
    $data['action'] = $this->url->link('payment/instamojo', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['instamojo_checkout_label'])) {
      $data['instamojo_checkout_label'] = $this->request->post['instamojo_checkout_label'];
    } else {
      $data['instamojo_checkout_label'] = $this->config->get('instamojo_checkout_label');
    }
 
    if (isset($this->request->post['instamojo_api_key'])) {
      $data['instamojo_api_key'] = $this->request->post['instamojo_api_key'];
    } else {
      $data['instamojo_api_key'] = $this->config->get('instamojo_api_key');
    }
        
    if (isset($this->request->post['instamojo_auth_token'])) {
      $data['instamojo_auth_token'] = $this->request->post['instamojo_auth_token'];
    } else {
      $data['instamojo_auth_token'] = $this->config->get('instamojo_auth_token');
    }

    if (isset($this->request->post['instamojo_private_salt'])) {
      $data['instamojo_private_salt'] = $this->request->post['instamojo_private_salt'];
    } else {
      $data['instamojo_private_salt'] = $this->config->get('instamojo_private_salt');
    }

    if (isset($this->request->post['instamojo_payment_link'])) {
      $data['instamojo_payment_link'] = $this->request->post['instamojo_payment_link'];
    } else {
      $data['instamojo_payment_link'] = $this->config->get('instamojo_payment_link');
    }

    if (isset($this->request->post['instamojo_custom_field'])) {
      $data['instamojo_custom_field'] = $this->request->post['instamojo_custom_field'];
    } else {
      $data['instamojo_custom_field'] = $this->config->get('instamojo_custom_field');
    }
            
    if (isset($this->request->post['instamojo_status'])) {
      $data['instamojo_status'] = $this->request->post['instamojo_status'];
    } else {
      $data['instamojo_status'] = $this->config->get('instamojo_status');
    }
        
    if (isset($this->request->post['instamojo_order_status_id'])) {
      $data['instamojo_order_status_id'] = $this->request->post['instamojo_order_status_id'];
    } else {
      $data['instamojo_order_status_id'] = $this->config->get('instamojo_order_status_id');
    }

    if (isset($this->request->post['instamojo_sort_order'])) {
      $data['instamojo_sort_order'] = $this->request->post['instamojo_sort_order'];
    } else {
      $data['instamojo_sort_order'] = $this->config->get('instamojo_sort_order');
    }
 
    $this->load->model('localisation/order_status');
    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            
    $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

    $data['breadcrumbs'][] = array(
      'text' => 'Payment',
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/instamojo', 'token=' . $this->session->data['token'], 'SSL')
    );


    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer'); 

    $this->response->setOutput($this->load->view('payment/instamojo.tpl', $data));
  }
}