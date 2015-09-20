<?php
function ensure($arr, $keys) {
	if (!is_array($arr)) return false;
	foreach ($keys as $k) {
		if (!isset($arr[$k])) {
			return false;
		}
	}
	return true;
}

function twitterAPI() {
	require_once('codebird/src/codebird.php');
	require_once('config.php');
	\Codebird\Codebird::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(OAUTH_TOKEN, OAUTH_SECRET);
	return $cb;
}

function twitterCachedCall($method, $parameters, $field = null, $lifetime = 3600) {
	$twitter = twitterAPI();
	$refresh = false;
	$fn = __DIR__ . 'cache/' . md5(OAUTH_TOKEN . $method . serialize($parameters));
	if (!file_exists($fn)) {
		$refresh = true;
	} else {
		if ((time() - filectime($fn)) > $lifetime) $refresh = true;
	}
	if ($refresh) {
		$data = $twitter->$method($parameters);
		file_put_contents($fn, json_encode($data));
	}
	$data = json_decode(file_get_contents($fn), true);
	if (!is_null($field)) {
		$data = $data[$field];
	}
	return $data;
}

function twitterScreenName() {
	return twitterCachedCall('account_settings', array(), 'screen_name');
}

