<?php

$fakeFileContent = '';

$vueFile = '/var/tmp/forms/src/views/Create.vue';
// $vueFile = '/var/tmp/nextcloud/spreed/src/components/AdminSettings/HostedSignalingServer.vue';

$vueSource = file($vueFile);
if ($vueSource === false) {
	echo "Warning: could not read " . $vueFile . PHP_EOL;
	// continue;
}

// t - Single Translations
$tRegexArray = [
	"/\Wt\s*\(\s*'([\w]+)',\s*'(.+)'/", // Single Quotes
	"/\Wt\s*\(\s*\"([\w]+)\",\s*\"(.+)\"/", // Double Quotes
	"/\Wt\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*\)/msU" // Template Quotes
];
foreach($tRegexArray as $regex) {
	$regexMatches = preg_grep($regex, $vueSource);

	foreach ($regexMatches as $lineNum => $match) {
		// Check for comment in previous line. Either HTML or JS comment.
		if(preg_match("/\W(\/\/|\/\*|<!--)\s(TRANSLATORS)\s(.+)/", $vueSource[$lineNum-1], $commentSplit)) {
			// Remove possible comment-end-tags and insert comment
			$commentText = preg_replace('/\s*(-->|\*\/)\s*/', '', $commentSplit[3]);
			$fakeFileContent .= "// TRANSLATORS " . preg_replace('/\s+/', ' ', $commentText) . PHP_EOL;
		}

		// Insert translation-function
		preg_match($regex, $match, $matchSplit);
		$fakeFileContent .= "t('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $matchSplit[2]) . "');" . PHP_EOL;
	}
}

// n - Plural Translations
$nRegexArray = [
	"/\Wn\s*\(\s*'([\w]+)',\s*'(.+)'\s*,\s*'(.+)'\s*(.+)/", // Single Quotes
	"/\Wn\s*\(\s*\"([\w]+)\",\s*\"(.+)\"\s*,\s*\"(.+)\"\s*(.+)/", // Double Quotes
	"/\Wn\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*,\s*\`(.+)\`\s*\)/msU" // Template Quotes
];
foreach($nRegexArray as $regex) {
	$regexMatches = preg_grep($regex, $vueSource);

	foreach ($regexMatches as $lineNum => $match) {
		// Check for comment in previous line. Either HTML or JS comment.
		if(preg_match("/\W(\/\/|\/\*|<!--)\s(TRANSLATORS)\s(.+)/", $vueSource[$lineNum-1], $commentSplit)) {
			// Remove possible comment-end-tags and insert comment
			$commentText = preg_replace('/\s*(-->|\*\/)\s*/', '', $commentSplit[3]);
			$fakeFileContent .= "// TRANSLATORS " . preg_replace('/\s+/', ' ', $commentText) . PHP_EOL;
		}

		// Insert translation-function
		preg_match($regex, $match, $matchSplit);
		$fakeFileContent .= "n('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $matchSplit[2]) . "', '" . preg_replace('/\s+/', ' ', $matchSplit[3]) . "');" . PHP_EOL;
	}
}

error_log(print_r($fakeFileContent, TRUE));
