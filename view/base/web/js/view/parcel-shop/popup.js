define([
    'uiComponent',
    'Salecto_Shipping/js/model/parcel-shop/popup'
], function(Component, parcelShopPopup) {

    return Component.extend({
        defaults: {
            modalComponent: parcelShopPopup.component
        }
    });
});
