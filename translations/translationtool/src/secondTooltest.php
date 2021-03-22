<?php
$vueFile = './Create.vue';
// $vueFile = '/var/tmp/nextcloud/spreed/src/components/AdminSettings/HostedSignalingServer.vue';

$fakeFileContent = '';

$vueSource = file_get_contents($vueFile);
if ($vueSource === false) {
	echo "Warning: could not read " . $vueFile . PHP_EOL;
	// continue;
}

// Optional TRANSLATORS-comment
$translatorsRegex = "(\W(\/\/|\/\*|<!--)\s(TRANSLATORS)\s(.+))*";
// Text between comment and translation-function
$inbetweenRegex = "(\s*.*\s*)";

/**
 * t - Singular Translations
 */
// Regex for translation-function t. Items Contain Regex-Ending '/' as template quotes needs special ending
$tTranslationRegex = [
	"\Wt\s*\(\s*'([\w]+)',\s*'(.+)'" . "/", 				// Single Quotes
	"\Wt\s*\(\s*\"([\w]+)\",\s*\"(.+)\"" . "/", 			// Double Quotes
	"\Wt\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*\)" . "/msU" 	// Template Quotes
];

foreach ($tTranslationRegex as $tRegex) {
	// print_r('----------------- ' . $tRegex . ' --------------');

	// Regex-Ending within tRegex, see array above.
	preg_match_all("/". $translatorsRegex . $inbetweenRegex . $tRegex, $vueSource, $matches);
	// print_r($matches);

	/* The variable $matches now contains 8 Arrays corresponding to the Regex-Groups, out of which:
	 * $matches[0] Holds an array of the whole Matches
	 * $matches[4] Holds an array of the comments, but still including a possible comment-ending (-->|* /)
	 * $matches[7] Holds an array of the texts to translate
	 * All inner arrays contain the respective content with corresponding indices.
	 * So $matches[4][2] is the corresponding comment to the text in $matches[7][2]
	 */
	foreach ($matches[0] as $index => $fullMatch) {
		if ($matches[4][$index] !== '') {
			// Replace end-tags by nothing
			$comment = preg_replace('/\s*(-->|\*\/)\s*/', '', $matches[4][$index]);
			$fakeFileContent .= "// TRANSLATORS " . $comment . PHP_EOL;
		}
		$fakeFileContent .= "t('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $matches[7][$index]) . "');" . PHP_EOL;
	}
};


/**
 * n - Plural translations
 */
// Regex for translation-function n. Items Contain Regex-Ending '/' as template quotes needs special ending
$nTranslationRegex = [
	"\Wn\s*\(\s*'([\w]+)',\s*'(.+)'\s*,\s*'(.+)'\s*(.+)" . "/", 				// Single Quotes
	"\Wn\s*\(\s*\"([\w]+)\",\s*\"(.+)\"\s*,\s*\"(.+)\"\s*(.+)" . "/", 			// Double Quotes
	"\Wn\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*,\s*\`(.+)\`\s*\)" . "/msU" 	// Template Quotes
];

foreach ($nTranslationRegex as $nRegex) {
	print_r('----------------- ' . $nRegex . ' --------------');

	// Regex-Ending within tRegex, see array above.
	preg_match_all("/". $translatorsRegex . $inbetweenRegex . $nRegex, $vueSource, $matches);
	print_r($matches);

	/* The variable $matches now contains 8 Arrays corresponding to the Regex-Groups, out of which:
	 * $matches[0] Holds an array of the whole Matches
	 * $matches[4] Holds an array of the comments, but still including a possible comment-ending (-->|* /)
	 * $matches[7] Holds an array of the singular form of the text to translate
	 * $matches[8] Holds an array of the plural form of the text to translate
	 * All inner arrays contain the respective content with corresponding indices.
	 * So $matches[4][2] is the corresponding comment to the text in $matches[7][2]
	 */
	foreach ($matches[0] as $index => $fullMatch) {
		if ($matches[4][$index] !== '') {
			// Replace end-tags by nothing
			$comment = preg_replace('/\s*(-->|\*\/)\s*/', '', $matches[4][$index]);
			$fakeFileContent .= "// TRANSLATORS " . $comment . PHP_EOL;
		}
		$fakeFileContent .= "n('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $matches[7][$index]) . "', '" . preg_replace('/\s+/', ' ', $matches[8][$index]) . "');" . PHP_EOL;
	}
};

print_r($fakeFileContent);
