<?php
if ($total_comments > 0) { ?>
<div class="post-nav">
	<? if ($page == 0) $dis = 'disabled'; else $dis = 'link'; ?>
	<span class="left">
		<span onclick="navComments(<? echo $post->id; ?>, 0)" class="<? echo $dis; ?>">[&lt;&lt; First</span>
		<span onclick="navComments(<? echo $post->id; ?>, <? echo $page-1; ?>)" class="<? echo $dis; ?>">&lt;&lt; Prev</span>
	</span>
	<? if ((($page+1)*COMMENTS_PER_PAGE) >= $total_comments) $dis = 'disabled'; else $dis = 'link'; ?>
	<span class="right">
		<span <? if ($dis != 'disabled'){ ?> onclick="navComments(<? echo $post->id; ?>, <? echo $page+1; ?>)" <? } ?> class="<? echo $dis; ?>">Next &gt;&gt;</span>
		<span <? if ($dis != 'disabled'){ ?> onclick="navComments(<? echo $post->id; ?>, <? echo floor($total_comments / COMMENTS_PER_PAGE); ?>)" <? } ?> class="<? echo $dis; ?>">Last &gt;&gt;]</span>
	</span>
	<div>
		<? 
		
		if (($begin == $total_comments) && ($end == $total_comments))
			echo $total_comments . ' ';
		else
			echo $begin . '-' . $end; ?> 
		of <? echo $total_comments; ?>
	</div>
</div>
<?php } ?>