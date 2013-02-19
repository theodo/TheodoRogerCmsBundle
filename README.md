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

### Step 3: Doctrine configuration

This bundle uses Doctrine PHPCR ODM for object persistance. Configure it to suit your needs.
The simplest configuration would be as follow:

```yaml
doctrine_phpcr:
    session:
        backend:
            type: doctrinedbal
            connection: doctrine.dbal.default_connection
    odm:
        auto_mapping: true
```

### Step 4: Routing

Add the following lines to your `app/config/routing.yml` file:

``` bash
RogerCms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.xml"
    prefix: /
```

### Step 5: Read the docs

For more documentation, check out the [`Resources/doc`](Resources/doc) folder.
