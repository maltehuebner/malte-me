define(['leaflet'], function () {
    LocationBox = function (context, options) {
        $('#location-modal').on('shown.bs.modal', function (event) {
            var map = L.map('location-map').setView([51.505, -0.09], 13);

            L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([51.5, -0.09]).addTo(map)
                .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
                .openPopup();
        });
    };

    LocationBox.prototype._standardLatLng = [57, 10];

    return LocationBox;
});
