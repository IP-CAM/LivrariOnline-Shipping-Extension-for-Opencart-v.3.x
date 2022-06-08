$(document).ready(function() {
    $('#content').on('click', 'input[value^="livrarionline."][value*=".p."], input[value^="livrarionline."][value*=".p."] + a, #pp-selected-dp-text, #pp-selected-dp-text2', function() {
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

    $('#content').on('shown.bs.collapse', '#collapse-shipping-method', function() {
        if (typeof last_dp_id !== 'undefined' && last_dp_id) {
            $('#pp-selected-dp-text').html('Coletul va fi livrat la <b>' + last_dp_name + '</b>');
            $('#pp-selected-dp-text').closest('label').find('input').prop( "checked", true );
        }
    });

    $('#pp-close').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#pp-selected-dp-text').html('Coletul va fi livrat la <b>' + $('#pachetomate option:selected').text() + '</b>');
        $('#harta-pp').hide();
        $('body').removeClass('pp-overlay');
        if (typeof last_dp_id !== 'undefined' && last_dp_id) {
            window['_QuickCheckout'].save(); // pentru journal3
            $('a[href="#collapse-shipping-method"]').click();
            $('#button-shipping-address, #button-guest-shipping').click();
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

    $('#harta-pp').on('change', '#judete', function() {
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
                $('#orase').empty();
                $('#orase').append($('<option>', {
                    value: 0,
                    text: 'Selectati un oras',
                    disabled: true,
                    selected: true
                }));
                $('#pachetomate').empty();
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

    $('#harta-pp').on('change', '#orase', function() {
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
                $('#pachetomate').empty();
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

    $('#harta-pp').on('change', '#pachetomate', function() {
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
