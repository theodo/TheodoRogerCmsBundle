Using ThothCms with dedicated database
======================================

Why?
----
The reasons to use Thoth with dedicated database are:

### Avoid table collisions

Thoth has a fairly big model, especially its own model of users, and some similiar
bundles (eg. blogs) may use similar tables. Separating databases keeps you safe.

### Facilitate developement by multiple developers

We often work in teams, and working with an CMS requires constant synchronisations of database.
To keep the amount of the data transfered to minimum you have only few tables to synchronize.
Having your databases separated allows you to do this in few easy steps.

How?
----

1. Define your connection
(all changes take place in config yml)
```yaml
doctrine:
    dbal:
        connections:
            ...
            cms:
                driver:   %cms.database_driver%
                host:     %cms.database_host%
                dbname:   %cms.database_name%
                user:     %cms.database_user%
                password: %cms.database_password%
                charset:  UTF8
```

2. Add an entity manager to this connection:
```yaml
doctrine:
    orm:
        auto_generate_proxy_classes: %kernel.debug%
        entity_managers:
            ...
            cms:
                connection:   cms
                mappings:
                    TheodoThothCmsBundle: ~
                    StofDoctrineExtensionsBundle: false
```

3. Add the timestampable behaviour to that entity manager
```yaml
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        ...
        cms:
            timestampable: true
            tree:          false
            sluggable:     false
            translatable:  false
            loggable:      false
```

4. Overwrite Thoth services to use your new entity manager
Replace all appareances of '@doctrine.orm.entity_manager' in Thoth's services.yml
by '@doctrine.orm.cms_entity_manager'

And it's done!
--------------

Don't forget to add --em=orm or --connection=orm when calling doctrine tasks
using cms (schema creation, fixtures loading etc.)!