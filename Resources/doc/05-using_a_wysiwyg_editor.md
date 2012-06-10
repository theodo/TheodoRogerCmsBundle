Theodo RogerCMSBundle
=====================

## Using WYSIWYG

By default the bundle comes with the edit-area (http://www.cdolivet.com/editarea/) JS plugin, which is by no means a WYSIWYG editor. Hovewer, you can load any tool you like by overriding the TheodoRogerCmsBundle::editor.html.twig template.

Just create a `editor.html.twig` file in `app/Resources/TheodoRogerCmsBundle/` and insert there the JavaScript code needed to instantiate your prefered WYSIWYG editor.
