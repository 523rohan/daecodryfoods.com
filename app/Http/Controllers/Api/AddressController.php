<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\AddressResource;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\CountryResource;
use App\Http\Resources\Api\StateResource;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private function addressRules(): array
    {
        return [
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'state_id' => ['required', 'integer', 'exists:states,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'pincode' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'is_default' => ['nullable', 'in:0,1'],
        ];
    }

    public function index()
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        return AddressResource::collection($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->addressRules());

        $userId = auth()->user()->id;
        $address                = new UserAddress;
        $address->user_id       = $userId;
        $address->country_id    = $validated['country_id'];
        $address->state_id      = $validated['state_id'];
        $address->city_id       = $validated['city_id'];
        $address->pincode       = $validated['pincode'];

        if (($validated['is_default'] ?? 0) == 1) {
            $prevDefault = UserAddress::where('user_id', $userId)->where('is_default', 1)->first();
            if (!is_null($prevDefault)) {
                $prevDefault->is_default = 0;
                $prevDefault->save();
            }
        }
        $address->is_default    = $validated['is_default'] ?? 0;
        $address->address       = $validated['address'];
        $address->save();
        return $this->success(localize('Address has been inserted successfully'));
    }

    # edit address
    public function edit($id)
    {
        $address  = UserAddress::where('user_id', auth()->user()->id)->where('id', $id)->first();
        
        return new AddressResource($address);
    }

    # update address
    public function update(Request $request)
    {
        $validated = $request->validate(array_merge($this->addressRules(), [
            'id' => ['required', 'integer'],
        ]));

        $userId   = auth()->user()->id;
        $address  = UserAddress::where('user_id', $userId)->where('id', $validated['id'])->first();

        $address->country_id    = $validated['country_id'];
        $address->state_id      = $validated['state_id'];
        $address->city_id       = $validated['city_id'];
        $address->pincode       = $validated['pincode'];
        if (($validated['is_default'] ?? 0) == 1) {
            $prevDefault = UserAddress::where('user_id', $userId)->where('is_default', 1)->first();
            if (!is_null($prevDefault)) {
                $prevDefault->is_default = 0;
                $prevDefault->save();
            }
        }
        $address->is_default    = $validated['is_default'] ?? 0;
        $address->address       = $validated['address'];
        $address->save();
        return $this->success(localize('Address has been updated successfully'));
    }

    # delete address
    public function delete($id)
    {
        $user = auth()->user();
        UserAddress::where('user_id', $user->id)->where('id', $id)->delete();

        return $this->success(localize('Address has been deleted successfully'));
    }



    # get states based on country
    public function getCountries(Request $request)
    {
        $countries = Country::isActive()->get();
        return CountryResource::collection($countries);
    }


    # get states based on country
    public function getStates(Request $request)
    {
        $states = State::isActive()->where('country_id', $request->country_id)->get();
        return StateResource::collection($states);
    }

    # get cities based on state
    public function getCities(Request $request)
    {
        $cities = City::isActive()->where('state_id', $request->state_id)->get();
        return CityResource::collection($cities);
    }
}
