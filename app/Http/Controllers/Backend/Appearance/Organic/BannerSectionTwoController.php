<?php

namespace App\Http\Controllers\Backend\Appearance\Organic;

use App\Http\Controllers\Controller;
use App\Models\MediaManager;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class BannerSectionTwoController extends Controller
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
        if (getSetting('organic_banner_section_two_banners') != null) {
            $banners = json_decode(getSetting('organic_banner_section_two_banners'));
        }
        return $banners;
    }

    # banner section two
    public function index()
    {
        $banners = $this->getBanners();
        return view('backend.pages.appearance.organic.homepage.bannerSectionTwo', compact('banners'));
    }

    # banner section two
    public function storeBannerTwo(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_two_banners')->first();
        if (!is_null($bannerImage)) {
            if (!is_null($bannerImage->value) && $bannerImage->value != '') {
                $banners            = json_decode($bannerImage->value);
                $newBanner          = new MediaManager; //temp obj
                $newBanner->id      = rand(100000, 999999);
                $newBanner->image       = $request->image ? $request->image : '';
                $newBanner->link        = $request->link ? $request->link : '';
                $newBanner->title       = $request->title ? $request->title : '';
                $newBanner->subtitle    = $request->subtitle ? $request->subtitle : '';

                array_push($banners, $newBanner);
                $bannerImage->value = json_encode($banners);
                $bannerImage->save();
            } else {
                $value                  = [];
                $newBanner              = new MediaManager; //temp obj
                $newBanner->id          = rand(100000, 999999);
                $newBanner->image       = $request->image ? $request->image : '';
                $newBanner->link        = $request->link ? $request->link : '';
                $newBanner->title       = $request->title ? $request->title : '';
                $newBanner->subtitle    = $request->subtitle ? $request->subtitle : '';

                array_push($value, $newBanner);
                $bannerImage->value = json_encode($value);
                $bannerImage->save();
            }
        } else {
            $bannerImage = new SystemSetting;
            $bannerImage->entity = 'organic_banner_section_two_banners';

            $value              = [];
            $newBanner          = new MediaManager; //temp obj
            $newBanner->id      = rand(100000, 999999);
            $newBanner->image       = $request->image ? $request->image : '';
            $newBanner->link        = $request->link ? $request->link : '';
            $newBanner->title       = $request->title ? $request->title : '';
            $newBanner->subtitle    = $request->subtitle ? $request->subtitle : '';

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
        return view('backend.pages.appearance.organic.homepage.bannerSectionTwoEdit', compact('banners', 'id'));
    }

    # update banner
    public function update(Request $request)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_two_banners')->first();

        $banners = $this->getBanners();
        $tempBanners = [];

        foreach ($banners as $banner) {
            if ($banner->id == $request->id) {
                $banner->image      = $request->image;
                $banner->link       = $request->link;
                $banner->title      = $request->title;
                $banner->subtitle   = $request->subtitle;
                array_push($tempBanners, $banner);
            } else {
                array_push($tempBanners, $banner);
            }
        }

        $bannerImage->value = json_encode($tempBanners);
        $bannerImage->save();
        cacheClear();
        flash(localize('Banner updated successfully'))->success();
        return redirect()->route('admin.appearance.organic.homepage.bannerTwo');
    }

    # delete banner
    public function delete($id)
    {
        $bannerImage = SystemSetting::where('entity', 'organic_banner_section_two_banners')->first();

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
