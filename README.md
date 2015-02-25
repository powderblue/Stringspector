# Stringspector

Stringspector enables you to find, and manipulate, email addresses and telephone numbers in strings.  Additionally, 
Stringspector provides a simple framework for implementing your own inspector plugins.

## Examples

The code for the following examples can be found in `tests/examples/`.

### Default `obfuscate()` Behaviour

```php
$message = <<<END
Hello, World!

I prefer to be contacted by email, but you can also telephone me.

Send email to dan@powder-blue.com or dan@seetheworld.com.

Telephone enquirers, call +44 (0)1962 791 191 and then dial extension 107.

Thanks,
Dan

END;

$stringspector = (new \PowderBlue\Stringspector\Factory())->create($message);

$stringspector->emailAddresses->obfuscate();
$stringspector->telephoneNumbers->obfuscate();

print $stringspector->getString();
```

The above example will output:

```
Hello, World!

I prefer to be contacted by email, but you can also telephone me.

Send email to ******************* or *******************.

Telephone enquirers, call +44 *************** and then dial extension 107.

Thanks,
Dan
```

### `obfuscate()` Using A Custom Replacement String

```php
$message = <<<END
<p>You can email me at dan@powder-blue.com.</p>

END;

$stringspector = (new \PowderBlue\Stringspector\Factory())->create($message);

$stringspector->emailAddresses->obfuscate('<span class="redacted email"></span>');

print $stringspector->getString();
```

The above example will output:

```
<p>You can email me at <span class="redacted email"></span>.</p>
```
