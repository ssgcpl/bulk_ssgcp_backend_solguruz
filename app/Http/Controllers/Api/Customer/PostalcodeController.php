<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\DB;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class PostalcodeController extends BaseController
{
    /**
     * Master: postalcode
     *
     * 
     * @response
    {
        "success": "1",
        "status": "200",
        "message": "Postalcode List Found",
        "data": [
            {
                "Pincode": "1",
                "District": "ABC",
                "State": "Gujarat",
                "Region": "http://localhost/ssgc-bulkorder/public/flags/india.png",
                "Country":"India"
            }
        ]
    }
     */

    public function index($pincode)
    {
        if (empty($pincode)) {
            return response()->json(['error' => 'Postal code not provided in the URL.'], 400);
        }
        $cityAndState = DB::table('postcodes')
            ->select('cities.name as District', 'states.name as State', 'postcodes.postcode as Pincode')
            ->join('cities', 'postcodes.city_id', '=', 'cities.id')
            ->join('states', 'cities.state_id', '=', 'states.id')
            ->where('postcodes.postcode', '=', $pincode)
            ->where('postcodes.status', '=', 'active')
            ->first();

        if (!empty($cityAndState)) {
            $cityAndState->Description = '';
            $cityAndState->Region = '';
            $cityAndState->Country = '';
            return $this->sendResponse([$cityAndState], trans('Postal code found'));
        } else {
            $filePath = asset('postalcode.json');

            $jsonContents = file_get_contents($filePath);
            $data = json_decode($jsonContents, true);
            $filteredData = array_filter($data, function ($item) use ($pincode) {
                return $item['Pincode'] == $pincode;
            });

            if (!empty($filteredData)) {
                $matchedItem = reset($filteredData);

                $indexedData = array_values($filteredData);
                return $this->sendResponse($indexedData, trans('Postal code found'));
            } else {
                return $this->sendError('', 'Postal code not found');
            }
        }
    }
}
