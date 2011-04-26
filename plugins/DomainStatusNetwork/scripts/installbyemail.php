#!/usr/bin/env php
<?php
/**
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2011, StatusNet, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category Installation
 * @package  Installation
 *
 * @author   Brion Vibber <brion@status.net>
 * @author   Evan Prodromou <evan@status.net>
 * @license  GNU Affero General Public License http://www.gnu.org/licenses/
 * @version  1.0.0
 * @link     http://status.net
 */

if (php_sapi_name() !== 'cli') {
    exit(1);
}

define('INSTALLDIR', dirname(dirname(__FILE__)));
set_include_path(get_include_path() . PATH_SEPARATOR . INSTALLDIR . '/extlib');

require_once INSTALLDIR . '/lib/installer.php';

class EmailMultihomeInstaller extends Installer
{
    protected $address;

    function __construct($address)
    {
        $this->address = $address;
    }

    /**
     * Go for it!
     * @return boolean success
     */
    function main()
    {
        if (!$this->checkPrereqs()) {
            return false;
        }
        if ($this->prepare()) {
            return $this->handle();
       } else {
            $this->showHelp();
            return false;
        }
    }

    /**
     * Get our input parameters...
     * @return boolean success
     */

    function prepare()
    {

    }

    function handle()
    {
        return $this->doInstall();
    }

    function showHelp()
    {
        print "USAGE: installbyemail.php <email-address>";
    }

    function warning($message, $submessage='')
    {
        print $this->html2text($message) . "\n";
        if ($submessage != '') {
            print "  " . $this->html2text($submessage) . "\n";
        }
        print "\n";
    }

    function updateStatus($status, $error=false)
    {
        if ($this->verbose || $error) {
            if ($error) {
                print "ERROR: ";
            }
            print $this->html2text($status);
            print "\n";
        }
    }

    private function html2text($html)
    {
        // break out any links for text legibility
        $breakout = preg_replace('/<a[^>+]\bhref="(.*)"[^>]*>(.*)<\/a>/',
                                 '\2 &lt;\1&gt;',
                                 $html);
        return html_entity_decode(strip_tags($breakout), ENT_QUOTES, 'UTF-8');
    }
}

$installer = new EmailMultihomeInstaller();
$ok = $installer->main($argv[1]);
exit($ok ? 0 : 1);
