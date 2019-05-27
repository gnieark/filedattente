<?php
$xml = simplexml_load_file('../xml/rhs.xml');

$node = $_GET['del'];
$doc=new SimpleXMLElement($xml->asXML());

$items=$doc->xpath('//item[@id="'.$node.'"]');
if (count($items)>=1) {
    $item=$items[0];
	$dom=dom_import_simplexml($item);
    $dom->parentNode->removeChild($dom);
}
echo $doc->asXml('../xml/rhs.xml');