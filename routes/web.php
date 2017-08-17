<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Backend
Route::group(['namespace' => 'backend'], function() {
    // Auth
    Route::get('admin', 'LoginController@index');
    Route::post('admin/login', ['as' => 'backend.login', 'uses' => 'LoginController@login']);
    Route::get('admin/logout', ['as' => 'backend.logout', 'uses' => 'LoginController@logout']);

    // Dashboard
    Route::get('admin/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    // Users
    Route::resource('admin/users', 'UsersController');
    Route::get('admin/users/status/{id}', ['as' => 'users.status', 'uses' => 'UsersController@status']);
    Route::get('admin/users/delete/{id}', ['as' => 'users.delete', 'uses' => 'UsersController@destroy']);

    // Profile
    Route::get('admin/profile', ['as' => 'profile.edit', 'uses' => 'AccountController@edit']);
    Route::put('admin/profile', ['as' => 'profile.update', 'uses' => 'AccountController@update']);

    // Pages
    Route::resource('admin/pages', 'PagesController');
    Route::get('admin/pages/delete/{id}', ['as' => 'pages.delete', 'uses' => 'PagesController@destroy']);

    // Banners
    Route::resource('admin/banners', 'BannersController');
    Route::get('admin/banners/delete/{id}', ['as' => 'banners.delete', 'uses' => 'BannersController@destroy']);

    // Search
    Route::get('admin/search', ['as' => 'backend.search', 'uses' => 'SearchController@index']);

    // Settings
    Route::get('admin/settings', ['as' => 'settings.edit', 'uses' => 'SettingsController@edit']);
    Route::put('admin/settings/update', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
});

// Frontend
Route::group(['namespace' => 'frontend'], function() {
    // Payments
    Route::resource('payments', 'PaymentsController');
    Route::get('payments/delete/{id}', ['as' => 'payments.destroy', 'uses' => 'BackPaymentsController@destroy']);

    // Kreditai
    Route::get('mano-kreditai', ['as' => 'credits', 'uses' => 'CreditsController@index']);

    // Apmokėjimas
    Route::get('apmoketa', ['as' => 'payments.success', 'uses' => 'PaymentsController@success']);
    Route::get('mokejimas-atsauktas', ['as' => 'payments.cancel', 'uses' => 'PaymentsController@cancel']);
    Route::post('payment-callback', ['as' => 'payments.callback', 'uses' => 'PaymentsController@callback']);

    // Mano anketos
    Route::get('mano-anketos', ['as' => 'campaigns.my', 'uses' => 'CampaignsController@my']);
    Route::get('sukurti-anketa', ['as' => 'campaigns.create', 'uses' => 'CampaignsController@create']);
    Route::post('sukurti-anketa', ['as' => 'campaigns.store', 'uses' => 'CampaignsController@store']);
    Route::get('istrinti-anketa/{id}', ['as' => 'campaigns.destroy', 'uses' => 'CampaignsController@destroy']);

    Route::get('anketa/{id}/nustatymai', ['as' => 'campaigns.edit', 'uses' => 'CampaignsController@edit']);
    Route::post('anketa/{id}/nustatymai', ['as' => 'campaigns.update', 'uses' => 'CampaignsController@update']);

    Route::get('anketa/{id}/kopijuoti', ['as' => 'campaigns.copy', 'uses' => 'CampaignsController@copy']);

    Route::get('anketa/{id}/deaktyvuoti', ['as' => 'campaigns.deactivate', 'uses' => 'CampaignsController@deactivate']);

    Route::get('anketa/{id}/klausimai', ['as' => 'campaigns.questions', 'uses' => 'CampaignsController@questions']);

    Route::get('anketa/{id}/rezultatai', ['as' => 'campaigns.results', 'uses' => 'CampaignsController@results']);
    Route::post('anketa/{id}/rezultatai/poriniai-stebejimai', ['as' => 'campaigns.cross_tabulation', 'uses' => 'CampaignsController@cross_tabulation']);
    Route::post('anketa/{id}/rezultatai/koreliacine-analize', ['as' => 'campaigns.correlation', 'uses' => 'CampaignsController@correlation_analysis']);
    Route::post('anketa/{id}/rezultatai/regresine-analize', ['as' => 'campaigns.regression', 'uses' => 'CampaignsController@regression_analysis']);
    Route::get('anketa/{id}/rezultatai/xlsx', ['as' => 'campaigns.results.xlsx', 'uses' => 'CampaignsController@results_xlsx']);

    Route::get('anketa/{id}/klausimai/prideti-klausima/{type}', ['as' => 'campaigns.questions.add', 'uses' => 'CampaignsController@add_question']);
    Route::post('anketa/{id}/klausimai/prideti-klausima/{type}', ['as' => 'campaigns.questions.store', 'uses' => 'CampaignsController@store_question']);

    Route::get('anketa/{id}/klausimai/redaguoti-klausima/{question_id}', ['as' => 'campaigns.questions.edit', 'uses' => 'CampaignsController@edit_question']);
    Route::post('anketa/{id}/klausimai/redaguoti-klausima/{question_id}', ['as' => 'campaigns.questions.update', 'uses' => 'CampaignsController@update_question']);

    Route::get('anketa/{id}/klausimai/istrinti-klausima/{question_id}', ['as' => 'campaigns.questions.destroy', 'uses' => 'CampaignsController@destroy_question']);

    // Atsakymai į anketą
    Route::get('anketos-pildymas/{id}', ['as' => 'campaigns.answer', 'uses' => 'CampaignsController@answer']);
    Route::post('anketos-pildymas/{id}', ['as' => 'campaigns.answer.store', 'uses' => 'CampaignsController@store_answer']);
    Route::get('anketa-nerasta', ['as' => 'campaigns.notfound', 'uses' => 'CampaignsController@notfound']);
    Route::get('anketa-uzpildyta', ['as' => 'campaigns.answered', 'uses' => 'CampaignsController@answered']);

    // Anketos rezultatai
    Route::get('anketos-rezultatai/{id}', ['as' => 'campaigns.answers', 'uses' => 'CampaignsController@answers']);

    // Anketų sąrašas
    Route::get('anketu-sarasas', ['as' => 'campaigns', 'uses' => 'CampaignsController@index']);
    Route::get('anketu-paieska', ['as' => 'campaigns.search', 'uses' => 'CampaignsController@search']);

    // Paskyros nustatymai
    Route::get('paskyros-nustatymai', ['as' => 'account.index', 'uses' => 'AccountController@index']);
    Route::post('paskyros-nustatymai', ['as' => 'account.update', 'uses' => 'AccountController@update']);

    // Password forgot
    Route::get('priminti-slaptazodi', ['as' => 'password.remind', 'uses' => 'RemindersController@getRemind']);
    Route::post('priminti-slaptazodi', ['as' => 'password.remind.post', 'uses' => 'RemindersController@postRemind']);
    Route::get('atkurti-slaptazodi/{token}', ['as' => 'password.reset', 'uses' => 'RemindersController@getReset']);
    Route::post('atkurti-slaptazodi', ['as' => 'password.reset.post', 'uses' => 'RemindersController@postReset']);
    Route::get('slaptazodis-pakeistas', ['as' => 'password.success', 'uses' => 'RemindersController@success']);

    // Login & Registration
    Route::get('prisijungimas', ['as' => 'login', 'uses' => 'LoginController@index']);
    Route::get('registruotis', ['as' => 'login.registration', 'uses' => 'LoginController@registration']);
    Route::post('registruotis', ['as' => 'login.register', 'uses' => 'LoginController@register']);
    Route::post('prisijungti', ['as' => 'login.session', 'uses' => 'LoginController@login']);
    Route::get('atsijungti', ['as' => 'login.logout', 'uses' => 'LoginController@logout']);
    Route::get('registruotis/facebook', ['as' => 'login.register_facebook', 'uses' => 'LoginController@redirectToFacebook']);
    Route::get('registruotis/facebook/callback', 'LoginController@handleFacebookCallback');
    Route::get('registruotis/google', ['as' => 'login.register_google', 'uses' => 'LoginController@redirectToGoogle']);
    Route::get('registruotis/google/callback', 'LoginController@handleGoogleCallback');

    // Pages
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('{page}', 'PagesController@index');
});