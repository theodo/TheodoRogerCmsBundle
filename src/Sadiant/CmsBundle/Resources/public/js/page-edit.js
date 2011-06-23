jQuery(document).ready(function ()
{
  // Load input name listener
  loadInputNameListener();
});

/**
 * Load input name listener
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var loadInputNameListener = function ()
{
  // Set listener on input
  jQuery('#page_name').bind('keyup', function (event)
  {    
    // Update breadcrumb value
    updateBreadcrumbValue();
    
    // Update slug value
    updateSlugValue();
  });
}

/**
 * Update breadcrumb value
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var updateBreadcrumbValue = function ()
{
  // Update breadcrumb value
  jQuery('#page_breadcrumb').val(jQuery('#page_name').val());
}

/**
 * Update slug value
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var updateSlugValue = function ()
{
  // Update breadcrumb value
  jQuery('#page_slug').val(string_to_slug(jQuery('#page_name').val()));
}
