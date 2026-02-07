<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Appearance\Organic\HeroController;
use App\Http\Controllers\Backend\Appearance\Organic\HomepageController;
use App\Http\Controllers\Backend\Appearance\Organic\TopCategoriesController;
use App\Http\Controllers\Backend\Appearance\Organic\ClientFeedbackController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionOneController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionSixController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionTwoController;
use App\Http\Controllers\Backend\Appearance\Organic\BestDealProductsController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionFiveController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionFourController;


use App\Http\Controllers\Backend\Appearance\Organic\FeatureCategoriesController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionSevenController;
use App\Http\Controllers\Backend\Appearance\Organic\BannerSectionThreeController;
use App\Http\Controllers\Backend\Appearance\Organic\FeaturedCollectionProductsController;

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
        Route::group(['prefix' => 'appearance/organic'], function () {

            # homepage - hero
            Route::get('/homepage/hero', [HeroController::class, 'hero'])->name('admin.appearance.organic.homepage.hero');
            Route::post('/homepage/hero', [HeroController::class, 'storeHero'])->name('admin.appearance.organic.homepage.storeHero');
            Route::get('/homepage/hero/edit/{id}', [HeroController::class, 'edit'])->name('admin.appearance.organic.homepage.editHero');
            Route::post('/homepage/hero/update', [HeroController::class, 'update'])->name('admin.appearance.organic.homepage.updateHero');
            Route::get('/homepage/hero/delete/{id}', [HeroController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteHero');
        
            // # homepage - top trending products
            Route::get('/homepage/featured-collection-products', [FeaturedCollectionProductsController::class, 'index'])
                ->name('admin.appearance.organic.homepage.featuredCollectionProducts');

            Route::post('/homepage/get-products-for-featured', [FeaturedCollectionProductsController::class, 'getProducts'])
                ->name('admin.appearance.organic.homepage.getFeaturedCollectionProducts');

            Route::get('/homepage/featured-brand-products', [FeaturedCollectionProductsController::class, 'featureBrandProducts'])
                ->name('admin.appearance.organic.homepage.featuredBrandProducts');

            # homepage - top category 
            Route::get('/homepage/top-category', [TopCategoriesController::class, 'index'])->name('admin.appearance.organic.homepage.topCategories');
            // # homepage - feature categories
            Route::get('/homepage/feature-categories', [FeatureCategoriesController::class, 'index'])->name('admin.appearance.organic.homepage.featureCategories');
            // # homepage - about us 
            // Route::get('/homepage/about-us', [HomepageController::class, 'aboutUs'])->name('admin.appearance.organic.homepage.aboutUs');

            // # homepage - features
            // Route::get('/homepage/features', [HomepageController::class, 'features'])->name('admin.appearance.organic.homepage.features');        
            // # homepage - popular
            // Route::get('/homepage/popular', [HomepageController::class, 'popular'])->name('admin.appearance.organic.homepage.popular');   

            # homepage - whyChooseUs
            Route::get('/homepage/why-choose-us', [HomepageController::class, 'whyChooseUs'])->name('admin.appearance.organic.homepage.whyChooseUs');
            
            // # client feedback
            Route::get('/homepage/client-feedback', [ClientFeedbackController::class, 'index'])->name('admin.appearance.organic.homepage.clientFeedback');
            Route::post('/homepage/client-feedback', [ClientFeedbackController::class, 'store'])->name('admin.appearance.organic.homepage.storeClientFeedback');
            Route::get('/homepage/client-feedback/edit/{id}', [ClientFeedbackController::class, 'edit'])->name('admin.appearance.organic.homepage.editClientFeedback');
            Route::post('/homepage/client-feedback/update', [ClientFeedbackController::class, 'update'])->name('admin.appearance.organic.homepage.updateClientFeedback');
            Route::get('/homepage/client-feedback/delete/{id}', [ClientFeedbackController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteClientFeedback');
            
            // # homepage - onSaleProducts
            // Route::get('/homepage/on-sale-products', [HomepageController::class, 'onSaleProducts'])->name('admin.appearance.organic.homepage.onSaleProducts');
            
            # homepage - blogs
            Route::get('/homepage/blogs', [HomepageController::class, 'blogs'])->name('admin.appearance.organic.homepage.blogs');


            //---------------------------------
            # homepage - banner section one
            //---------------------------------
            Route::get('/homepage/banner-section-one', [BannerSectionOneController::class, 'index'])->name('admin.appearance.organic.homepage.bannerOne');
            Route::post('/homepage/banner-section-one', [BannerSectionOneController::class, 'storeBannerOne'])->name('admin.appearance.organic.homepage.storeBannerOne');
            Route::get('/homepage/banner-section-one/edit/{id}', [BannerSectionOneController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerOne');
            Route::post('/homepage/banner-section-one/update', [BannerSectionOneController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerOne');
            Route::get('/homepage/banner-section-one/delete/{id}', [BannerSectionOneController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerOne');


            // //---------------------------------
            // # homepage - banner section Two
            // //---------------------------------
            Route::get('/homepage/banner-section-two', [BannerSectionTwoController::class, 'index'])->name('admin.appearance.organic.homepage.bannerTwo');
            Route::post('/homepage/banner-section-two', [BannerSectionTwoController::class, 'storeBannerTwo'])->name('admin.appearance.organic.homepage.storeBannerTwo');
            Route::get('/homepage/banner-section-two/edit/{id}', [BannerSectionTwoController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerTwo');
            Route::post('/homepage/banner-section-two/update', [BannerSectionTwoController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerTwo');
            Route::get('/homepage/banner-section-two/delete/{id}', [BannerSectionTwoController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerTwo');


            // //---------------------------------
            // # homepage - banner section Three
            //---------------------------------
            Route::get('/homepage/banner-section-three', [BannerSectionThreeController::class, 'index'])->name('admin.appearance.organic.homepage.bannerThree');
            Route::post('/homepage/banner-section-three', [BannerSectionThreeController::class, 'storeBannerThree'])->name('admin.appearance.organic.homepage.storeBannerThree');
            Route::get('/homepage/banner-section-three/edit/{id}', [BannerSectionThreeController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerThree');
            Route::post('/homepage/banner-section-three/update', [BannerSectionThreeController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerThree');
            Route::get('/homepage/banner-section-three/delete/{id}', [BannerSectionThreeController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerThree');


            //---------------------------------
            # homepage - banner section Four
            //---------------------------------
            Route::get('/homepage/banner-section-four', [BannerSectionFourController::class, 'index'])->name('admin.appearance.organic.homepage.bannerFour');
            Route::post('/homepage/banner-section-four', [BannerSectionFourController::class, 'storeBannerFour'])->name('admin.appearance.organic.homepage.storeBannerFour');
            Route::get('/homepage/banner-section-four/edit/{id}', [BannerSectionFourController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerFour');
            Route::post('/homepage/banner-section-four/update', [BannerSectionFourController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerFour');
            Route::get('/homepage/banner-section-four/delete/{id}', [BannerSectionFourController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerFour');


            // //---------------------------------
            // # homepage - banner section Five
            //---------------------------------
            Route::get('/homepage/banner-section-five', [BannerSectionFiveController::class, 'index'])->name('admin.appearance.organic.homepage.bannerFive');
            Route::post('/homepage/banner-section-five', [BannerSectionFiveController::class, 'storeBannerFive'])->name('admin.appearance.organic.homepage.storeBannerFive');
            Route::get('/homepage/banner-section-five/edit/{id}', [BannerSectionTwoController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerFive');
            Route::post('/homepage/banner-section-five/update', [BannerSectionFiveController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerFive');
            Route::get('/homepage/banner-section-five/delete/{id}', [BannerSectionFiveController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerFive');


            // //---------------------------------
            // # homepage - banner section Six
            //---------------------------------
            Route::get('/homepage/banner-section-six', [BannerSectionSixController::class, 'index'])->name('admin.appearance.organic.homepage.bannerSix');
            Route::post('/homepage/banner-section-six', [BannerSectionSixController::class, 'storeBannerSix'])->name('admin.appearance.organic.homepage.storeBannerSix');
            Route::get('/homepage/banner-section-six/edit/{id}', [BannerSectionSixController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerSix');
            Route::post('/homepage/banner-section-six/update', [BannerSectionSixController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerSix');
            Route::get('/homepage/banner-section-six/delete/{id}', [BannerSectionSixController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerSix');

              // //---------------------------------
            // # homepage - banner section Seven
            //---------------------------------
            Route::get('/homepage/banner-section-seven', [BannerSectionSevenController::class, 'index'])->name('admin.appearance.organic.homepage.bannerSeven');
            Route::post('/homepage/banner-section-seven', [BannerSectionSevenController::class, 'storeBannerSeven'])->name('admin.appearance.organic.homepage.storeBannerSeven');
            Route::get('/homepage/banner-section-seven/edit/{id}', [BannerSectionSevenController::class, 'edit'])->name('admin.appearance.organic.homepage.editBannerSeven');
            Route::post('/homepage/banner-section-seven/update', [BannerSectionSevenController::class, 'update'])->name('admin.appearance.organic.homepage.updateBannerSeven');
            Route::get('/homepage/banner-section-seven/delete/{id}', [BannerSectionSevenController::class, 'delete'])->name('admin.appearance.organic.homepage.deleteBannerSeven');

            // # homepage - best deal products
            Route::get('/homepage/best-deal-products', [BestDealProductsController::class, 'index'])->name('admin.appearance.organic.homepage.bestDealProducts');

        }); 
        
    }
);
