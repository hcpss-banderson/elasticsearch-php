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

namespace Elastic\Elasticsearch\Util;

class Utility {

    static public function removeDirectory(string $dir): void
    {
        foreach(glob("{$dir}/*") as $file)
        {
            if(is_dir($file)) { 
                self::removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }
}