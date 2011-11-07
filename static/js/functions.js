var t, c;
var loading = '<div style="width: 550px; text-align: center; margin: 0 auto;"><img src="/static/images/loading.gif" /></div>';
jQuery(document).ready(function() {
	if ($("#msg").html() != '')
		showMsgs();
		
	$(".game-piece").draggable({ 
		grid: [ 20,20 ],
		containment: 'parent'
	});
	
	$("#login-wrapper, #register-wrapper, #upload-wrapper, #user-account").dialog({
		modal: true,
		autoOpen: false,
		resizable: false
	});

	updateUserStatus();
	
	$.ajax({
		url: '/ajax/searchComplete', 
		type: 'json', 
		success: function(data) {
			$("#search").autocomplete({
				source: $.parseJSON(data),
				select: function() {
					$("#searchForm").submit();
				}
			});
		}
	});

	$("#comment-button").button();

	$("#content").tabs();

	$("#login-form input").keypress(function (e) {
		if ((e.which && e.which==13) || (e.keyCode && e.keyCode==13)) {
			$username = $("#login-username").val();
			$pass = $("#login-password").val();
			$.post("/action/login/", { username: $username, password: $pass }, function(data) {
				$("#msg").html(data);
				showMsgs();
				$result = $("#msg p").attr('class');
				if ($result == 'success') {
					$("#login-wrapper").dialog('close');
					updateUserStatus();
					$("#users-tab").css('display', 'block');
					$("#users").css('display', 'block');
				}
			});			
			return false;
		} else {
			return true;
		}
	});

	$("#register-form input").keypress(function (e) {
		if ((e.which && e.which==13) || (e.keyCode && e.keyCode==13)) {
			$fname = $("#register-firstname").val();
			$lname = $("#register-lastname").val();
			$email = $("#register-email").val();
			$username = $("#register-username").val();
			$pass = $("#register-password").val();
			$conf = $("#register-confirm").val();
			$.post("/action/register/", { firstname: $fname, lastname: $lname, email: $email, username: $username, password: $pass, confirm: $conf }, function(data) {
				$("#msg").html(data);
				showMsgs();
				$result = $("#msg p").attr('class');
				if ($result == 'success') {
					$("#register-wrapper").dialog('close');
				}
			});
			return false;
		} else {
			return true;
		}
	});
	
	$("#sidebar input").keypress(function(e) {
		if ((e.which && e.which==13) || (e.keyCode && e.keyCode==13)) {
			$chat = $("#chat").val();
			$url = '/ajax/chat';
			$.post($url, { chat: $chat }, function(data) {
				$("#chat-content").html(data);
				$("#chat").val('');
				var elm = document.getElementById('chat-content');
				$("#chat-content").scrollTop(elm.scrollHeight);
			});
			return false;	
		} else {
			return true;
		}
	});

});

function chat() {
	$chat = $("#chat").val();
	$url = '/ajax/chat';
	$.post($url, { chat: $chat }, function(data) {
		$("#chat-content").html(data);
		$("#chat").val('');
	});
	return false;
}

function updateChat() {
	c = setTimeout("updateChat()", 500);
	$url = '/ajax/chat';
	$content = $("#chat-content").html();
	$.post($url, function(data) {
		$("#chat-content").html(data);
//		var elm = document.getElementById('chat-content');
//		$("#chat-content").scrollTop(elm.scrollHeight);
	});
	return false;
}

function login() {
	$("#login-wrapper").dialog({
		buttons: {
			"Login": function() {
				$username = $("#login-username").val();
				$pass = $("#login-password").val();
				$.post("/action/login/", { username: $username, password: $pass }, function(data) {
					$("#msg").html(data);
					showMsgs();
					$result = $("#msg p").attr('class');
					if ($result == 'success') {
						$("#login-wrapper").dialog('close');
						updateUserStatus();
						$("#users-tab").css('display', 'block');
						$("#users").css('display', 'block');
					}
				});
			},
			"Cancel": function() {
				$(this).dialog('close');
			}
		}
	});
	$("#login-wrapper").dialog('open');
	return false;
}

function logout() {	
	$url = "/action/logout/";
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateUserStatus();
		$("#users-tab").css('display', 'none');
		$("#users").css('display', 'none');
		$("#content").tabs({ selected: 0 });
	});
	return false;
}

function register() {
	$("#register-wrapper").dialog({
		buttons: {
			"Register": function() { 
				$fname = $("#register-firstname").val();
				$lname = $("#register-lastname").val();
				$email = $("#register-email").val();
				$username = $("#register-username").val();
				$pass = $("#register-password").val();
				$conf = $("#register-confirm").val();
				$.post("/action/register/", { firstname: $fname, lastname: $lname, email: $email, username: $username, password: $pass, confirm: $conf }, function(data) {
					$("#msg").html(data);
					showMsgs();
					$result = $("#msg p").attr('class');
					if ($result == 'success') {
						$("#register-wrapper").dialog('close');
					}
				});
			},
			"Cancel": function() {
				$(this).dialog('close');
			}
		}
	});
	$("#register-wrapper").dialog('open');
	return false;
}

function deletePost(id) {
	if (confirm('Are you sure?')) {
		$url = '/action/deletePost/' + id;
		$.post($url, function(data) {
			$("#msg").html(data);
			showMsgs();
			$result = $("#msg p").attr('class');
			if ($result == 'success')
				updateContent();
		});	
	}	
	return false;
}

function deleteComment(id) {
	if (confirm('Are you sure?')) {
		$url = '/action/deleteComment/' + id;
		$.post($url, function(data) {
			$("#msg").html(data);
			showMsgs();
			$result = $("#msg p").attr('class');
			if ($result == 'success')
				updatePost();
		});	
	}		
	return false;
}

function leaveComment(id) {
	$url = '/action/comment/' + id;
	$comment = $("#comment").val();
	$.post($url, { comment: $comment }, function(data) {
		$("#msg").html(data);
		showMsgs();
		$result = $("#msg p").attr('class');
		if ($result == 'success')
			updatePost();
	});
	return false;
}

function userInfo(id) {
	$.post('/ajax/user_info/' + id, function(data) {
		$("#user-inner").html(data);
	});
}

function userTrophies(mana) {
	$.post('/ajax/user_trophies/' + mana, function(data) {
		$("#user-inner").html(data);
	});
}

function userPosts(id) {
	$.post('/ajax/user_posts/' + id, function(data) {
		$("#user-inner").html(data);
	});
}

function userComments(id) {
	$.post('/ajax/user_comments/' + id, function(data) {
		$("#user-inner").html(data);
		$("#user-inner .top-comments span").click(function() {
			$post = $(this).attr('post');
			$comment = $(this).attr('comment');
			gotoComment($post, $comment);
		});
	});
}

function viewPost(id) {
	window.location = '/main/post/' + id;
/*
	$url = '/ajax/post/' + id;
	$.post($url, function(data) {
		$("#posts").html(data);
	});
*/
}	

function showUserInfo(uid, username) {
	$.post('/ajax/user/' + uid, function(data) {
		$title = username + "'s Account";
		$("#user-account").dialog({
			height: 600,
			width: 450,
			title: $title,
			buttons: {
				"Close": function() {
					$(this).dialog('close');
				}
			}
		});
		$("#user-account").html(data);
		$("#user-radio").buttonset();
		$("#user-radio").css('font-size', '10px');
		$("#user-radio").css('width', '300px');
		$("#user-radio").css('margin', '0 auto');
		$("#user-account").dialog('open');
	});
}

function showUpload() {
	$("#upload-wrapper").dialog({
		height: 300,
		width: 400
	});
	$("#upload-wrapper").dialog({
		buttons: {
			"Post": function() {
				$title = $("#upload-title").val();
				$details = $("#upload-details").val();
				
				$.post('/action/upload/', { title: $title, details: $details }, function(data) {
					$("#msg").html(data);
					showMsgs();
					$result = $("#msg p").attr('class');
					if ($result == 'success') {
						$("#upload-wrapper").dialog('close');
						updateContent();
					}
				});
			},
			"Cancel": function() {
				$(this).dialog('close');
			}
		}
	});
	$("#upload-wrapper").dialog('open');
	return false;
}

function updateUpload() {
	$type = document.getElementById('upload-type').value;
	$.post('/ajax/post_form/', { type: $type }, function(data) {
		$("#upload-ajax").html(data);
	});
}

function showMsgs() {
	$("#msg").css('display', 'block');
	t = setTimeout("clearMsgs()", 5000);
}

function clearMsgs() {
	$("#msg").html('');
	$("#msg").css('display', 'none');
 	clearTimeout(t);
}

function gotoComment(pid, cid) {
	$url = '/test/gotoComment/' + pid + '/' + cid + '/';
	$.post($url, function(data) {
		$("#post").html(data);
		$("#comment-button").button();
	});
}

function updateAvatars() {
	$url = '/ajax/updateAvatars/';
	$.post($url);
}

function upVotePost(id, page, type, way) {
	$url = '/ajax/upvote_post/';
	$id = id;
			
	$.post($url, { id: $id }, function(data) {
		if (data == 'success')
			sortPosts(page, type, way);
		else
			login();
	});
}

function downVotePost(id, page, type, way) {
	$url = '/ajax/downvote_post/';
	$id = id;
			
	$.post($url, { id: $id }, function(data) {
		if (data == 'success')
			sortPosts(page, type, way);
		else
			login();
	});
}

function upVoteComment(id, pg) {
	$url = '/ajax/upvote_comment/';
	$id = id;
	$.post($url, { id: $id }, function(data) {
		if (data == 'success')
			updateCommentVote(pg);
		else
			login();
	});
}

function downVoteComment(id, pg) {
	$url = '/ajax/downvote_comment/';
	$id = id;
	$.post($url, { id: $id }, function(data) {
		if (data == 'success')
			updateCommentVote(pg);
		else
			login();
	});
}

function updateCommentVote(pg) {
	$id = $("#post").attr('pid');
	$url = '/ajax/post/' + $id;
	$.post($url, { page: pg }, function(data) {
		$("#post").html(data);
		$("#comment-button").button();
	});
}

function updatePost() {
	$("#post").html(loading);
	$id = $("#post").attr('pid');
	$url = '/ajax/post/' + $id;
	$.post($url, function(data) {
		$("#post").html(data);
		$("#comment-button").button();
	});
}

function updateContent() {
	$("#posts").html(loading);
	$.post('/ajax/posts/', function(data) {
		$("#posts").html(data);
	});
	$.post('/ajax/users/', function(data) {
		$("#users").html(data);
	});
	updatePost();
	updateChat();
	updateAvatars();
}

function updateUserStatus() {
	$.post('/ajax/userstatus/', function(data) {
		$("#userstatus").html(data);
		updateContent();
	});
}

function sortPosts(page, type, way) {
	$.post('/ajax/posts/', { page: page, sort: type, way: way }, function(data) {
		$("#posts").html(data);
	});
}

function navComments(post, page) {
	$("#post").html(loading);
	$url = '/ajax/post/' + post;
	$.post($url, { page: page }, function(data) {
		$("#post").html(data);
		$("#comment-button").button();
	});
}

function activateUserConf(id) {
	$conf = ' - Are you sure? <span class="activate" onclick="activateUser(' + id + ')">Yes</span>';
	$conf += ' | <span class="delete" onclick="updateContent()">No</span>';
	$("#user_"+id).html($conf);
}

function activateUser(id) {
	$url = '/action/activate/' + id;
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateContent();
	});
}

function deactivateUserConf(id) {
	$conf = ' - Are you sure? <span class="activate" onclick="deactivateUser(' + id + ')">Yes</span>';
	$conf += ' | <span class="delete" onclick="updateContent()">No</span>';
	$("#user_"+id).html($conf);
}

function deactivateUser(id) {
	$url = '/action/deactivate/' + id;
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateContent();
	});
}

function deleteUserConf(id) {
	$conf = ' - Are you sure? <span class="activate" onclick="deleteUser(' + id + ')">Yes</span>';
	$conf += ' | <span class="delete" onclick="updateContent()">No</span>';
	$("#user_"+id).html($conf);
}

function deleteUser(id) {
	$url = '/action/delete/' + id;
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateContent();
	});
}

function promoteUserConf(id) {
	$conf = ' - Are you sure? <span class="activate" onclick="promoteUser(' + id + ')">Yes</span>';
	$conf += ' | <span class="delete" onclick="updateContent()">No</span>';
	$("#user_"+id).html($conf);
}

function promoteUser(id) {
	$url = '/action/promote/' + id;
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateContent();
	});
}

function demoteUserConf(id) {
	$conf = ' - Are you sure? <span class="activate" onclick="demoteUser(' + id + ')">Yes</span>';
	$conf += ' | <span class="delete" onclick="updateContent()">No</span>';
	$("#user_"+id).html($conf);
}

function demoteUser(id) {
	$url = '/action/demote/' + id;
	$.post($url, function(data) {
		$("#msg").html(data);
		showMsgs();
		updateContent();
	});
}
