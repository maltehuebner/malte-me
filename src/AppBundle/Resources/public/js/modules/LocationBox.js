define(['jquery', 'leaflet', 'leaflet-extramarkers'], function ($) {
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

            that._initValues($photo);
            that._initForm($photo);
            that._initMap(center);
            that._initMarker(center);
        });

        $(context).on('hidden.bs.modal', function (event) {
            that._map.remove();
        });
    };

    LocationBox.prototype._initForm = function($photo) {
        var photoId = $photo.data('photo-id');

        var route = Routing.generate('locate_photo', { photoId: photoId });

        var $form = $('form#location-form');

        $form.attr('action', route);
    };

    LocationBox.prototype._initValues = function($photo) {
        var latitude = $photo.data('latitude');
        var longitude = $photo.data('longitude');
        var location = $photo.data('location');

        $('#photo-latitude-input').val(latitude);
        $('#photo-longitude-input').val(longitude);
        $('#photo-location-input').val(location);
    };

    LocationBox.prototype._initMap = function(center) {
        this._map = L.map('location-map').setView(center, 13);

        L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this._map);
    };

    LocationBox.prototype._initMarker = function(center) {
        var icon = L.ExtraMarkers.icon({
            icon: 'fa-camera',
            markerColor: 'yellow',
            shape: 'square',
            prefix: 'fa'
        });

        this._marker = L.marker(center, {
            draggable: true,
            icon: icon
        }).addTo(this._map);

        this._marker
            .bindPopup('Wo hast du das Foto geschossen?')
            .openPopup()
        ;

        this._marker.on('moveend', this._onMarkerMove.bind(this));
    };

    LocationBox.prototype._onMarkerMove = function() {
        var latLng = this._marker.getLatLng();

        $('#photo-latitude-input').val(latLng.lat);
        $('#photo-longitude-input').val(latLng.lng);
    };

    return LocationBox;
});
