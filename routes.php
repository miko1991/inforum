<?php

use Kernel\Router;
use Kernel\Kernel;

// Debug Routes

Router::get("/debug/reloadRoutes", "DebugController@reloadRoutes");
Router::get("/debug/reloadApplications", "DebugController@reloadApplications");

// Installation Routes

Router::get("/admin/install", "InstallationController@index");
Router::get("/admin/install/installTables", "InstallationController@installTables");
Router::post("/admin/install/installTables", "InstallationController@installTables");
Router::get("/admin/install/stepAccount", "InstallationController@stepAccount");
Router::post("/admin/install/createAdminUser", "InstallationController@createAdminUser");

//


