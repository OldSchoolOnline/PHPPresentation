<?php
/**
 * This file is part of PHPPresentation - A pure PHP library for reading and writing
 * presentations documents.
 *
 * PHPPresentation is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPPresentation/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPPresentation
 * @copyright   2010-2014 PhpPresentation contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpPresentation\Tests;

use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;

/**
 * Test helper class
 */
class TestHelperDOCX
{
    /**
     * Temporary file name
     *
     * @var string
     */
    static protected $file;

    /**
     * Get document content
     *
     * @param \PhpOffice\PhpPresentation\PhpPresentation $PhpPresentation
     * @param string $writerName
     * @return \PhpOffice\PhpPresentation\Tests\XmlDocument
     */
    public static function getDocument(PhpPresentation $phpPresentation, $writerName = 'PowerPoint2007')
    {
        self::$file = tempnam(sys_get_temp_dir(), 'PhpPresentation');
        if (!is_dir(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/')) {
            mkdir(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/');
        }

        $xmlWriter = IOFactory::createWriter($phpPresentation, $writerName);
        $xmlWriter->save(self::$file);

        $zip = new \ZipArchive;
        $res = $zip->open(self::$file);
        if ($res === true) {
            $zip->extractTo(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/');
            $zip->close();
        }

        return new XmlDocument(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/');
    }

    /**
     * Clear document
     */
    public static function clear()
    {
        if (file_exists(self::$file)) {
            unlink(self::$file);
        }
        if (is_dir(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/')) {
            self::deleteDir(sys_get_temp_dir() . '/PhpPresentation_Unit_Test/');
        }
    }

    /**
     * Delete directory
     *
     * @param string $dir
     */
    public static function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } elseif (is_file($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            } elseif (is_dir($dir . "/" . $file)) {
                self::deleteDir($dir . "/" . $file);
            }
        }

        rmdir($dir);
    }

    /**
     * Get file
     *
     * @return string
     */
    public static function getFile()
    {
        return self::$file;
    }
}
