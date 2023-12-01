/***********
 *
 * Paysera payment gateway
 *
 * Javascript actions
 *
 ***********/
jQuery( document ).ready(function($) {
    const COUNTRY_SELECTION = '.country_select';

    $(COUNTRY_SELECTION).on("click", function() {
        var id, countryBilling, countryOption;

        $('.payment-countries').hide('slow');
        countryBilling = $('#billing_country');
        countryOption = countryBilling.find('option');
        if (typeof countryBilling.val() === "undefined" || countryBilling.val() === null) {
            id = countryOption.eq(1).val();
        } else {
            id = countryBilling.val().toLowerCase();
        }

        idcheck = $('#' + id).attr('class');
        if(!idcheck){
            id = 'other';
            idcheck = $('#' + id).attr('class');
            if(!idcheck) {
                id = countryOption.eq(1).val();
            }
        }

        countryOption.attr("selected", false);
        $('#paysera_country').find('option[value=\"' + id + '\"]').attr("selected", true);
        $('#' + id).show('slow');
    });

    $(document).on('change', '#paysera_country' ,function(){
        $('.payment-countries').hide('slow');
        $('#' + $('#paysera_country').val()).show('slow');
    });

    $(document).on('change', 'input[name="payment[pay_type]"]' ,function(){
        $('.payment').removeClass('activePayseraPayment');
        $(this).parent().parent().addClass('activePayseraPayment');
    });
});