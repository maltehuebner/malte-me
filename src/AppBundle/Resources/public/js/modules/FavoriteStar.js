define([], function () {
    FavoriteStar = function (context, options) {
        $('.favorite-star').click(function(e) {
            e.preventDefault();

            var $star = $(this);
            var url = $star.attr('href');

            $.get(url);
        });
    };

    return FavoriteStar;
});
