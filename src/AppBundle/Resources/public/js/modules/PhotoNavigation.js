define([], function () {
    PhotoNavigation = function (context, options) {
        this._initEventListeners();
    };

    PhotoNavigation.prototype._initEventListeners = function() {
        var that = this;

        $('img').click(function (e) { //Relative ( to its parent) mouse position
            e.preventDefault();

            var clickX = e.pageX - $(this).position().left;
            var imgWidth = $(this).width();

            if (imgWidth / 2 < clickX) {
                alert('rechts');
            } else {
                alert('links');
            }
        });
    };

    return PhotoNavigation;
});
