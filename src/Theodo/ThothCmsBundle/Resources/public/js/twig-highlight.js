jQuery(document).ready(function ()
{
  /*
  editAreaLoader.init({
			id: textarea_id	// id of the textarea to transform
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: "twig"
		});
  */
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.entities = false;
  CKEDITOR.config.autoParagraph = false;
  CKEDITOR.config.templates_replaceContent = false;
  //CKEDITOR.replace(textarea_id);
  CKEDITOR.replaceAll('textarea');

});
