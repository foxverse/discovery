<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '../AltoRouter.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '../discovery.php');

if ($discoveryConfig['maintenanceMode']) {
	$dom = new DOMDocument();
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;

	$xml->addChild('has_error', 1);
	$xml->addChild('version', 1);
	$xml->addChild('code', 400);
	$xml->addChild('error_code', 3);
	$xml->addChild('message', 'SERVICE_MAINTENANCE');

	$dom->loadXML($xml->asXML());

	header('Content-Type: application/xml');
	http_response_code(400);
	print($dom->saveXML());
	exit;
}

$router = new AltoRouter();

$router->addRoutes(array(
	array('GET', '/v1/endpoint[*:type]', 'v1/endpointHandler.php', 'Endpoint-handler'),
));

$match = $router->match(urldecode($_SERVER['REQUEST_URI']));
if ($match) {
	foreach ($match['params'] as &$param) {
		${key($match['params'])} = $param;
	}
	require_once $match['target'];
} else {
	http_response_code(404);
	print('404');
	exit;
}