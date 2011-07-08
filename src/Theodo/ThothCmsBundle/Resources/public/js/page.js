jQuery(document).ready(function ()
{
  // Load input name listener
  loadInputNameListener();

  // Load more/less listeners
  loadExtraFieldsListeners();

  // Load expand page listener
  loadExpandPageListener();
});

/**
 * Load expand page listener
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var loadExpandPageListener = function ()
{
  // Set listener on input
  jQuery('.expandable').live('click', function (event)
  {
    event.preventDefault();

    // Retrieve node
    var node = jQuery(this);

    // Load children
    loadChildren(node);
  });
}

/**
 * Load expand page listener
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var loadChildren = function (node)
{
  // Test if page already expand
  if (node.hasClass('expanded'))
  {
    // Hide children
    hideChildren(node);
  }
  // Test if page has children in html
  else if (jQuery('.child_' + node.parents('tr.node').attr('id')).length > 0)
  {

    // Show children
    showChildren(node);
  }
  // Ajax query
  else
  {
    // Load children
    loadAjaxChildren(node);
  }
}

/**
 * Hide children
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var hideChildren = function (node)
{
  // Hide children
  var children = jQuery('.child_' + node.parents('tr.node').attr('id'));
  children.hide();

  // Update expand picture
  node.html('<img alt="toggle children" class="expander" src="../images/admin/expand.png" />');

  // remove expanded class
  node.removeClass('expanded');

  // Hide node children
  jQuery.each(children, function (index, child)
  {
    hideChildren(jQuery(child).find('a.expandable'));
  });
}

/**
 * show children
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var showChildren = function (node)
{
  // Show children
  var children = jQuery('.child_' + node.parents('tr.node').attr('id'));
  children.show();

  // Update expand picture
  node.html('<img alt="toggle children" class="expander" src="../images/admin/collapse.png" />');

  // remove expanded class
  node.addClass('expanded');
}

/**
 * Load ajax children
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-23
 */
var loadAjaxChildren = function (node)
{
  jQuery.ajax({
    url: node.attr('href'),
    type: 'post',
    beforeSend: function ()
    {
      // Show ajax loader
      node.parents('td').find('.ajax-loader').removeClass('hide');
    },
    success: function (response)
    {
      // Show children
      node.parents('tr.node').after(response);

      // Add expanded class
      node.addClass('expanded');

      // Update expand picture
      node.html('<img alt="toggle children" class="expander" src="../images/admin/collapse.png" />');
    },
    complete: function()
    {
      // Hide ajax loader
      node.parents('td').find('.ajax-loader').addClass('hide');
    }
  });
}

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

