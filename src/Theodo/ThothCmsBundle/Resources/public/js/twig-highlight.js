jQuery(document).ready(function ()
{
  editAreaLoader.init({
			id: textarea_id	// id of the textarea to transform
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: "php"
		});

  // hack to bypass a strange bug with editArea on some versions of Chrome
  jQuery('#' + form_id).submit(function() {
    jQuery('#' + textarea_id).val(editAreaLoader.getValue(textarea_id));
    return true;
  });

});