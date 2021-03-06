<?php

class Controllerextensionpaymentxhopenalipay extends Controller
{

    private $error = array();
    private function _redirect($url, $status = 302) {
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
        exit();
    }
   
    public function index()
    {
        $this->load->language('payment/xhopenalipay');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('xhopenalipay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
          
           $url =$this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');
         
             $this->_redirect($url);
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        
        $data['entry_account'] = $this->language->get('entry_account');
        $data['entry_secret'] = $this->language->get('entry_secret');
        $data['entry_transaction_url'] = $this->language->get('entry_transaction_url');
        $data['entry_rate'] = $this->language->get('entry_rate');
        
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_succeed_status'] = $this->language->get('entry_order_succeed_status');
        $data['entry_order_payWait_status_id'] = $this->language->get('entry_order_payWait_status_id');
        $data['entry_order_failed_status'] = $this->language->get('entry_order_failed_status');
       
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order'); 
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');      
        $data['tab_general'] = $this->language->get('tab_general');  
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['account'])) {
            $data['error_account'] = $this->error['account'];
        } else {
            $data['error_account'] = '';
        }
        
        if (isset($this->error['secret'])) {
            $data['error_secret'] = $this->error['secret'];
        } else {
            $data['error_secret'] = '';
        }
        
        if (isset($this->error['transaction_url'])) {
            $data['error_transaction_url'] = $this->error['transaction_url'];
        } else {
            $data['error_transaction_url'] = '';
        }
        
        if (isset($this->error['rate'])) {
            $data['error_rate'] = $this->error['rate'];
        } else {
            $data['error_rate'] = '';
        }
       
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' =>false
        );
        
        $url_payment =$this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');
        
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $url_payment,
            'separator' => '::'
        );
   
       $url_payment= $this->url->link('extension/payment/xhopenalipay', 'token=' . $this->session->data['token'], 'SSL');
        
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' =>$url_payment,
            'separator' => '::'
        );
        
        $data['action'] = $this->url->link('extension/payment/xhopenalipay', 'token=' . $this->session->data['token'], 'SSL');  
        $data['cancel'] = $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');	

        // 接收商户号
        if (isset($this->request->post['xhopenalipay_account'])) {
            $data['xhopenalipay_account'] = $this->request->post['xhopenalipay_account'];
        } else {
            $data['xhopenalipay_account'] = $this->config->get('xhopenalipay_account');
        }
        
        if(empty($data['xhopenalipay_account'])){
            $data['xhopenalipay_account'] ='20146123713';
        }
        // 接收商户key
        if (isset($this->request->post['xhopenalipay_secret'])) {
            $data['xhopenalipay_secret'] = $this->request->post['xhopenalipay_secret'];
        } else {
            $data['xhopenalipay_secret'] = $this->config->get('xhopenalipay_secret');
        }
        if(empty($data['xhopenalipay_secret'])){
            $data['xhopenalipay_secret'] ='6D7B025B8DD098C485F0805193136FB9';
        }
        if (isset($this->request->post['xhopenalipay_transaction_url'])) {
            $data['xhopenalipay_transaction_url'] = $this->request->post['xhopenalipay_transaction_url'];
        } else {
            $data['xhopenalipay_transaction_url'] = $this->config->get('xhopenalipay_transaction_url');
        }
        if(empty($data['xhopenalipay_transaction_url'])){
            $data['xhopenalipay_transaction_url'] ='https://pay2.xunhupay.com/v2';
        }
        //rate
        if (isset($this->request->post['xhopenalipay_rate'])) {
            $data['xhopenalipay_rate'] = round(floatval($this->request->post['xhopenalipay_rate']),3);
        } else {
            $data['xhopenalipay_rate'] = $this->config->get('xhopenalipay_rate');
        }
  
        if (isset($this->request->post['xhopenalipay_order_status_id'])) {
            $data['xhopenalipay_order_status_id'] = $this->request->post['xhopenalipay_order_status_id'];
        } else {
            $data['xhopenalipay_order_status_id'] = $this->config->get('xhopenalipay_order_status_id');
        }
        
        if (isset($this->request->post['xhopenalipay_order_succeed_status_id'])) {
            $data['xhopenalipay_order_succeed_status_id'] = $this->request->post['xhopenalipay_order_succeed_status_id'];
        } else {
            $data['xhopenalipay_order_succeed_status_id'] = $this->config->get('xhopenalipay_order_succeed_status_id');
        }
        
        if (isset($this->request->post['xhopenalipay_order_failed_status_id'])) {
            $data['xhopenalipay_order_failed_status_id'] = $this->request->post['xhopenalipay_order_failed_status_id'];
        } else {
            $data['xhopenalipay_order_failed_status_id'] = $this->config->get('xhopenalipay_order_failed_status_id');
        }
        
        if (isset($this->request->post['xhopenalipay_order_payWait_status_id'])) {
            $data['xhopenalipay_order_payWait_status_id'] = $this->request->post['xhopenalipay_order_payWait_status_id'];
        } else {
            $data['xhopenalipay_order_payWait_status_id'] = $this->config->get('xhopenalipay_order_payWait_status_id');
        }
        
        $this->load->model('localisation/order_status');
        
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
        if (isset($this->request->post['xhopenalipay_geo_zone_id'])) {
            $data['xhopenalipay_geo_zone_id'] = $this->request->post['xhopenalipay_geo_zone_id'];
        } else {
            $data['xhopenalipay_geo_zone_id'] = $this->config->get('xhopenalipay_geo_zone_id');
        }
        
        $this->load->model('localisation/geo_zone');
        
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
        if (isset($this->request->post['xhopenalipay_status'])) {
            $data['xhopenalipay_status'] = $this->request->post['xhopenalipay_status'];
        } else {
            $data['xhopenalipay_status'] = $this->config->get('xhopenalipay_status');
        }
        
        if (isset($this->request->post['xhopenalipay_sort_order'])) {
            $data['xhopenalipay_sort_order'] = $this->request->post['xhopenalipay_sort_order'];
        } else {
            $data['xhopenalipay_sort_order'] = $this->config->get('xhopenalipay_sort_order');
        }
        
        $data['lower_version']=false;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('payment/xhopenalipay.tpl', $data));
    }

    protected function validate()
    {
        if (! $this->user->hasPermission('modify', 'extension/payment/xhopenalipay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (! $this->request->post['xhopenalipay_account']) {
            $this->error['account'] = $this->language->get('error_account');
        }
        
        if (! $this->request->post['xhopenalipay_secret']) {
            $this->error['secret'] = $this->language->get('error_secret');
        }
        
        if (! $this->request->post['xhopenalipay_transaction_url']) {
            $this->error['transaction_url'] = $this->language->get('error_transaction_url');
        }
        
        if (! $this->request->post['xhopenalipay_rate']) {
            $this->error['rate'] = $this->language->get('error_rate');
        }
        return ! $this->error;
    }
}
?>