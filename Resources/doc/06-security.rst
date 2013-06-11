Security
========

There are multiple roles defined in the security. In order to securise your admin you can use the following ones:

role_hierarchy:
    ROLE_ROGER_CONTENT:   [ROLE_ROGER_READ_CONTENT, ROLE_ROGER_WRITE_CONTENT, ROLE_ROGER_DELETE_CONTENT, ROLE_ROGER_PUBLISH_CONTENT]
    ROLE_ROGER_DESIGN:    [ROLE_ROGER_READ_DESIGN, ROLE_ROGER_WRITE_DESIGN, ROLE_ROGER_DELETE_DESIGN]
    ROLE_ROGER_EDITOR:    [ROLE_ROGER_CONTENT, ROLE_ROGER_READ_DESIGN, ROLE_ROGER_PUBLISHER]
    ROLE_ROGER_DESIGNER:  [ROLE_ROGER_CONTENT, ROLE_ROGER_DESIGN, ROLE_ROGER_PUBLISHER]

    ROLE_ADMIN:           [ROLE_USER, ROLE_ROGER_DESIGNER, ROLE_ROGER_EDITOR]

These are the values we use by default in the tests. You can use them or adapt to your needs.
