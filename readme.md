# Luckyseven Validation Bundle
Luckyseven Search Bundle for Doctrine and other ORM manager (TODO)

## Update composer.json and register the repositories
```
{
    ...
    "repositories": [
        {"type": "git", "url":  "https://github.com/giacomoto/search-bundle.git"}
    ],
}
```

## Install
```
composer require luckyseven/search:dev-main
```

## Usage
Searchable Entities must implement `IEntityHasGeolocatedSearch`

Option 1: declare SearchService via service.yaml and specify your ORM Adapter via argument parameter
```yaml
services:
    Luckyseven\Bundle\LuckysevenSearchBundle\Service\SearchService:
        arguments:
        $ormAdapter: '@Luckyseven\Bundle\LuckysevenSearchBundle\Adapter\DoctrineManagerAdapter'
```

Option 2: extend the SearchService to access the QueryStruct and add more options such as filters.

Ex: SearchService.php
```php
<?php

namespace App\Service;

use App\Entity\Place;
use Luckyseven\Bundle\LuckysevenSearchBundle\Adapter\DoctrineManagerAdapter;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchCoordinates;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchOptions;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\QueryStructure;
use Luckyseven\Bundle\LuckysevenSearchBundle\Service\SearchService as L7SearchService;

class SearchService extends L7SearchService
{
    public function __construct(DoctrineManagerAdapter $doctrineManagerAdapter)
    {
        parent::__construct($doctrineManagerAdapter);
    }

    public function searchPlacesByCoordsAndFilters(array $queryParams): array
    {
        $myPosition = new GeolocatedSearchCoordinates($queryParams['lat'], $queryParams['lng']);
        $options = new GeolocatedSearchOptions($myPosition, $queryParams['radius']);

        // access the QueryStruct object and add more sql
        return parent::searchByCoords(Place::class, $options, function ($queryStruct, $tableAlias) use ($queryParams) {
            foreach ($queryParams as $key => $value) {
                if ($key === 'tags') {
                    $queryStruct->addJoin(QueryStructure::LEFT_JOIN, "entity_tag tag_table", "on tag_table.reference_id = {$tableAlias}.id");
                    $queryStruct->andWhere("tag_table.tag_id IN ({$value})");
                }
            }
        });
    }
}
```
