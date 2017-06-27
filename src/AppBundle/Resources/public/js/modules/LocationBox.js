define(['leaflet'], function () {
    LocationBox = function (context, options) {
        this._initModalEventListener(context);
    };

    LocationBox.prototype._standardCenter = [53.550757, 9.993010];
    LocationBox.prototype._map = null;
    LocationBox.prototype._marker = null;

    LocationBox.prototype._initModalEventListener = function(context) {
        var that = this;

        $(context).on('shown.bs.modal', function (event) {
            var $button = $(event.relatedTarget);
            var $photo = $button.closest('.photo');
            var latitude = $photo.data('latitude');
            var longitude = $photo.data('longitude');

            var center = that._standardCenter;

            if (latitude && longitude) {
                center = [latitude, longitude];
            }

            that._initMap(center);
            that._initMarker(center);
        });
    };

    LocationBox.prototype._initMap = function(center) {
        this._map = L.map('location-map').setView(center, 13);

        L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this._map);
    };

    LocationBox.prototype._initMarker = function(center) {
        this._marker = L.marker(center, {
            draggable: true
        }).addTo(this._map)
            .bindPopup('Wo hast du das Foto geschossen?')
            .openPopup();
    };

    return LocationBox;
});
