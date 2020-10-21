<?php

/* Authentication */
Route::post('register', 'Authentication\AuthController@register')->name('authentication.register');

Route::post('admin/login', 'Authentication\AuthController@adminLogin')->name('authentication.admin.login');
Route::post('login', 'Authentication\AuthController@login')->name('authentication.login');
Route::post('locale', 'Authentication\AuthController@setLocale')->name('locale');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', 'Authentication\AuthController@logout')->name('authentication.logout');
    Route::get('/payload', 'Authentication\AuthController@payload')->name('authentication.payload');
    Route::get('/user', 'Authentication\AuthController@user')->name('authentication.user');

    /* Stats */
    Route::get('stats/projects-created', 'Stat\StatController@projectsCreated')->name('stats.properties.created');
    Route::get('stats/projects-viewed/{developer?}', 'Stat\StatController@projectsViewed')->name('stats.properties.viewed');
    Route::get('stats/projects-who-viewed/{developer?}', 'Stat\StatController@projectsWhoViewed')->name('stats.properties.whoViewed');
    Route::get('stats/users-logins-and-signups', 'Stat\StatController@usersLoginsAndSignups')->name('stats.users.usersLoginsAndSignups');
    Route::get('stats/developers-without-agreement', 'Stat\StatController@developersWithoutAgreement')->name('stats.developers.developersWithoutAgreement');
    Route::get('stats/payments-verified-and-unverified', 'Stat\StatController@paymentsVerifiedAndUnverified')->name('stats.payments.paymentsVerifiedAndUnverified');

    /* Ads */
    Route::get('ad', 'Ad\AdController@random')->name('ad.show.random');
    Route::get('ads', 'Ad\AdController@index')->name('ads.index');
    Route::get('ads/{ad}', 'Ad\AdController@show')->name('ads.show');
    Route::post('ads', 'Ad\AdController@store')->name('ads.store');

    Route::get('ads/{ad}/areas', 'Ad\AdController@getAdAreas')->name('ad.areas');
    Route::get('ads/{ad}/developer', 'Ad\AdController@getAdDeveloper')->name('ad.developer');
    Route::get('ads/{ad}/leads', 'Ad\AdController@getAdLeads')->name('ad.leads');

    /* Agreements */
    Route::get('agreements', 'Agreement\AgreementController@index')->name('agreements.index');
    Route::get('agreements/{agreement}', 'Agreement\AgreementController@show')->name('agreements.show');

    /* Areas */
    Route::get('areas', 'Area\AreaController@index')->name('areas.index');
    Route::get('areas/{area}', 'Area\AreaController@show')->name('areas.show');
    Route::get('areas/{area}/ads', 'Area\AreaController@getAreaAds')->name('area.ads');
    Route::get('areas/{area}/ad', 'Area\AreaController@getRandomAdForArea')->name('area.ad.random');

    /* Developers */
    Route::get('developers', 'Developer\DeveloperController@index')->name('developers.index');
    Route::get('developers/{developer}', 'Developer\DeveloperController@show')->name('developers.show');
    Route::post('developers', 'Developer\DeveloperController@store')->name('developers.store');
    Route::put('developers/{developer}', 'Developer\DeveloperController@update')->name('developers.update');
    Route::delete('developers/{developer}', 'Developer\DeveloperController@destroy')->name('developers.destroy');

    /* Developer Agreements */
    Route::get('developers/{developer}/agreements', 'Developer\DeveloperAgreementController@index')->name('developers.agreements.index');
    Route::get('developers/{developer}/agreements/{agreement}', 'Developer\DeveloperAgreementController@show')->name('developers.agreements.show');
    Route::put('developers/{developer}/agreements/{agreement}', 'Developer\DeveloperAgreementController@update')->name('developers.agreements.update');
    Route::delete('developers/{developer}/agreements/{agreement}', 'Developer\DeveloperAgreementController@destroy')->name('developers.agreements.destroy');

    /* Developer Agreements with Stripe payments */
    Route::get('stripe/plans', 'Developer\DeveloperAgreementController@getStripePlans')->name('stripe.plans.index');
    Route::post('stripe/subscriptions', 'Developer\DeveloperAgreementController@storeWithPayment')->name('stripe.subscription.store');
    Route::post('stripe/subscriptions/cancel', 'Developer\DeveloperAgreementController@destroyWithPayment')->name('stripe.subscription.cancel');

    /* Developer Properties */
    Route::get('developers/{developer}/properties', 'Developer\DeveloperPropertyController@index')->name('developers.properties.index');

    /* Developer Ads */
    Route::get('developers/{developer}/ads', 'Developer\DeveloperAdController@index')->name('developers.ads.index');
    Route::get('developers/{developer}/ads/{ad}', 'Developer\DeveloperAdController@show')->name('developers.ads.show');
    /* TODO: It can be a situation when developer will not be able to create new add. Remove this endpoint. */
    Route::post('developers/{developer}/ads', 'Developer\DeveloperAdController@store')->name('developers.ads.store');
    Route::put('developers/{developer}/ads/{ad}', 'Developer\DeveloperAdController@update')->name('developers.ads.update');
    Route::delete('developers/{developer}/ads/{ad}', 'Developer\DeveloperAdController@destroy')->name('developers.ads.destroy');
    Route::delete('developers/{developer}/ads/{ad}/image', 'Developer\DeveloperAdController@destroyImage')->name('developers.ads.destroyImage');

    /* PDF Reports */
    Route::get('pdf/reportOne', 'Pdf\PdfController@pdfReportOne')->name('pdf.reportOne');
    Route::get('pdf/reportTwo', 'Pdf\PdfController@pdfReportTwo')->name('pdf.reportTwo');
    Route::get('pdf/reportThree', 'Pdf\PdfController@pdfReportThree')->name('pdf.reportThree');

    /* Leads */
    Route::get('leads', 'Lead\LeadController@index')->name('leads.index');
    Route::get('leads/{lead}', 'Lead\LeadController@show')->name('leads.show');
    Route::get('leads/{lead}/ad', 'Lead\LeadController@getAdForLead')->name('leads.ad');
    Route::get('leads/{lead}/user', 'Lead\LeadController@getUserForLead')->name('leads.user');

    /* Properties */
    Route::get('properties', 'Property\PropertyController@index')->name('properties.index');
    Route::get('properties/{property}', 'Property\PropertyController@show')->name('properties.show');
    Route::post('properties', 'Property\PropertyController@store')->name('properties.store');
    Route::put('properties/{property}', 'Property\PropertyController@update')->name('properties.update');
    Route::delete('properties/{property}', 'Property\PropertyController@destroy')->name('properties.destroy');

    Route::post('properties/{property}/visit', 'Property\PropertyController@visit')->name('properties.visit');

    /* Property Developer */
    Route::get('properties/{property}/developers', 'Property\PropertyController@getDeveloperForProperty');

    /* Property Location */
    Route::get('properties/{property}/locations', 'Property\PropertyLocationController@index')->name('properties.location.index');
    Route::post('properties/{property}/locations', 'Property\PropertyLocationController@store')->name('properties.location.store');
    Route::put('properties/{property}/locations/{location}', 'Property\PropertyLocationController@update')->name('properties.location.update');
    Route::delete('properties/{property}/locations/{location}', 'Property\PropertyLocationController@destroy')->name('properties.location.destroy');

    /* Property Features */
    Route::get('properties/{property}/features', 'Property\PropertyFeatureController@index')->name('properties.feature.index');
    Route::post('properties/{property}/features', 'Property\PropertyFeatureController@store')->name('properties.feature.store');
    Route::put('properties/{property}/features/{feature}', 'Property\PropertyFeatureController@update')->name('properties.feature.update');
    Route::delete('properties/{property}/features/{feature}', 'Property\PropertyFeatureController@destroy')->name('properties.feature.destroy');

    /* Property Images */
    Route::get('properties/{property}/images', 'Property\PropertyImageController@index')->name('properties.images.index');
    Route::post('properties/{property}/images', 'Property\PropertyImageController@store')->name('properties.images.store');
    Route::delete('properties/{property}/images/{image}', 'Property\PropertyImageController@destroy')->name('properties.images.destroy');

    /* Property Videos */
    Route::get('properties/{property}/videos', 'Property\PropertyVideoController@index')->name('properties.videos.index');
    Route::post('properties/{property}/videos', 'Property\PropertyVideoController@store')->name('properties.videos.store');
    Route::delete('properties/{property}/videos/{video}', 'Property\PropertyVideoController@destroy')->name('properties.videos.destroy');

    /* Users */
    Route::get('users', 'User\UserController@index')->name('users.index');
    Route::get('users/{user}', 'User\UserController@show')->name('users.show');
    Route::post('users', 'User\UserController@store')->name('users.store');
    Route::put('users/{user}', 'User\UserController@update')->name('users.update');
    Route::delete('users/{user}', 'User\UserController@destroy')->name('users.destroy');

    Route::get('users/{user}/leads', 'User\UserController@getLeadsForUser')->name('user.leads');
    Route::get('users/{user}/properties', 'User\UserController@getPropertiesForUser')->name('user.properties');
    Route::post('users/{user}/ads/{ad}/leads', 'User\UserController@storeLeadForUser')->name('user.lead.store');
    Route::delete('users/{user}/ads/{ad}/leads/{lead}', 'User\UserController@destroyLeadForUser')->name('user.lead.destroy');
});
