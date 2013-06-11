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
    }
```

Follow StofDoctrineExtensionsBundle's doc to add the configuration for **timestampable** behavior.

### Step 3: Routing

Add the following lines to your `app/config/routing.yml` file:

``` bash
_internal:
    resource: "@FrameworkBundle/Resources/config/routing/internal.xml"
    prefix:   /_internal

_roger_cms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.xml"
    prefix: /

```

If you want to have a fine control of urls, you can also do that:

``` bash
_internal:
    resource: "@FrameworkBundle/Resources/config/routing/internal.xml"
    prefix:   /_internal

_roger_cms_admin:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing/admin.xml"
    prefix: /my-admin/cms

_roger_cms_frontend:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing/frontend.xml"
    prefix: /cms
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

### Step 5: Timestampable behaviour

You need to enable the timestampable behavoiur of stof_doctrine_extension

``` yml
# app/config/config.yml
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            timestampable: true
```