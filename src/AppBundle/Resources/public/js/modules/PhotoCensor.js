define(['jquery-areaselect'], function () {
    PhotoCensor = function () {
    };

    PhotoCensor.prototype._photoId = 0;

    PhotoCensor.prototype.init = function () {
        var that = this;
        var $photo = $('#photo');

        var photoWidth = $photo.width();

        $photo.selectAreas({
            width: photoWidth
        });

        $('#save').click(function() {
            $(this).prop('disabled', true);

            var areaData = $('#photo').selectAreas('relativeAreas');

            var url = Routing.generate('censor_photo', { photoId: that._photoId }, true);

            $.post(url + '?width=' + photoWidth,
                JSON.stringify(areaData)
            ).done(function(data) {
               // window.location=document.referrer;
            });
        });
    };

    PhotoCensor.prototype.setPhotoId = function (photoId) {
        this._photoId = photoId;
    };

    return PhotoCensor;
});
