(function() {
    tinymce.PluginManager.add('simple_faq_button', function( editor, url ) {
        editor.addButton( 'faq_button', {
            title: 'Simple FAQ Shortcode Generator',
            icon: 'simple-faq',
            onclick: function() {
                editor.windowManager.open({
					file : url + '/faq_generator.php', // file that contains HTML for our modal window
				});
            }
        });
    });
})();