<?php

namespace Inventorai\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Inventorai\SDK\InventoraiClient;

/**
 * @method static \Inventorai\SDK\Resources\Properties properties()
 * @method static \Inventorai\SDK\Resources\Inspections inspections()
 * @method static \Inventorai\SDK\Resources\InspectionAreas inspectionAreas()
 * @method static \Inventorai\SDK\Resources\InspectionItems inspectionItems()
 * @method static \Inventorai\SDK\Resources\InspectionElements inspectionElements()
 * @method static \Inventorai\SDK\Resources\Defects defects()
 * @method static \Inventorai\SDK\Resources\MeterReadings meterReadings()
 * @method static \Inventorai\SDK\Resources\KeysFobs keysFobs()
 * @method static \Inventorai\SDK\Resources\Compliance compliance()
 * @method static \Inventorai\SDK\Resources\ComplianceForms complianceForms()
 * @method static \Inventorai\SDK\Resources\InspectionAi inspectionAi()
 * @method static \Inventorai\SDK\Resources\PropertyTemplates propertyTemplates()
 * @method static \Inventorai\SDK\Resources\Components components()
 * @method static \Inventorai\SDK\Resources\User user()
 * @method static \Inventorai\SDK\Resources\AddressLookup addressLookup()
 * @method static \Inventorai\SDK\Resources\Stats stats()
 * @method static \Inventorai\SDK\Resources\Phrases phrases()
 * @method static \Inventorai\SDK\Resources\Modifiers modifiers()
 * @method static \Inventorai\SDK\Resources\Scheduler scheduler()
 *
 * @see \Inventorai\SDK\InventoraiClient
 */
class Inventorai extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InventoraiClient::class;
    }
}
