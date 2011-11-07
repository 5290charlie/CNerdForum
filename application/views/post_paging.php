<?php
if ($total_posts > 0) { ?>
<div class="post-nav">
	<? if ($page == 0) $dis = 'disabled'; else $dis = 'link'; ?>
	<span class="left">
		<span onclick="sortPosts(0,<? echo $sort_parms; ?>)" class="<? echo $dis; ?>">[&lt;&lt; First</span>
		<span onclick="sortPosts(<? echo $page-1 . ',' . $sort_parms; ?>)" class="<? echo $dis; ?>">&lt;&lt; Prev</span>
	</span>
	<? if ((($page+1)*POSTS_PER_PAGE) >= $total_posts) $dis = 'disabled'; else $dis = 'link'; ?>
	<span class="right">
		<span <? if ($dis != 'disabled'){ ?> onclick="sortPosts(<? echo $page+1 . ',' . $sort_parms; ?>)" <? } ?> class="<? echo $dis; ?>">Next &gt;&gt;</span>
		<span <? if ($dis != 'disabled'){ ?> onclick="sortPosts(<? echo floor($total_posts / POSTS_PER_PAGE) . ',' . $sort_parms; ?>)" <? } ?> class="<? echo $dis; ?>">Last &gt;&gt;]</span>
	</span>
	<div>
		<? 
		if (($begin == $total_posts) && ($end == $total_posts))
			echo $total_posts . 'th ';
		else
			echo $begin . '-' . $end; ?> 
		of <? echo $total_posts; ?>
	</div>
</div>
<?php } ?>