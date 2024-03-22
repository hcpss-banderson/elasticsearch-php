<?php
/**
 * Elasticsearch PHP Client
 *
 * @link      https://github.com/elastic/elasticsearch-php
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the MIT License.
 * See the LICENSE file in the project root for more information.
 */
declare(strict_types = 1);

// Set the default timezone. While this doesn't cause any tests to fail,
// PHP can complains if it is not set in 'date.timezone' of php.ini.
date_default_timezone_set('UTC');

use Elastic\Elasticsearch\Util\PhpUnitTests;

require dirname(__DIR__) . '/vendor/autoload.php';

$testDir = __DIR__ . '/tests'; // directory of the YAML tests
$outputTest = __DIR__ . '/../tests/Yaml'; // directory of the PHPUnit tests

$version = getenv('STACK_VERSION');
if (false === $version) {
    printf("ERROR: you need to specify the STACK_VERSION parameter\n", $version);
    exit(1);
}

$stack = getenv('TEST_SUITE');
if (false === $stack) {
    printf("ERROR: you need to specify the TEST_SUITE parameter\n", $stack);
    exit(1);
}

printf("********************************\n");
printf("** Downloading the YAML tests **\n");
printf("********************************\n");

$tests = file_get_contents("https://github.com/elastic/elasticsearch-clients-tests/archive/refs/heads/main.zip");
if (empty($tests)) {
    print("ERROR: I cannot download the artifcats from elasticsearch-clients-tests\n");
    exit(1);
}
$hash = md5($tests);
$tmpFilePath = sprintf("%s/%s.zip", sys_get_temp_dir(), $hash);

if (!file_exists($tmpFilePath)) {
    file_put_contents($tmpFilePath, $tests);
} else {
    printf("The file %s already exists\n", $tmpFilePath);
}

// $zip = new ZipArchive();
// $zip->open($tmpFilePath);
// printf("Extracting into %s\n", $testDir);
// $zip->extractTo($testDir);
// $zip->close();

printf ("YAML tests installed successfully!\n\n");

printf("********************************\n");
printf("** Building the PHPUnit tests **\n");
printf("********************************\n");

printf ("** Bulding YAML tests for %s suite\n", strtoupper($stack));
printf ("** Using Elasticsearch %s version\n", $version);

$yamlTestFolder = sprintf("%s/tests/%s/tests", __DIR__, strtolower($stack));

$test = new PhpUnitTests($yamlTestFolder, $outputTest, $version, $stack);
$result = $test->build();

printf ("Generated %d PHPUnit files and %d tests.\n", $result['files'], $result['tests']);
printf ("Files saved in %s\n", realpath($result['path']));
printf ("\n");

