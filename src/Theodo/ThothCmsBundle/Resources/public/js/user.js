jQuery(document).ready(function ()
{
  // Load change password listener
  initChangePassswordListener();
});

/**
 * Load change password listener
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-27
 */
var initChangePassswordListener = function ()
{
  jQuery('#change-password-link').bind('click', function (event)
  {
    event.preventDefault();

    showPassword();
  });

  jQuery('#cancel-change-password-link').bind('click', function (event)
  {
    event.preventDefault();

    hidePassword();
  });
}

/**
 * Change password
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-27
 */
var showPassword = function ()
{
  // Hide change password link
  jQuery('#display_password').removeClass('display');
  jQuery('#display_password').addClass('hide');

  // Show password inputs
  jQuery('.change-password').removeClass('hide');
  jQuery('.change-password').addClass('display');
}

/**
 * Hide password
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2011-06-27
 */
var hidePassword = function ()
{
  // Show change password link
  jQuery('#display_password').removeClass('hide');
  jQuery('#display_password').addClass('display');

  // Hide password inputs
  jQuery('.change-password').removeClass('display');
  jQuery('.change-password').addClass('hide');
}
