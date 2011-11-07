<div style="width: 100px; margin: 0 auto;" id="user-radio">
	<input type="radio" id="user-info" name="user-radio" onclick="userInfo(<? echo $user->id; ?>)" checked="checked" /><label for="user-info">Information</label>
	<input type="radio" id="user-trophies" onclick="userTrophies(<? echo $postMana + $commentMana; ?>)" name="user-radio" /><label for="user-trophies">Trophies</label>
	<input type="radio" id="user-posts" name="user-radio" onclick="userPosts(<? echo $user->id; ?>)" /><label for="user-posts">Posts</label>
	<input type="radio" id="user-comments" name="user-radio" onclick="userComments(<? echo $user->id; ?>)" /><label for="user-comments">Comments</label>
</div>
<div id="user-inner" style="margin-top:20px;">
	<? include('ajax_user_info.php'); ?>
</div>