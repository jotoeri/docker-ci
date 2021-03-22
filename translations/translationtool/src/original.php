<?php
$vueFile = './Create.vue';
// $vueFile = '/var/tmp/nextcloud/spreed/src/components/AdminSettings/HostedSignalingServer.vue';

$fakeFileContent = '';

$vueSource = file_get_contents($vueFile);
if ($vueSource === false) {
	echo "Warning: could not read " . $vueFile . PHP_EOL;
	// continue;
}

// t
preg_match_all("/\Wt\s*\(\s*'([\w]+)',\s*'(.+)'/", $vueSource, $singleQuoteMatches);
preg_match_all("/\Wt\s*\(\s*\"([\w]+)\",\s*\"(.+)\"/", $vueSource, $doubleQuoteMatches);
preg_match_all("/\Wt\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*\)/msU", $vueSource, $templateQuoteMatches);
$matches = array_merge($singleQuoteMatches[2], $doubleQuoteMatches[2], $templateQuoteMatches[2]);
foreach ($matches as $match) {
	$fakeFileContent .= "t('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $match) . "');" . PHP_EOL;
}

// n
preg_match_all("/\Wn\s*\(\s*'([\w]+)',\s*'(.+)'\s*,\s*'(.+)'\s*(.+)/", $vueSource, $singleQuoteMatches);
preg_match_all("/\Wn\s*\(\s*\"([\w]+)\",\s*\"(.+)\"\s*,\s*\"(.+)\"\s*(.+)/", $vueSource, $doubleQuoteMatches);
preg_match_all("/\Wn\s*\(\s*\'([\w]+)\'\s*,\s*\`(.+)\`\s*,\s*\`(.+)\`\s*\)/msU", $vueSource, $templateQuoteMatches);
$matches1 = array_merge($singleQuoteMatches[2], $doubleQuoteMatches[2], $templateQuoteMatches[2]);
$matches2 = array_merge($singleQuoteMatches[3], $doubleQuoteMatches[3], $templateQuoteMatches[3]);
foreach (array_keys($matches1) as $k) {
	$match1 = $matches1[$k];
	$match2 = $matches2[$k];
	$fakeFileContent .= "n('" . '$this->name' . "', '" . preg_replace('/\s+/', ' ', $match1) . "', '" . preg_replace('/\s+/', ' ', $match2) . "');" . PHP_EOL;
}

print_r($fakeFileContent);
print_r($matches);
