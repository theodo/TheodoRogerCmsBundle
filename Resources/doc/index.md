Getting Started with Theodo RogerCMSBundle
==========================================

WARNING: This bundle is still under developpement and it's not considered stable.

## Installation

### Step 1: Downloading the bundle
As for time being this bundle is tested only being in your `src/` directory.
To add it to your project add the following entry to your deps file:

``` bash
[RogerCmsBundle]
    git=git@github.com:Theodo/TheodoRogerCMSBundle.git
    version=origin/citedelespace
    target=../src/Theodo/RogerCmsBundle
```

### Step 2: Dependencies

**Using the vendors script**

Add the following lines to your `deps` file:
```
[DoctrineExtensionsBundle]
    git=https://github.com/stof/StofDoctrineExtensionsBundle.git
    version=origin/master
    target=/bundles/Stof/DoctrineExtensionsBundle

[DoctrineExtensions]
    git=http://github.com/l3pp4rd/DoctrineExtensions.git
    version=origin/master
    target=/gedmo-doctrine-extensions
```
