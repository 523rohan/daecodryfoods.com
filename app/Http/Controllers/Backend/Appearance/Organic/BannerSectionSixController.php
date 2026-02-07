<?php

namespace App\Http\Controllers\Backend\Appearance\Organic;

use App\Http\Controllers\Controller;
use App\Models\MediaManager;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class BannerSectionSixController extends Controller
{

    # construct
    public function __construct()
    {
        $this->middleware(['permission:homepage'])->only('index');
    }

    # get the banners
    private function getBanners()
    {
        $banners = [];
        if (getSetting('organic_banner_section_six_banners') != null) {
            $banners = json_decode(getSetting('organic_banner_section_six_banners'));
        }
        return $banners;
    }

    # banner section two
    public function index()
    {
        $banners = $this->getBanners();
        return view('backend.pages.appearance.organic.homepage.bannerSectionSix', compact('banners'));
    }

    # banner section two
    public function storeBannerSix(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_six_banners')->first();
        if (!is_null($bannerImage)) {
            if (!is_null($bannerImage->value) && $bannerImage->value != '') {
                $banners                              = json_decode($bannerImage->value);
                $newBanner                            = new MediaManager; //temp obj
                $newBanner->id                        = rand(100000, 999999);
                $newBanner->title                     = $request->title ? $request->title : '';
                $newBanner->subtitle                  = $request->subtitle ? $request->subtitle : '';
                $newBanner->firstCartImage            = $request->firstCartImage ? $request->firstCartImage : '';
                $newBanner->firstCardClientCount      = $request->firstCardClientCount ? $request->firstCardClientCount : '';
                $newBanner->firstCardText             = $request->firstCardText ? $request->firstCardText : '';
                $newBanner->secondCartImage           = $request->secondCartImage ? $request->secondCartImage : '';
                $newBanner->secondCardClientCount     = $request->secondCardClientCount ? $request->secondCardClientCount : '';
                $newBanner->secondCardText            = $request->secondCardText ? $request->secondCardText : '';
                $newBanner->thirdCartImage            = $request->thirdCartImage ? $request->thirdCartImage : '';
                $newBanner->thirdCardClientCount      = $request->thirdCardClientCount ? $request->thirdCardClientCount : '';
                $newBanner->thirdCardText             = $request->thirdCardText ? $request->thirdCardText : '';

                array_push($banners, $newBanner);
                $bannerImage->value = json_encode($banners);
                $bannerImage->save();
            } else {
                $value                  = [];
                $newBanner              = new MediaManager; //temp obj
                $newBanner->id          = rand(100000, 999999);
                $newBanner->title                     = $request->title ? $request->title : '';
                $newBanner->subtitle                  = $request->subtitle ? $request->subtitle : '';
                $newBanner->firstCartImage            = $request->firstCartImage ? $request->firstCartImage : '';
                $newBanner->firstCardClientCount      = $request->firstCardClientCount ? $request->firstCardClientCount : '';
                $newBanner->firstCardText             = $request->firstCardText ? $request->firstCardText : '';
                $newBanner->secondCartImage           = $request->secondCartImage ? $request->secondCartImage : '';
                $newBanner->secondCardClientCount     = $request->secondCardClientCount ? $request->secondCardClientCount : '';
                $newBanner->secondCardText            = $request->secondCardText ? $request->secondCardText : '';
                $newBanner->thirdCartImage            = $request->thirdCartImage ? $request->thirdCartImage : '';
                $newBanner->thirdCardClientCount      = $request->thirdCardClientCount ? $request->thirdCardClientCount : '';
                $newBanner->thirdCardText             = $request->thirdCardText ? $request->thirdCardText : '';

                array_push($value, $newBanner);
                $bannerImage->value = json_encode($value);
                $bannerImage->save();
            }
        } else {
            $bannerImage = new SystemSetting;
            $bannerImage->entity = 'organic_banner_section_six_banners';

                $value                                = [];
                $newBanner                            = new MediaManager; //temp obj
                $newBanner->id                        = rand(100000, 999999);
                $newBanner->title                     = $request->title ? $request->title : '';
                $newBanner->subtitle                  = $request->subtitle ? $request->subtitle : '';
                $newBanner->firstCartImage            = $request->firstCartImage ? $request->firstCartImage : '';
                $newBanner->firstCardClientCount      = $request->firstCardClientCount ? $request->firstCardClientCount : '';
                $newBanner->firstCardText             = $request->firstCardText ? $request->firstCardText : '';
                $newBanner->secondCartImage           = $request->secondCartImage ? $request->secondCartImage : '';
                $newBanner->secondCardClientCount     = $request->secondCardClientCount ? $request->secondCardClientCount : '';
                $newBanner->secondCardText            = $request->secondCardText ? $request->secondCardText : '';
                $newBanner->thirdCartImage            = $request->thirdCartImage ? $request->thirdCartImage : '';
                $newBanner->thirdCardClientCount      = $request->thirdCardClientCount ? $request->thirdCardClientCount : '';
                $newBanner->thirdCardText             = $request->thirdCardText ? $request->thirdCardText : '';

            array_push($value, $newBanner);
            $bannerImage->value = json_encode($value);
            $bannerImage->save();
        }
        cacheClear();
        flash(localize('Banner  added successfully'))->success();
        return back();
    }

    # edit banner
    public function edit($id)
    {
        $banners = $this->getBanners();
        return view('backend.pages.appearance.organic.homepage.bannerSectionSixEdit', compact('banners', 'id'));
    }

    # update banner
    public function update(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_six_banners')->first();

        $banners = $this->getBanners();
        $tempBanners = [];

        foreach ($banners as $banner) {
            if ($banner->id == $request->id) {

                $banner->title                     = $request->title ? $request->title : '';
                $banner->subtitle                  = $request->subtitle ? $request->subtitle : '';
                $banner->firstCartImage            = $request->firstCartImage ? $request->firstCartImage : '';
                $banner->firstCardClientCount      = $request->firstCardClientCount ? $request->firstCardClientCount : '';
                $banner->firstCardText             = $request->firstCardText ? $request->firstCardText : '';
                $banner->secondCartImage           = $request->secondCartImage ? $request->secondCartImage : '';
                $banner->secondCardClientCount     = $request->secondCardClientCount ? $request->secondCardClientCount : '';
                $banner->secondCardText            = $request->secondCardText ? $request->secondCardText : '';
                $banner->thirdCartImage            = $request->thirdCartImage ? $request->thirdCartImage : '';
                $banner->thirdCardClientCount      = $request->thirdCardClientCount ? $request->thirdCardClientCount : '';
                $banner->thirdCardText             = $request->thirdCardText ? $request->thirdCardText : '';
                
                array_push($tempBanners, $banner);
            } else {
                array_push($tempBanners, $banner);
            }
        }
        $bannerImage->value = json_encode($tempBanners);
        $bannerImage->save();
        cacheClear();
        flash(localize('Banner updated successfully'))->success();
        return redirect()->route('admin.appearance.organic.homepage.bannerSix');
    }

    # delete banner
    public function delete($id)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_six_banners')->first();

        $banners = $this->getBanners();
        $tempBanners = [];

        foreach ($banners as $banner) {
            if ($banner->id != $id) {
                array_push($tempBanners, $banner);
            }
        }

        $bannerImage->value = json_encode($tempBanners);
        $bannerImage->save();

        cacheClear();
        flash(localize('Banner deleted successfully'))->success();
        return back();
    }
}
