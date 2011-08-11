jQuery(document).ready(function ()
{
  // Load input name listener
  loadInputNameListener();

  // Load more/less listeners
  loadExtraFieldsListeners();

  // Load expand page listener
  loadExpandPageListener();
  
  // Load toogle tabs listener
  loadToogleTabListener();
  
  // Load add tab listener
  loadAddTabListener();
  
  // Load supp tab listener
  loadSupTabListener();
    
  loadPopinListerner();

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
  node.html('<img alt="toggle children" class="expander" src="/bundles/theodothothcms/images/admin/expand.png" />');

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
  node.html('<img alt="toggle children" class="expander" src="/bundles/theodothothcms/images/admin/collapse.png" />');

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
      node.html('<img alt="toggle children" class="expander" src="/bundles/theodothothcms/images/admin/collapse.png" />');
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

/**
 * Load toogle tab listeners
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-09
 */
var loadToogleTabListener = function ()
{
  // Set listener on input
  jQuery('.tab').live('click', function (event)
  {
    event.preventDefault();

    // Retrieve node
    var node = jQuery(this);
    
    highlightPageBlock(node);
    
  });
  
}

var highlightPageBlock = function (node)
{
    // Hide old tab
    jQuery('.tab.here').each( function () {jQuery(this).removeClass('here');});
    
    // highlight new tab
    node.addClass('here');
    
    // hide the old page and display the new
    tooglePage(node);
}

/**
 * Update page status
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-09
 */
var tooglePage = function (node)
{
  // Get page id to display
  page_id = node.attr('id').replace('tab','page');

  // Hide old page
  jQuery('.page:not(.hide)').each( function () {jQuery(this).addClass('hide');});

  // Display new page
  jQuery('div#'+page_id).removeClass('hide');
}
 
/**
 * Load Add tab listener 
 * 
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-09
 */
var loadAddTabListener = function ()
{
  // Set listener on input
  jQuery('#tab_toolbar > .popup').live('click', function (event)
  {
    event.preventDefault();

      // Retrieve node
      node = jQuery(this).parent('a');

      jQuery('#popin-add')
        .jqmShow()
        .find(':submit:visible')
        .click(function(){
            if (this.value == 'ok') {
                page_name = jQuery(this).parent('div').children('input:text').val();
                if ('' != page_name) {
                    addTabPage(page_name);
                }
                jQuery(this).parent('div').children('input:text').val('');
            }
            $('#popin-add').jqmHide();
        });

      return false;
  });
    
}

/**
 * Add new block 
 * 
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-09
 */
var addTabPage = function (page_name)
{
    jQuery('#tabs').append(
        "<a id='tab_"+page_name+"' href='#' class='tab'>" +
            "<span> "+page_name+" </span>" +
            "<img src='/bundles/theodothothcms/images/admin/tab_close.png' class='close' alt='Remove part' title='Remove part' />" +
        "</a>"  
    );
    
    jQuery('#pages').append(
        '<div class="page hide" id="page_'+page_name+'">' +
            '<div class="part" id="part_'+page_name+'">' +
                '<p>' +
                    '<span class="reference_links">' +
                        '<a href="http://www.twig-project.org/documentation" target="_blank">Twig documentation</a>' +
                    '</span>' +
                '</p>' +
                '<div>' +
                    '<textarea id="page_content_'+page_name+'" class="textarea large" name="page[block_'+page_name+']" style="width: 100%;"></textarea> ' +
                '</div>' +
            '</div>' +
        '</div>'
    );
    
    highlightPageBlock(jQuery(".tab:last"));
    
    CKEDITOR.replace('page_content_'+page_name);
}

/**
 * Load delete tab listener 
 * 
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-11
 */
var loadSupTabListener = function ()
{
    // Set listener on input
    jQuery('.tab > img.close').live('click', function (event)
    {
      event.preventDefault();

      // Retrieve node
      node = jQuery(this).parent('a');

      jQuery('#popin-delete')
        .jqmShow()
        .find(':submit:visible')
        .click(function(){
            if (this.value == 'yes') {
                // delete page & tab
                supTabPage(node);

                if (node.hasClass('here'))
                {
                   // highlight new tab
                   highlightPageBlock(jQuery('.tab:first'));
                }
            }
            $('#popin-delete').jqmHide();
        });

      return false;
     });  
    
}

/**
 * Delete page and tab
 * 
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-11
 */
var supTabPage = function (node) 
{
    // Get page id to delete
    page_id = node.attr('id').replace('tab','page');

    // Delete tab
    node.remove();
    
    // Delete page
    jQuery('div#'+page_id).remove();
}

/**
 * Load delete tab listener 
 * 
 *
 * @author Romain Barberi <romainb@theodo.fr>
 * @since 2011-08-11
 */
var loadPopinListerner = function ()
{
    jQuery('#popin-delete, #popin-add').jqm({overlay: 45, toTop: true});
}