#!/usr/bin/env php
<?php
/**
 * @author Laurent Bachelier <laurentb@theodo.fr>
 * @since 2010-04-16
 */
$path = realpath(dirname(__FILE__)).'/';
chdir($path);

$mycnf = getenv('HOME').'/.my.cnf';

if (isset($argv[1]) && $argv[1] == 'config')
{
  echo "mysql username with root rights:\n";
  $user = trim(fgets(STDIN));
  echo "password for this user:\n";
  $password = trim(fgets(STDIN));

  $cfg = "[mysql]\nuser=\"%U\"\npassword=\"%P\"\n\n"
       . "[mysqldump]\nuser=\"%U\"\npassword=\"%P\"\ntriggers\nopt\nroutines";

  $cfg = str_replace(array('%U', '%P'), array($user, $password), $cfg);

  file_put_contents($mycnf, $cfg);

  echo $mycnf." written.\n";
  exit(0);
}

if (!file_exists($mycnf))
{
  echo "No ".$mycnf." found.";
  echo "Please run ".$argv[0]." config\n";
  exit(1);
}


$configs = file($path.'app/config/dbs.list',
          FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
$users = array();
$dbs = array();
$sql = '';
foreach ($configs as $config)
{
  if (false !== strpos($config, ':'))
  {
    $users[] = explode(':', $config);
  }
  else
  {
    $dbs[] = $config;
  }
}
foreach ($users as $user)
{
  $sql .= "GRANT USAGE ON * . * TO '".$user[0]."'@'localhost' IDENTIFIED BY '".$user[1]."';\n";
}
foreach ($dbs as $db)
{
  $sql .= "CREATE DATABASE IF NOT EXISTS `".$db."` "
       . "DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;\n";
  foreach ($users as $user)
  {
    $sql .= "GRANT ALL PRIVILEGES ON `".$db."` . *"
          . " TO  '".$user[0]."'@'localhost';\n";
  }
}
$sql .= "FLUSH PRIVILEGES;\n";

file_put_contents($path.'app/config/create_dbs.sql', $sql);
echo "config/create_dbs.sql written.\n";

system('mysql -e "source app/config/create_dbs.sql"');
echo "mysql users and databases created.\n";
