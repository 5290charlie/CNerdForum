<?php if ($user) { ?>
	<h1>Pending Users</h1>
	<ul>
	<?php if ($pending) { $i=0;
		foreach($pending->result() as $row) { $i++;
			echo '<li>';
			if ((time() - $row->laston) < 500) {
				$status = 'success.png';
				$title = 'Online';
			} else {
				$status = 'failure.png';
				$title = 'Offline';
			}
			echo '<img title="' . $title . '" width="20px" src="/static/images/icons/' . $status . '" />';
			echo $row->firstname . ' ' . $row->lastname . ' (' . $row->username . ')'; ?>
			(<span class="link" onclick="showUserInfo(<? echo $row->id; ?>,'<? echo $row->username; ?>')"><? echo $row->username; ?></span>)
			<span class="extra">[<img src="/static/images/icons/mana.png" width="11px" style="margin: 0 2px;" /><? echo $mana_list[$row->id]; ?>] <? echo $rank_list[$row->id]; ?></span>
<?			if ($user->admin) {
				echo '<span id="user_' . $row->id . '">';
				echo ' - <span class="activate" onclick="activateUserConf(' . $row->id . ')">activate</span> | <span class="delete" onclick="deleteUserConf(' . $row->id . ')">delete</span>';
				echo '</span>';
			}
			echo '</li>';
		}
	} 
	if($i==0) { echo '<li>No pending users</li>'; } ?>
	</ul>
	<h1>Administrators</h1>
	<ul>
	<?php if ($instructors) { $i=0;
		foreach($instructors->result() as $in) { $i++;
			echo '<li>';
			if ((time() - $in->laston) < 500) {
				$status = 'success.png';
				$title = 'Online';
			} else {
				$status = 'failure.png';
				$title = 'Offline';
			}
			echo '<img title="' . $title . '" width="20px" src="/static/images/icons/' . $status . '" />';
			echo $in->firstname . ' ' . $in->lastname; ?>
			(<span class="link" onclick="showUserInfo(<? echo $in->id; ?>,'<? echo $in->username; ?>')"><? echo $in->username; ?></span>)
			<span class="extra"><? echo $rank_list[$in->id]; ?> [<img src="/static/images/icons/mana.png" width="11px" style="margin: 0 2px;" /><? echo $mana_list[$in->id]; ?>]</span>
<?			if ($user->admin) {
				echo '<span id="user_' . $in->id . '">';
				echo ' - <span class="activate" onclick="demoteUserConf(' . $in->id . ')">demote</span> | <span class="delete" onclick="deactivateUserConf(' . $in->id . ')">deactivate</span>';
				echo '</span>';
			}
			echo '</li>';
		}
	} 
	if($i==0) { echo '<li>No instructors</li>'; } ?>
	</ul>
	<h1>Current Users</h1>
	<ul>
		<?php if ($participants) { $i=0;
			foreach ($participants->result() as $par) { $i++;
				echo '<li>';
				if ((time() - $par->laston) < 500) {
					$status = 'success.png';
					$title = 'Online';
				} else {
					$status = 'failure.png';
					$title = 'Offline';
				}
				echo '<img title="' . $title . '" width="20px" src="/static/images/icons/' . $status . '" />';
				echo $par->firstname . ' ' . $par->lastname; ?>
				(<span class="link" onclick="showUserInfo(<? echo $par->id; ?>,'<? echo $par->username; ?>')"><? echo $par->username; ?></span>)
				<span class="extra"><? echo $rank_list[$par->id]; ?> [<img src="/static/images/icons/mana.png" width="11px" style="margin: 0 2px;" /><? echo $mana_list[$par->id]; ?>]</span>
<?				if ($user->admin) {
					echo '<span id="user_' . $par->id . '">';
					echo ' - <span class="activate" onclick="promoteUserConf(' . $par->id . ')">promote</span> | <span class="delete" onclick="deactivateUserConf(' . $par->id . ')">deactivate</span>';
					echo '</span>';
				}
				echo '</li>';
			}
		} 
		if($i==0) { echo '<li>No participants</li>'; } ?>
	</ul>
<?php 
} ?>
