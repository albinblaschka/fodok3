<?php

$projects = fodok_utils::projects();
$select = '<label class="span3" for="project°">Zuordnung zu °. Projekt:</label><select id="project°" name="project°"><option value="">Bitte wählen Sie!</option>';
$projectSelect = array();
$allProjects = $select;
$inst = fodok_utils::getStructure();

while (list($key, $val) = each($inst)) {
    $projectSelect[$key] = $select;
}
reset($inst);
while (list($key, $val) = each($inst)) {
	foreach ($projects as $project) {
		if ($project['institut'] == $key) {
		    $projectSelect[$key] .='<option class="I'.$key.'" value="'.$project['leistungsnummer'].'">'.
		    trim($project['bezeichnung']).' ('.trim($project['leistungsnummer']).')</option>'."\n";
			$allProjects .='<option class="I'.$key.'" value="'.$project['leistungsnummer'].'">'.
		    trim($project['bezeichnung']).' ('.trim($project['leistungsnummer']).')</option>'."\n";
		}
	}
	$projectSelect[$key] .= '</select>';
}
$projectSelect[0] = $allProjects.'</select>'
?>
