/**
 * copied from user.js
 */
jQuery(document).ready(function ()
{
  // Load file upload listener
  initUploadListener();
});

/**
 * Load File Upload listener
 *
 * @author cyrillej
 * @since 2011-07-06
 */
var initUploadListener = function ()
{
  jQuery('#show-upload-link').bind('click', function (event)
  {
    event.preventDefault();

    showUpload();
  });

  jQuery('#cancel-upload-link').bind('click', function (event)
  {
    event.preventDefault();

    hideUpload();
  });
}

/**
 * Show File Upload
 *
 * @author cyrillej
 * @since 2011-07-06
 */
var showUpload = function ()
{
  // Hide File Upload link
  jQuery('#show-upload-link').removeClass('display-ib');
  jQuery('#show-upload-link').addClass('hide');
  jQuery('#cancel-upload-link').removeClass('hide');
  jQuery('#cancel-upload-link').addClass('display-ib');

  // Show File Upload
  jQuery('#upload-file').removeClass('hide');
  jQuery('#upload-file').addClass('display');
}

/**
 * Hide File Upload
 *
 * @author cyrillej
 * @since 2011-07-06
 */
var hideUpload = function ()
{
  // Show FileUpload link
  jQuery('#cancel-upload-link').removeClass('display-ib');
  jQuery('#cancel-upload-link').addClass('hide');
  jQuery('#show-upload-link').removeClass('hide');
  jQuery('#show-upload-link').addClass('display-ib');

  // Hide File Upload
  jQuery('#upload-file').removeClass('display');
  jQuery('#upload-file').addClass('hide');
}
