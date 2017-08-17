define([], function () {
    PhotoNavigation = function (context, options) {
        this._initEventListeners(context);
    };

    PhotoNavigation.prototype._initEventListeners = function(selector) {
        var that = this;

        $(selector).click(function (e) {
            e.preventDefault();

            var clickX = e.pageX - $(this).position().left;
            var imgWidth = $(this).width();

            if (imgWidth / 2 < clickX) {
                that._getNextPhotoAddress();
            } else {
                that._getPreviousPhotoAddress();
            }
        });
    };

    PhotoNavigation.prototype._getPreviousPhotoAddress = function() {
        var url = this._getButtonHref($('#button-previous-photo'));

        if (url) {
            window.location = url;
        }
    };

    PhotoNavigation.prototype._getNextPhotoAddress = function() {
        var url = this._getButtonHref($('#button-next-photo'));

        if (url) {
            window.location = url;
        }
    };

    PhotoNavigation.prototype._getButtonHref = function($element) {
        var url = null;

        if ($element) {
            url = $element.attr('href');
        }
        
        return url;
    };

    return PhotoNavigation;
});
