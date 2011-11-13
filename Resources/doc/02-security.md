Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developpement and is not considered stable.


## Security


### Basic configuration

TheodoRoger CMS has a basic security/user management configuration included.

1. Modify your security.yml

* If you don't have any pages that require user login:

Replace your app/config/security.yml by Roger's security.yml
(`path/to/Roger/Resources/config/security.yml`).

* If you integrate Roger's security into an already existing frontend system:

Add the following entries to your security.yml

``` bash
security:
    encoders:
        [...]
        Theodo\RogerCmsBundle\Entity\User: sha512

    role_hierarchy:
        [...]
        ROLE_USER:        ROLE_CLIENT
        ROLE_ADMIN:       [ROLE_USER, ROLE_CLIENT]
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_CLIENT, ROLE_ALLOWED_TO_SWITCH]

    providers:
        [...]
        cms_user:
            id: roger.user_provider

    firewalls:
        [...]
        admin:
            provider: cms_user
            pattern:    ^/admin/*
            access_denied_url: /admin/access-denied
            form_login:
                check_path:  /admin/login
                login_path:  /admin
                remember_me: true
            logout:
                path:               /admin/logout
                target:             /admin
                invalidate_session: true
            http_basic: true
            remember_me:
                key:      463a06ee69fbea064218b1d02c416125
                lifetime: 86400
                path:     /

    access_control:
        [...]
        - { path: ^/admin/users*, roles: ROLE_ADMIN }
        - { path: ^/admin/snippets*, roles: ROLE_USER }
        - { path: ^/admin/layouts*, roles: ROLE_USER }
        - { path: ^/admin/*, roles: ROLE_CLIENT }
        - { path: ^/admin, roles: IS_AUTHENTICATED_ANONYMOUSLY }

```

2. Creating users

The simplest way to create your first users is to load them from fixtures.
RogerCMS provides some examples in /path/to/Roger/DataFixtures/ORM/Users.php,
but you'll need to install DoctrineFixtures in order to use them.
Follow the official doc to do so: http://symfony.com/doc/2.0/bundles/DoctrineFixturesBundle/index.html#setup-and-configuration

Having this installed, just replace the email addresses and passwords with your
preferred configuration and run:

``` bash
app/console doctrine:fixtures:load --fixtures=/path/to/Roger/DataFixtures/ORM --append
```

3. Sign in:

The admin login form will be accessible now by /admin path of your website.
Loading fixtures also enabled some of the default webpages. You can access the homepage
by /homepage path of your website.

4. Managing your users

You can now choose Settings->Users in Roger's menu, to manage your users.