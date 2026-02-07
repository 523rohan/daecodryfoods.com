<?php

namespace App\Http\Controllers\Backend\Appearance\Furniture;

use App\Http\Controllers\Controller;
use App\Models\MediaManager;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class BannerSectionFourController extends Controller
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
        if (getSetting('furniture_banner_section_four_banners') != null) {
            $banners = json_decode(getSetting('furniture_banner_section_four_banners'));
        }
        return $banners;
    }

    # banner section Four
    public function index()
    {
        $banners = $this->getBanners();
        return view('backend.pages.appearance.furniture.homepage.bannerSectionFour', compact('banners'));
    }

    # banner section Four
    public function storeBannerFour(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'furniture_banner_section_four_banners')->first();
        if (!is_null($bannerImage)) {
            if (!is_null($bannerImage->value) && $bannerImage->value != '') {
                $banners            = json_decode($bannerImage->value);
                $newBanner          = new MediaManager; //temp obj
                $newBanner->id      = rand(100000, 999999);
                $newBanner->image       = $request->image ? $request->image : '';
                $newBanner->link        = $request->link ? $request->link : '';

                array_push($banners, $newBanner);
                $bannerImage->value = json_encode($banners);
                $bannerImage->save();
            } else {
                $value                  = [];
                $newBanner              = new MediaManager; //temp obj
                $newBanner->id          = rand(100000, 999999);
                $newBanner->image       = $request->image ? $request->image : '';
                $newBanner->link        = $request->link ? $request->link : '';

                array_push($value, $newBanner);
                $bannerImage->value = json_encode($value);
                $bannerImage->save();
            }
        } else {
            $bannerImage = new SystemSetting;
            $bannerImage->entity = 'furniture_banner_section_four_banners';

            $value              = [];
            $newBanner          = new MediaManager; //temp obj
            $newBanner->id      = rand(100000, 999999);
            $newBanner->image       = $request->image ? $request->image : '';
            $newBanner->link        = $request->link ? $request->link : '';

            array_push($value, $newBanner);
            $bannerImage->value = json_encode($value);
            $bannerImage->save();
        }
        cacheClear();
        flash(localize('Banner image added successfully'))->success();
        return back();
    }

    # edit banner
    public function edit($id)
    {
        $banners = $this->getBanners();
        return view('backend.pages.appearance.furniture.homepage.bannerSectionFourEdit', compact('banners', 'id'));
    }

    # update banner
    public function update(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'furniture_banner_section_four_banners')->first();

        $banners = $this->getBanners();
        $tempBanners = [];

        foreach ($banners as $banner) {
            if ($banner->id == $request->id) {
                $banner->image      = $request->image;
                $banner->link       = $request->link;
                array_push($tempBanners, $banner);
            } else {
                array_push($tempBanners, $banner);
            }
        }

        $bannerImage->value = json_encode($tempBanners);
        $bannerImage->save();
        cacheClear();
        flash(localize('Banner updated successfully'))->success();
        return redirect()->route('admin.appearance.furniture.homepage.bannerFour');
    }

    # delete banner
    public function delete($id)
    {
        $bannerImage = SystemSetting::where('entity', 'furniture_banner_section_four_banners')->first();

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
