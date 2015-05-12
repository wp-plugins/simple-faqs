<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert Simple FAQ Shortcode</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<style type="text/css" src="../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css"></style>
<link rel="stylesheet" href="simple_faq_generator.css" />

<script type="text/javascript">

var ButtonDialog = {
	local_ed : 'ed',
	init : function(ed) {
		ButtonDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {
	 
	 
		// set up variables to contain our input values
		var style = jQuery('#faq-dialog select#faq-style').val();
		var order = jQuery('#faq-dialog select#faq-order').val();		 
		var category = jQuery('#faq-dialog select#faq-category').val();		 
		var skin = jQuery('#faq-dialog select#faq-skin').val();
		 
		var output = '';
		
		// setup the output of our shortcode
		output = '[simple-faq ';
			output += 'style="' + style + '" ';
			output += 'order="' + order + '" ';
			output += 'skin="' + skin + '" ';
			output += 'category="' + category + '" ]';
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);

</script>

</head>
<body>
	<?php /** Get list of categories outsid WP **/
	include('../../../wp-config.php');
	$faq_category_list = get_terms('faq_category');
	?>

	<div id="faq-dialog">
		<form action="/" method="get" accept-charset="utf-8">
			<div>
				<label for="faq-style">Style</label>
				<select name="faq-style" id="faq-style" size="1">
					<option value="Accordion" selected="selected">Accordion</option>
					<option value="Simple">Simple</option>
					<option value="Bookmarks">Bookmark</option>
				</select>
			</div>
			<div>
				<label for="faq-order">Order</label>
				<select name="faq-order" id="faq-order" size="1">
					<option value="Default" selected="selected">Default</option>
					<option value="Alpha">Alphabetical</option>
					<option value="Date">Date</option>
				</select>
			</div>
			<div>
				<label for="faq-skin">Skin</label>
				<select name="faq-skin" id="faq-skin" size="1">
					<option value="none" selected="selected">None</option>
					<option value="Black">Black</option>
					<option value="Green">Green</option>
					<option value="Blue">Blue</option>
					<option value="Red">Red</option>
				</select>
			</div>
			<div>
				<label for="faq-category">Category</label>
				<select name="faq-category" id="faq-category" size="1">
					<option value="All" selected="selected">All</option>
					<?php foreach ($faq_category_list as $faq_category) {
						echo '<option value="'.$faq_category->slug.'">'.$faq_category->name.'</option>';
					}
					?>
				</select>
			</div>
			<div>	
				<a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)" id="insert" style="display: block; line-height: 24px;">Insert</a>
			</div>
		</form>
	</div>
</body>
</html>