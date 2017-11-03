<?php

namespace App\Http\Controllers;

use App\Airport as AirportModel;
use App\Airport;
use App\Helper\ErrorTrait;
use App\Helper\RequestValidator;
use App\Services\FlightManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Aircraft extends Controller
{
    use ErrorTrait;

    const NO_ERROR = 0;
    const ERROR_MISSING_PARAMETERS = 1;
    const ERROR_COULD_NOT_FIND_ELIGIBLE_AIRPORTS = 2;
    const ERROR_COULD_NOT_FIND_ELIGIBLE_AIRCRAFT = 4;

    public function __invoke(Request $request)
    {
        $parameters = RequestValidator::transformCoordinates(
            RequestValidator::validate($request, [
                'from' => 'required|regex:/^[0-9]+\.[0-9]+,[0-9]+\.[0-9]+$/',
                'to' => 'required|regex:/^[0-9]+\.[0-9]+,[0-9]+\.[0-9]+$/',
            ])
        );

        //INIT
        $responseData = [];
        $airportModel = new AirportModel();
        $flightManager = new FlightManager(); //todo add FlightMAnager to ServiceFactory

        if (!count($parameters)) {
            $this->addError(Aircraft::ERROR_MISSING_PARAMETERS);
            goto couldYouPlaceAGoToInYourCodeForAnInterview;
        }

        //DELEGATE JOBS
        $departuresAirports = $airportModel::getClosestsFromCoordinates($parameters['from'][RequestValidator::LATITUDE], $parameters['from'][RequestValidator::LONGITUDE]);
        $destinationsAirports = $airportModel->getClosestsFromCoordinates($parameters['to'][RequestValidator::LATITUDE], $parameters['to'][RequestValidator::LONGITUDE]);

        if (!$departuresAirports->count() || !$destinationsAirports->count()) {
            $this->addError(Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRPORTS);
        } else {
            $flightManager->calculateFlightForFoundAirports($departuresAirports, $destinationsAirports);
            $flights = $flightManager->getFlightCollection()->getFlights();
            $responseData = &$flights;
            if (!count($flights)) {
                $this->addError(Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRCRAFT);
            }
        }

        couldYouPlaceAGoToInYourCodeForAnInterview:

        return $this->prepareResponse($responseData);
    }

    /**
     * todo move Single Responsabily object
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function prepareResponse(array $data): JsonResponse
    {
        $responseData = [
            'message' => $this->hasError() ? 'Error': 'Success',
            'data' => $data,
        ];

        if ($this->hasError()) {
            $responseData['errors'] = $this->getErrors();
        }

        return new JsonResponse($responseData, $this->getStatucCodeFromError());
    }

    /**
     * todo move ErrorManager
     * @return array
     */
    protected function getErrors()
    {
        $errors = [];

        if (Aircraft::ERROR_MISSING_PARAMETERS & $this->error) {
            $errors[] = '[from=lat,lgn, to=lat,lgn] parameters are mandatories';
        }

        if (Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRPORTS & $this->error) {
            $errors[] = 'No eligible airports could be find for given coordinates';
        }

        if (Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRCRAFT & $this->error) {
            $errors[] = 'No eligible aircraft could be find for given coordinates';
        }

        return $errors;
    }

    /**
     * todo move ErrorManager
     * @return int
     */
    protected function getStatucCodeFromError()
    {
        $status = 200;
        $status = Aircraft::ERROR_MISSING_PARAMETERS & $this->error ? 400 : $status;
        $status = (Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRPORTS & $this->error || Aircraft::ERROR_COULD_NOT_FIND_ELIGIBLE_AIRCRAFT & $this->error) ? 404 : $status;

        return $status;
    }
}
