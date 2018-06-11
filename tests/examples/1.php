<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$message = <<<END
Hello, World!

I prefer to be contacted by email, but you can also telephone me.

Send email to dan@powder-blue.com or dan@seetheworld.com.

Telephone enquirers, call +44 (0)1962 791 191 and then dial extension 107.

Thanks,
Dan

END;

$stringspector = (new \PowderBlue\Stringspector\Factory())->createContactDetailsObfuscator($message);

$stringspector->emailAddresses->obfuscate();
$stringspector->telephoneNumbers->obfuscate();

print $stringspector->getString();
