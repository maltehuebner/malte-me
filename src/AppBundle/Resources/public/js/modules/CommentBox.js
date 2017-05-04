define([], function () {
    CommentBox = function (context, options) {
        $('.photo').each(function() {
            console.log($(this).data('photo-id'));
        });
    };

    return CommentBox;
});
