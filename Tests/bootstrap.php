<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Updated fot the TheodoRogerCmsBundle needs by Benjamin Grandfond <benjaming@theodo.fr>
 */

if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.dist.php')) {
    require_once $file;
}