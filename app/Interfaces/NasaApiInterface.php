<?php

namespace App\Interfaces;

interface NasaApiInterface
{
    public function getValidEndpoints(): array;
    public function getAllInstruments(): array;
    public function getAllActivityIDs(): array;
    public function getInstrumentUsagePercentages(): array;
    public function getInstrumentActivityUsage(string $instrument): array;
}
