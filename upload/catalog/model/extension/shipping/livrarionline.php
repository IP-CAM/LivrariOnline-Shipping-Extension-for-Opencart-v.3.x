<?php

class ModelExtensionShippingLivrariOnline extends Model
{

	public static function multidimensional_search($parents, $searched)
	{
		if (empty($searched) || empty($parents)) {
			return false;
		}

		$keys = array();

		foreach ($parents as $key => $value) {
			$exists = true;
			foreach ($searched as $skey => $svalue) {
				$exists = ($exists && isset($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
			}
			if ($exists) {
				$keys[] = $key;
			}
		}

		return $keys;
	}

	function getQuote($address)
	{
		$error = '';
		$quote_data = array();

		$currency = $this->config->get('config_currency');
		$loginid = $this->config->get('livrarionline_loginid');
		$key = $this->config->get('livrarionline_key');

		$total = $this->cart->getTotal();

		$colete = array();

		$greutate = 0;

		foreach ($this->cart->getProducts() as $product) {
			$greutate += ($product['weight'] ? round((float)$product['weight'], 2) : 0);
		}

		$colete[] = array(
			'greutate' => $greutate,
			'lungime'  => 1,
			'latime'   => 1,
			'inaltime' => 1,
			'continut' => 4,
			'tipcolet' => 2,
		);

		$f_request_awb = array();
		$f_request_awb['request_data_ridicare'] = date('Y-m-d');
		$f_request_awb['request_ora_ridicare'] = date('H:i:s');
		$f_request_awb['request_ora_ridicare_end'] = date('H:i:s');
		$f_request_awb['request_ora_livrare_sambata'] = date('H:i:s');
		$f_request_awb['request_ora_livrare_end_sambata'] = date('H:i:s');
		$f_request_awb['request_ora_livrare'] = date('H:i:s');
		$f_request_awb['request_ora_livrare_end'] = date('H:i:s');
		$f_request_awb['descriere_livrare'] = 'estimare pret ' . $this->config->get('config_name');
		$f_request_awb['referinta_expeditor'] = '';
		$f_request_awb['valoare_declarata'] = round((float)$total, 2);
		$f_request_awb['ramburs'] = round((float)$total, 2);
		$f_request_awb['asigurare_la_valoarea_declarata'] = false;
		$f_request_awb['retur_documente'] = false;
		$f_request_awb['retur_documente_bancare'] = false;
		$f_request_awb['confirmare_livrare'] = false;
		$f_request_awb['livrare_sambata'] = false;
		$f_request_awb['currency'] = $this->config->get('config_currency');
		$f_request_awb['currency_ramburs'] = $this->config->get('config_currency');
		$f_request_awb['notificare_email'] = false;
		$f_request_awb['notificare_sms'] = false;
		$f_request_awb['cine_plateste'] = 0;
		$f_request_awb['request_mpod'] = false;
		$f_request_awb['colete'] = $colete;

		if ($this->customer->isLogged()) {
			$email = $this->customer->getEmail();
			$phone = $this->customer->getTelephone();
		} else {
			if (!empty($this->session->data['guest'])) {
				$email = $this->session->data['guest']['email'];
				$phone = $this->session->data['guest']['telephone'];
			} else {
				$email = '';
				$phone = '';
			}
		}

		$f_request_awb['destinatar'] = array(
			'first_name'   => $address['firstname'],           //Obligatoriu
			'last_name'    => $address['lastname'],            //Obligatoriu
			'email'        => $email,     //Obligatoriu
			'phone'        => $phone, //phone sau mobile Obligatoriu
			'mobile'       => $phone,
			'lang'         => 'ro',                            //Obligatoriu ro/en
			'company_name' => $address['company'],             //optional
			'j'            => '',                              //optional
			'bank_account' => '',                              //optional
			'bank_name'    => '',                              //optional
			'cui'          => ''                               //optional
		);

		$f_request_awb['shipTOaddress'] = array(                        //Obligatoriu
		                                                                'address1'   => $address['address_1'],
		                                                                'address2'   => $address['address_2'],
		                                                                'city'       => $address['city'],
		                                                                'state'      => $address['zone'],
		                                                                'zip'        => $address['postcode'],
		                                                                'country'    => $address['country'],
		                                                                'phone'      => $phone,
		                                                                'observatii' => '',
		);

		$this->load->model('localisation/country');
		$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
		$country = $country['name'];
		$this->load->model('localisation/zone');

		$pr_email = $this->config->get('livrarionline_email_punct_ridicare');
		$pr_first_name = $this->config->get('livrarionline_prenume_punct_ridicare');
		$pr_last_name = $this->config->get('livrarionline_nume_punct_ridicare');
		$pr_mobile = $this->config->get('livrarionline_telefon_mobil_punct_ridicare');
		$pr_main_address = $this->config->get('livrarionline_adresa_punct_ridicare');
		$pr_city = $this->config->get('livrarionline_oras_punct_ridicare');
		$pr_state = $this->config->get('livrarionline_judet_punct_ridicare');
		$pr_zip = $this->config->get('livrarionline_zipcode_punct_ridicare');
		$pr_phone = $this->config->get('livrarionline_telefon_punct_ridicare');


		$county = $this->model_localisation_zone->getZone($pr_state[0]);
		$county = $county['name'];

		$f_request_awb['shipFROMaddress'] = array(
			'email'        => $pr_email[0],
			'first_name'   => $pr_first_name[0],
			'last_name'    => $pr_last_name[0],
			'mobile'       => $pr_mobile[0],
			'main_address' => $pr_main_address[0],
			'city'         => $pr_city[0],
			'state'        => $county,
			'zip'          => $pr_zip[0],
			'country'      => 'Romania',
			'phone'        => $pr_phone[0],
			'instructiuni' => '',
		);

		$denumiri_servicii_pachetomat = $this->config->get('livrarionline_denumire_serviciu_pachetomat');
		$id_servicii_pachetomat = $this->config->get('livrarionline_id_serviciu_pachetomat');
		$id_shipping_company_pachetomat = $this->config->get('livrarionline_id_shipping_company_pachetomat');
		$livrarionline_pretfix_pachetomat = $this->config->get('livrarionline_pretfix_pachetomat');
		$livrarionline_denumire_pretfix_pachetomat = $this->config->get('livrarionline_denumire_pretfix_pachetomat');
		$livrarionline_semn_reducere_pachetomat = $this->config->get('livrarionline_semn_reducere_pachetomat');
		$livrarionline_reducere_pachetomat = $this->config->get('livrarionline_reducere_pachetomat');
		$livrarionline_tip_reducere_pachetomat = $this->config->get('livrarionline_tip_reducere_pachetomat');
		$livrarionline_gratuit_peste_pachetomat = $this->config->get('livrarionline_gratuit_peste_pachetomat');

		$denumiri_servicii_national = $this->config->get('livrarionline_denumire_serviciu_national');
		$id_servicii_national = $this->config->get('livrarionline_id_serviciu_national');
		$id_shipping_company_national = $this->config->get('livrarionline_id_shipping_company_national');
		$livrarionline_pretfix_national = $this->config->get('livrarionline_pretfix_national');
		$livrarionline_denumire_pretfix_national = $this->config->get('livrarionline_denumire_pretfix_national');
		$livrarionline_semn_reducere_national = $this->config->get('livrarionline_semn_reducere_national');
		$livrarionline_reducere_national = $this->config->get('livrarionline_reducere_national');
		$livrarionline_tip_reducere_national = $this->config->get('livrarionline_tip_reducere_national');
		$livrarionline_gratuit_peste_national = $this->config->get('livrarionline_gratuit_peste_national');

		$judet_selectat = $address['zone_id'];

		$this->load->language('extension/shipping/livrarionline');

		require_once('lo/lib/lo.php');
		$lo = new LivrariOnline\LO();
		$lo->f_login = $loginid;
		$lo->setRSAKey($key);


		$f_request_awb['f_shipping_company_id'] = (int)$id_shipping_company_national[0];
		$preturi = $lo->estimeazaPretServicii($f_request_awb);
		$preturi = json_encode($preturi);
		$preturi = json_decode($preturi, true);

		$local = false;
		if (!empty($id_servicii_national)) {
			$matches = self::multidimensional_search($preturi, array('f_tip' => 'l'));
			if (!empty($matches)) {
				foreach ($matches as $key => $value) {
					if (in_array($preturi[$value]['f_serviciuid'], $id_servicii_national)) {
						foreach ($id_servicii_national as $k => $v) {
							if ($v == $preturi[$value]['f_serviciuid']) {
								$livrarionline_gratuit_peste_national = floatval($livrarionline_gratuit_peste_national[$k]);
								$livrarionline_reducere_national = floatval($livrarionline_reducere_national[$k]);
								if ($livrarionline_pretfix_national[$k]) {
									$livrarionline_pretfix_national = floatval($livrarionline_pretfix_national[$k]);
								}

								$price_standard = round((float)$preturi[$value]['f_pret'], 2);
								if ($livrarionline_gratuit_peste_national > 0 && $total >= $livrarionline_gratuit_peste_national) {
									$price_standard = 0;
								} else {

									if (!is_array($livrarionline_pretfix_national)) {
										$price_standard = (float)$livrarionline_pretfix_national;
									}

									if ($price_standard && $livrarionline_semn_reducere_national[$k] && $livrarionline_reducere_national && $livrarionline_tip_reducere_national[$k]) {
										if ($livrarionline_tip_reducere_national[$k] == 'V') {
											if ($livrarionline_semn_reducere_national[$k] == 'P') {
												$price_standard += $livrarionline_reducere_national;
											} else {
												$price_standard -= $livrarionline_reducere_national;
											}
										} else {
											if ($livrarionline_semn_reducere_national[$k] == 'P') {
												$price_standard += $price_standard * $livrarionline_reducere_national / 100;
											} else {
												$price_standard -= $price_standard * $livrarionline_reducere_national / 100;
											}
										}
										$price_standard = max(0, $price_standard);
									}
								}
								$quote_data[str_replace(" ", "_", $denumiri_servicii_national[$k])] = array(
									'code'         => 'livrarionline.' . str_replace(" ", "_", $denumiri_servicii_national[$k]),
									'title'        => $denumiri_servicii_national[$k],
									'cost'         => $this->currency->convert($price_standard, $currency, $this->config->get('config_currency')),
									'tax_class_id' => 'livrarionline.tax.' . str_replace(" ", "_", $denumiri_servicii_national[$k]),
									'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($price_standard, $currency, $this->config->get('config_currency')), $this->config->get('config_tax'), 1), $this->config->get('config_currency'), 1.0000000),
								);
								break;
							}
						}
						$local = true;
					}
				}

			}
		}

		$matches = self::multidimensional_search($preturi, array('f_tip' => 'n'));

		if ($local == false && !empty($matches)) {
			if (!empty($matches)) {
				foreach ($matches as $key => $value) {
					if (in_array($preturi[$value]['f_serviciuid'], $id_servicii_national)) {
						foreach ($id_servicii_national as $k => $v) {
							if ($v == $preturi[$value]['f_serviciuid']) {
								$livrarionline_gratuit_peste_national = floatval($livrarionline_gratuit_peste_national[$k]);
								$livrarionline_reducere_national = floatval($livrarionline_reducere_national[$k]);

								if ($livrarionline_pretfix_national[$k]) {
									$livrarionline_pretfix_national = floatval($livrarionline_pretfix_national[$k]);
								}

								$price_standard = round((float)$preturi[$value]['f_pret'], 2);
								if ($livrarionline_gratuit_peste_national > 0 && $total >= $livrarionline_gratuit_peste_national) {
									$price_standard = 0;
								} else {
									if (!is_array($livrarionline_pretfix_national)) {
										$price_standard = (float)$livrarionline_pretfix_national;
									}
									if ($price_standard && $livrarionline_semn_reducere_national[$k] && $livrarionline_reducere_national && $livrarionline_tip_reducere_national[$k]) {
										if ($livrarionline_tip_reducere_national[$k] == 'V') {
											if ($livrarionline_semn_reducere_national[$k] == 'P') {
												$price_standard += $livrarionline_reducere_national;
											} else {
												$price_standard -= $livrarionline_reducere_national;
											}
										} else {
											if ($livrarionline_semn_reducere_national[$k] == 'P') {
												$price_standard += $price_standard * $livrarionline_reducere_national / 100;
											} else {
												$price_standard -= $price_standard * $livrarionline_reducere_national / 100;
											}
										}
										$price_standard = max(0, $price_standard);
									}
								}

								$quote_data[str_replace(" ", "_", $denumiri_servicii_national[$k])] = array(
									'code'         => 'livrarionline.' . str_replace(" ", "_", $denumiri_servicii_national[$k]),
									'title'        => $denumiri_servicii_national[$k],
									'cost'         => $this->currency->convert($price_standard, $currency, $this->config->get('config_currency')),
									'tax_class_id' => 'livrarionline.tax.' . str_replace(" ", "_", $denumiri_servicii_national[$k]),
									'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($price_standard, $currency, $this->config->get('config_currency')), $this->config->get('config_tax'), 1), $this->config->get('config_currency'), 1.0000000),
								);
								break;
							}
						}
						$local = true;
					}
				}

			}
		}

		for ($i = 0; $i < count($id_servicii_pachetomat); $i++) {
			$f_request_awb['f_shipping_company_id'] = (int)$id_shipping_company_pachetomat[$i];
			$f_request_awb['serviciuid'] = (int)$id_servicii_pachetomat[$i];

			$livrarionline_gratuit_peste_pachetomat[$i] = floatval($livrarionline_gratuit_peste_pachetomat[$i]);
			$livrarionline_reducere_pachetomat[$i] = floatval($livrarionline_reducere_pachetomat[$i]);

			$free = false;
			if ($livrarionline_gratuit_peste_pachetomat[$i] > 0 && $total >= $livrarionline_gratuit_peste_pachetomat[$i]) {
				$free = true;
				$price_standard = 0;
			} else {
				$price_standard = (float)$livrarionline_pretfix_pachetomat[$i];
				if ($price_standard && $livrarionline_semn_reducere_pachetomat[$i] && $livrarionline_reducere_pachetomat[$i] && $livrarionline_tip_reducere_pachetomat[$i]) {
					if ($livrarionline_tip_reducere_pachetomat[$i] == 'V') {
						if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
							$price_standard += $livrarionline_reducere_pachetomat[$i];
						} else {
							$price_standard -= $livrarionline_reducere_pachetomat[$i];
						}
					} else {
						if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
							$price_standard += $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
						} else {
							$price_standard -= $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
						}
					}
					$price_standard = max(0, $price_standard);
				}
			}

			if (($free || $price_standard) && isset($livrarionline_denumire_pretfix_pachetomat[$i]) && $livrarionline_denumire_pretfix_pachetomat[$i]) { //daca avem pret fix de livrare
				$quote_data[str_replace(" ", "_", $livrarionline_denumire_pretfix_pachetomat[$i])] = array(
					'code'         => 'livrarionline.' . str_replace(" ", "_", $livrarionline_denumire_pretfix_pachetomat[$i]) . '.p.' . (isset($this->session->data['dp_id']) ? $this->session->data['dp_id'] : ''),
					'title'        => '<a title="Modifica punctul de ridicare">' . $livrarionline_denumire_pretfix_pachetomat[$i] . '</a> <span id="pp-selected-dp-text"></span>',
					'cost'         => $this->currency->convert($price_standard, $currency, $this->config->get('config_currency')),
					'tax_class_id' => 'livrarionline.tax.' . str_replace(" ", "_", $livrarionline_denumire_pretfix_pachetomat[$i]),
					'text'         => '<span>' . $this->currency->format($this->tax->calculate($this->currency->convert($price_standard, $currency, $this->config->get('config_currency')), $this->config->get('config_tax'), 1), $this->config->get('config_currency'), 1.0000000) . '</span>',
				);
			} else {
				$matches = self::multidimensional_search($preturi, array('f_tip' => 'p'));
				if (!empty($matches)) {
					foreach ($matches as $key => $value) {
						if (in_array($preturi[$value]['f_serviciuid'], $id_servicii_pachetomat)) {
							foreach ($id_servicii_pachetomat as $k => $v) {
								if ($v == $preturi[$value]['f_serviciuid']) {
									$price_standard = round((float)$preturi[$value]['f_pret'], 2);
									if ($livrarionline_gratuit_peste_pachetomat[$i] > 0 && $total > $livrarionline_gratuit_peste_pachetomat[$i]) {
										$price_standard = 0;
									}
									break;
								}
							}
						}
					}
				} else {
					$price_standard = 0;
				}
				if ($livrarionline_gratuit_peste_pachetomat[$i] == 0 || $total < $livrarionline_gratuit_peste_pachetomat[$i]) {
					if (!empty($this->session->data['dp_id'])) {
						$raspuns = $lo->EstimeazaPretSmartlocker($f_request_awb, $this->session->data['dp_id'], 0);
						if (!isset($raspuns->f_pret)) {
							$error = 'Nu am putut calcula pretul';
						} else {
							$price_standard = (float)$raspuns->f_pret;
							if ($livrarionline_semn_reducere_pachetomat[$i] && $livrarionline_reducere_pachetomat[$i] && $livrarionline_tip_reducere_pachetomat[$i]) {
								if ($livrarionline_tip_reducere_pachetomat[$i] == 'V') {
									if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
										$price_standard += $livrarionline_reducere_pachetomat[$i];
									} else {
										$price_standard -= $livrarionline_reducere_pachetomat[$i];
									}
								} else {
									if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
										$price_standard += $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
									} else {
										$price_standard -= $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
									}
								}
								$price_standard = max(0, $price_standard);
							}
						}
					} else {
						if ($livrarionline_semn_reducere_pachetomat[$i] && $livrarionline_reducere_pachetomat[$i] && $livrarionline_tip_reducere_pachetomat[$i]) {
							if ($livrarionline_tip_reducere_pachetomat[$i] == 'V') {
								if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
									$price_standard += $livrarionline_reducere_pachetomat[$i];
								} else {
									$price_standard -= $livrarionline_reducere_pachetomat[$i];
								}
							} else {
								if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') {
									$price_standard += $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
								} else {
									$price_standard -= $price_standard * $livrarionline_reducere_pachetomat[$i] / 100;
								}
							}
							$price_standard = max(0, $price_standard);
						}
					}
				}

				$quote_data[str_replace(" ", "_", $denumiri_servicii_pachetomat[$i])] = array(
					'code'         => 'livrarionline.' . str_replace(" ", "_", $denumiri_servicii_pachetomat[$i]) . '.p.' . (isset($this->session->data['dp_id']) ? $this->session->data['dp_id'] : ''),
					'title'        => '<a title="Modifica punctul de ridicare">' . $denumiri_servicii_pachetomat[$i] . '</a> <span id="pp-selected-dp-text">' . (!empty($this->session->data['dp_id']) ? $this->session->data['dp_name'] : '<a title="Modifica punctul de ridicare">Nu a fost selectat pachetomat</a> <span id="pp-selected-dp-text2"></span>') . '</span>',
					'cost'         => $this->currency->convert($price_standard, $currency, $this->config->get('config_currency')),
					'tax_class_id' => 'livrarionline.tax.' . str_replace(" ", "_", $denumiri_servicii_pachetomat[$i]),
					'text'         => '<span>' . ($error ? $error : $this->currency->format($this->tax->calculate($this->currency->convert($price_standard, $currency, $this->config->get('config_currency')), $this->config->get('config_tax'), 1), $this->config->get('config_currency'), 1.0000000)) . '</span>',
				);

				$error = '';
			}
		}


		if (isset($quote_data) && !empty($quote_data)) {
			$method_data = array(
				'code'       => 'livrarionline',
				'title'      => 'LivrariOnline',
				'quote'      => $quote_data,
				'sort_order' => '1',
				'error'      => $error,
			);

			return $method_data;
		}
	}
}
