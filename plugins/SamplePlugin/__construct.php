<?php
namespace Boke0\Mechanism\Plugins\SamplePlugin;

use \Boke0\Mechanism\Api\Plugin;

$plugin=new Plugin();
$plugin->endpoint(SampleEndpoint::class);
$plugin->templateExtension(SampleTemplateExtension::class);

return $plugin;
