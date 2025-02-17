<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\NasaApiInterface;

class NasaApiController extends Controller
{
    private $nasaService;

    public function __construct(NasaApiInterface $nasaService)
    {
        $this->nasaService = $nasaService;
    }

    public function getValidEndpoints()
    {
        return response()->json($this->nasaService->getValidEndpoints());
    }

    public function getAllInstruments()
    {
        return response()->json(['instruments' => $this->nasaService->getAllInstruments()]);
    }

    public function getAllActivityIDs()
    {
        return response()->json(["activityIDs" => $this->nasaService->getAllActivityIDs()]);
    }

    public function getInstrumentUsage()
    {
        return response()->json($this->nasaService->getInstrumentUsagePercentages());
    }

    public function getInstrumentActivityUsage(Request $request)
    {
        $instrument = $request->input('instrument');

        if (!$instrument) {
            return response()->json(['error' => 'Instrument is required'], 400);
        }

        return response()->json($this->nasaService->getInstrumentActivityUsage($instrument));
    }
}
