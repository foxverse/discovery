<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '../../discovery.php');

if (!isset($type)) {
	$type = 'index';
}

$dom = new DOMDocument();
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$xml->addChild('has_error', 0);
$xml->addChild('version', 1);

$endpoint = $xml->addChild('endpoint');

switch ($type) {
	case '-d1':
		$dev = $discoveryConfig['Endpoint-dev'];
		$endpoint->addChild('host', $_SERVER['HTTP_HOST']);
		$endpoint->addChild('api_host', $dev['api']);
		$endpoint->addChild('portal_host', $dev['portal']);
		$endpoint->addChild('n3ds_host', $dev['n3ds']);
		break;
	case 'index':
		$index = $discoveryConfig['Endpoint-index'];
		$endpoint->addChild('host', $_SERVER['HTTP_HOST']);
		$endpoint->addChild('api_host', $index['api']);
		$endpoint->addChild('portal_host', $index['portal']);
		$endpoint->addChild('n3ds_host', $index['n3ds']);
		break;
}

$dom->loadXML($xml->asXML());
$xml = $dom->saveXML();

header('Content-Type: application/xml');
header('Content-Length: ' . strlen($xml));

echo $xml;
