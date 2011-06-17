#!/usr/bin/env php
<?php
/**
 * Script pour gérer les fichiers sample.
 * Utilisation :
 *  php checksamples.php # pour voir l’état des samples
 *  php checksamples.php ln # pour créer les samples manquants avec ln -s (recommandé en dev)
 *  php checksamples.php cp # pour créer les samples manquants avec cp
 *
 * CONFIGURATION
 *  Éditer app/config/samples.list et mettre un fichier par ligne.
 *
 * @author Laurent Bachelier <laurentb@theodo.fr>
 * @since 2010-04-07
 */


$operation = isset($argv[1]) && in_array($argv[1], array('cp', 'ln'))
           ? $argv[1]
           : null;

$path = realpath(dirname(__FILE__)).'/';
chdir($path);

$files = file($path.'app/config/samples.list',
    FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

foreach ($files as $file)
{
  $destination = str_replace('.sample', '', $file);
  if (file_exists($path.$file))
  {
    echo $file.' => '.$destination."\n";
    if (!file_exists($path.$destination))
    {
      echo " missing.\n";
      if ($operation == 'ln')
      {
        system('ln -sv '.escapeshellarg(basename($file)).' '.escapeshellarg($destination));
      }
      elseif ($operation == 'cp')
      {
        system('cp -v '.escapeshellarg($file).' '.escapeshellarg($destination));
      }
    }
    else
    {
      if (is_link($path.$destination))
      {
        echo " present, symlink.\n";
      }
      else
      {
        echo " present, copy.\n";
      }
    }
  }
}

if (empty($operation))
{
  echo 'Usage: '.$argv[0].' [ln|cp]'."\n";
}
