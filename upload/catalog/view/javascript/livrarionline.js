$(document).ready(function () {
    var last_dp_id;

    var map;

    function plotMarkers(m) {
        if (m) {
            map.removeMarkers();
            markers_data = [];
            m.forEach(function (marker) {
                var temperatura = '';
                if (typeof marker.temperatura !== undefined && marker.temperatura) {
                    temperatura = '<p><b>Temperatura: </b>' + marker.dp_temperatura.split('.')[0] + '<sup>o</sup>C</p>';
                }
                markers_data.push({
                    id: marker.dp_id,
                    lat: parseFloat(marker.dp_gps_lat),
                    lng: parseFloat(marker.dp_gps_long),
                    title: marker.dp_denumire,
                    icon: {
                        size: new google.maps.Size(20, 30),
                        url: icon
                    },
                    infoWindow: {
                        content: '<div class="pp-map__infowindow infowindow">\
				  				<div class="infowindow-header">\
									<div class="infowindow-body">\
										<h3 class="infowindow-title">' + marker.dp_denumire + '</h3>\
										<p>' + marker.dp_adresa + ', ' + marker.dp_oras + ', ' + marker.dp_judet + ' (' + (marker.dp_indicatii ? marker.dp_indicatii : '') + ')</p>\
										<hr class="hr--dashed" />\
										' + temperatura + '\
										<div>' + marker.orar + '</div>\
				 '
                    },
                    run_ajax: true,
                    click: function (t) {
                        let id = t.id;
                        if (id > 0 && t.run_ajax && marker.dp_active > 0 && marker.dp_active != 10) {
                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: 'index.php?route=api/shipping/livrarionline_pachetomat',
                                method: 'post',
                                data: {
                                    'pachetomat': id
                                },
                                beforeSend: function () {
                                },
                                success: function (response) {
                                    last_dp_id = response.pachetomat.id;
                                    $('#judete').val(response.pachetomat.judet).trigger('change');
                                    $('.jconfirm-buttons > .btn-lo-right').prop('disabled', '');
                                },
                                error: function () {
                                }
                            });
                        } else {
                            t.run_ajax = true;
                        }
                    },
                });
            });
            map.addMarkers(markers_data);
            map.fitZoom();
        }
    }

    function showMarkerDetails(dp_id) {
        map.markers.forEach(function (value, index) {
            if (value.id == dp_id) {
                position = map.markers[index].getPosition();
                lat = position.lat();
                lng = position.lng();
                map.setCenter(lat, lng);
                map.setZoom(15);
                map.markers[index].run_ajax = false;
                google.maps.event.trigger(map.markers[index], 'click');
            }
        });

    }

    $('#content').on('click', 'input[value^="livrarionline."][value*=".p."], input[value^="livrarionline."][value*=".p."] + a, .pp-selected-dp-text, .pp-selected-dp-text2', function () {
        $('#harta-pp').css({
            'transform': 'translateY(0)',
            'z-index': '999',
            'display': 'block'
        });
        $('#pp-selected-dp-map').text('Alege punctul de ridicare');
        $('body').addClass('pp-overlay');
        $('#judete, #order_review').trigger('change');
        map = new GMaps({
            div: '#pp-map-canvas',
            lat: 46.203567,
            lng: 25.003274,
            zoom: 15,
        });
        plotMarkers(ppLocationsArray);
    });

    $('#content').on('shown.bs.collapse', '#collapse-shipping-method', function () {
        if (typeof last_dp_id !== 'undefined' && last_dp_id) {
            $('.pp-selected-dp-text').html('Coletul va fi livrat la <b>' + last_dp_name + '</b>');
            $('.pp-selected-dp-text').closest('label').click();
        }
    });

    $('#pp-close').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.pp-selected-dp-text').html('Coletul va fi livrat la <b>' + $('#pachetomate option:selected').text() + '</b>');
        $('#harta-pp').hide();
        $('body').removeClass('pp-overlay');
        if (typeof last_dp_id !== 'undefined' && last_dp_id) {
            $('a[href="#collapse-shipping-method"]').click();
            $('#button-shipping-address').click();
        }
    });

    $('#pp-selected-dp-map').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#harta-pp').css({
            'transform': 'translateY(0)',
            'z-index': '999',
            'display': 'block'
        });

        $('body').addClass('pp-overlay');
        $('#judete, #order_review').trigger('change');

        map = new GMaps({
            div: '#pp-map-canvas',
            lat: 46.203567,
            lng: 25.003274,
            zoom: 15,
        });
        plotMarkers(ppLocationsArray);
    });

    $('#harta-pp').on('change', '#judete', function (e, data) {
        e.preventDefault();
        e.stopPropagation();
        var js = $('option:selected', this).val();
        $('.pp-panel__body', '#order_review').show();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'index.php?route=api/shipping/livrarionline_localitati',
            data: {
                'judet': $('option:selected', this).val()
            },
            beforeSend: function () {
                $('#judete').closest('li').find('input.shipping_method').attr('checked', true);
                $('#orase').find('option').remove().end();
                $('#pachetomate').find('option').remove().end();
            },
            success: function (response) {
                var count = response.count;
                var orase = response.orase;
                var pachetomate = response.pachetomate;
                var oras_selectat = response.selected;
                var pselected = response.pselected;
                var jselected = response.jselected;

                $('#orase').append($('<option>', {
                    value: 0,
                    text: 'Selectati un oras',
                    disabled: true,
                    selected: true
                }));
                $('#pachetomate').append($('<option>', {
                    value: 0,
                    text: 'Selectati un punct de ridicare',
                    disabled: true,
                    selected: true
                }));
                $.each(orase, function (index, value) {
                    $('#orase').append($('<option>', {
                        value: value.oras,
                        text: value.oras
                    }));
                });
                if (jselected == js && oras_selectat && pselected) {
                    $('#orase').val(oras_selectat);
                    $('#orase').trigger('change');
                } else if (count == 1) {
                    $('#orase option').eq(1).prop('selected', true);
                    $('#orase').trigger('change');
                    plotMarkers(pachetomate);
                } else {
                    plotMarkers(pachetomate);
                }

                $('#orase').closest('.pp-form-group').show();
            }
        });
    });

    $('#harta-pp').on('change', '#orase', function () {
        var os = $('option:selected', this).val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'index.php?route=api/shipping/livrarionline_pachetomate',
            data: {
                'oras': $('option:selected', this).val()
            },
            beforeSend: function () {
                $('#pachetomate').find('option').remove().end()
            },
            success: function (response) {
                var count = response.count;
                var pachetomate = response.pachetomate;
                var pachetomat_selectat = response.selected;
                var oselected = response.oselected;

                $('#pachetomate').append($('<option>', {
                    value: 0,
                    text: 'Selectati un punct de ridicare',
                    disabled: true,
                    selected: true
                }));
                $.each(pachetomate, function (index, value) {
                    var p = $('<option>', {
                        value: value.dp_id,
                        text: value.dp_denumire + (value.dp_active == 10 ? ' - Pachetomat plin' : '')
                    });
                    if (value.dp_active == 10) {
                        p.prop('disabled', true);
                    }
                    $('#pachetomate').append(p);
                });

                if (oselected == os && pachetomat_selectat) {
                    $('#pachetomate').val(pachetomat_selectat);
                } else if (count == 1) {
                    $('#pachetomate option').eq(1).prop('selected', true);
                    plotMarkers(pachetomate);
                } else {
                    plotMarkers(pachetomate);
                }
                $('#pachetomate').trigger('change');

                $('#pachetomate').closest('.pp-form-group').show();
            }
        });
    });

    $('#harta-pp').on('change', '#pachetomate', function () {
        var dp_id = $('option:selected', this).val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'index.php?route=api/shipping/livrarionline_pachetomat',
            data: {
                'pachetomat': dp_id,
                'judet': $('#judete', '#order_review').val(),
                'oras': $('#orase', '#order_review').val(),
            },
            beforeSend: function () {
            },
            success: function (response) {
                var pachetomat = response.pachetomat;
                var pachetomat_selectat = response.selected;
                last_dp_id = response.selected;
                last_dp_name = response.selected_name;
                showMarkerDetails(pachetomat_selectat);
            }
        });
    });
});
