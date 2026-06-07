# Inventorai Laravel SDK

Laravel integration for the [Inventorai](https://app.inventorai.co.uk) API. This package wraps the base [`inventorai/sdk`](https://github.com/Inventorai/sdk-php) PHP package and provides a service provider, facade, config publishing, and an Artisan test command.

## Requirements

- PHP 8.3+
- Laravel 13+

## Installation

```bash
composer require inventorai/laravel
```

The package uses Laravel's auto-discovery, so the service provider and facade are registered automatically. The base `inventorai/sdk` package is pulled in as a dependency — you don't need to install it separately.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=inventorai-config
```

This creates `config/inventorai.php`. Add your API token to `.env`:

```env
INVENTORAI_API_TOKEN=your-api-token-here
```

An active [Inventorai](https://app.inventorai.co.uk) subscription is required. You can generate an API token from **Team Settings > API** in your dashboard.

| Variable | Description | Default |
|---|---|---|
| `INVENTORAI_API_TOKEN` | Your team API token | _(required)_ |
| `INVENTORAI_API_URL` | Base URL for the API | `https://api.inventorai.co.uk/v1/team` |

## Test Connection

Verify your API credentials:

```bash
php artisan inventorai:test
```

## Usage

### Via Facade

```php
use Inventorai\Laravel\Facades\Inventorai;

$properties = Inventorai::properties()->list([
    'filter' => ['status' => 'active'],
    'include' => ['landlord'],
    'per_page' => 20,
]);

$property = Inventorai::properties()->get($id, [
    'include' => 'landlord'
]);
```

### Via Dependency Injection

```php
use Inventorai\SDK\InventoraiClient;

class PropertyController extends Controller
{
    public function index(InventoraiClient $client)
    {
        $properties = $client->properties()->list();
        return view('properties.index', compact('properties'));
    }
}
```

### Via Container

```php
$client = app('inventorai');
$properties = $client->properties()->list();
```

## Available Resources

All 21 resources from the base SDK are available through the facade. See the [PHP SDK documentation](https://github.com/Inventorai/sdk-php) for full method signatures.

### Properties

```php
Inventorai::properties()->list($params);
Inventorai::properties()->get($id, $params);
Inventorai::properties()->create($data);
Inventorai::properties()->activeTenancy($propertyId);
Inventorai::properties()->uploadCoverImage($propertyId, $file);
Inventorai::properties()->deleteCoverImage($propertyId);
```

### Inspections

```php
Inventorai::inspections()->list($params);

// One call returns the whole inspection tree — property, tenancy, areas →
// items → elements (with photos auto-loaded), meter readings, keys, asset
// checks, and compliance form responses. Prefer this over chaining the
// sub-resource list() calls below for reads.
Inventorai::inspections()->get($id, [
    'include' => [
        'property', 'property.currentTenancy.tenants', 'inspector',
        'areas.items.elements',
        'meterReadings', 'keysFobs',
        'assetChecks.propertyAsset.propertyArea',
        'complianceForms.sections.fields.responses',
    ],
]);

Inventorai::inspections()->create($data);
Inventorai::inspections()->initialize($data);
Inventorai::inspections()->checkExisting($params);
Inventorai::inspections()->comparable($params);
Inventorai::inspections()->begin($inspectionId);
Inventorai::inspections()->takeOver($inspectionId);
Inventorai::inspections()->takeBackToWeb($inspectionId);
Inventorai::inspections()->finalize($inspectionId, $data);
Inventorai::inspections()->reschedule($inspectionId, $data);
Inventorai::inspections()->uploadCoverImage($inspectionId, $file);
Inventorai::inspections()->deleteCoverImage($inspectionId);
```

### Inspection Areas

> The sub-resource methods below (Areas, Items, Elements, Meters, Keys, …) are
> for **writes** (create / update / delete / duplicate / reorder / photo upload),
> **mobile or offline sync** (re-pulling one slice after a local change), and
> **leaf pagination** (large HMO inspections with hundreds of items, where
> `include=areas.items` would return an unpaginated response). For normal reads,
> use `Inventorai::inspections()->get($id, ['include' => [...]])` above.

```php
Inventorai::inspectionAreas()->list($inspectionId, $params);
Inventorai::inspectionAreas()->get($inspectionId, $areaId, $params);
Inventorai::inspectionAreas()->create($inspectionId, $data);
Inventorai::inspectionAreas()->update($inspectionId, $areaId, $data);
Inventorai::inspectionAreas()->delete($inspectionId, $areaId);
Inventorai::inspectionAreas()->duplicate($inspectionId, $areaId);
Inventorai::inspectionAreas()->reorder($inspectionId, $order);
Inventorai::inspectionAreas()->uploadPhoto($inspectionId, $areaId, $file);
Inventorai::inspectionAreas()->deletePhoto($inspectionId, $areaId, $photoId);
```

### Inspection Items

```php
Inventorai::inspectionItems()->list($inspectionId, $params);
Inventorai::inspectionItems()->get($inspectionId, $itemId, $params);
Inventorai::inspectionItems()->create($inspectionId, $data);
Inventorai::inspectionItems()->update($inspectionId, $itemId, $data);
Inventorai::inspectionItems()->delete($inspectionId, $itemId);
Inventorai::inspectionItems()->duplicate($inspectionId, $itemId);
Inventorai::inspectionItems()->uploadPhoto($inspectionId, $itemId, $file);
Inventorai::inspectionItems()->deletePhoto($inspectionId, $itemId, $photoId);
```

### Inspection Elements

```php
Inventorai::inspectionElements()->list($inspectionId, $params);
Inventorai::inspectionElements()->get($inspectionId, $elementId, $params);
Inventorai::inspectionElements()->create($inspectionId, $data);
Inventorai::inspectionElements()->update($inspectionId, $elementId, $data);
Inventorai::inspectionElements()->delete($inspectionId, $elementId);
Inventorai::inspectionElements()->uploadPhoto($inspectionId, $elementId, $file);
Inventorai::inspectionElements()->deletePhoto($inspectionId, $elementId, $photoId);
```

### Defects

```php
Inventorai::defects()->list($inspectionId, $params);
Inventorai::defects()->get($inspectionId, $defectId);
Inventorai::defects()->create($inspectionId, $data);
Inventorai::defects()->createForArea($inspectionId, $areaId, $data);
Inventorai::defects()->createForItem($inspectionId, $itemId, $data);
Inventorai::defects()->createForElement($inspectionId, $elementId, $data);
Inventorai::defects()->update($inspectionId, $defectId, $data);
Inventorai::defects()->delete($inspectionId, $defectId);
Inventorai::defects()->uploadPhoto($inspectionId, $defectId, $file);
Inventorai::defects()->deletePhoto($inspectionId, $defectId, $photoId);
```

### Meter Readings

```php
Inventorai::meterReadings()->list($inspectionId, $params);
Inventorai::meterReadings()->get($inspectionId, $meterReadingId);
Inventorai::meterReadings()->create($inspectionId, $data);
Inventorai::meterReadings()->update($inspectionId, $meterReadingId, $data);
Inventorai::meterReadings()->delete($inspectionId, $meterReadingId);
Inventorai::meterReadings()->uploadPhoto($inspectionId, $meterReadingId, $file);
Inventorai::meterReadings()->deletePhoto($inspectionId, $meterReadingId, $photoId);
```

### Keys & Fobs

```php
Inventorai::keysFobs()->list($inspectionId, $params);
Inventorai::keysFobs()->get($inspectionId, $keyFobId);
Inventorai::keysFobs()->create($inspectionId, $data);
Inventorai::keysFobs()->update($inspectionId, $keyFobId, $data);
Inventorai::keysFobs()->delete($inspectionId, $keyFobId);
Inventorai::keysFobs()->uploadPhoto($inspectionId, $keyFobId, $file);
Inventorai::keysFobs()->deletePhoto($inspectionId, $keyFobId, $photoId);
```

### Compliance

```php
Inventorai::compliance()->list($inspectionId);
Inventorai::compliance()->attach($inspectionId, $formId);
Inventorai::compliance()->attachMultiple($inspectionId, $formIds);
Inventorai::compliance()->updateResponse($inspectionId, $fieldId, $data);
Inventorai::compliance()->batchUpdateResponses($inspectionId, $fields);
Inventorai::compliance()->uploadFile($inspectionId, $file);
Inventorai::compliance()->addSectionInstance($inspectionId, $data);
Inventorai::compliance()->removeSectionInstance($inspectionId, $instanceId);
Inventorai::compliance()->summary($inspectionId);
Inventorai::compliance()->update($inspectionId, $formId, $data);
Inventorai::compliance()->detach($inspectionId, $formId);
```

### Compliance Forms

```php
Inventorai::complianceForms()->list($params);
```

### Inspection AI

```php
Inventorai::inspectionAi()->enable($inspectionId);
Inventorai::inspectionAi()->disable($inspectionId);
Inventorai::inspectionAi()->status($inspectionId);
Inventorai::inspectionAi()->retryCredits($inspectionId);
Inventorai::inspectionAi()->requestRetry($inspectionId);
Inventorai::inspectionAi()->retryStatus($inspectionId);
Inventorai::inspectionAi()->submitFeedback($inspectionId, $data);
```

### Property Templates

```php
Inventorai::propertyTemplates()->list($params);
Inventorai::propertyTemplates()->get($id);
```

### Branches

```php
Inventorai::branches()->list($params);
Inventorai::branches()->get($id);
```

### HMO

```php
Inventorai::hmo()->summary($inspectionId);
Inventorai::hmo()->tenants($inspectionId);
Inventorai::hmo()->assignTenantToArea($inspectionId, $areaId, $data);
Inventorai::hmo()->bulkAssignTenants($inspectionId, $data);
```

### Components

```php
Inventorai::components()->list($params);
```

### Address Lookup

```php
Inventorai::addressLookup()->lookup($postcode);
Inventorai::addressLookup()->bulkLookup($postcodes);
Inventorai::addressLookup()->getDetails($data);
Inventorai::addressLookup()->usage();
```

### Stats

```php
Inventorai::stats()->index();
Inventorai::stats()->team();
Inventorai::stats()->user();
Inventorai::stats()->schedule();
```

### Phrases

```php
Inventorai::phrases()->sync();
Inventorai::phrases()->search($params);
Inventorai::phrases()->create($data);
Inventorai::phrases()->generate($data);
Inventorai::phrases()->learn($data);
Inventorai::phrases()->stats();
```

### Modifiers

```php
Inventorai::modifiers()->list();
Inventorai::modifiers()->types();
Inventorai::modifiers()->forItem($params);
Inventorai::modifiers()->search($params);
Inventorai::modifiers()->disabled();
Inventorai::modifiers()->master();
Inventorai::modifiers()->createCustom($data);
Inventorai::modifiers()->deleteCustom($id);
Inventorai::modifiers()->disable($id);
Inventorai::modifiers()->enable($id);
Inventorai::modifiers()->compose($data);
```

### Scheduler

```php
Inventorai::scheduler()->calendar($params);
Inventorai::scheduler()->weeklyAvailability($data);
Inventorai::scheduler()->checkConflicts($data);
Inventorai::scheduler()->officeHours();
Inventorai::scheduler()->estimateDuration($data);
```

### User

```php
Inventorai::user()->me();
```

## Error Handling

```php
use Inventorai\SDK\Exceptions\ApiException;
use Inventorai\SDK\Exceptions\AuthenticationException;
use Inventorai\SDK\Exceptions\RateLimitException;

try {
    $properties = Inventorai::properties()->list();
} catch (AuthenticationException $e) {
    // Invalid or expired token
} catch (RateLimitException $e) {
    // Too many requests
} catch (ApiException $e) {
    // Other API errors
}
```

## Testing

```bash
composer test
# or
vendor/bin/pest
```

## Licence

MIT
