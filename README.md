To using composer:

```
composer require dewebontw/jsonvalidator ^0.1
```

Beware that this is currenly more a proof of concept, as does more or less basic JSON validation.

## The why:

When working with JSON API's it's a lot of hassle validating the response. Are you going to do it something like this?

```php
$json = json_decode($response_body, true));

if (is_array($json)) {
	foreach ($json as $property) {
		$info_i_need = $property['sub']['item'] ?? null;
		
		if (is_string($info_i_need)) {
			echo $info_i_need . "\n";
		}
	}
}
```

And this is only a really, really simple example. Usually it is loop in loop in loop.

## JSON Schema

Then there is [JSON Schema](https://json-schema.org/), which is great, but:

* It is in draft;
* The schema's can become quite large;
* All fields have to be defined, which does make sense, except:
  * When you only need 1% of the response the schema becomes quite unclear;
  * When the response changes (a field is added for example), the schema as a whole does not match anymore.

## JSON Validator

So I started working on this little JSON validator. Lets take this Yahoo weather as a example request: https://gist.github.com/vrijdag/54d17f2158e538c0ad723d7e544523ee

In this response we can find all sorts of data, let's say I need the following:

* The language of the response;
* The current wind direction;
* The max high temperature of today's forecast.

Based on the response I have to create this 'schema'. Only the fields in this schema are required to be in the response, all other data is left alone.

```json
{
  "query": {
    "lang": "string",
    "results": {
      "channel": {
        "wind": {
          "direction": "number"
        },
        "item": {
          "forecast": [
            {
              "high": "number"
            }
          ]
        }
      }
    }
  }
}
```

That's quite a clear file:

* The ``query`` property has to be an object, containing;
* A ``language`` property which needs to be a string;
* A ``results`` property, containing an object;
* Which contains a channel property

... and so on.

A special one is the ``forecast`` property. Based on the schema, this should contain an array of which each entry should be an object containing a ``high`` property which should be a number (that is a numeric string, not an int or float).

#### Tying this together

So tying to this together we have a JSON response from the API and a schema, it would be something like this to validate:

```php
$schema = file_get_contents('schema.json');
$response = file_get_contents('response.json');

try {
    $jsonValidator = new JsonValidator\JsonValidator($schema, $response);
    $jsonValidator->validate();

    // This now exists, and is numeric
    echo $jsonValidator->getData()->query->results->channel->wind->direction . "\n";

    foreach ($jsonValidator->getData()->query->results->channel->item->forecast as $forecast) {
        // Each days high temperature
        echo $forecast->high . "\n";
    }
}
catch (JsonValidator\Exceptions\JsonValidatorException $e) {
    echo $e->getMessage();
}
```