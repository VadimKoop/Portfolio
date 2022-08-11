/***********
 *
 * Paysera payment gateway
 *
 * Javascript actions
 *
 ***********/
jQuery(document).ready(function($) {
    var navTab = $('.nav-tab');
    var tabContent = $('.tabContent');

    tabContent.hide('slow');
    $('#tab0').addClass('nav-tab-active');
    $('#content0').show('slow');

    navTab.on('click',function(evt) {
        navTab.removeClass('nav-tab-active');
        tabContent.hide('slow');

        $('#' + evt.target.attributes.getNamedItem('id').value).addClass('nav-tab-active');
        $('#' + evt.target.attributes.getNamedItem('data-cont').value).show('slow');
    });
});
