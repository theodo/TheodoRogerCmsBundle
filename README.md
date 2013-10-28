Theodo RogerCMSBundle
=====================

WARNING: This bundle is still under developpement.
While it is functional, the service names, configuration options etc. may change
without worrying about BC breaks.

## Installation

### Step 1: Adding the bundle to your project

Add the Roger repository to your composer.json file:

```json
"require": {
    "theodo/roger-cms-bundle": "dev-master"
}
```

Then run

```
$ php composer.phar update theodo/roger-cms-bundle
```

### Step 2: AppKernel.php

Enable TheodoRogerCmsBundle and StofDoctrineExtensionsBundle in `app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Theodo\RogerCmsBundle\TheodoRogerCmsBundle(),
    );
}
```

### Step 3: Update database

```
$ php app/console doctrine:schema:update --force
```

### Step 4: config.yml

Add StofDoctrineExtensions:

```yaml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
            sluggable: true
```


### Step 5: Define RogerCms routes

Add the following lines to your `app/config/routing.yml` file:

```yaml
RogerCms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.xml"
    prefix: /
```

### step 6: Add roles

Add RogerCms roles in `app/config/security.yml`:

```yaml
security:

    ...

    role_hierarchy:
        ROLE_ROGER_CONTENT:   [ROLE_ROGER_READ_CONTENT, ROLE_ROGER_WRITE_CONTENT, ROLE_ROGER_DELETE_CONTENT, ROLE_ROGER_PUBLISH_CONTENT]
        ROLE_ROGER_DESIGN:    [ROLE_ROGER_READ_DESIGN, ROLE_ROGER_WRITE_DESIGN, ROLE_ROGER_DELETE_DESIGN]
        ROLE_ROGER_EDITOR:    [ROLE_ROGER_CONTENT, ROLE_ROGER_READ_DESIGN]
        ROLE_ROGER_DESIGNER:  [ROLE_ROGER_CONTENT, ROLE_ROGER_DESIGN]

        ROLE_ADMIN:           [ROLE_USER, ROLE_ROGER_DESIGNER]
        ROLE_SUPER_ADMIN:     [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

    providers:
        in_memory:
            memory:
                users:
                    admin: { password: admin, roles: [ 'ROLE_ADMIN' ] }

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        login:
            pattern:  ^/demo/secured/login$
            security: false

        secured_area:
            pattern:    ^/demo/secured/
            form_login:
                check_path: _security_check
                login_path: _demo_login
            logout:
                path:   _demo_logout
                target: _demo

        roger_admin:
            pattern:    ^/admin
            http_basic:
                realm: "Secured Demo Area"

    ...
```

Then go to `/admin` and log in.

### Step 7: Read the docs

For more documentation, check out the [`Resources/doc`](Resources/doc) folder.

