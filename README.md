Theodo RogerCMSBundle
=====================

WARNING: This bundle is still under developpement.
While it is functional, the service names, configuration options etc. may change
without worrying about BC breaks.

## Installation

### Step 1: Adding the bundle to your project

Add the Roger repository to your composer.json file:

``` json
    "require": {
        "theodo/roger-cms-bundle": "dev-master"
    }
```

Then run ```php composer.phar update theodo/roger-cms-bundle``` and you are done.

### Step 2: AppKernel.php

Register TheodoRogerCmsBundle in your `app/AppKernel.php` file:

``` php
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            [...]
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Theodo\RogerCmsBundle\TheodoRogerCmsBundle(),
        );
```

Follow StofDoctrineExtensionsBundle's doc to add the configuration for **timestampable** behavior.

### Step 3: Routing

Add the following lines to your `app/config/routing.yml` file:

``` bash
RogerCms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.xml"
    prefix: /
```

### Step 4: Database and entities

RogerCMS uses database to store all content informations, so you need to add its
entities to your entity manager. As it also uses his own user management system
it may be a good idea to use a separate database. For further informations on
how to setup and manage a separate database connection for the CMS, refer to
99-multiple_databases.md file.

If you don't feel like having Roger in separate db, the Symfony Standard Edition
default config will work out of the box. Just generate your schema/migrations
and update your db.

### Step 5: Read the docs

For more documentation, check out the [`Resources/doc`](https://github.com/theodo/TheodoRogerCmsBundle/tree/master/Resources/doc) folder.
