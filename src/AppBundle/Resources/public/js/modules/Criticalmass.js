define(['jquery', 'leaflet', 'dateformat'], function ($) {
    Criticalmass = function (context, options) {
        this._cardSelector = context;

        this._options = options;

        this._fetchData();
    };

    Criticalmass.prototype._mapSelector = null;
    Criticalmass.prototype._options = null;

    Criticalmass.prototype._fetchData = function() {
        $.ajax({
            dataType: 'json',
            context: this,
            url: 'https://criticalmass.in/api/hamburg/current',
            success: function(rideData) {
                this._createCalendar(rideData);

                $('.hide-after-load').hide();
                $('.show-after-load').show();
            },
            error: function() {
            }
        });
    };

    Criticalmass.prototype._createCalendar = function(rideData) {
        var date = new Date(rideData.dateTime * 1000);

        $('time').html(date.format('dd.mm.yyyy'));
        $('address').html(rideData.location);

        $('#city-page').attr('href', 'https://criticalmass.in/hamburg');
        $('#ride-page').attr('href', 'https://criticalmass.in/hamburg/' + date.format('yyyy-mm-dd'));
    };

    return Criticalmass;
});
