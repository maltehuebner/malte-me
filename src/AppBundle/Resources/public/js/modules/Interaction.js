define([], function () {
    Interaction = function (context, options) {
        this._$interaction = $(context);

        this._initEventListener();
    };

    Interaction.prototype._$interaction = null;
    Interaction.prototype._isFullscreen = false;

    Interaction.prototype._initEventListener = function() {
        var interactionTop = this._$interaction.offset().top;

        $(window).scroll(function() {
            console.log($(this).scrollTop(), interactionTop);
            /*if($(this).scrollTop()>=aTop){
                alert('header just passed.');
                // instead of alert you can use to show your ad
                // something like $('#footAd').slideup();
            }*/
        });
    };

    Interaction.prototype.expand = function() {
        var d = {};

        var speed = 200;

        if (!this.isFullscreen){ // MAXIMIZATION
            d.width = "100%";
            d.height = "100%";
            isFullscreen = true;
            $("h1").slideUp(speed);
        } else { // MINIMIZATION
            d.width = "300px";
            d.height = "100px";
            isFullscreen = false;
            $("h1").slideDown(speed);
        }

        $("#controls").animate(d,speed);
    };

    return Interaction;
});
