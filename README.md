Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developpement and is not considered stable.


## Installation


### Step 1: Downloading the bundle
As for time being this bundle is tested only being in your `src/` directory.
To add it to your project add the following entry to your deps file:

``` bash
[RogerCmsBundle]
    git=https://github.com/theodo/TheodoRogerCmsBundle.git
    target=../src/Theodo/RogerCmsBundle
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

### Step 3: AppKernel.php

Add the following line to your `app/AppKernel.php` file: `new Theodo\RogerCmsBundle\TheodoRogerCmsBundle(),`

``` php
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            [...]

            new Theodo\RogerCmsBundle\TheodoRogerCmsBundle(),
        );
```

### Step 4: Routing

Add the following lines to your `app/config/routing.yml` file:

``` bash
RogerCms:
    resource: "@TheodoRogerCmsBundle/Resources/config/routing.yml"
    prefix: /
```