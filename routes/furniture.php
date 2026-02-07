<?php

use App\Http\Controllers\Backend\Appearance\Furniture\FeaturedCollectionProductsController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionFiveController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionFourController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionOneController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionSixController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionThreeController;
use App\Http\Controllers\Backend\Appearance\Furniture\BannerSectionTwoController;
use App\Http\Controllers\Backend\Appearance\Furniture\ClientFeedbackController;
use App\Http\Controllers\Backend\Appearance\Furniture\HeroController;
use App\Http\Controllers\Backend\Appearance\Furniture\HomepageController;
use App\Http\Controllers\Backend\Appearance\Furniture\TopCategoriesController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halal Theme Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 

Route::group(
    ['prefix' => 'admin', 'middleware' => ['auth', 'admin']],
    function () {  
        # appearance
        Route::group(['prefix' => 'appearance/furniture'], function () {

            # homepage - hero
            Route::get('/homepage/hero', [HeroController::class, 'hero'])->name('admin.appearance.furniture.homepage.hero');
            Route::post('/homepage/hero', [HeroController::class, 'storeHero'])->name('admin.appearance.furniture.homepage.storeHero');
            Route::get('/homepage/hero/edit/{id}', [HeroController::class, 'edit'])->name('admin.appearance.furniture.homepage.editHero');
            Route::post('/homepage/hero/update', [HeroController::class, 'update'])->name('admin.appearance.furniture.homepage.updateHero');
            Route::get('/homepage/hero/delete/{id}', [HeroController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteHero');
        
            # homepage - top trending products
            Route::get('/homepage/featured-collection-products', [FeaturedCollectionProductsController::class, 'index'])
                ->name('admin.appearance.furniture.homepage.featuredCollectionProducts');

            Route::post('/homepage/get-products-for-featured', [FeaturedCollectionProductsController::class, 'getProducts'])
                ->name('admin.appearance.furniture.homepage.getFeaturedCollectionProducts');

            Route::get('/homepage/featured-brand-products', [FeaturedCollectionProductsController::class, 'featureBrandProducts'])
                ->name('admin.appearance.furniture.homepage.featuredBrandProducts');

            # homepage - top category 
            Route::get('/homepage/top-category', [TopCategoriesController::class, 'index'])->name('admin.appearance.furniture.homepage.topCategories');

            # homepage - about us 
            Route::get('/homepage/about-us', [HomepageController::class, 'aboutUs'])->name('admin.appearance.furniture.homepage.aboutUs');

            # homepage - features
            Route::get('/homepage/features', [HomepageController::class, 'features'])->name('admin.appearance.furniture.homepage.features');        
            # homepage - popular
            Route::get('/homepage/popular', [HomepageController::class, 'popular'])->name('admin.appearance.furniture.homepage.popular');            
            # homepage - whyChooseUs
            Route::get('/homepage/why-choose-us', [HomepageController::class, 'whyChooseUs'])->name('admin.appearance.furniture.homepage.whyChooseUs');
            
            # client feedback
            Route::get('/homepage/client-feedback', [ClientFeedbackController::class, 'index'])->name('admin.appearance.furniture.homepage.clientFeedback');
            Route::post('/homepage/client-feedback', [ClientFeedbackController::class, 'store'])->name('admin.appearance.furniture.homepage.storeClientFeedback');
            Route::get('/homepage/client-feedback/edit/{id}', [ClientFeedbackController::class, 'edit'])->name('admin.appearance.furniture.homepage.editClientFeedback');
            Route::post('/homepage/client-feedback/update', [ClientFeedbackController::class, 'update'])->name('admin.appearance.furniture.homepage.updateClientFeedback');
            Route::get('/homepage/client-feedback/delete/{id}', [ClientFeedbackController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteClientFeedback');
            
            # homepage - onSaleProducts
            Route::get('/homepage/on-sale-products', [HomepageController::class, 'onSaleProducts'])->name('admin.appearance.furniture.homepage.onSaleProducts');
            
            # homepage - blogs
            Route::get('/homepage/blogs', [HomepageController::class, 'blogs'])->name('admin.appearance.furniture.homepage.blogs');


            //---------------------------------
            # homepage - banner section one
            //---------------------------------
            Route::get('/homepage/banner-section-one', [BannerSectionOneController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerOne');
            Route::post('/homepage/banner-section-one', [BannerSectionOneController::class, 'storeBannerOne'])->name('admin.appearance.furniture.homepage.storeBannerOne');
            Route::get('/homepage/banner-section-one/edit/{id}', [BannerSectionOneController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerOne');
            Route::post('/homepage/banner-section-one/update', [BannerSectionOneController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerOne');
            Route::get('/homepage/banner-section-one/delete/{id}', [BannerSectionOneController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerOne');


            //---------------------------------
            # homepage - banner section Two
            //---------------------------------
            Route::get('/homepage/banner-section-two', [BannerSectionTwoController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerTwo');
            Route::post('/homepage/banner-section-two', [BannerSectionTwoController::class, 'storeBannerTwo'])->name('admin.appearance.furniture.homepage.storeBannerTwo');
            Route::get('/homepage/banner-section-two/edit/{id}', [BannerSectionTwoController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerTwo');
            Route::post('/homepage/banner-section-two/update', [BannerSectionTwoController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerTwo');
            Route::get('/homepage/banner-section-two/delete/{id}', [BannerSectionTwoController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerTwo');


            //---------------------------------
            # homepage - banner section Three
            //---------------------------------
            Route::get('/homepage/banner-section-three', [BannerSectionThreeController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerThree');
            Route::post('/homepage/banner-section-three', [BannerSectionThreeController::class, 'storeBannerThree'])->name('admin.appearance.furniture.homepage.storeBannerThree');
            Route::get('/homepage/banner-section-three/edit/{id}', [BannerSectionThreeController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerThree');
            Route::post('/homepage/banner-section-three/update', [BannerSectionThreeController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerThree');
            Route::get('/homepage/banner-section-three/delete/{id}', [BannerSectionThreeController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerThree');


            //---------------------------------
            # homepage - banner section Four
            //---------------------------------
            Route::get('/homepage/banner-section-four', [BannerSectionFourController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerFour');
            Route::post('/homepage/banner-section-four', [BannerSectionFourController::class, 'storeBannerFour'])->name('admin.appearance.furniture.homepage.storeBannerFour');
            Route::get('/homepage/banner-section-four/edit/{id}', [BannerSectionFourController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerFour');
            Route::post('/homepage/banner-section-four/update', [BannerSectionFourController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerFour');
            Route::get('/homepage/banner-section-four/delete/{id}', [BannerSectionFourController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerFour');


            //---------------------------------
            # homepage - banner section Five
            //---------------------------------
            Route::get('/homepage/banner-section-five', [BannerSectionFiveController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerFive');
            Route::post('/homepage/banner-section-five', [BannerSectionFiveController::class, 'storeBannerFive'])->name('admin.appearance.furniture.homepage.storeBannerFive');
            Route::get('/homepage/banner-section-five/edit/{id}', [BannerSectionTwoController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerFive');
            Route::post('/homepage/banner-section-five/update', [BannerSectionFiveController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerFive');
            Route::get('/homepage/banner-section-five/delete/{id}', [BannerSectionFiveController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerFive');


            //---------------------------------
            # homepage - banner section Six
            //---------------------------------
            Route::get('/homepage/banner-section-six', [BannerSectionSixController::class, 'index'])->name('admin.appearance.furniture.homepage.bannerSix');
            Route::post('/homepage/banner-section-six', [BannerSectionSixController::class, 'storeBannerSix'])->name('admin.appearance.furniture.homepage.storeBannerSix');
            Route::get('/homepage/banner-section-six/edit/{id}', [BannerSectionSixController::class, 'edit'])->name('admin.appearance.furniture.homepage.editBannerSix');
            Route::post('/homepage/banner-section-six/update', [BannerSectionSixController::class, 'update'])->name('admin.appearance.furniture.homepage.updateBannerSix');
            Route::get('/homepage/banner-section-six/delete/{id}', [BannerSectionSixController::class, 'delete'])->name('admin.appearance.furniture.homepage.deleteBannerSix');
        }); 
        
    }
);
