#!/usr/bin/php
<?php
require_once 'config.php';
require_once 'functions.php';

$twitter = twitterAPI();

function get_followers() {
	$followers = array();
	$everyone = false;
	$cursor = -1;
	$screen_name = twitterScreenName();
	global $twitter;
	while ($cursor != 0 ) {
		$fields = array (
			'cursor' => $cursor,
			'screen_name' => $screen_name,
			'count' => 200,
		);
		$r = $twitter->followers_list($fields);
		$cursor = $r->next_cursor;
		foreach ($r->users as $u) {
			$followers[] = $u->screen_name;
		}
	}
	return $followers;
}

$followers = get_followers();

function send_text($users) {
	$text = shell_exec('./text.sh');
	global $twitter;
	foreach($users as $u) {
		$fields = array(
			'screen_name' => $u,
			'text' => $text,
		);
		$twitter->directMessages_new($fields);
	}
}

send_text($followers);
