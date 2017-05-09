define([], function () {
    CommentBox = function (context, options) {
        $('#comment-modal').on('show.bs.modal', function (event) {
            var $button = $(event.relatedTarget);
            var $photo = $button.closest('.photo');
            var photoId = $photo.data('photo-id');

            var route = Routing.generate('photo_add_comment', { photoId: photoId });

            var $modal = $(this);
            var $form = $modal.find('form');

            $form.attr('action', route);
        });
    };

    return CommentBox;
});
