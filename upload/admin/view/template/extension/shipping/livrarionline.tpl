<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <table class="table">
            <tr>
              <td colspan=2>
                <span class="help">Stimate client, puteti obtine informatii pentru configurare la adresa: <a href="http://wiki.livrarionline.ro/" target="_blank">http://wiki.livrarionline.ro/</a></span>
                <span class="help">Va multumim pentru ca folositi serviciile LivrariOnline.</span>
              </td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_status; ?></td>
              <td><select class="form-control" name="livrarionline_status">
                  <?php if ($livrarionline_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td colspan=2>
                <span class="help" style="text-align:center"><b><?php echo $entry_securitate;?></b></span>
              </td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_loginid; ?></td>
              <td><input class="form-control" type="text" name="livrarionline_loginid" value="<?php echo $livrarionline_loginid; ?>" />
                <?php if ($error_loginid) { ?>
                <span class="error"><?php echo $error_loginid; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_key; ?></td>
              <td><textarea class="form-control" name="livrarionline_key" cols="100" rows="5"><?php echo $livrarionline_key; ?></textarea>
                <?php if ($error_key) { ?>
                <span class="error"><?php echo $error_key; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_ramburs; ?></td>
              <td><select class="form-control" name="livrarionline_ramburs">
                  <option value="1"<?php if ($livrarionline_ramburs == 1) { ?> selected<?php } ?>><?php echo $entry_ramburs_cash; ?></option>
                  <option value="2"<?php if ($livrarionline_ramburs == 2) { ?> selected<?php } ?>><?php echo $entry_ramburs_banca; ?></option>
                </select>
                <?php if ($error_ramburs) { ?>
                <span class="error"><?php echo $error_ramburs; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_gmaps_key; ?></td>
              <td><input type="text" class="form-control" name="livrarionline_gmaps_key" value="<?php echo $livrarionline_gmaps_key; ?>" />
                <?php if ($error_gmaps_key) { ?>
                <span class="error"><?php echo $error_gmaps_key; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_use_thermo; ?></td>
              <td><input type="checkbox" class="form-control" name="livrarionline_use_thermo" value="<?php echo $livrarionline_use_thermo; ?>" />
            </tr>
            <tr>
              <td><span class="required">*</span><?php echo $entry_statusawb; ?></td>
              <td>
                <select class="form-control" name="livrarionline_statusawb">
                  <option <?php echo $livrarionline_statusawb==0?'selected="selected"':''?> value="0"><?php echo $entry_statusawb_select; ?></option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $livrarionline_statusawb) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_statusawb) { ?>
                <span class="error"><?php echo $error_statusawb; ?></span>
                <?php } ?></td>
              </td>
            </tr>
            <tr>
              <td colspan=2>
                <span class="help" style="text-align:center"><b><?php echo $entry_services?></b></span>
              </td>
            </tr>
            <tr>
              <td>
                <span class="help"><b><?php echo $entry_parametri_national?></b></span>
              </td>
              <td>
                <a id="addNationalField" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-plus-circle"></i> <?php echo $entry_add_service?></a>
              </td>
            </tr>
            <?php if (!isset($livrarionline_id_serviciu_national) || empty($livrarionline_id_serviciu_national)): ?>
            <tr class="national">
              <td><span class="required">*</span> <?php echo $entry_national; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td>Denumire serviciu</td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_serviciu_national[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td>ID Serviciu</td>
                    <td><input class="form-control" type="text" name="livrarionline_id_serviciu_national[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td>ID Shipping Company</td>
                    <td><input class="form-control" type="text" name="livrarionline_id_shipping_company_national[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_pretfix_national[]" value="" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_reducere; ?></b></span>
                    </td>
                    <td>
                      <div class="col-md-3">
                        <select class="form-control" name="livrarionline_semn_reducere_national[]">
                          <option value="P" selected="">+</option>
                          <option value="M">-</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="livrarionline_reducere_national[]" value="" />
                      </div>
                      <div class="col-md-5">
                        <select class="form-control" name="livrarionline_tip_reducere_national[]">
                          <option value="V" selected=""><?php echo $entry_reducere_v; ?> (<?php echo $currency_code; ?>)</option>
                          <option value="P"><?php echo $entry_reducere_p; ?> (%)</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_gratuit_peste; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_gratuit_peste_national[]" value="" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_denumire_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_denumire_pretfix_national[]" value="" size="50"/>
                    </td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_service?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php else: ?>
            <?php for ($i=0; $i<count($livrarionline_id_serviciu_national); $i++): ?>
            <tr class="national">
              <td><span class="required">*</span> <?php echo $entry_national; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td><?php echo $entry_denumire_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_serviciu_national[]" value="<?php echo $livrarionline_denumire_serviciu_national[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_serviciu_national[]" value="<?php echo $livrarionline_id_serviciu_national[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_shipping_company; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_shipping_company_national[]" value="<?php echo $livrarionline_id_shipping_company_national[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_pretfix_national[]" value="<?php echo $livrarionline_pretfix_national[$i]; ?>" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_reducere; ?></b></span>
                    </td>
                    <td>
                      <div class="row">
                        <div class="col-md-3">
                          <select class="form-control" name="livrarionline_semn_reducere_national[]">
                            <option value="P"<?php if (isset($livrarionline_semn_reducere_national[$i]) && $livrarionline_semn_reducere_national[$i] == 'P') { ?> selected<?php } ?>>+</option>
                            <option value="M"<?php if (isset($livrarionline_semn_reducere_national[$i]) && $livrarionline_semn_reducere_national[$i] == 'M') { ?> selected<?php } ?>>-</option>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <input class="form-control" type="text" name="livrarionline_reducere_national[]" value="<?php echo $livrarionline_reducere_national[$i]; ?>" />
                        </div>
                        <div class="col-md-5">
                          <select class="form-control" name="livrarionline_tip_reducere_national[]">
                            <option value="V"<?php if (isset($livrarionline_tip_reducere_national[$i]) && $livrarionline_tip_reducere_national[$i] == 'V') { ?> selected<?php } ?>><?php echo $entry_reducere_v; ?> (<?php echo $currency_code; ?>)</option>
                            <option value="P"<?php if (isset($livrarionline_tip_reducere_national[$i]) && $livrarionline_tip_reducere_national[$i] == 'P') { ?> selected<?php } ?>><?php echo $entry_reducere_p; ?> (%)</option>
                          </select>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_gratuit_peste; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_gratuit_peste_national[]" value="<?php echo $livrarionline_gratuit_peste_national[$i]; ?>" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_denumire_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_denumire_pretfix_national[]" value="<?php echo $livrarionline_denumire_pretfix_national[$i]; ?>" size="50"/>
                    </td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_service; ?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php endfor; ?>
            <?php endif; ?>
            <tr>
              <td>
                <span class="help"><b><?php echo $entry_parametri_pachetomat; ?></b></span>
              </td>
              <td>
                <a id="addPachetomatField" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-plus-circle"></i> <?php echo $entry_add_pachetomat; ?></a>
              </td>
            </tr>
            <?php if (!isset($livrarionline_id_serviciu_pachetomat) || empty($livrarionline_id_serviciu_pachetomat)): ?>
            <tr class="pachetomat">
              <td><span class="required">*</span> <?php echo $entry_pachetomat; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td><?php echo $entry_denumire_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_serviciu_pachetomat[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_serviciu_pachetomat[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_shipping_company; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_shipping_company_pachetomat[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_pretfix_pachetomat[]" value="" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_reducere; ?></b></span>
                    </td>
                    <td>
                      <div class="col-md-3">
                        <select class="form-control" name="livrarionline_semn_reducere_pachetomat[]">
                          <option value="P" selected="">+</option>
                          <option value="M">-</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="livrarionline_reducere_pachetomat[]" value="" />
                      </div>
                      <div class="col-md-5">
                        <select class="form-control" name="livrarionline_tip_reducere_pachetomat[]">
                          <option value="V" selected=""><?php echo $entry_reducere_v; ?> (<?php echo $currency_code; ?>)</option>
                          <option value="P"><?php echo $entry_reducere_p; ?> (%)</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_gratuit_peste; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_gratuit_peste_pachetomat[]" value="" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_denumire_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_denumire_pretfix_pachetomat[]" value="" size="50"/>
                    </td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_service?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php else: ?>
            <?php for ($i=0; $i<count($livrarionline_id_serviciu_pachetomat); $i++): ?>
            <tr class="pachetomat">
              <td><span class="required">*</span> <?php echo $entry_pachetomat; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td><?php echo $entry_denumire_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_serviciu_pachetomat[]" value="<?php echo $livrarionline_denumire_serviciu_pachetomat[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_serviciu; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_serviciu_pachetomat[]" value="<?php echo $livrarionline_id_serviciu_pachetomat[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_id_shipping_company; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_id_shipping_company_pachetomat[]" value="<?php echo $livrarionline_id_shipping_company_pachetomat[$i]; ?>" size="50"/></td>
                  </tr>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_pretfix_pachetomat[]" value="<?php echo $livrarionline_pretfix_pachetomat[$i]; ?>" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_reducere; ?></b></span>
                    </td>
                    <td>
                      <div class="col-md-3">
                        <select class="form-control" name="livrarionline_semn_reducere_pachetomat[]">
                          <option value="P"<?php if ($livrarionline_semn_reducere_pachetomat[$i] == 'P') { ?> selected<?php } ?>>+</option>
                          <option value="M"<?php if ($livrarionline_semn_reducere_pachetomat[$i] == 'M') { ?> selected<?php } ?>>-</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="livrarionline_reducere_pachetomat[]" value="<?php echo $livrarionline_reducere_pachetomat[$i]; ?>" />
                      </div>
                      <div class="col-md-5">
                        <select class="form-control" name="livrarionline_tip_reducere_pachetomat[]">
                          <option value="V"<?php if ($livrarionline_tip_reducere_pachetomat[$i] == 'V') { ?> selected<?php } ?>><?php echo $entry_reducere_v; ?> (<?php echo $currency_code; ?>)</option>
                          <option value="P"<?php if ($livrarionline_tip_reducere_pachetomat[$i] == 'P') { ?> selected<?php } ?>><?php echo $entry_reducere_p; ?> (%)</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_gratuit_peste; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_gratuit_peste_pachetomat[]" value="<?php echo $livrarionline_gratuit_peste_pachetomat[$i]; ?>" /> <?php echo $currency_code; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="help"><b><?php echo $entry_denumire_pret_fix_livrare; ?></b></span>
                    </td>
                    <td>
                      <input class="form-control" type="text" name="livrarionline_denumire_pretfix_pachetomat[]" value="<?php echo $livrarionline_denumire_pretfix_pachetomat[$i]; ?>" size="50"/>
                    </td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_service?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php endfor; ?>
            <?php endif; ?>
            <tr>
              <td colspan=2>
                <span class="help" style="text-align:center"><b><?php echo $entry_puncte_ridicare; ?></b></span>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <a id="addPunctRidicare" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-plus-circle"></i> <?php echo $entry_add_punct_ridicare?></a>
              </td>
            </tr>
            <?php if (!isset($livrarionline_denumire_punct_ridicare) || empty($livrarionline_denumire_punct_ridicare)): ?>
            <tr class="punct-ridicare">
              <td><span class="required">*</span> <?php echo $entry_punct_ridicare; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_denumire_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_email_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_email_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_nume_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_nume_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_prenume_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_prenume_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_telefon_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_telefon_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_telefon_mobil_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_telefon_mobil_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_adresa_punct_ridicare; ?></td>
                    <td><textarea class="form-control" name="livrarionline_adresa_punct_ridicare[]" cols="52" rows="5"></textarea></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_oras_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_oras_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_county; ?></td>
                    <td>
                      <select class="form-control" name="livrarionline_judet_punct_ridicare[]">
                        <option <?php echo $livrarionline_judet_punct_ridicare == 0 ? 'selected="selected"' : ''?> value="0"><?php echo $entry_county_select; ?></option>
                        <?php foreach ($judete as $judet) { ?>
                        <?php if ($judet['zone_id'] == $livrarionline_judet_punct_ridicare) { ?>
                        <option value="<?php echo $judet['zone_id']; ?>" selected="selected"><?php echo $judet['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $judet['zone_id']; ?>"><?php echo $judet['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_zipcode_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_zipcode_punct_ridicare[]" value="" size="50"/></td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_punct_ridicare?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php else: ?>
            <?php for ($i=0;$i<count($livrarionline_denumire_punct_ridicare);$i++): ?>
            <tr class="punct-ridicare">
              <td><span class="required">*</span> <?php echo $entry_punct_ridicare; ?></td>
              <td>
                <table class="table">
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_denumire_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_denumire_punct_ridicare[]" value="<?php echo $livrarionline_denumire_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_email_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_email_punct_ridicare[]" value="<?php echo $livrarionline_email_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_nume_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_nume_punct_ridicare[]" value="<?php echo $livrarionline_nume_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_prenume_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_prenume_punct_ridicare[]" value="<?php echo $livrarionline_prenume_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_telefon_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_telefon_punct_ridicare[]" value="<?php echo $livrarionline_telefon_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_telefon_mobil_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_telefon_mobil_punct_ridicare[]" value="<?php echo $livrarionline_telefon_mobil_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_adresa_punct_ridicare; ?></td>
                    <td><textarea class="form-control" name="livrarionline_adresa_punct_ridicare[]" cols="52" rows="5"><?php echo $livrarionline_adresa_punct_ridicare[$i]; ?></textarea></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_oras_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_oras_punct_ridicare[]" value="<?php echo $livrarionline_oras_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_county; ?></td>
                    <td>
                      <select class="form-control" name="livrarionline_judet_punct_ridicare[]">
                        <option <?php echo $livrarionline_judet_punct_ridicare == 0 ? 'selected="selected"' : ''?> value="0"><?php echo $entry_county_select; ?></option>
                        <?php foreach ($judete as $judet) { ?>
                        <?php if ($judet['zone_id'] == $livrarionline_judet_punct_ridicare[$i]) { ?>
                        <option value="<?php echo $judet['zone_id']; ?>" selected="selected"><?php echo $judet['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $judet['zone_id']; ?>"><?php echo $judet['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_zipcode_punct_ridicare; ?></td>
                    <td><input class="form-control" type="text" name="livrarionline_zipcode_punct_ridicare[]" value="<?php echo $livrarionline_zipcode_punct_ridicare[$i]; ?>" size="50"/></td>
                  </tr>
                  <tr><td colspan="2"><a class="delete" style="cursor:pointer;font-weight:bold;text-decoration:none;"><i class="fa fa-minus-circle"></i> <?php echo $entry_delete_punct_ridicare; ?></a></td></tr>
                </table>
              </td>
            </tr>
            <?php endfor; ?>
            <?php endif; ?>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#addNationalField').on('click', function(){
    var clone = $("tr.national:last").clone().find('input').val('').end().find('textarea').text('').end().find('select').val('0').end();
    $("tr.national:last").after(clone);
  });
  $('#addPachetomatField').on('click', function(){
    var clone = $("tr.pachetomat:last").clone().find('input').val('').end().find('textarea').text('').end().find('select').val('0').end();
    $("tr.pachetomat:last").after(clone);
  });
  $('#addPunctRidicare').on('click', function(){
    var clone = $("tr.punct-ridicare:last").clone().find('input').val('').end().find('textarea').text('').end().find('select').val('0').end();
    $("tr.punct-ridicare:last").after(clone);
  });
  $('table.table').on('click', '.delete', function(){
    if ($(this).closest('tr.national').length) {
      if ($(this).closest('tr.national').parent().find('>.national').length > 1) {
        $(this).closest('tr.national').remove();
      }
    } else if ($(this).closest('tr.pachetomat').length) {
      if ($(this).closest('tr.pachetomat').parent().find('>.pachetomat').length > 1) {
        $(this).closest('tr.pachetomat').remove();
      }
    } else if ($(this).closest('tr.punct-ridicare').length) {
      if ($(this).closest('tr.punct-ridicare').parent().find('>.punct-ridicare').length > 1) {
        $(this).closest('tr.punct-ridicare').remove();
      }
    }
  });
</script>
<?php echo $footer; ?>
