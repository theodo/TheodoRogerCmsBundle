jQuery(document).ready(function ()
{
  // Load input name listener
  loadInputNameListener();
  
  // Load more/less listeners
  loadExtraFieldsListeners();
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
  jQuery('#page_name').bind('keyup', function ()
  {    
    // Update breadcrumb value
    updateBreadcrumbValue();
    
    // Update slug value
    updateSlugValue();
  });
}

/**
 * Load more/less listeners
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var loadExtraFieldsListeners = function ()
{
  // Set listener on more/less links
  jQuery('#link-more-fields, #link-less-fields').bind('click', function (event)
  {
    event.preventDefault();

    jQuery('.extra-fields-link').toggle();
    jQuery('#extra-fields').toggle(200);
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
