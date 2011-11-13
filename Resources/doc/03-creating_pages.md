Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developpement and is not considered stable.

## Templates

A Roger CMS template is a standard Twig template as used by a tag {% extends %}.
It can be chosen later during page edition to specify parent template for page.

1. In CMS backend choose __Design->Layouts__ from menu.
2. Create a new template. It's name will appear in your pages edition form,
so choose something meaningful.
3. Create an template with some twig __blocks__ within.
4. If you need to expand another CMS layout, add __{% extends 'layout:name_of_parent %}__
on the top of your layout.

## Pages

1. In CMS backend choose __Content->Pages__ from menu (this if the default page after login).
2. If you've loaded the fixtures, you already have some basic website tree. Click "Homepage" to edit the page.
If not click "New Homepage" button in top right corner.
3. The page edit form includes 4 main fields:
* name - defines how page appears in cms page's list
* page configuration - (click more below title to expand) provides you with options
that you usually configure only once
* page edition area - (new page opens with a "body" tab) - here you manage your page content and layout
* page status - lets you configure whether page is displayed or not

### Page edition area

* Page edition area contains some tabs. Each of those tabs specifies a block configured
in your layout. The name of the tab has to be exactly the same as in {% block %} tag.

* To add a new block, click the "tab" icon in right top corner of the area.

* If you choose not to extend any layout, your pages block will be added one after another,
in the same order as they appear in admin. They will be automatically concatenated into
one tab, as there will be no layout which defines how to set them apart.

**Notice:**
Each page is compiled and parsed for twig markup validity.

### Page configuration

This area lets you configure options like html head content (title, description, keywords),
caching options and internal references.

* slug: specifies page's url part; note that this has to be unique.
As for this moment, Roger's routing analyzes only the last part of the url, so
/homepage/my-page and /homepage/my-subpage/my-page will actually render the same page.

## Snippets

Snippets are reusable boxes, that can be used anywhere on your site.

1. In CMS backend choose __Design->Snippets__ from menu.
2. Creating a snippet is as much as naming it and putting some html/twig code inside.
3. To render snippet in your template or page add {% snippet name %}

**Notice:**
As each snippet requires a database query and a lot of internal handling, use them with moderation.
Good candidates for snippets are those page elements that need to be easy to edit
and appear in different templates, eg.: main menu, footer.