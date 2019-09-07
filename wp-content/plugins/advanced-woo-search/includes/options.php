<?php
/**
 * Array of plugin options
 */

$options = array();

$options['general'][] = array(
    "name" => __( "Main Settings", "advanced-woo-search" ),
    "type" => "heading"
);

$options['general'][] = array(
    "name"  => __( "Seamless integration", "advanced-woo-search" ),
    "desc"  => __( "Replace all the standard search forms on your website ( may not work with some themes ).", "advanced-woo-search" ),
    "id"    => "seamless",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Cache results", "advanced-woo-search" ),
    "desc"  => __( "Turn off if you have old data in the search results after content of products was changed.<br><strong>CAUTION:</strong> can dramatically increase search speed", "advanced-woo-search" ),
    "id"    => "cache",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Sync index table", "advanced-woo-search" ),
    "desc"  => __( "Automatically update plugin index table when product content was changed. This means that in search there will be always latest product data.", "advanced-woo-search" ) . '<br>' .
               __( "Turn this off if you have any problems with performance.", "advanced-woo-search" ),
    "id"    => "autoupdates",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Search in", "advanced-woo-search" ),
    "desc"  => __( "Search source: Drag&drop sources to activate or deactivate them.", "advanced-woo-search" ),
    "id"    => "search_in",
    "value" => "title,content,sku,excerpt",
    "choices" => array( "title", "content", "sku", "excerpt", "category", "tag", "id" ),
    "type"  => "sortable"
);

$options['general'][] = array(
    "name"  => __( "Show out-of-stock", "advanced-woo-search" ),
    "desc"  => __( "Show out-of-stock products in search", "advanced-woo-search" ),
    "id"    => "outofstock",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'Show', 'advanced-woo-search' ),
        'false'  => __( 'Hide', 'advanced-woo-search' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Stop words list", "advanced-woo-search" ),
    "desc"  => __( "Comma separated list of words that will be excluded from search.", "advanced-woo-search" ) . '<br>' . __( "Re-index required on change.", "advanced-woo-search" ),
    "id"    => "stopwords",
    "value" => "a, also, am, an, and, are, as, at, be, but, by, call, can, co, con, de, do, due, eg, eight, etc, even, ever, every, for, from, full, go, had, has, hasnt, have, he, hence, her, here, his, how, ie, if, in, inc, into, is, it, its, ltd, me, my, no, none, nor, not, now, of, off, on, once, one, only, onto, or, our, ours, out, over, own, part, per, put, re, see, so, some, ten, than, that, the, their, there, these, they, this, three, thru, thus, to, too, top, un, up, us, very, via, was, we, well, were, what, when, where, who, why, will",
    "cols"  => "85",
    "rows"  => "3",
    "type"  => "textarea"
);

$options['general'][] = array(
    "name"  => __( "Synonyms", "advanced-woo-search" ),
    "desc"  => __( "Comma separated list of synonym words. Each group of synonyms must be on separated text line.", "advanced-woo-search" ) . '<br>' . __( "Re-index required on change.", "advanced-woo-search" ),
    "id"    => "synonyms",
    "value" => "buy, pay, purchase, acquire&#13;&#10;box, housing, unit, package",
    "cols"  => "85",
    "rows"  => "3",
    "type"  => "textarea"
);

$options['general'][] = array(
    "name"  => __( "Use Google Analytics", "advanced-woo-search" ),
    "desc"  => __( "Use google analytics to track searches. You need google analytics to be installed on your site.", "advanced-woo-search" ) . '<br>' . __( "Will send event with category - 'AWS search', action - 'AWS Search Term' and label of value of search term.", "advanced-woo-search" ),
    "id"    => "use_analytics",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

// Search Form Settings
$options['form'][] = array(
    "name"  => __( "Text for search field", "advanced-woo-search" ),
    "desc"  => __( "Text for search field placeholder.", "advanced-woo-search" ),
    "id"    => "search_field_text",
    "value" => __( "Search", "advanced-woo-search" ),
    "type"  => "text"
);

$options['form'][] = array(
    "name"  => __( "Text for show more button", "advanced-woo-search" ),
    "desc"  => __( "Text for link to search results page at the bottom of search results block.", "advanced-woo-search" ),
    "id"    => "show_more_text",
    "value" => __( "View all results", "advanced-woo-search" ),
    "type"  => "text"
);

$options['form'][] = array(
    "name"  => __( "Nothing found field", "advanced-woo-search" ),
    "desc"  => __( "Text when there is no search results.", "advanced-woo-search" ),
    "id"    => "not_found_text",
    "value" => __( "Nothing found", "advanced-woo-search" ),
    "type"  => "textarea"
);

$options['form'][] = array(
    "name"  => __( "Minimum number of characters", "advanced-woo-search" ),
    "desc"  => __( "Minimum number of characters required to run ajax search.", "advanced-woo-search" ),
    "id"    => "min_chars",
    "value" => 1,
    "type"  => "number"
);

$options['form'][] = array(
    "name"  => __( "Show loader", "advanced-woo-search" ),
    "desc"  => __( "Show loader animation while searching.", "advanced-woo-search" ),
    "id"    => "show_loader",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['form'][] = array(
    "name"  => __( "Show clear button", "advanced-woo-search" ),
    "desc"  => __( "Show 'Clear search string' button for desktop devices ( for mobile it is always visible ).", "advanced-woo-search" ),
    "id"    => "show_clear",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['form'][] = array(
    "name"  => __( "Show 'View All Results'", "advanced-woo-search" ),
    "desc"  => __( "Show link to search results page at the bottom of search results block.", "advanced-woo-search" ),
    "id"    => "show_more",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' )
    )
);

$options['form'][] = array(
    "name"  => __( "Search Results", "advanced-woo-search" ),
    "desc"  => __( "Choose how to view search results.", "advanced-woo-search" ),
    "id"    => "show_page",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'     => __( 'Both ajax search results and search results page', 'advanced-woo-search' ),
        'false'    => __( 'Only ajax search results ( no search results page )', 'advanced-woo-search' ),
        'ajax_off' => __( 'Only search results page ( no ajax search results )', 'advanced-woo-search' )
    )
);

$options['form'][] = array(
    "name"  => __( "Form Styling", "advanced-woo-search" ),
    "desc"  => __( "Choose search form layout", "advanced-woo-search" ) . '<br>' . __( "Filter button will be visible only if you have more than one active filter for current search form instance.", "advanced-woo-search" ),
    "id"    => "buttons_order",
    "value" => '1',
    "type"  => "radio-image",
    'choices' => array(
        '1' => 'btn-layout1.png',
        '2' => 'btn-layout2.png',
        '3' => 'btn-layout3.png',
    )
);


// Search Results Settings

$options['results'][] = array(
    "name"  => __( "Description source", "advanced-woo-search" ),
    "desc"  => __( "From where to take product description.<br>If first source is empty data will be taken from other sources.", "advanced-woo-search" ),
    "id"    => "desc_source",
    "value" => 'content',
    "type"  => "radio",
    'choices' => array(
        'content'  => __( 'Content', 'advanced-woo-search' ),
        'excerpt'  => __( 'Excerpt', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Description length", "advanced-woo-search" ),
    "desc"  => __( "Maximal allowed number of words for product description.", "advanced-woo-search" ),
    "id"    => "excerpt_length",
    "value" => 20,
    "type"  => "number"
);

$options['results'][] = array(
    "name"  => __( "Max number of results", "advanced-woo-search" ),
    "desc"  => __( "Maximum number of displayed search results.", "advanced-woo-search" ),
    "id"    => "results_num",
    "value" => 10,
    "type"  => "number"
);

$options['results'][] = array(
    "name"    => __( "View", "advanced-woo-search" ),
    "type"    => "heading"
);

$options['results'][] = array(
    "name"  => __( "Show image", "advanced-woo-search" ),
    "desc"  => __( "Show product image for each search result.", "advanced-woo-search" ),
    "id"    => "show_image",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show description", "advanced-woo-search" ),
    "desc"  => __( "Show product description for each search result.", "advanced-woo-search" ),
    "id"    => "show_excerpt",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false'  => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Description content", "advanced-woo-search" ),
    "desc"  => __( "What to show in product description?", "advanced-woo-search" ),
    "id"    => "mark_words",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( "Smart scrapping sentences with searching terms from product description.", "advanced-woo-search" ),
        'false' => __( "First N words of product description ( number of words that you choose below. )", "advanced-woo-search" ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show price", "advanced-woo-search" ),
    "desc"  => __( "Show product price for each search result.", "advanced-woo-search" ),
    "id"    => "show_price",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show price for out of stock", "advanced-woo-search" ),
    "desc"  => __( "Show product price for out of stock products.", "advanced-woo-search" ),
    "id"    => "show_outofstock_price",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show categories archive", "advanced-woo-search" ),
    "desc"  => __( "Include categories archives pages to search result.", "advanced-woo-search" ),
    "id"    => "show_cats",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show tags archive", "advanced-woo-search" ),
    "desc"  => __( "Include tags archives pages to search results.", "advanced-woo-search" ),
    "id"    => "show_tags",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show sale badge", "advanced-woo-search" ),
    "desc"  => __( "Show sale badge for products in search results.", "advanced-woo-search" ),
    "id"    => "show_sale",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show product SKU", "advanced-woo-search" ),
    "desc"  => __( "Show product SKU in search results.", "advanced-woo-search" ),
    "id"    => "show_sku",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show stock status", "advanced-woo-search" ),
    "desc"  => __( "Show stock status for every product in search results.", "advanced-woo-search" ),
    "id"    => "show_stock",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show featured icon", "advanced-woo-search" ),
    "desc"  => __( "Show or not star icon for featured products.", "advanced-woo-search" ),
    "id"    => "show_featured",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'advanced-woo-search' ),
        'false' => __( 'Off', 'advanced-woo-search' ),
    )
);