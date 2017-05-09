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
            $element.removeClass('fa-star').addClass('fa-star-o');
            this._updateCounter($star, -1);
        } else {
            $element.removeClass('fa-star-o').addClass('fa-star');
            this._updateCounter($star, 1);
        }
    };

    FavoriteStar.prototype._updateCounter = function($star, value) {
        var $counter = $star.find('.favorite-counter');
        var oldValue = parseInt($counter.html());

        var newValue = oldValue + value;

        $counter.html(newValue);
    };

    return FavoriteStar;
});
