jQuery(document).ready( function($) {
    "use strict";

    jQuery( function() {
        jQuery('#ei_posttypes, #ei_taxonomies, #php_posttypes, #php_taxonomies, #settings, #templates').hide();
    } );

    // Export / Import Post Types
    jQuery( 'body' ).on( 'click', '#ei_posttypes_link', function(e) {
        jQuery( '#ei_posttypes' ).show();
        jQuery( '#ei_taxonomies, #php_posttypes, #php_taxonomies, #settings, #templates' ).hide();
        e.preventDefault();
    } );

    // Export / Import Taxonomies
    jQuery( 'body' ).on( 'click', '#ei_taxonomies_link', function(e) {
        jQuery( '#ei_taxonomies' ).show();
        jQuery( '#ei_posttypes, #php_posttypes, #php_taxonomies, #settings, #templates' ).hide();
        e.preventDefault();
    } );

    // PHP Output Post Types
    jQuery( 'body' ).on( 'click', '#php_posttypes_link', function(e) {
        jQuery( '#php_posttypes' ).show();
        jQuery( '#ei_posttypes, #ei_taxonomies, #php_taxonomies, #settings, #templates' ).hide();
        e.preventDefault();
    } );

    // PHP Output Taxonomies
    jQuery( 'body' ).on( 'click', '#php_taxonomies_link', function(e) {
        jQuery( '#php_taxonomies' ).show();
        jQuery( '#ei_posttypes, #ei_taxonomies, #php_posttypes, #settings, #templates' ).hide();
        e.preventDefault();
    } );

    // Saved Settings
    jQuery( 'body' ).on( 'click', '#settings_link', function(e) {
        jQuery( '#settings' ).show();
        jQuery( '#ei_posttypes, #ei_taxonomies, #php_posttypes, #php_taxonomies, #templates' ).hide();
        e.preventDefault();
    } );

    // Saved Settings
    jQuery( 'body' ).on( 'click', '#templates_link', function(e) {
        jQuery( '#templates' ).show();
        jQuery( '#ei_posttypes, #ei_taxonomies, #php_posttypes, #php_taxonomies, #settings' ).hide();
        e.preventDefault();
    } );
} );