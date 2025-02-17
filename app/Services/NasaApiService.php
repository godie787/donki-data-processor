<?php

namespace App\Services;
use App\Interfaces\NasaApiInterface;

class NasaApiService implements NasaApiInterface

{

    public function getApiKey()
    {
        return env('NASA_API_KEY', 'DEMO_KEY');
    }

    public function allEndpoints()
    {
        $apiKey = $this->getApiKey();
        $allEndpoints = array(
            "CME" => "https://api.nasa.gov/DONKI/CME?startDate=2017-01-03&endDate=2017-01-03&api_key={$apiKey}",
            "CMEAnalysis" => "https://api.nasa.gov/DONKI/CMEAnalysis?startDate=2016-09-01&endDate=2016-09-30&mostAccurateOnly=true&speed=500&halfAngle=30&catalog=ALL&api_key={$apiKey}",
            "GST" => "https://api.nasa.gov/DONKI/GST?startDate=2016-01-01&endDate=2016-01-30&api_key={$apiKey}",
            "IPS" => "https://api.nasa.gov/DONKI/IPS?startDate=2016-01-01&endDate=2016-01-30&api_key={$apiKey}",
            "FLR" => "https://api.nasa.gov/DONKI/FLR?startDate=2016-01-01&endDate=2016-01-30&api_key={$apiKey}",
            "SEP" => "https://api.nasa.gov/DONKI/SEP?startDate=2016-01-01&endDate=2016-01-30&api_key={$apiKey}",
            "MPC" => "https://api.nasa.gov/DONKI/MPC?startDate=2016-01-01&endDate=2016-03-31&api_key={$apiKey}",
            "RBE" => "https://api.nasa.gov/DONKI/RBE?startDate=2016-01-01&endDate=2016-01-31&api_key={$apiKey}",
            "HSS" => "https://api.nasa.gov/DONKI/HSS?startDate=2016-01-01&endDate=2016-01-31&api_key={$apiKey}",
            "WSAEnlilSimulations" => "https://api.nasa.gov/DONKI/WSAEnlilSimulations?startDate=2011-09-19&endDate=2011-09-20&api_key={$apiKey}",
            "notifications" => "https://api.nasa.gov/DONKI/notifications?startDate=2014-05-01&endDate=2014-05-08&type=all&api_key={$apiKey}",
        );
        return $allEndpoints;
    }

    public function validEndpoints()
    {
        $validEndpoints =
            array(
                "https://api.nasa.gov/DONKI/CME?startDate=2017-01-03&endDate=2017-01-03&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/IPS?startDate=2016-01-01&endDate=2016-01-30&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/FLR?startDate=2016-01-01&endDate=2016-01-30&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/SEP?startDate=2016-01-01&endDate=2016-01-30&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/MPC?startDate=2016-01-01&endDate=2016-03-31&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/HSS?startDate=2016-01-01&endDate=2016-01-31&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",
                "https://api.nasa.gov/DONKI/RBE?startDate=2016-01-01&endDate=2016-01-31&api_key=zySgdG6v4SwuNl9xEmmsrtY5Kcpzjx9LC3t491I6",

            );

        return $validEndpoints;
    }

    public function getValidEndpoints(): array
    {
        $endpoints = $this->allEndpoints();

        $validEndpoints = [];

        foreach ($endpoints as $nombre => $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $data = json_decode($response, true);

                foreach ($data as $event) {
                    if (isset($event['instruments'])) {
                        if (!isset($validEndpoints[$nombre])) {
                            $validEndpoints[$nombre] = $url;
                        }
                    }
                }
            }
        }
        return array_values($validEndpoints);
    }

    public function getAllInstruments(): array
    {
        $validEndpointsWithoutFunction = $this->validEndpoints();

        $validEndpoints = $validEndpointsWithoutFunction;
        $instruments = [];

        foreach ($validEndpoints as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);

                foreach ($data as $event) {
                    if (isset($event['instruments'])) {
                        foreach ($event['instruments'] as $instrument) {
                            if (isset($instrument['displayName'])) {
                                $instruments[] = trim($instrument['displayName']);
                            }
                        }
                    }
                }
            }
        }

        return $instruments;
    }

    public function getAllActivityIDs(): array
    {
        $validEndpoints = $this->validEndpoints();
        $activityIDs = [];

        foreach ($validEndpoints as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);

                foreach ($data as $event) {
                    if (isset($event['activityID'])) {
                        $parts = explode("-", $event['activityID']);
                        $activityIDs[] = $parts[count($parts) - 2] . "-" . end($parts);
                    }

                    if (isset($event['linkedEvents'])) {
                        foreach ($event['linkedEvents'] as $linkedEvent) {
                            if (isset($linkedEvent['activityID'])) {
                                $parts = explode("-", $linkedEvent['activityID']);
                                $activityIDs[] = $parts[count($parts) - 2] . "-" . end($parts);
                            }
                        }
                    }
                }
            }
        }

        return $activityIDs;
    }

    public function getInstrumentUsagePercentages(): array
    {
        $instruments = $this->getAllInstruments();
        $totalInstruments = count($instruments);

        if ($totalInstruments === 0) {
            return ["instruments_use" => []];
        }

        $usageCount = array_count_values($instruments);

        $instrumentUsage = [];
        foreach ($usageCount as $instrument => $count) {
            $instrumentUsage[$instrument] = round($count / $totalInstruments, 2);
        }

        return ["instruments_use" => $instrumentUsage];
    }

    public function getInstrumentActivityUsage(string $instrument): array
    {
        $validEndpoints = $this->validEndpoints();
        $activityCounts = [];
        $totalActivities = 0;

        foreach ($validEndpoints as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);

                foreach ($data as $event) {
                    if (isset($event['instruments'])) {
                        foreach ($event['instruments'] as $eventInstrument) {
                            if ($eventInstrument['displayName'] === $instrument) {

                                $activityID = $event['activityID'] ?? $event['hssID'] ?? null;

                                if ($activityID) {
                                    $activityParts = explode('-', $activityID);
                                    if (count($activityParts) >= 2) {
                                        $activityType = $activityParts[count($activityParts) - 2];
                                        $activityCounts[$activityType] = ($activityCounts[$activityType] ?? 0) + 1;
                                        $totalActivities++;
                                    }
                                }
                                if (isset($event['linkedEvents'])) {
                                    foreach ($event['linkedEvents'] as $linkedEvent) {
                                        if (isset($linkedEvent['activityID'])) {
                                            $activityParts = explode('-', $linkedEvent['activityID']);
                                            if (count($activityParts) >= 2) {
                                                $activityType = $activityParts[count($activityParts) - 2];
                                                $activityCounts[$activityType] = ($activityCounts[$activityType] ?? 0) + 1;
                                                $totalActivities++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $activityPercentages = [];
        if ($totalActivities > 0) {
            foreach ($activityCounts as $activityType => $count) {
                $activityPercentages[$activityType] = round($count / $totalActivities, 2);
            }
        }

        return [
            'instrument_activity' => [
                $instrument => $activityPercentages
            ]
        ];
    }
}
