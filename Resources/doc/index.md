Getting Started with Theodo ThothCMSBundle
==========================================

WARNING: This bundle is still under developpement and it's not considered stable.
WARNING2: Keep this bundle private, please. It's not intended to be distributed
at this stage of developement.

## Installation

### Step 1: Downloading the bundle
As for time being this bundle is usable and tested only as a submodule in your
`src/` directory.
To add it to your project execute this command:

``` bash
$ git submodule add git@github.com:Allomatch/TheodoThothCMSBundle.git src/Theodo/ThothCmsBundle
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