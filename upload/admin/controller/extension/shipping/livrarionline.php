<?php
class ControllerExtensionShippingLivrariOnline extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('extension/shipping/livrarionline');
			
		$this->document->setTitle($this->language->get('heading_title'));


		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('livrarionline', $this->request->post);
			$this->model_setting_setting->editSetting('shipping_livrarionline', array(
				'shipping_livrarionline_status' => $this->request->post['livrarionline_status']
			));
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			require_once('../catalog/model/extension/shipping/lo/lib/lo.php');
			$lo = new LivrariOnline\LO();
			$lo->f_login = (int)$this->config->get('livrarionline_loginid');
			$lo->setRSAKey($this->config->get('livrarionline_key'));
			//$lo->run_lockers_update();
						
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['entry_securitate'] = $this->language->get('entry_securitate');
		$data['entry_services'] = $this->language->get('entry_services');
		$data['entry_puncte_ridicare'] = $this->language->get('entry_puncte_ridicare');
		$data['entry_delete_service'] = $this->language->get('entry_delete_service');
		$data['entry_delete_punct_ridicare'] = $this->language->get('entry_delete_punct_ridicare');
		$data['entry_add_service'] = $this->language->get('entry_add_service');
		$data['entry_add_punct_ridicare'] = $this->language->get('entry_add_punct_ridicare');
		$data['entry_parametri_national'] = $this->language->get('entry_parametri_national');
		$data['entry_parametri_pachetomat'] = $this->language->get('entry_parametri_pachetomat');
		$data['entry_add_pachetomat'] = $this->language->get('entry_add_pachetomat');
		$data['entry_punct_ridicare'] = $this->language->get('entry_punct_ridicare');
		$data['entry_denumire_punct_ridicare'] = $this->language->get('entry_denumire_punct_ridicare');

		$data['entry_email_punct_ridicare'] = $this->language->get('entry_email_punct_ridicare');
		$data['entry_nume_punct_ridicare'] = $this->language->get('entry_nume_punct_ridicare');
		$data['entry_prenume_punct_ridicare'] = $this->language->get('entry_prenume_punct_ridicare');
		$data['entry_telefon_punct_ridicare'] = $this->language->get('entry_telefon_punct_ridicare');
		$data['entry_telefon_mobil_punct_ridicare'] = $this->language->get('entry_telefon_mobil_punct_ridicare');
		$data['entry_adresa_punct_ridicare'] = $this->language->get('entry_adresa_punct_ridicare');
		$data['entry_oras_punct_ridicare'] = $this->language->get('entry_oras_punct_ridicare');
		$data['entry_zipcode_punct_ridicare'] = $this->language->get('entry_zipcode_punct_ridicare');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_loginid'] = $this->language->get('entry_loginid');
        $data['entry_key'] = $this->language->get('entry_key');
        $data['entry_ramburs'] = $this->language->get('entry_ramburs');
        $data['entry_ramburs_cash'] = $this->language->get('entry_ramburs_cash');
        $data['entry_ramburs_banca'] = $this->language->get('entry_ramburs_banca');
        $data['entry_gmaps_key'] = $this->language->get('entry_gmaps_key');
        $data['entry_use_thermo'] = $this->language->get('entry_use_thermo');
        $data['entry_statusawb'] = $this->language->get('entry_statusawb');
        $data['entry_statusawb_select'] = $this->language->get('entry_statusawb_select');
        $data['entry_national'] = $this->language->get('entry_national');
		$data['entry_pachetomat'] = $this->language->get('entry_pachetomat');
		$data['entry_denumire_serviciu'] = $this->language->get('entry_denumire_serviciu');
		$data['entry_id_serviciu'] = $this->language->get('entry_id_serviciu');
		$data['entry_id_shipping_company'] = $this->language->get('entry_id_shipping_company');
		$data['entry_county'] = $this->language->get('entry_county');
		$data['entry_county_select'] = $this->language->get('entry_county_select');

		$data['entry_pret_fix_livrare'] = $this->language->get('entry_pret_fix_livrare');
		$data['entry_reducere'] = $this->language->get('entry_reducere');
		$data['entry_reducere_v'] = $this->language->get('entry_reducere_v');
		$data['entry_reducere_p'] = $this->language->get('entry_reducere_p');
		$data['entry_gratuit_peste'] = $this->language->get('entry_gratuit_peste');
		$data['entry_denumire_pret_fix_livrare'] = $this->language->get('entry_denumire_pret_fix_livrare');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['loginid'])) {
			$data['error_loginid'] = $this->error['loginid'];
		} else {
			$data['error_loginid'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['ramburs'])) {
			$data['error_ramburs'] = $this->error['ramburs'];
		} else {
			$data['error_ramburs'] = '';
		}

		if (isset($this->error['gmaps_key'])) {
			$data['error_gmaps_key'] = $this->error['gmaps_key'];
		} else {
			$data['error_gmaps_key'] = '';
		}

		if (isset($this->error['statusawb'])) {
			$data['error_statusawb'] = $this->error['statusawb'];
		} else {
			$data['error_statusawb'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/shipping/livrarionline', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/shipping/livrarionline', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['livrarionline_status'])) {
			$data['livrarionline_status'] = $this->request->post['livrarionline_status'];
		} else {
			$data['livrarionline_status'] = $this->config->get('livrarionline_status');
		}
		if (empty($data['livrarionline_status'])) {
			$this->install();
		}

		if (isset($this->request->post['livrarionline_loginid'])) {
			$data['livrarionline_loginid'] = $this->request->post['livrarionline_loginid'];
		} else {
			$data['livrarionline_loginid'] = $this->config->get('livrarionline_loginid');
		}
		
		if (isset($this->request->post['livrarionline_key'])) {
			$data['livrarionline_key'] = $this->request->post['livrarionline_key'];
		} else {
			$data['livrarionline_key'] = $this->config->get('livrarionline_key');
		}
		
		if (isset($this->request->post['livrarionline_ramburs'])) {
			$data['livrarionline_ramburs'] = $this->request->post['livrarionline_ramburs'];
		} else {
			$data['livrarionline_ramburs'] = $this->config->get('livrarionline_ramburs');
		}
		
		if (isset($this->request->post['livrarionline_gmaps_key'])) {
			$data['livrarionline_gmaps_key'] = $this->request->post['livrarionline_gmaps_key'];
		} else {
			$data['livrarionline_gmaps_key'] = $this->config->get('livrarionline_gmaps_key');
		}
		
		if (isset($this->request->post['livrarionline_use_thermo'])) {
			$data['livrarionline_use_thermo'] = $this->request->post['livrarionline_use_thermo'];
		} else {
			$data['livrarionline_use_thermo'] = $this->config->get('livrarionline_use_thermo');
		}

		if (isset($this->request->post['livrarionline_statusawb'])) {
			$data['livrarionline_statusawb'] = $this->request->post['livrarionline_statusawb'];
		} else {
			$data['livrarionline_statusawb'] = $this->config->get('livrarionline_statusawb');
		}

		//national
		if (isset($this->request->post['livrarionline_denumire_serviciu_national'])) {
			$data['livrarionline_denumire_serviciu_national'] = $this->request->post['livrarionline_denumire_serviciu_national'];
		} else {
			$data['livrarionline_denumire_serviciu_national'] = $this->config->get('livrarionline_denumire_serviciu_national');
		}

		if (isset($this->request->post['livrarionline_id_serviciu_national'])) {
			$data['livrarionline_id_serviciu_national'] = $this->request->post['livrarionline_id_serviciu_national'];
		} else {
			$data['livrarionline_id_serviciu_national'] = $this->config->get('livrarionline_id_serviciu_national');
		}

		if (isset($this->request->post['livrarionline_id_shipping_company_national'])) {
			$data['livrarionline_id_shipping_company_national'] = $this->request->post['livrarionline_id_shipping_company_national'];
		} else {
			$data['livrarionline_id_shipping_company_national'] = $this->config->get('livrarionline_id_shipping_company_national');
		}
		
		if (isset($this->request->post['livrarionline_pretfix_national'])) {
			$data['livrarionline_pretfix_national'] = $this->request->post['livrarionline_pretfix_national'];
		} else {
			$data['livrarionline_pretfix_national'] = $this->config->get('livrarionline_pretfix_national');
		}
		
		if (isset($this->request->post['livrarionline_semn_reducere_national'])) {
			$data['livrarionline_semn_reducere_national'] = $this->request->post['livrarionline_semn_reducere_national'];
		} else {
			$data['livrarionline_semn_reducere_national'] = $this->config->get('livrarionline_semn_reducere_national');
		}
		
		if (isset($this->request->post['livrarionline_reducere_national'])) {
			$data['livrarionline_reducere_national'] = $this->request->post['livrarionline_reducere_national'];
		} else {
			$data['livrarionline_reducere_national'] = $this->config->get('livrarionline_reducere_national');
		}
		
		if (isset($this->request->post['livrarionline_tip_reducere_national'])) {
			$data['livrarionline_tip_reducere_national'] = $this->request->post['livrarionline_tip_reducere_national'];
		} else {
			$data['livrarionline_tip_reducere_national'] = $this->config->get('livrarionline_tip_reducere_national');
		}
		
		if (isset($this->request->post['livrarionline_gratuit_peste_national'])) {
			$data['livrarionline_gratuit_peste_national'] = $this->request->post['livrarionline_gratuit_peste_national'];
		} else {
			$data['livrarionline_gratuit_peste_national'] = $this->config->get('livrarionline_gratuit_peste_national');
		}

		if (isset($this->request->post['livrarionline_denumire_pretfix_national'])) {
			$data['livrarionline_denumire_pretfix_national'] = $this->request->post['livrarionline_denumire_pretfix_national'];
		} else {
			$data['livrarionline_denumire_pretfix_national'] = $this->config->get('livrarionline_denumire_pretfix_national');
		}

		//pachetomate
		if (isset($this->request->post['livrarionline_denumire_serviciu_pachetomat'])) {
			$data['livrarionline_denumire_serviciu_pachetomat'] = $this->request->post['livrarionline_denumire_serviciu_pachetomat'];
		} else {
			$data['livrarionline_denumire_serviciu_pachetomat'] = $this->config->get('livrarionline_denumire_serviciu_pachetomat');
		}

		if (isset($this->request->post['livrarionline_id_serviciu_pachetomat'])) {
			$data['livrarionline_id_serviciu_pachetomat'] = $this->request->post['livrarionline_id_serviciu_pachetomat'];
		} else {
			$data['livrarionline_id_serviciu_pachetomat'] = $this->config->get('livrarionline_id_serviciu_pachetomat');
		}

		if (isset($this->request->post['livrarionline_id_shipping_company_pachetomat'])) {
			$data['livrarionline_id_shipping_company_pachetomat'] = $this->request->post['livrarionline_id_shipping_company_pachetomat'];
		} else {
			$data['livrarionline_id_shipping_company_pachetomat'] = $this->config->get('livrarionline_id_shipping_company_pachetomat');
		}

		if (isset($this->request->post['livrarionline_pretfix_pachetomat'])) {
			$data['livrarionline_pretfix_pachetomat'] = $this->request->post['livrarionline_pretfix_pachetomat'];
		} else {
			$data['livrarionline_pretfix_pachetomat'] = $this->config->get('livrarionline_pretfix_pachetomat');
		}

		if (isset($this->request->post['livrarionline_semn_reducere_pachetomat'])) {
			$data['livrarionline_semn_reducere_pachetomat'] = $this->request->post['livrarionline_semn_reducere_pachetomat'];
		} else {
			$data['livrarionline_semn_reducere_pachetomat'] = $this->config->get('livrarionline_semn_reducere_pachetomat');
		}

		if (isset($this->request->post['livrarionline_reducere_pachetomat'])) {
			$data['livrarionline_reducere_pachetomat'] = $this->request->post['livrarionline_reducere_pachetomat'];
		} else {
			$data['livrarionline_reducere_pachetomat'] = $this->config->get('livrarionline_reducere_pachetomat');
		}

		if (isset($this->request->post['livrarionline_tip_reducere_pachetomat'])) {
			$data['livrarionline_tip_reducere_pachetomat'] = $this->request->post['livrarionline_tip_reducere_pachetomat'];
		} else {
			$data['livrarionline_tip_reducere_pachetomat'] = $this->config->get('livrarionline_tip_reducere_pachetomat');
		}

		if (isset($this->request->post['livrarionline_gratuit_peste_pachetomat'])) {
			$data['livrarionline_gratuit_peste_pachetomat'] = $this->request->post['livrarionline_gratuit_peste_pachetomat'];
		} else {
			$data['livrarionline_gratuit_peste_pachetomat'] = $this->config->get('livrarionline_gratuit_peste_pachetomat');
		}

		if (isset($this->request->post['livrarionline_denumire_pretfix_pachetomat'])) {
			$data['livrarionline_denumire_pretfix_pachetomat'] = $this->request->post['livrarionline_denumire_pretfix_pachetomat'];
		} else {
			$data['livrarionline_denumire_pretfix_pachetomat'] = $this->config->get('livrarionline_denumire_pretfix_pachetomat');
		}

		//puncte de ridicare
		if (isset($this->request->post['livrarionline_denumire_punct_ridicare'])) {
			$data['livrarionline_denumire_punct_ridicare'] = $this->request->post['livrarionline_denumire_punct_ridicare'];
		} else {
			$data['livrarionline_denumire_punct_ridicare'] = $this->config->get('livrarionline_denumire_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_email_punct_ridicare'])) {
			$data['livrarionline_email_punct_ridicare'] = $this->request->post['livrarionline_email_punct_ridicare'];
		} else {
			$data['livrarionline_email_punct_ridicare'] = $this->config->get('livrarionline_email_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_nume_punct_ridicare'])) {
			$data['livrarionline_nume_punct_ridicare'] = $this->request->post['livrarionline_nume_punct_ridicare'];
		} else {
			$data['livrarionline_nume_punct_ridicare'] = $this->config->get('livrarionline_nume_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_prenume_punct_ridicare'])) {
			$data['livrarionline_prenume_punct_ridicare'] = $this->request->post['livrarionline_prenume_punct_ridicare'];
		} else {
			$data['livrarionline_prenume_punct_ridicare'] = $this->config->get('livrarionline_prenume_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_telefon_punct_ridicare'])) {
			$data['livrarionline_telefon_punct_ridicare'] = $this->request->post['livrarionline_telefon_punct_ridicare'];
		} else {
			$data['livrarionline_telefon_punct_ridicare'] = $this->config->get('livrarionline_telefon_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_telefon_mobil_punct_ridicare'])) {
			$data['livrarionline_telefon_mobil_punct_ridicare'] = $this->request->post['livrarionline_telefon_mobil_punct_ridicare'];
		} else {
			$data['livrarionline_telefon_mobil_punct_ridicare'] = $this->config->get('livrarionline_telefon_mobil_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_adresa_punct_ridicare'])) {
			$data['livrarionline_adresa_punct_ridicare'] = $this->request->post['livrarionline_adresa_punct_ridicare'];
		} else {
			$data['livrarionline_adresa_punct_ridicare'] = $this->config->get('livrarionline_adresa_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_oras_punct_ridicare'])) {
			$data['livrarionline_oras_punct_ridicare'] = $this->request->post['livrarionline_oras_punct_ridicare'];
		} else {
			$data['livrarionline_oras_punct_ridicare'] = $this->config->get('livrarionline_oras_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_judet_punct_ridicare'])) {
			$data['livrarionline_judet_punct_ridicare'] = $this->request->post['livrarionline_judet_punct_ridicare'];
		} else {
			$data['livrarionline_judet_punct_ridicare'] = $this->config->get('livrarionline_judet_punct_ridicare');
		}
		if (isset($this->request->post['livrarionline_zipcode_punct_ridicare'])) {
			$data['livrarionline_zipcode_punct_ridicare'] = $this->request->post['livrarionline_zipcode_punct_ridicare'];
		} else {
			$data['livrarionline_zipcode_punct_ridicare'] = $this->config->get('livrarionline_zipcode_punct_ridicare');
		}

		$this->load->model('localisation/zone');
		$data['judete'] = $this->model_localisation_zone->getZonesByCountryId(175);

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['currency_code'] = $this->config->get('config_currency');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->config->set('template_engine', 'template');
		$this->response->setOutput($this->load->view('extension/shipping/livrarionline', $data));
	}
	
	public function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/livrarionline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['livrarionline_loginid']) {
			$this->error['loginid'] = $this->language->get('error_loginid');
		}

		if (!$this->request->post['livrarionline_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['livrarionline_ramburs']) {
			$this->error['key'] = $this->language->get('error_ramburs');
		}

		if (!$this->request->post['livrarionline_gmaps_key']) {
			$this->error['key'] = $this->language->get('error_gmaps_key');
		}

		if (!$this->request->post['livrarionline_statusawb']) {
			$this->error['statusawb'] = $this->language->get('error_statusawb');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "livrarionline` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `order_id` int(11) NOT NULL,
          `awb` varchar(50) NOT NULL,
          `date_added` datetime NOT NULL,
          `serviciu` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
		)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `lo_delivery_points` (
			`dp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`dp_denumire` varchar(255) NOT NULL,
			`dp_adresa` varchar(255) NOT NULL,
			`dp_judet` varchar(50) NOT NULL,
			`dp_oras` varchar(50) NOT NULL,
			`dp_tara` varchar(255) NOT NULL,
			`dp_cod_postal` varchar(255) NOT NULL,
			`dp_gps_lat` double NOT NULL,
			`dp_gps_long` double NOT NULL,
			`dp_tip` int(11) NOT NULL,
			`dp_active` tinyint(1) NOT NULL DEFAULT '0',
			`version_id` int(11) NOT NULL,
			`stamp_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`dp_temperatura` decimal(10,2) DEFAULT NULL,
			`dp_indicatii` text CHARACTER SET utf8,
			`termosensibil` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`dp_id`)
		) AUTO_INCREMENT=1");
		  
		$this->db->query("CREATE TABLE IF NOT EXISTS `lo_dp_day_exceptions` (
			`leg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`dp_id` int(10) unsigned NOT NULL,
			`exception_day` date NOT NULL,
			`dp_start_program` time NOT NULL DEFAULT '00:00:00',
			`dp_end_program` time NOT NULL DEFAULT '00:00:00',
			`active` tinyint(1) NOT NULL,
			`version_id` int(10) NOT NULL,
			`stamp_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`leg_id`),
			KEY `delivery_point` (`dp_id`,`exception_day`)
		) AUTO_INCREMENT=1");
		  
		$this->db->query("CREATE TABLE IF NOT EXISTS `lo_dp_program` (
			`leg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`dp_start_program` time NOT NULL DEFAULT '00:00:00',
			`dp_end_program` time NOT NULL DEFAULT '00:00:00',
			`dp_id` int(10) unsigned NOT NULL,
			`day_active` tinyint(1) NOT NULL,
			`version_id` int(10) NOT NULL,
			`day_number` int(11) NOT NULL,
			`day` varchar(50) NOT NULL,
			`day_sort_order` int(1) NOT NULL,
			`stamp_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`leg_id`),
			KEY `delivery_point` (`dp_id`,`day`(1))
		) AUTO_INCREMENT=1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `lo_awb` (
			`id` int(9) NOT NULL AUTO_INCREMENT,
			`awb` varchar(50) NOT NULL,
			`id_comanda` int(11) unsigned NOT NULL,
			`id_serviciu` int(11) NOT NULL,
			`deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`generat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			UNIQUE KEY `unique` (`awb`,`id_comanda`),
			KEY `id_comanda` (`id_comanda`,`deleted`)
		 ) AUTO_INCREMENT=1");

		$this->db->query("DROP TRIGGER IF EXISTS lo_dp_program_BEFORE_INSERT");
		$this->db->query("DROP TRIGGER IF EXISTS lo_dp_program_BEFORE_UPDATE");
		
		$this->db->query("CREATE TRIGGER lo_dp_program_BEFORE_INSERT BEFORE INSERT ON lo_dp_program FOR EACH ROW
		  SET new.`day_sort_order` =
		  CASE
			WHEN (new.`day_number` = 1) THEN 1
			WHEN (new.`day_number` = 2) THEN 2
			WHEN (new.`day_number` = 3) THEN 3
			WHEN (new.`day_number` = 4) THEN 4
			WHEN (new.`day_number` = 5) THEN 5
			WHEN (new.`day_number` = 6) THEN 6
			WHEN (new.`day_number` = 0) THEN 7
		END");
		
		$this->db->query("CREATE TRIGGER lo_dp_program_BEFORE_UPDATE BEFORE UPDATE ON lo_dp_program FOR EACH ROW
		  SET new.`day_sort_order` =
		  CASE
			WHEN (new.`day_number` = 1) THEN 1
			WHEN (new.`day_number` = 2) THEN 2
			WHEN (new.`day_number` = 3) THEN 3
			WHEN (new.`day_number` = 4) THEN 4
			WHEN (new.`day_number` = 5) THEN 5
			WHEN (new.`day_number` = 6) THEN 6
			WHEN (new.`day_number` = 0) THEN 7
		END");
	}

	public function getAWB()
	{
		$post = $_POST;

		require_once('../catalog/model/extension/shipping/lo/lib/lo.php');

		$lo = new LivrariOnline\LO();

		$f_request_awb = array();

		//preiau serviciul selectat. Vine de forma 1|2|Denumire, unde 1 este serviciuid si 2 este shipping_company_id, Denumire = denumirea serviciului prin care se trimite coletul
		$serviciu = $post['serviciuid'];
		$serviciu = explode('|',$serviciu);

		$storeid = $post['shops'];

		$f_request_awb['serviciuid'] 						= (int)$serviciu[0];
		$f_request_awb['f_shipping_company_id'] 			= (int)$serviciu[1];
		$f_request_awb['request_data_ridicare'] 			= $post['request_data_ridicare'];
		$f_request_awb['request_ora_ridicare'] 				= $post['request_ora_ridicare'];
		$f_request_awb['request_ora_ridicare_end'] 			= $post['request_ora_ridicare_end'];
		$f_request_awb['request_ora_livrare_sambata'] 		= $post['request_ora_livrare_sambata'];
		$f_request_awb['request_ora_livrare_end_sambata'] 	= $post['request_ora_livrare_end_sambata'];
		$f_request_awb['request_ora_livrare'] 				= $post['request_ora_livrare'];
		$f_request_awb['request_ora_livrare_end'] 			= $post['request_ora_livrare_end'];
		$f_request_awb['descriere_livrare'] 				= $post['descriere_livrare'];
		$f_request_awb['referinta_expeditor'] 				= $post['referinta_expeditor'];
		$f_request_awb['valoare_declarata']					= (float)$post['valoare_declarata'];
		$f_request_awb['ramburs'] 							= (float)$post['ramburs'];
		$f_request_awb['asigurare_la_valoarea_declarata'] 	= $lo->checkboxSelected(isset($post['asigurare_la_valoarea_declarata'])?$post['asigurare_la_valoarea_declarata']:0);
		$f_request_awb['retur_documente'] 					= $lo->checkboxSelected(isset($post['retur_documente'])?$post['retur_documente']:0);
		$f_request_awb['retur_documente_bancare'] 			= $lo->checkboxSelected(isset($post['retur_documente_bancare'])?$post['retur_documente_bancare']:0);
		$f_request_awb['confirmare_livrare'] 				= $lo->checkboxSelected(isset($post['confirmare_livrare'])?$post['confirmare_livrare']:0);
		$f_request_awb['livrare_sambata'] 					= $lo->checkboxSelected(isset($post['livrare_sambata'])?$post['livrare_sambata']:0);
		$f_request_awb['currency'] 							= $post['currency'];
		$f_request_awb['currency_ramburs'] 					= $post['currency_ramburs'];
		$f_request_awb['notificare_email'] 					= $lo->checkboxSelected(isset($post['notificare_email'])?$post['notificare_email']:0);
		$f_request_awb['notificare_sms'] 					= $lo->checkboxSelected(isset($post['notificare_sms'])?$post['notificare_sms']:0);
		$f_request_awb['cine_plateste'] 					= (int)$post['cine_plateste'];
		$f_request_awb['serviciuid'] 						= (int)$post['serviciuid'];
		$f_request_awb['request_mpod'] 						= $lo->checkboxSelected(isset($post['request_mpod'])?$post['request_mpod']:0);

		$denumire_punct_ridicare = $this->config->get('livrarionline_denumire_punct_ridicare');

		$pr_email = $this->config->get('livrarionline_email_punct_ridicare');
		$pr_first_name = $this->config->get('livrarionline_prenume_punct_ridicare');
		$pr_last_name = $this->config->get('livrarionline_nume_punct_ridicare');
		$pr_mobile = $this->config->get('livrarionline_telefon_mobil_punct_ridicare');
		$pr_main_address = $this->config->get('livrarionline_adresa_punct_ridicare');
		$pr_city = $this->config->get('livrarionline_oras_punct_ridicare');
		$pr_state = $this->config->get('livrarionline_judet_punct_ridicare');
		$pr_zip = $this->config->get('livrarionline_zipcode_punct_ridicare');
		$pr_phone = $this->config->get('livrarionline_telefon_punct_ridicare');

		$poz=-1;
		for ($i=0;$i<count($denumire_punct_ridicare);$i++)
		{
			if ($denumire_punct_ridicare[$i] == $storeid)
			{
				$poz = $i;
				break;
			}
		}

		$this->load->model('localisation/zone');
		$county = $this->model_localisation_zone->getZone($pr_state[$poz]);
		$county = $county['name'];

		$f_request_awb['shipFROMaddress'] = array(
		                                            'email'                 => $pr_email[$poz],
		                                            'first_name'            => $pr_first_name[$poz],
		                                            'last_name'             => $pr_last_name[$poz],              
		                                            'mobile'                => $pr_mobile[$poz],
		                                            'main_address'          => $pr_main_address[$poz],
		                                            'city'                  => $pr_city[$poz],
		                                            'state'                 => $county,
		                                            'zip'                   => $pr_zip[$poz],
		                                            'country'               => 'Romania',
		                                            'phone'                 => $pr_phone[$poz],
		                                            'instructiuni'          => ''
		                                        );


		$f_request_post = json_decode(base64_decode($post['f_request_awb']), true);

		$colete = array();

		for ($i=0;$i<count($post['tipcolet']);$i++)
		{
			$colete[] = array(
								'greutate'	=>	(float)$post['greutate'][$i],
								'lungime'	=>	(float)$post['lungime'] [$i],
								'latime'	=>	(float)$post['latime']  [$i],
								'inaltime'	=>	(float)$post['inaltime'][$i],
								'continut'	=>	(int)$post['continut'][$i],
								'tipcolet'	=>	(int)$post['tipcolet'][$i]
							);
		}

		foreach ($f_request_post as $p => $value) {
			$f_request_awb[$p] = $value;
		}

		$f_request_awb['colete'] = $colete;

		//f_login si RSA key vor fi setate in config
		$lo->f_login = (int)$this->config->get('livrarionline_loginid');
		$lo->setRSAKey($this->config->get('livrarionline_key'));

		//generare AWB
		$shipping_code = $this->db->query("SELECT shipping_code FROM " . DB_PREFIX . "order WHERE order_id = " . $post['orders_id'])->row['shipping_code'];
		if (strpos($shipping_code, 'livrarionline.') === 0 && strpos($shipping_code, '.p.') !== false) {
			$delivery_point_id = substr($shipping_code, strrpos($shipping_code, '.') + 1);
			$response_awb = $lo->GenerateAwbSmartloker($f_request_awb, $delivery_point_id, 1, $post['orders_id']);
		} else {
			$response_awb = $lo->GenerateAwb($f_request_awb);
		}

		//raspuns generare AWB
		if (isset($response_awb->status) && !empty($response_awb->status) && ($response_awb->status == 'error' || !isset($response_awb->f_awb_collection[0])))
			echo $response_awb->message;
		else
		{
			$raspuns = '<p>Coletul trimis prin serviciul <b>'.$serviciu[2].'</b> a primit AWB nr. <b>'.$response_awb->f_awb_collection[0].'</b></p>';

			$this->db->query('INSERT INTO ' . DB_PREFIX . 'livrarionline(order_id,awb,date_added,serviciu) values("'.$post['orders_id'].'","'.$response_awb->f_awb_collection[0].'","'.date('Y-m-d H:i:s').'","'.$serviciu[2].'")');


			$raspuns .= '<form name="form-tracking-awb" id="form-tracking-awb" method="post" action="index.php?route=shipping/livrarionline/trackingAWB&user_token=' . $this->session->data['user_token'] . '">
							<input type="hidden" name="tawb" id="tawb" value="'.$response_awb->f_awb_collection[0].'"/>
							<input type="hidden" name="f_login" value="'.$this->config->get('livrarionline_loginid').'" />
							<input type="submit" id="tracking-awb" value="Tracking AWB"/>
						</form>';

			$raspuns .= '<form name="form-cancel-awb" id="form-cancel-awb" method="post" action="index.php?route=shipping/livrarionline/cancelAWB&user_token=' . $this->session->data['user_token'] . '">
							<input type="hidden" name="cawb" id="cawb" value="'.$response_awb->f_awb_collection[0].'"/>
							<input type="hidden" name="f_login" value="'.$this->config->get('livrarionline_loginid').'"/>
							<input type="submit" id="cancel-awb" value="Cancel AWB"/>
						</form>';
			$f_request_print = array('awb'=>$response_awb->f_awb_collection[0]); 
			$raspuns .= $lo->PrintAwb($f_request_print,'','');

			echo $raspuns;
		}

	}

	public function trackingAWB()
	{
		require_once('../catalog/model/extension/shipping/lo/lib/lo.php');

		$rsakey = $this->config->get('livrarionline_key');

		$post = $_POST;

		$lo = new LivrariOnline\LO();

		$f_request_tracking = array();

		$f_request_tracking['awb'] 			= trim($post['tawb']);

		$lo->f_login = $post['f_login'];
		$lo->setRSAKey($rsakey);

		//tracking
		$response_tracking = $lo->Tracking($f_request_tracking);

		//raspuns TRACKING
		if ( isset($response_tracking->status) && !empty($response_tracking->status) && $response_tracking->status == 'error')
			echo $response_tracking->message;
		else {
			$stare_curenta 	= $response_tracking->f_stare_curenta;
			$istoric 		= $response_tracking->f_istoric;
			$raspuns  = '<h3>Tracking AWB</h3>';
			$raspuns .= '<div><span>'.date('d-m-Y H:i:s',strtotime($stare_curenta->stamp)).'</span> - <span>'.$stare_curenta->stare.'</span></div>';
			foreach ($istoric as $is) {
				$raspuns .= '<div><span>'.date('d-m-Y H:i:s',strtotime($is->stamp)).'</span> - <span>'.$is->stare.'</span></div>';
			}
			echo $raspuns;
		}
	}

	public function cancelAWB()
	{
		require_once('../catalog/model/extension/shipping/lo/lib/lo.php');

		$rsakey = $this->config->get('livrarionline_key');

		$post = $_POST;

		$lo = new LivrariOnline\LO();

		$f_request_cancel = array();

		$f_request_cancel['awb'] = trim($post['cawb']);

		$lo->f_login = (int)$post['f_login'];
		$lo->setRSAKey($rsakey);

		$response_cancel = $lo->CancelLivrare($f_request_cancel);

		//raspuns CANCEL LIVRARE
		if ( isset($response_cancel->status) && !empty($response_cancel->status) && $response_cancel->status == 'error')
			echo $response_cancel->message;
		else {
			if ($response_cancel->status=="success")
			{
				$stergere = $this->db->query('DELETE FROM ' . DB_PREFIX . 'livrarionline WHERE awb="'.$f_request_cancel['awb'].'"');

				if (!$stergere)
			    	die('Eroare stergere din baza de date');
			}
			echo $response_cancel->status;
		}
	}
}
?>