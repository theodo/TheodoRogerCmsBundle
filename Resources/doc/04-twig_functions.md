Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developement and is not considered stable.

## Twig functions

RogerCMS' twig extension adds 2 functions and one tag:

1. `{{ snippet name }}`
2. `{{ page_url(page_slug) }}`
 * takes page slug as parameter
 * generates a full url (concatenated with all ascendants)
3. `{{ media_url() }}`
 * takes media name as parameter

Roger's TwigLoaderRepository extends also existing functions: __include__ and __extends__ by adding
three keywords to them:

* page
* layout
* name

You can use them to retrieve required element by following type by its name `{% extends 'layout:main' %}`,
`{% include 'page:My Page' %}`
