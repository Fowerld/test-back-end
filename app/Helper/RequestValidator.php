<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadeValidator;

class RequestValidator
{
    const DEFAULT_STRING_COORDINATES_PARAMETERS = ['from', 'to'];
    const LATITUDE = 'lat';
    const LONGITUDE = 'lng';

    /**
     * @param Request $request
     * @param array $parameters
     *
     * @return array
     */
    public static function validate(Request $request, array $parameters): array
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = FacadeValidator::make($request->all(), $parameters);

        return $validator->fails() ? [] : $validator->getData();
    }

    /**
     * @param array $parameters
     * @param array $coordinatesParameters
     *
     * @return array
     */
    public static function transformCoordinates(array $parameters, array $coordinatesParameters = RequestValidator::DEFAULT_STRING_COORDINATES_PARAMETERS)
    {
        foreach ($parameters as $key => &$coordinates) {
            if (in_array($key, $coordinatesParameters)) {
                $coordinates = array_combine([RequestValidator::LATITUDE, RequestValidator::LONGITUDE], explode(',', $coordinates));
            }
        }

        return $parameters;
    }
}