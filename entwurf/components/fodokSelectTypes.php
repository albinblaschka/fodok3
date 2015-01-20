<?php

$types = fodok_utils::types();
$accordion = '<div id="accordion2" class="accordion">';

while (list($key,$val) = each($types)) {
	    $accordion .= '
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" href="#'.$key.'" data-parent="#accordion2" data-toggle="collapse">
									<i class="icon-chevron-down"></i><strong>'.$val.'</strong></a>
							</div>
							<div id="'.$key.'" class="accordion-body collapse">
								<div class="accordion-inner">'.
									file_get_contents('components/fodokDescriptions/'.$key.'.htm').
								'</div>
							</div>
						</div>';
}
$accordion .= '</div>';

?>