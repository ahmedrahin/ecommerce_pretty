<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    // $trail->push('Dashboard',route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('notification', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Notifications', route('all.notification'));
});

// Home > Dashboard > Admin Management
Breadcrumbs::for('admin-management.admin-list.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Admin Management', route('admin-management.admin-list.index'));
});

Breadcrumbs::for('message', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Contact Messages', );
});
Breadcrumbs::for('message-details', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Contact Message Details', );
});

Breadcrumbs::for('compare', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Compare', );
});

Breadcrumbs::for('question', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Question', );
});
Breadcrumbs::for('question-details', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Reply to question', );
});

Breadcrumbs::for('subscription', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Subscription Email', );
});
Breadcrumbs::for('banner_content', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Banner Content', );
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('admin-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > User > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > Admin Management > Admin > [Admin]
Breadcrumbs::for('admin-management.admin-list.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('admin-management.admin-list.index');
    $trail->push(ucwords($user->name), route('admin-management.admin-list.show', $user));
});


// Home > Dashboard > User Management > Roles
Breadcrumbs::for('admin-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin-management.admin-list.index');
    $trail->push('Roles', route('admin-management.roles.index'));
});

// Home > Dashboard > Admin Management > Roles > [Role]
Breadcrumbs::for('admin-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('admin-management.roles.index');
    $trail->push(ucwords($role->name), route('admin-management.roles.show', $role));
});

// Home > Dashboard > Admin Management > Permission
Breadcrumbs::for('admin-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin-management.admin-list.index');
    $trail->push('Permissions', route('admin-management.permissions.index'));
});

Breadcrumbs::for('slider', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Banner Images', route('slider.index'));
});

Breadcrumbs::for('warranty', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Warranty List', route('warranty.index', [date('Y'),\Carbon\Carbon::now()->format('M')]));
    $trail->push('Create Warranty', route('warranty.create'));
});
Breadcrumbs::for('warrantyedit', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('dashboard');
    $trail->push('Warranty List', route('warranty.index', [date('Y'),\Carbon\Carbon::now()->format('M')]));
    $trail->push('Edit Warranty', route('warranty.edit',$id));
});
Breadcrumbs::for('monthlywarrantyList', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Monthly Warranty Sheet', route('warranty.index'));
});
Breadcrumbs::for('warrantyList', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Warranty List', route('warranty.index'));
});

Breadcrumbs::for('service', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Servicing List', route('service.index', [date('Y'),\Carbon\Carbon::now()->format('M')]));
    $trail->push('Create Servicing', route('service.create'));
});
Breadcrumbs::for('serviceedit', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('dashboard');
    $trail->push('Servicing List', route('service.index', [date('Y'),\Carbon\Carbon::now()->format('M')]));
    $trail->push('Edit Servicing', route('service.edit',$id));
});
Breadcrumbs::for('serviceList', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Servicing List', route('service.index'));
});

// Home > Dashboarad > Brand
Breadcrumbs::for('brand', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Brand', route('product-catalogue.brand.index'));
});
// Home > Dashboarad > Category
Breadcrumbs::for('category', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('product-catalogue.category.index'));
});
// Home > Dashboarad > Subcategory
Breadcrumbs::for('subcategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Subcategory', route('product-catalogue.subcategory.index'));
});
Breadcrumbs::for('subsubcategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Subsubcategory', route('product-catalogue.subsubcategory.index'));
});
// Home > Dashboarad > Product
Breadcrumbs::for('product', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Management', route('product-management.create'));
});
Breadcrumbs::for('product-details', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Details', route('product-management.create'));
});

// Home > Dashboarad > Product variant
Breadcrumbs::for('variant', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Variant', route('product-management.create'));
});
// Home > Dashboarad > Product review
Breadcrumbs::for('review', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Product Review', route('review.index'));
});
// Home > Dashboarad > shipping
Breadcrumbs::for('shipping', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('State List', route('shipping.state'));
});
// Home > Dashboarad > order
Breadcrumbs::for('orderlist', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Order List', );
});
Breadcrumbs::for('addOrder', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Add New Order', );
});
Breadcrumbs::for('trash_order', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Trash Order', );
});
Breadcrumbs::for('todayorder', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Today Orders', );
});

Breadcrumbs::for('monthlyorder', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Monthly Order List', );
});
Breadcrumbs::for('order', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Order Details', );
});
// Home > Dashboarad > order
Breadcrumbs::for('addorder', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Add Order', );
});
// Home > Dashboarad > order invoice
Breadcrumbs::for('invoice', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Order Invoice', );
});

Breadcrumbs::for('state', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('State/Area List', route('shipping.state'));
});

Breadcrumbs::for('shipping_method', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipping Methods', route('shipping.shipping_method'));
});

Breadcrumbs::for('coupon', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Coupon', route('coupon.index'));
});

// Home > Dashboarad > genarel setting
Breadcrumbs::for('setting', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Genarel Settings', );
});
// Home > Dashboarad > genarel setting
Breadcrumbs::for('webitesetting', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Website Settings', );
});

Breadcrumbs::for('createoffer', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Offer List', route('offer.index'));
    $trail->push('Create New Offer', route('offer.index'));
});

Breadcrumbs::for('offer', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Offer List', route('offer.index'));
});

Breadcrumbs::for('filter', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Category Filter', route('product-catalogue.product.filter'));
});

Breadcrumbs::for('editoffer', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('dashboard');
    $trail->push('Offer List', route('offer.index'));
    $trail->push('Edit Offer', route('offer.edit',$id));
});


//report
Breadcrumbs::for('stockout', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Stock Out Report', route('report.stockout'));
});
Breadcrumbs::for('lowstock', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Low Stock Report', route('report.lowstock'));
});
Breadcrumbs::for('stockin', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Stock In Report', route('report.stockin'));
});
Breadcrumbs::for('addstock', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Stock In', route('report.stockin',[date('Y'), \Carbon\Carbon::now()->format('M')]));
    $trail->push('Add new stock', route('report.add.stock'));
});
