define(['jquery'], function ($) {
    Sidebar = function (context, options) {
        this._initEventListeners(context);
    };

    Sidebar.prototype._initEventListeners = function(context) {
        $(context).click(this.openSidebar);
    };

    Sidebar.prototype.openSidebar = function(e) {
        $sidebar = $('#sidebar');
        $container = $('#floating-sidebar-container');
        $sidebarParent = $('#sidebar-container');

        if ($sidebar.hasClass('floating-sidebar')) {
            $sidebar.detach().appendTo($sidebarParent);
            $sidebar.removeClass('floating-sidebar');
            $container.hide();
        } else {
            $sidebar.detach().appendTo($container);
            $sidebar.addClass('floating-sidebar');
            $container.show();
        }
    };

    return Sidebar;
});
