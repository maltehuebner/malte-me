define([], function () {
    FavoriteStar = function (context, options) {
        this._initEventListeners();
    };

    FavoriteStar.prototype._initEventListeners = function() {
        var that = this;

        $('.favorite-star').click(function(e) {
            e.preventDefault();

            var $star = $(this);

            that._onClick($star);
        });
    };

    FavoriteStar.prototype._onClick = function($star) {
        this._performAjax($star);
        this._toggleStar($star);
    };

    FavoriteStar.prototype._performAjax = function($star) {
        var url = $star.attr('href');

        $.get(url);
    };

    FavoriteStar.prototype._toggleStar = function($star) {
        var $element = $star.find('i.fa');

        if ($element.hasClass('fa-star')) {
            console.log('FOO');
            $element.removeClass('fa-star').addClass('fa-star-o');
        } else {
            console.log('BAR');
            $element.removeClass('fa-star-o').addClass('fa-star');
        }
    };

    return FavoriteStar;
});
