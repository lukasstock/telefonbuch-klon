<?php

$token = "a48887ee-d5b0-4298-89f4-5a2636a877f5";
$hits = [];

//replace Funktion da curl bei Umlauten fehlerhafte Ergebnisse liefert
function replace($string)
{
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
    $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
    return str_replace($search, $replace, $string);
}


$id = $_POST['id'] ?? null;
$ort = replace($_POST['ort']);
$wort = replace($_POST['wort']);
$url = "https://www.dastelefonbuch.de/Suche/" . ucfirst($wort) . '/' . ucfirst($ort);


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Accept:application/vnd.de.it2media.tb.ipad.v2+json',
    'Authorization: Bearer ' . $token
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$output = json_decode(curl_exec($curl), true);


foreach ($output['hitlist']['hits'] as $key => $hit) {

    if (isset($_POST['id'])) {
        //Bei Detailanzeige nur ausgewählten Eintrag laden
        if ($hit['id'] == $id)
            $hits[] = $hit;
    } else {
        $hits[] = $hit;
    }
}
echo json_encode($hits);