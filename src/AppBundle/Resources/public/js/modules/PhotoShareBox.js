define(['jquery'], function ($) {
    PhotoShareModal = function (context, options) {
        this._initModalEventListener(context);

        this._options = options;
    };

    PhotoShareModal.prototype._options = {};

    PhotoShareModal.prototype._initModalEventListener = function(context) {
        var that = this;

        $(context).on('shown.bs.modal', function (event) {
            var $button = $(event.relatedTarget);
            var $photo = $button.closest('.photo');

            var network = $button.data('network');

            that._initForm($photo, network);
        });
    };

    PhotoShareModal.prototype._initForm = function($photo, network) {
        var photoId = $photo.data('photo-id');

        var route = Routing.generate('photo_share_' + network, { photoId: photoId });

        var $form = $('form#photo-share-form');

        $form.attr('action', route);
    };

    return PhotoShareModal;
});
