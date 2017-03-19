<?php

function toGeoJSON($data)
{
	$geojson = array(
		'type'      => 'FeatureCollection',
		'features'  => array()
	);

	foreach($data as $value) {
		$feature = array(
//			'id' => $value['@unid'],
			'type' => 'Feature', 
			'geometry' => array(
				'type' => 'Point',
				# Pass Longitude and Latitude Columns here
				'coordinates' => array(doubleval($value['SLng']), doubleval($value['SLat']))
			),
			# Pass other attribute columns here
			'properties' => $value
			);
		# Add feature arrays to feature collection array
		array_push($geojson['features'], $feature);
	}

	return $geojson;
}

function garbageCollection($data)
{
	$vec = array();

	foreach($data as $value) {
		if( !isset($value['@category'])) {
			unset($value['@entryid']);
//			unset($value['@unid']);
			unset($value['@position']);
			unset($value['@siblings']);
			$vec[] = $value;
		}
	}

	return $vec;
}

function getData($url)
{
	return file_get_contents($url);
}

function start($site)
{
	$json = getData($site);
	$array = json_decode($json, TRUE);
	$array = garbageCollection($array);
	$geojson = toGeoJSON($array);
	echo(json_encode($geojson));
}

	start($_GET['site']);
?>
