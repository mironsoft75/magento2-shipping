define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Salecto_Shipping/js/model/parcel-shop/popup',
    'text!Salecto_Shipping/template/parcel-shop/modal-popup.html',
    'mage/translate'
], function($, modal, popup, modalTpl) {

    $.widget('salectoShipping.parcelShopModal', modal, {
        options: {
            autoOpen: false,
            modalClass: 'ws-parcelshop-popup',
            responsive: true,
            clickableOverlay: true,
            title: $.mage.__('Find a Service Point'),
            type: 'popup',
            buttons: [],
            popupTpl: modalTpl,
            customTpl: modalTpl
        },

        _create: function() {
            this._super();
            popup.setModal(this);
        }
    });

    return $.salectoShipping.parcelShopModal;
});
