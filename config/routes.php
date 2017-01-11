<?php

return array(
    'product/([0-9]+)' => 'product/view/$1', // actionView в ProductController
    
    'catalog' => 'catalog/index', // actionIndex в CatalogController
    
    'category/([0-9]+)/page-([0-9]+)'=>'catalog/category/$1/$2', //actionCategory in CatalogController
    'category/([0-9]+)'=>'catalog/category/$1', //actionCategory in CatalogController
    
    //Корзина
    'cart/add/([0-9]+)' =>'cart/add/$1', //actionAdd в CartController
    'cart/addAjax/([0-9]+)' =>'cart/addAjax/$1',//actionAddAjax in CartController
    'cart/checkout' => 'cart/checkout',//actionCheckout в CartController
    'cart/delete/([0-9]+)'=>'cart/delete/$1',//actionDelete в CartController
    'cart'=>'cart/index',//actionIndex in CartController
    //пользователь
    'user/register' => 'user/register',//action regiter in UserController
    'user/login' => 'user/login',//actionLogin in UserController
    'user/logout' => 'user/logout',//actionLogout in UserController
    'cabinet/edit' =>'cabinet/edit',//actionIndex в CabinetController
    'cabinet' =>'cabinet/index',//actionIndex в CabinetController
    
    
    'about'=> 'site/about',//actionAbout in SiteController
    'contacts' =>'site/contact',//actionContact в SiteController
    // Управление товарами
    'admin/product/create'=>'adminProduct/create',
    'admin/product/update/([0-9]+)'=>'adminProduct/update/$1',
    'admin/product/delete/([0-9]+)'=>'adminProduct/delete/$1',
    'admin/product'=>'adminProduct/index',
    // Управление категориями
    'admin/category/create'=>'adminCategory/create',
    'admin/category/update/([0-9]+)'=>'adminCategory/update/$1',
    'admin/category/delete/([0-9]+)'=>'adminCategory/delete/$1',
    'admin/category'=>'adminCategory/index',
    // Управление заказами
    'admin/order/view/([0-9]+)'=>'adminOrder/view/$1',
    'admin/order/update/([0-9]+)'=>'adminOrder/update/$1',
    'admin/order/delete/([0-9]+)'=>'adminOrder/delete/$1',
    'admin/order'=>'adminOrder/index',
    //Паналь администратора
    'admin'=>'admin/index',//actionIndex в AdminController
    
    ''=> 'site/index',
);
