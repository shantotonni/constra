<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Api\Frontend\CustomerAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CollegesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPermissionController;
use App\Http\Controllers\OurTeamController;
use App\Http\Controllers\PageController;

use App\Http\Controllers\QuestionAnswerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TestimonialController;


use App\Http\Controllers\WhyChooseUsController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SettingController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CommonController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('app-supporting-data', [SettingController::class, 'appSupportingData']);
});

Route::group(['middleware' => ['jwt:api']], function () {
    // ADMIN USERS
    Route::apiResource('users',UserController::class);
    Route::get('search/users/{query}', [UserController::class,'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);
    Route::get('get-all-role/', [UserController::class, 'getAllRole']);

    //services
    Route::apiResource('services',ServiceController::class);
    Route::get('service-details/{id}',[ServiceController::class,'show']);
    //WHY CHOOSE US
    Route::apiResource('why-choose-us',WhyChooseUsController::class);
    Route::get('why-choose-us-details/{id}',[WhyChooseUsController::class,'show']);
    //colleges
    Route::apiResource('colleges',CollegesController::class);
    //Slider
    Route::apiResource('sliders',SliderController::class);
    Route::get('slider-details/{id}',[SliderController::class,'show']);
    Route::get('search/sliders/{query}', [SliderController::class,'search']);

    //About
    Route::apiResource('abouts',AboutController::class);
    Route::get('search/abouts/{query}', [AboutController::class,'search']);


    //Gallery
    Route::apiResource('gallery',GalleryController::class);
    Route::get('search/gallery/{query}', [GalleryController::class,'search']);

    //customer
    Route::apiResource('customers',CustomerController::class);
    Route::get('search/customers/{query}', [CustomerController::class,'search']);
    Route::get('get-all-customer-events', [CustomerController::class,'getAllCustomerEvents']);
    Route::get('export-customer', [CustomerController::class,'exportCustomer']);
    Route::get('export-event', [CustomerController::class,'exportEvent']);

    //pages
    Route::apiResource('pages',PageController::class);
    Route::get('page-details/{id}',[PageController::class,'show']);
    Route::get('search/pages/{query}', [PageController::class,'search']);


    //BLOG
    Route::apiResource('news',BlogController::class);
    Route::get('news-details/{id}',[BlogController::class,'show']);
    Route::get('search/news/{query}', [BlogController::class,'search']);

    //colleges
    Route::apiResource('college',CollegesController::class);
    Route::get('search/college/{query}', [CollegesController::class,'search']);
    //testimonial
    Route::apiResource('testimonial',TestimonialController::class);
    Route::get('search/testimonial/{query}', [TestimonialController::class,'search']);

    //our team

    Route::apiResource('our-team',OurTeamController::class);
    Route::get('our-team-details/{id}',[OurTeamController::class,'show']);
    Route::get('search/our-team/{query}', [OurTeamController::class,'search']);

    //QUESTION ANSWER

    Route::apiResource('question-answer',QuestionAnswerController::class);
    Route::get('question-answer-details/{id}',[QuestionAnswerController::class,'show']);
    Route::get('search/question-answer/{query}', [QuestionAnswerController::class,'search']);

    //time schedule

    Route::apiResource('time-schedule',ScheduleController::class);
    Route::get('search/time-schedule/{query}', [ScheduleController::class,'search']);
    //countries
    Route::apiResource('countries',CountriesController::class);
    Route::get('search/countries/{query}', [CountriesController::class,'search']);


    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class,'search']);
    Route::get('get-all-menu', [MenuController::class,'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class,'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class,'saveUserMenuPermission']);

    Route::get('get-all-session', [CommonController::class,'getAllSession']);

    //new route created by shanto
    Route::get('contacts', [\App\Http\Controllers\Api\ContactController::class,'list']);
    Route::get('questions', [\App\Http\Controllers\Api\QuestionController::class,'list']);

    //change-password
    Route::post('change-password', [SettingController::class,'changePassword']);
    Route::get('get-all-setting', [SettingController::class,'getAllSetting']);
    Route::post('update-setting', [SettingController::class,'UpdateSetting']);

});

//For Frontend

//customer login
Route::post('auth/login', [CustomerAuthController::class, 'login']);
Route::post('auth/logout', [CustomerAuthController::class, 'logout']);
Route::get('auth/user', [CustomerAuthController::class, 'me']);
Route::post('auth/registration', [CustomerAuthController::class, 'registration']);

Route::group(['middleware' => 'CustomerAuth'], function () {
    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfile']);
    Route::get('get-customer-donation-list', [CustomerAuthController::class, 'customerDonationList']);
    Route::get('get-customer-program-list', [CustomerAuthController::class, 'customerProgramList']);
    Route::get('donate-print/{id}', [CustomerAuthController::class, 'donatePrint']);

    Route::get('join-events', [CustomerController::class, 'joinEvents']);
    Route::post('get-all-invoice-data', [CustomerController::class, 'invoiceData']);
});

Route::get('get-pages', [\App\Http\Controllers\Api\Frontend\PagesController::class, 'getPages']);


Route::get('get-our-blog', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getOurBlog']);

Route::get('get-all-slider', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getHomePageSlider']);
Route::get('get-settings', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getSettings']);
Route::get('get-about', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAbout']);
Route::get('get-all-services', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllServices']);
Route::get('get-service-details', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getServicesDetails']);
Route::get('get-all-choose', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllChoose']);
Route::get('get-all-question-answer-list', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllQA']);
Route::get('get-all-testimonial', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllTestimonials']);
Route::get('get-all-news', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllNews']);
Route::get('get-blog-details', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getBlogDetails']);
Route::get('get-team', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getTeam']);
Route::get('get-all-college', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllCollege']);
Route::get('get-all-countries', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllCountry']);
Route::get('get-page', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getPage']);
Route::get('get-morning-time', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getMorningTime']);
Route::get('get-afternoon-time', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAfternoonTime']);
Route::get('get-evening-time', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getEveningTime']);

Route::get('get-our-gallery', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getOurGallery']);
Route::get('get-testimonial', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getTestimonial']);
Route::get('get-frontend-menu', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getFrontendMenu']);


Route::post('immigration-registration', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'immigrationRegistration']);
Route::post('contact', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'contact']);
Route::post('free-evaluation-store', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'freeEvaluationStore']);
Route::post('cart-booking', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'cartAdd']);
Route::get('get-cart-item', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getCartItem']);
Route::get('cart-item-delete', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'cartItemDelete']);
Route::get('get-all-countries', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllCountry']);
Route::get('get-all-home-page-date', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAllHomePageDate']);




