<h4>User Information</h4>
<div class="avatar">
	<img src="<? echo $user->photoloc; ?>" />
</div>
<table align="center" type="hidden">
	<tr>
		<td style="text-align:right;font-weight:bold;">Username: </td>
		<td><? echo $user->username; ?></td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;">Firstname: </td>
		<td><? echo $user->firstname; ?></td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;">Lastname: </td>
		<td><? echo $user->lastname; ?></td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;">Email: </td>
		<td><? echo $user->email; ?></td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;">Total Mana: </td>
		<td>
			[<img src="/static/images/icons/mana.png" width="11px" style="margin: 0 2px;" /><? echo $postMana + $commentMana; ?>]
		</td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;">User Rank: </td>
		<td><? echo $userRank; ?></td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;"># Posts: </td>
		<td>
			<? echo $numPosts; ?>
			<span style="opacity:.5;font-size:10px;">
				[<img src="/static/images/icons/mana.png" width="8px" style="margin: 0 2px;" />
				<span title="Post Mana" style="color:green;"><? echo $postMana; ?></span>
				]
			</span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;font-weight:bold;"># Comments: </td>
		<td>
			<? echo $numComments; ?>
			<span style="opacity:.5;font-size:10px;">
				[<img src="/static/images/icons/mana.png" width="8px" style="margin: 0 2px;" />
				<span title="Comment Mana" style="color:blue;"><? echo $commentMana; ?></span>
				]
			</span>
		</td>
	</tr>
</table>
