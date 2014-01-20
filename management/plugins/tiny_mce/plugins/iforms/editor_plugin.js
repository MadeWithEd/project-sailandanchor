// Import theme specific language pack 
tinyMCE.importPluginLanguagePack('iforms', 'en');

// Returns the HTML contents of the ibrowser control.

function TinyMCE_iforms_getControlHTML(control_name) {
	switch (control_name) {
		case "iforms":
			return '<a class="mceTiledButton mceButtonNormal" id="{$editor_id}_iforms" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceBrowseForms\');"><img id="{$editor_id}_iforms23" src="{$pluginurl}/images/iforms.gif" title="{$lang_iforms_desc}" width="20" height="20"></a>';
	}

	return "";
}

function TinyMCE_iforms_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceBrowseForms":
			var template = new Array();

			template['file'] = '../../plugins/iforms/iforms.php'; // Relative to theme location
			template['width'] = 480;
			template['height'] = 380;

			var formid="";
			if (tinyMCE.selectedElement != null && tinyMCE.selectedElement.nodeName.toLowerCase() == "form"){
				tinyMCE.formElement = tinyMCE.selectedElement;}

			if (tinyMCE.formElement) {
				
				formid = tinyMCE.formElement.getAttribute('id') ? tinyMCE.formElement.getAttribute('id') : "";

				
			}
				tinyMCE.openWindow(template, {editor_id : editor_id, formid : formid});
				return true;
	}

	// Pass to next handler in chain
	return false;
}
