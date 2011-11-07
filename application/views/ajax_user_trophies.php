<h4>Trophy Case</h4>
<div class="trophies">
	<?php
	$n = $trophies[0];
	if ($n > 0) {
		for ($i=1; $i<=$n; $i++) {
			$row = $trophies[$i];
			echo '<img title="' . $row->mana . ' Mana" src="/static/images/icons/' . $row->icon . '" />';
			if (($i%4)==0)
				echo '<br />';
		}
	} else {
		echo 'No Trophies';
	}
	?>
</div>