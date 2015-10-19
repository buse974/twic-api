#!/usr/bin/php

<?php
$file = json_decode(file_get_contents("EQCQ.json"), true);

foreach($file as $v) {
    echo "INSERT INTO `dimension`(`name`,`describe`)VALUES('".$v['name']."','".str_replace("'","''",$v['describe'])."');\n";   
    echo "SELECT LAST_INSERT_ID() INTO @DIMENSION;\n"; 
    foreach($v['component'] as $c) {
	echo "INSERT INTO `component`(`name`,`dimension_id`,`describe`)VALUES('".$c['name']."', @DIMENSION, '".str_replace("'","''",$c['describe'])."');\n";
	echo "SELECT LAST_INSERT_ID() INTO @COMPONENT;\n";
    	foreach($c['component_scale'] as $cs) {
		echo "INSERT INTO `component_scale`(`component_id`,`min`,`max`,`describe`,`recommandation`) VALUES(@COMPONENT, ".$cs['min'].", ".$cs['max'].", '".str_replace("'","''",$cs['describe'])."', '".str_replace("'","''",$cs['recommandation'])."');\n";
    	}
	foreach($c['question'] as $q) {
	    echo "INSERT INTO `apilms`.`question`(`text`,`component_id`,`created_date`)VALUES('".$q['text']."', @COMPONENT, '".(new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')."');\n";
	}
    }
}

?>
