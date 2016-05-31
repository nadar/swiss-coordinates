Convert WGS (Word Geodetic System) coordinates into CH (Swiss-Coordinates) and vice versa.
====

Wrapper class with composer support based on http://www.swisstopo.admin.ch/internet/swisstopo/de/home/products/software/products/skripts.html

### Informations about Coordinates:

+ Lat/Long = Latitude And Longitude
+ WGS = Word Geodetic System
+ Lat = Y-Axis
+ Long = X-Axis

### Example swiss coordaintes:

+ Rigi: Y = 679520, X = 212273
+ Zürich-Seebach: Y = 684592, X = 252857

### Installation

```
composer require nadar/swiss-coordinates
```

### Usage example:

```php
use swisscoordinates\Converter;

echo Converter::CHtoWGSlat(679520, 212273); // 47.056709
echo Converter::CHtoWGSlong(679520, 212273); // 8.485306
```
