var Fahrradstadt = Fahrradstadt || {};

Fahrradstadt.loadModule = function(name, context, options, callback) {
    require([name], function(Module) {
        var module = new Module(context, options);

        if (callback) {
            callback(module);
        }
    });
};

require.config({
    baseUrl: '/bundles/app/js/modules/',
    paths:
    {
        'jquery': '/bundles/app/js/jquery/jquery-3.1.1.min',
        'leaflet': '/bundles/app/js/leaflet/leaflet',
        'leaflet-extramarkers': '/bundles/app/js/extramarkers/extramarkers.min',
        "popper": "/bundles/app/js/popper/popper.min",
        "bootstrap4": "/bundles/app/js/bootstrap/bootstrap.min"
    },
    shim: {
        'popper': {
            deps: ['jquery'],
            exports: 'Popper'
        },
        'leaflet-extramarkers': {
            deps: ['leaflet'],
            exports: 'L.ExtraMarkers'
        }
    }
});

define('initBootstrap', ['popper'], function(popper) {
    // set popper as required by Bootstrap
    window.Popper = popper;
    require(['bootstrap4'], function(bootstrap) {
        // do nothing - just let Bootstrap initialise itself
    });
});
