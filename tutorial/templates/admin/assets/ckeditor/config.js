/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = '/php/tutorial/templates/admin/assets/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl ='/php/tutorial/templates/admin/assets/ckfinder/ckfinder.html?type=Images';
	config.filebrowserUploadUrl = '/php/tutorial/templates/admin/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/php/tutorial/templates/admin/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
};
