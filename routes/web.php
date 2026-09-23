<?php

/*
|--------------------------------------------------------------------------
| SPBE SLA Plugin Routes
|--------------------------------------------------------------------------
| Registered via add_route() for LeazyCMS
*/

// --- Admin Routes ---
// Core SLA Modules
$dashCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\DashboardController';
$serviceCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\DigitalServiceController';
$ticketCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\TicketController';
$reviewCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\SlaReviewController';
$syncCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\SlaSyncController';

// Unit Kerja Modules
$unitKerjaCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\UnitKerjaController';
$assignUserCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\AssignUserController';

// Dashboard & Settings
add_route('admin', ['title' => 'SLA Dashboard', 'name' => 'spbe-sla.dashboard.index', 'icon' => 'fa-tachometer-alt', 'path' => 'spbe-sla/dashboard', 'method' => 'get', 'function' => 'index', 'controller' => $dashCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Settings', 'name' => 'spbe-sla.settings.index', 'icon' => 'fa-cog', 'path' => 'spbe-sla/settings', 'method' => 'get', 'function' => 'settings', 'controller' => $dashCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Settings Update', 'name' => 'spbe-sla.settings.update', 'icon' => '', 'path' => 'spbe-sla/settings', 'method' => 'put', 'function' => 'settings', 'controller' => $dashCtrl, 'show_in_sidebar' => false]);

// Unit Kerja
add_route('admin', ['title' => 'Unit Kerja', 'name' => 'spbe-sla.unit-kerja.index', 'icon' => 'fa-building', 'path' => 'spbe-sla/unit-kerja', 'method' => 'get', 'function' => 'index', 'controller' => $unitKerjaCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Add Unit Kerja', 'name' => 'spbe-sla.unit-kerja.create', 'icon' => '', 'path' => 'spbe-sla/unit-kerja/create', 'method' => 'get', 'function' => 'create', 'controller' => $unitKerjaCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Store Unit Kerja', 'name' => 'spbe-sla.unit-kerja.store', 'icon' => '', 'path' => 'spbe-sla/unit-kerja', 'method' => 'post', 'function' => 'store', 'controller' => $unitKerjaCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Edit Unit Kerja', 'name' => 'spbe-sla.unit-kerja.edit', 'icon' => '', 'path' => 'spbe-sla/unit-kerja/{unit_kerja}/edit', 'method' => 'get', 'function' => 'edit', 'controller' => $unitKerjaCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Update Unit Kerja', 'name' => 'spbe-sla.unit-kerja.update', 'icon' => '', 'path' => 'spbe-sla/unit-kerja/{unit_kerja}', 'method' => 'put', 'function' => 'update', 'controller' => $unitKerjaCtrl, 'show_in_sidebar' => false]);

// Assign Unit Kerja Users
add_route('admin', ['title' => 'Assign Users', 'name' => 'spbe-sla.users.index', 'icon' => 'fa-users-cog', 'path' => 'spbe-sla/users', 'method' => 'get', 'function' => 'index', 'controller' => $assignUserCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Assign Users Update', 'name' => 'spbe-sla.users.assign', 'icon' => '', 'path' => 'spbe-sla/users/assign', 'method' => 'post', 'function' => 'assign', 'controller' => $assignUserCtrl, 'show_in_sidebar' => false]);

// Digital Services
add_route('admin', ['title' => 'Digital Services', 'name' => 'spbe-sla.services.index', 'icon' => 'fa-server', 'path' => 'spbe-sla/services', 'method' => 'get', 'function' => 'index', 'controller' => $serviceCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Add Service', 'name' => 'spbe-sla.services.create', 'icon' => '', 'path' => 'spbe-sla/services/create', 'method' => 'get', 'function' => 'create', 'controller' => $serviceCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Store Service', 'name' => 'spbe-sla.services.store', 'icon' => '', 'path' => 'spbe-sla/services', 'method' => 'post', 'function' => 'store', 'controller' => $serviceCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Edit Service', 'name' => 'spbe-sla.services.edit', 'icon' => '', 'path' => 'spbe-sla/services/{service}/edit', 'method' => 'get', 'function' => 'edit', 'controller' => $serviceCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Update Service', 'name' => 'spbe-sla.services.update', 'icon' => '', 'path' => 'spbe-sla/services/{service}', 'method' => 'put', 'function' => 'update', 'controller' => $serviceCtrl, 'show_in_sidebar' => false]);

// App Development
$appDevCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Admin\AppDevelopmentController';
add_route('admin', ['title' => 'App Development', 'name' => 'spbe-sla.app-dev.index', 'icon' => 'fa-code', 'path' => 'spbe-sla/app-dev', 'method' => 'get', 'function' => 'index', 'controller' => $appDevCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Add App Development', 'name' => 'spbe-sla.app-dev.create', 'icon' => '', 'path' => 'spbe-sla/app-dev/create', 'method' => 'get', 'function' => 'create', 'controller' => $appDevCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Store App Development', 'name' => 'spbe-sla.app-dev.store', 'icon' => '', 'path' => 'spbe-sla/app-dev', 'method' => 'post', 'function' => 'store', 'controller' => $appDevCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Edit App Development', 'name' => 'spbe-sla.app-dev.edit', 'icon' => '', 'path' => 'spbe-sla/app-dev/{app_dev}/edit', 'method' => 'get', 'function' => 'edit', 'controller' => $appDevCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Update App Development', 'name' => 'spbe-sla.app-dev.update', 'icon' => '', 'path' => 'spbe-sla/app-dev/{app_dev}', 'method' => 'put', 'function' => 'update', 'controller' => $appDevCtrl, 'show_in_sidebar' => false]);

// Tickets
add_route('admin', ['title' => 'Tickets', 'name' => 'spbe-sla.tickets.index', 'icon' => 'fa-ticket-alt', 'path' => 'spbe-sla/tickets', 'method' => 'get', 'function' => 'index', 'controller' => $ticketCtrl, 'show_in_sidebar' => true]);
add_route('admin', ['title' => 'Create Ticket', 'name' => 'spbe-sla.tickets.create', 'icon' => '', 'path' => 'spbe-sla/tickets/create', 'method' => 'get', 'function' => 'create', 'controller' => $ticketCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Store Ticket', 'name' => 'spbe-sla.tickets.store', 'icon' => '', 'path' => 'spbe-sla/tickets', 'method' => 'post', 'function' => 'store', 'controller' => $ticketCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'View Ticket', 'name' => 'spbe-sla.tickets.show', 'icon' => '', 'path' => 'spbe-sla/tickets/{ticket}/show', 'method' => 'get', 'function' => 'show', 'controller' => $ticketCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Update Status', 'name' => 'spbe-sla.tickets.update-status', 'icon' => '', 'path' => 'spbe-sla/tickets/{ticket}/status', 'method' => 'post', 'function' => 'updateStatus', 'controller' => $ticketCtrl, 'show_in_sidebar' => false]);
add_route('admin', ['title' => 'Add Comment', 'name' => 'spbe-sla.tickets.comment', 'icon' => '', 'path' => 'spbe-sla/tickets/{ticket}/comment', 'method' => 'post', 'function' => 'storeComment', 'controller' => $ticketCtrl, 'show_in_sidebar' => false]);

// SLA Reviews
add_route('admin', ['title' => 'SLA Reviews', 'name' => 'spbe-sla.reviews.index', 'icon' => 'fa-chart-line', 'path' => 'spbe-sla/reviews', 'method' => 'get', 'function' => 'index', 'controller' => $reviewCtrl, 'show_in_sidebar' => true]);

// --- API Routes (Prefix applied automatically by leazycms, usually /api) ---
add_route('api', ['name' => 'spbe-sla.api.sync', 'path' => 'v1/national-portal/sync', 'method' => 'post', 'function' => 'sync', 'controller' => $syncCtrl]);

// --- Public / Custom Domain Routes ---
$pubAuthCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Public\AuthController';
$pubDashCtrl = 'App\Http\Controllers\Plugins\SpbeSla\Public\DashboardController';

// We specify plugin name so `add_plugin_public_route` handles main vs custom domain correctly
$pluginName = 'spbe-sla';

// Auth
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.home', 'method' => 'GET', 'path' => '/', 'controller' => $pubAuthCtrl, 'function' => 'showLogin']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.login', 'method' => 'GET', 'path' => '/login', 'controller' => $pubAuthCtrl, 'function' => 'showLogin']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.login.submit', 'method' => 'POST', 'path' => '/login', 'controller' => $pubAuthCtrl, 'function' => 'login']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.logout', 'method' => 'POST', 'path' => '/logout', 'controller' => $pubAuthCtrl, 'function' => 'logout']);

// Dashboard (Requires Auth - We use auth middleware in controllers or we just define it here. Better to protect it.)
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.dashboard', 'method' => 'GET', 'path' => '/dashboard', 'controller' => $pubDashCtrl, 'function' => 'index']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.tickets.create', 'method' => 'GET', 'path' => '/tickets/request', 'controller' => $pubDashCtrl, 'function' => 'createTicket']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.tickets.store', 'method' => 'POST', 'path' => '/tickets/request', 'controller' => $pubDashCtrl, 'function' => 'storeTicket']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.tickets.show', 'method' => 'GET', 'path' => '/tickets/{ticket}/show', 'controller' => $pubDashCtrl, 'function' => 'showTicket']);
add_plugin_public_route(['plugin' => $pluginName, 'name' => 'spbe-sla.public.tickets.comment', 'method' => 'POST', 'path' => '/tickets/{ticket}/comment', 'controller' => $pubDashCtrl, 'function' => 'storeComment']);
