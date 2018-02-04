define(['jquery'], function ($) {
    Sidebar = function (context, options) {
        this._initEventListeners(context);
    };

    Sidebar.prototype._initEventListeners = function(context) {
        $(context).click(this.openSidebar);
    };

    Sidebar.prototype.openSidebar = function(e) {
        $sidebar = $('#sidebar');
        $body = $('body');
        $sidebarParent = $('#sidebar-container');

        if ($sidebar.hasClass('floating-sidebar')) {
            $sidebar.detach().appendTo($sidebarParent);
            $sidebar.removeClass('floating-sidebar');
        } else {
            $sidebar.detach().appendTo($body);
            $sidebar.addClass('floating-sidebar');
        }
    };

    return Sidebar;
});
