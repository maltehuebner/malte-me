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
        'leaflet': '/bundles/app/js/leaflet/leaflet'
    }
});
