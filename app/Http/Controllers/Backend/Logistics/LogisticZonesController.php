<?php

namespace App\Http\Controllers\Backend\Logistics;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Logistic;
use App\Models\LogisticZone;
use App\Models\LogisticZoneCity;
use App\Models\State;
use Illuminate\Http\Request;

class LogisticZonesController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:shipping_zones'])->only('index');
        $this->middleware(['permission:add_shipping_zones'])->only(['create', 'store']);
        $this->middleware(['permission:edit_shipping_zones'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_shipping_zones'])->only(['delete']);
    }

    # zone list
    public function index(Request $request)
    {
        $searchKey = null;
        $searchLogistic = null;
        $logisticZones = LogisticZone::latest();
        if ($request->search != null) {
            $logisticZones = $logisticZones->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        if ($request->searchLogistic) {
            $logisticZones->where('logistic_id', $request->searchLogistic);
            $searchLogistic = $request->searchLogistic;
        }
        $logisticZones = $logisticZones->paginate(paginationNumber());
        return view('backend.pages.fulfillments.logisticZones.index', compact('logisticZones', 'searchKey', 'searchLogistic'));
    }

    # create zone
    public function create()
    {
        $logistics = Logistic::where('is_active', 1)->latest()->get();
        $states = State::where('is_active', 1)->orderBy('name')->get();
        return view('backend.pages.fulfillments.logisticZones.create', compact('logistics', 'states'));
    }


    # create zone
    public function getLogisticCities(Request $request)
    {
        $html = '<option value="">' . localize("Select City") . '</option>';

        $logisticId = $request->logistic_id ? (int) $request->logistic_id : null;
        $zoneId = $request->zone_id ? (int) $request->zone_id : null;

        if (!is_null($logisticId)) {
            $cities = $this->availableCitiesQuery($logisticId, $zoneId)
                ->orderBy('name')
                ->get();

            foreach ($cities as $city) {
                $html .= '<option value="' . $city->id . '">' . e($city->name . ' (' . optional($city->state)->name . ')') . '</option>';
            }
        }

        echo json_encode($html);
    }

    # get cities by states
    public function getStatesCities(Request $request)
    {
        $stateIds = collect($request->state_ids ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        if (empty($stateIds)) {
            return response()->json([
                'ids' => [],
                'count' => 0,
                'options' => [],
            ]);
        }

        $coverageMode = $request->coverage_mode === 'exclude' ? 'exclude' : 'include';
        $logisticId = $request->logistic_id ? (int) $request->logistic_id : null;
        $zoneId = $request->zone_id ? (int) $request->zone_id : null;

        $citiesQuery = $this->availableCitiesQuery($logisticId, $zoneId);

        if ($coverageMode === 'exclude') {
            $citiesQuery->whereNotIn('state_id', $stateIds);
        } else {
            $citiesQuery->whereIn('state_id', $stateIds);
        }

        $cities = $citiesQuery
            ->orderBy('name')
            ->get(['id', 'name', 'state_id']);

        return response()->json([
            'ids' => $cities->pluck('id')->all(),
            'count' => $cities->count(),
            'options' => $cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'text' => $city->name,
                ];
            })->all(),
        ]);
    }

    # zone store
    public function store(Request $request)
    {
        $cityIds = $this->resolveCityIds($request, (int) $request->logistic_id);
        if (empty($cityIds)) {
            flash(localize('Please select at least one state or city for this zone'))->error();
            return back()->withInput();
        }

        $logisticZone = new LogisticZone;
        $logisticZone->name = $request->name;
        $logisticZone->logistic_id = $request->logistic_id;
        $logisticZone->standard_delivery_charge = priceToUsd($request->standard_delivery_charge);
        $logisticZone->standard_delivery_time = $request->standard_delivery_time;
        $logisticZone->save();

        $this->syncZoneCities($logisticZone, $cityIds);

        flash(localize('Zone has been inserted successfully'))->success();
        return redirect()->route('admin.logisticZones.index');
    }

    # edit zone
    public function edit(Request $request, $id)
    {
        $logisticZone = LogisticZone::findOrFail($id);
        $states = State::where('is_active', 1)->orderBy('name')->get();
        $cities = $this->availableCitiesQuery($logisticZone->logistic_id, $logisticZone->id)
            ->orderBy('name')
            ->get();
        $selectedStateIds = $logisticZone->cities()->pluck('state_id')->unique()->values()->all();
        return view('backend.pages.fulfillments.logisticZones.edit', compact('logisticZone', 'cities', 'states', 'selectedStateIds'));
    }

    # update zone
    public function update(Request $request)
    {
        $logisticZone = LogisticZone::findOrFail($request->id);
        $cityIds = $this->resolveCityIds($request, (int) $logisticZone->logistic_id, (int) $logisticZone->id);
        if (empty($cityIds)) {
            flash(localize('Please select at least one state or city for this zone'))->error();
            return back()->withInput();
        }

        $logisticZone->name = $request->name;

        $logisticZone->standard_delivery_charge = priceToUsd($request->standard_delivery_charge);
        if ($request->express_delivery_charge) {
            $logisticZone->express_delivery_charge = priceToUsd($request->express_delivery_charge);
        }

        $logisticZone->standard_delivery_time = $request->standard_delivery_time;
        if ($request->express_delivery_charge) {
            $logisticZone->express_delivery_time = $request->express_delivery_time;
        }

        $logisticZone->save();

        $this->syncZoneCities($logisticZone, $cityIds);

        flash(localize('Zone has been updated successfully'))->success();
        return back();
    }

    # delete zone
    public function delete($id)
    {
        $logisticZone = LogisticZone::findOrFail($id);
        LogisticZoneCity::where('logistic_zone_id', $logisticZone->id)->delete();
        $logisticZone->delete();
        flash(localize('Zone has been deleted successfully'))->success();
        return back();
    }

    private function resolveCityIds(Request $request, int $logisticId, ?int $zoneId = null): array
    {
        $selectionMode = $request->selection_mode === 'state' ? 'state' : 'city';

        if ($selectionMode === 'state') {
            $stateIds = collect($request->state_ids ?? [])
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->values()
                ->all();

            if (empty($stateIds)) {
                return [];
            }

            $coverageMode = $request->coverage_mode === 'exclude' ? 'exclude' : 'include';
            $citiesQuery = $this->availableCitiesQuery($logisticId, $zoneId);

            if ($coverageMode === 'exclude') {
                $citiesQuery->whereNotIn('state_id', $stateIds);
            } else {
                $citiesQuery->whereIn('state_id', $stateIds);
            }

            return $citiesQuery->pluck('id')->all();
        }

        return collect($request->city_ids ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function syncZoneCities(LogisticZone $logisticZone, array $cityIds): void
    {
        LogisticZoneCity::where('logistic_zone_id', $logisticZone->id)->delete();

        foreach ($cityIds as $cityId) {
            LogisticZoneCity::where('logistic_id', $logisticZone->logistic_id)
                ->where('city_id', $cityId)
                ->where('logistic_zone_id', '!=', $logisticZone->id)
                ->delete();

            $logisticZoneCity = new LogisticZoneCity;
            $logisticZoneCity->logistic_id = $logisticZone->logistic_id;
            $logisticZoneCity->logistic_zone_id = $logisticZone->id;
            $logisticZoneCity->city_id = $cityId;
            $logisticZoneCity->save();
        }
    }

    private function availableCitiesQuery(?int $logisticId = null, ?int $zoneId = null)
    {
        $query = City::isActive()->with('state');

        if ($logisticId) {
            $assignedCityIds = LogisticZoneCity::where('logistic_id', $logisticId)
                ->when($zoneId, function ($builder) use ($zoneId) {
                    $builder->where('logistic_zone_id', '!=', $zoneId);
                })
                ->pluck('city_id');

            if ($assignedCityIds->isNotEmpty()) {
                $query->whereNotIn('id', $assignedCityIds);
            }
        }

        return $query;
    }
}
