define(['popper'], function (popper) {
    InitBootstrap = function (context, options) {
        window.Popper = popper;
        require(['bootstrap4'], function(bootstrap) {
            // do nothing - just let Bootstrap initialise itself
        });
    };

    return InitBootstrap;
});
