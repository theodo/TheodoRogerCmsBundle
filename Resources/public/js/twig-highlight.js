jQuery(document).ready(function ()
{
    editAreaLoader.init({
        id: jQuery('textarea').first().id	// id of the textarea to transform
        ,start_highlight: true	// if start with highlight
        ,allow_resize: "both"
        ,allow_toggle: true
        ,word_wrap: true
        ,language: "en"
        ,syntax: "twig"
    });
});
