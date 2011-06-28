/**
 * Convert string to slug
 *
 * @author http://dense13.com/blog/2009/05/03/converting-string-to-slug-javascript/
 * @since 2011-06-23
 */
var string_to_slug = function(str)
{
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();
  
  // remove accents, swap ñ for n, etc
  var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to   = "aaaaeeeeiiiioooouuuunc------";
  for (var i=0, l=from.length ; i<l ; i++)
  {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
}

jQuery(document).ready(function ()
{
  // Load input name listener
  closeErrorListener();
});

/**
 * Closes error messages
 *
 * @author cyrillej
 * @since 2011-06-28
 */
var closeErrorListener = function ()
{
  // Set listener on input
  jQuery('.closer').live('click', function (event)
  {
    event.preventDefault();

    jQuery(this).parent().fadeOut('slow');
  });
}