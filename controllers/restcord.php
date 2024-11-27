<?php
    include __DIR__.'/vendor/autoload.php';
	include 'config.php';

	use RestCord\DiscordClient;

	error_reporting(E_ALL ^ E_NOTICE);  

	$discord_client = new DiscordClient([
		'token' => BOT_TOKEN
	]);

	$limit = 1000;
	$membercnt = 0;
	$_ids = array();

	function getTotalUsersCount($ids_users, $limit, $discord_client) {
		if( $ids_users > 0 ) 
		{
			$last_id = max($ids_users);
			$last_id = (int)$last_id;
		} 
		else 
		{
			$last_id = null;
		}

		$members = $discord_client->guild->listGuildMembers(['guild.id' => intval(SERVERID), 'limit' => $limit, 'after' => $last_id]);
		$_ids = array();
		foreach( $members as $member ) {
			$ids_users[] = $member->user->id;
			$_ids[] = $member->user->id;
		}

		if( count($_ids) > 0 ) 
		{
			return getTotalUsersCount($ids_users, $limit, $discord_client);
		} 
		else 
		{
			return $ids_users;
		}
	}
?>