Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developpement and is not considered stable.


## Installation


### Step 1: Downloading the bundle
To add the bundle to your project add the following entry to your deps file:

``` bash
[RogerCmsBundle]
    git=https://github.com/theodo/TheodoRogerCmsBundle.git
    target=/bundles/Theodo/RogerCmsBundle
```


### Step 2: Dependencies

**Using the vendors script**

Add the following lines to your `deps` file:

``` bash
[DoctrineExtensionsBundle]
    git=https://github.com/stof/StofDoctrineExtensionsBundle.git
    version=origin/master
    target=/bundles/Stof/DoctrineExtensionsBundle

[DoctrineExtensions]
    git=http://github.com/l3pp4rd/DoctrineExtensions.git
    version=origin/master
    target=/gedmo-doctrine-extensions
```

**Notice:**
TheodoRogerCms depends on Twig and it's not usable without it.
Due to some bugs in previous versions of Twig, v 1.2.0 or higher is required.

### Step 3: autoload.php

You need to register the `Theodo` namespace before using the bundle. Add the following line to your `app/autoload.php` file: `'Theodo' => __DIR__.'/../vendor/bundles',`.
As TheodoRogerCms depends on the DoctrineExtensionsBundle and the DoctrineExtensions library you also need to register them in the autoload.

``` php
    use Symfony\Component\ClassLoader\UniversalClassLoader;
    use Doctrine\Common\Annotations\AnnotationRegistry;

    $loader = new UniversalClassLoader();
    $loader->registerNamespaces(array(
        'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
        'Stof'             => __DIR__.'/../vendor/bundles',
        'Gedmo'            => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
        'Theodo'           => __DIR__.'/../vendor/bundles',
    ));
```

### Step 4: AppKernel.php

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

### Step 5: Routing

Add the following lines to your `app/config/routing.yml` file:

``` bash
RogerCms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.yml"
    prefix: /
```

### Step 6: Database and entities

RogerCMS uses database to store all content informations, so you need to add its
entities to your entity manager. As it also uses his own user management system
it may be a good idea to use a separate database. For further informations on
how to setup and manage a separate database connection for the CMS, refer to
99-multiple_databases.md file.

If you don't feel like having Roger in separate db, the Symfony Standard Edition
default config will work out of the box. Just generate your schema/migrations
and update your db.

### Step 7: Read the docs

For more documentation, check out the Resources/doc folder.
