<?php

return [
     [
        "uri" => "/auth/check-token",
        "controller" => "AuthController",
        "function" => "checkToken",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsGuest"])
    ],
    [
        "uri" => "/admin/test/send-email",
        "controller" => "AdminController",
        "function" => "sendTestEmail",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/settings",
        "controller" => "SettingsController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/settings/save",
        "controller" => "SettingsController",
        "function" => "save",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/auth/login",
        "controller" => "AuthController",
        "function" => "login",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsGuest"])
    ],
    [
        "uri" => "/auth/register",
        "controller" => "AuthController",
        "function" => "register",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsGuest"])
    ],
    [
        "uri" => "/auth/login",
        "controller" => "AuthController",
        "function" => "loginProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsGuest"])
    ],
    [
        "uri" => "/auth/register",
        "controller" => "AuthController",
        "function" => "registerProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsGuest"])
    ],
    [
        "uri" => "/auth/logout",
        "controller" => "AuthController",
        "function" => "logout",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAuthenticated"])
    ],
    [
        "uri" => "/admin/plugins/([0-9]+)/delete",
        "controller" => "PluginsController",
        "function" => "deletePlugin",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/plugins/add",
        "controller" => "PluginsController",
        "function" => "addPlugin",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/plugins/add",
        "controller" => "PluginsController",
        "function" => "addPluginProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/plugins/([0-9]+)/deactivate",
        "controller" => "PluginsController",
        "function" => "deactivatePlugin",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/plugins/([a-zA-Z]+)/install",
        "controller" => "PluginsController",
        "function" => "install",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/plugins",
        "controller" => "PluginsController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin",
        "controller" => "AdminController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/",
        "controller" => "HomeController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode([""])
    ],
    [
        "uri" => "/admin/applications",
        "controller" => "ApplicationsController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/applications/browse",
        "controller" => "ApplicationsController",
        "function" => "browse",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/applications/reorder",
        "controller" => "ApplicationsController",
        "function" => "reorder",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/applications/toggle/([0-9]+)",
        "controller" => "ApplicationsController",
        "function" => "toggle",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages",
        "controller" => "PagesController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages/create",
        "controller" => "PagesController",
        "function" => "create",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages/([0-9]+)/edit",
        "controller" => "PagesController",
        "function" => "edit",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages/([0-9]+)/fetch",
        "controller" => "PagesController",
        "function" => "fetchPage",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages/create",
        "controller" => "PagesController",
        "function" => "createProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/pages/edit",
        "controller" => "PagesController",
        "function" => "editProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/page/([a-zA-Z0-9-_]+)",
        "controller" => "PagesController",
        "function" => "getPage",
        "method" => "get",
        "middlewares" => json_encode([])
    ],
    [
        "uri" => "/autocomplete",
        "controller" => "AutocompleteController",
        "function" => "search",
        "method" => "get",
        "middlewares" => json_encode([])
    ],
    [
        "uri" => "/admin/users/add",
        "controller" => "UsersController",
        "function" => "create",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/users/add",
        "controller" => "UsersController",
        "function" => "createProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/users",
        "controller" => "UsersController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/users/browse",
        "controller" => "UsersController",
        "function" => "browse",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],

    [
        "uri" => "/admin/groups/add",
        "controller" => "GroupController",
        "function" => "create",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/groups/add",
        "controller" => "GroupController",
        "function" => "createProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/groups",
        "controller" => "GroupController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/groups/browse",
        "controller" => "GroupController",
        "function" => "browse",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],

    [
        "uri" => "/admin/menus/add",
        "controller" => "MenuController",
        "function" => "create",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/menus/add",
        "controller" => "MenuController",
        "function" => "createProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/menus",
        "controller" => "MenuController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/menus/browse",
        "controller" => "MenuController",
        "function" => "browse",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/menus/getActiveMenu",
        "controller" => "MenuController",
        "function" => "getActiveMenu",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],

    [
        "uri" => "/admin/forums/add",
        "controller" => "ForumController",
        "function" => "create",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/forums/add",
        "controller" => "ForumController",
        "function" => "createProcess",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/forums",
        "controller" => "ForumController",
        "function" => "index",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/forums/getAll",
        "controller" => "ForumController",
        "function" => "getAll",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/forums/getAllParentNull",
        "controller" => "ForumController",
        "function" => "getAllParentNull",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/updater/checkHasUpdate",
        "controller" => "UpdateController",
        "function" => "checkHasUpdate",
        "method" => "get",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
    [
        "uri" => "/admin/updater/update",
        "controller" => "UpdateController",
        "function" => "update",
        "method" => "post",
        "middlewares" => json_encode(["Middleware\\IsAdmin"])
    ],
];
