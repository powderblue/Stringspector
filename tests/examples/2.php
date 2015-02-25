<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$message = <<<END
<p>You can email me at dan@powder-blue.com.</p>

END;

$stringspector = (new \PowderBlue\Stringspector\Factory())->create($message);

$stringspector->emailAddresses->obfuscate('<span class="redacted email"></span>');

print $stringspector->getString();
