Theodo RogerCMSBundle
=====================


WARNING: This bundle is still under developement and is not considered stable.

## Giving away the power

A common demand in web developement is to give the user (client) a possibility to
manage the content of his website - this is when a CMS comes in. But there are some
parts of the site, that we need to keep intact, just to keep things working
or ones that we want to hide from the user, to avoid confusion caused by their complexity.

One of the main goals of RogerCMS is to provide easy means to achieve that.

## CMS templates in view files

Using a CMS-defined template in a view file requires only two lines of code

1. In the return part of your controller, call the "roger.templating" service:

    ```php
    public function myAction()
    {
        /**
         * Some complicated stuff
         */
             
        return $this->get('roger.templating')
            ->renderResponse(
                 'MyBundle:myFolder:my.html.twig',
                 $variables
            );
    }
    ```

2. Call the template in your file, prefixing its name by 'layout:':

    ``` twig
    {# my.html.twig #}
    {% extends 'layout:my-layout' %} 
    ```

## Templates as files

Having the example controller action:

```php
public function productListAction()
{
   $products = $this->getDoctrine()
       ->getEntityManager()
       ->getRepository('MyBundle\Entity\Product')
       ->findAll();

   return $this->render(
       'MyBundle:Company:products_list.html.twig',
       array('products' => $products)
   );
}
```

And an example template:

```twig
{% extends 'MyBundle::layout.html.twig %}

{% block lead %}
{% endblock %}

{% block products %}
  {# products display logic #}
{% endblock %}
```

How to give the client a possibility to change the lead text, without altering the products display?

1. Create a page in the CMS, giving it "products-list" as a slug.

2. Create a tab for block called "lead", and make your cms page extend your local template.
*This feature is part of, soon-to-be-ready, expert mode, the current solution requires altering manually
the page row in database. To avoid that, you can register your template as standard CMS template, and move it
to a file as soon as it's possible.*

3. Use the "roger.templating" and "roger.content_repository" services in your controller to display your page: 

    ```php
    public function productListAction()
    {
       $products = $this->getDoctrine()
           ->getEntityManager()
           ->getRepository('MyBundle\Entity\Product')
           ->findAll();
    
       $cmsPage = $this->get('roger.content_repository')
           ->getPageBySlug('products-list');
    
       $variables = array(
          'products' => $products
       );
    
       return $this->get('roger.templating')->renderResponse(
           'page:'.$cmsPage->getName(),
           array(
               'page' => $cmsPage
          ) + $variables
       );
    }
    ```

4. And from now, your "lead" block can be easily modified from the CMS backend.
