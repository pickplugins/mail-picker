<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mail_picker_post_types{
	
	public function __construct(){
		
		add_action( 'init', array( $this, '_posttype_subscriber' ), 0 );
        add_action( 'init', array( $this, '_posttype_subscriber_source' ), 0 );

        add_action( 'init', array( $this, '_posttype_subscriber_form' ), 0 );

        add_action( 'init', array( $this, '_posttype_mail_template' ), 0 );
        add_action( 'init', array( $this, '_posttype_mail_campaign' ), 0 );
        //add_action( 'init', array( $this, '_posttype_sms_campaign' ), 0 );


	}
	
	public function _posttype_subscriber(){

		if ( post_type_exists( "subscriber" ))
		return;

		$singular  = __( 'Subscriber', 'mail-picker' );
		$plural    = __( 'Subscribers', 'mail-picker' );
	 
	 
		register_post_type( "subscriber",
			apply_filters( "register_post_type_subscriber", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $singular,
					'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
					'add_new' 				=> __( 'Add '.$singular, 'mail-picker' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
					'edit' 					=> __( 'Edit', 'mail-picker' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','custom-fields'),
				'show_in_nav_menus' 	=> false,
                'show_in_menu' 	=> false,
                //'taxonomies' => array('subscriber_tags'),
				'menu_icon' => 'dashicons-email',
			) )
		);





        $singular  = __( 'Subscriber list', 'mail-picker' );
        $plural    = __( 'Subscriber lists', 'mail-picker' );

        register_taxonomy( "subscriber_list",
            apply_filters( 'register_taxonomy_subscriber_list_object_type', array( 'subscriber' ) ),
            apply_filters( 'register_taxonomy_subscriber_list_args', array(
                'hierarchical' 			=> true,
                'show_admin_column' 	=> true,
                'label' 				=> $plural,
                'labels' => array(
                    'name'              => $plural,
                    'singular_name'     => $singular,
                    'menu_name'         => $plural,
                    'search_items'      => sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                    'all_items'         => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                    'parent_item'       => sprintf( __( 'Parent %s', 'mail-picker' ), $singular ),
                    'parent_item_colon' => sprintf( __( 'Parent %s:', 'mail-picker' ), $singular ),
                    'edit_item'         => sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                    'update_item'       => sprintf( __( 'Update %s', 'mail-picker' ), $singular ),
                    'add_new_item'      => sprintf( __( 'Add New %s', 'mail-picker' ), $singular ),
                    'new_item_name'     => sprintf( __( 'New %s Name', 'mail-picker' ),  $singular )
                ),
                'show_ui' 				=> true,
                'public' 	     		=> true,
                'rewrite' => array(
                    'show_in_menu' 	=> false,

                    'slug' => 'subscriber_list', // This controls the base slug that will display before each term
                    'with_front' => false, // Don't display the category base before "/locations/"
                    'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
                ),
            ) )
        );















    }





    public function _posttype_subscriber_form(){

        if ( post_type_exists( "subscriber_form" ))
            return;

        $singular  = __( 'Subscriber form', 'mail-picker' );
        $plural    = __( 'Subscribers form', 'mail-picker' );


        register_post_type( "subscriber_form",
            apply_filters( "register_post_type_subscriber_form", array(
                'labels' => array(
                    'name' 					=> $plural,
                    'singular_name' 		=> $singular,
                    'menu_name'             => $singular,
                    'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                    'add_new'               => sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                    'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                    'edit' 					=> __( 'Edit', 'mail-picker' ),
                    'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                    'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
                    'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                    'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                    'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                    'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
                    'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
                    'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
                ),

                'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
                'public' 				=> true,
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap'          => true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'rewrite' 				=> true,
                'query_var' 			=> true,
                'supports' 				=> array('title','custom-fields'),
                'show_in_nav_menus' 	=> false,
                'show_in_menu' 	        => false,

                    //'taxonomies' => array('subscriber_tags'),
                'menu_icon' => 'dashicons-feedback',

                    )
            )
        );

    }


    public function _posttype_subscriber_source(){

        if ( post_type_exists( "subscriber_source" ))
            return;

        $singular  = __( 'Subscriber source', 'mail-picker' );
        $plural    = __( 'Subscriber sources', 'mail-picker' );


        register_post_type( "subscriber_source",
            apply_filters( "register_post_type_subscriber_source", array(
                    'labels' => array(
                        'name' 					=> $plural,
                        'singular_name' 		=> $singular,
                        'menu_name'             => $singular,
                        'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                        'add_new'               => sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'edit' 					=> __( 'Edit', 'mail-picker' ),
                        'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                        'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
                        'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                        'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
                        'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
                        'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
                    ),

                    'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
                    'public' 				=> true,
                    'show_ui' 				=> true,
                    'capability_type' 		=> 'post',
                    'map_meta_cap'          => true,
                    'publicly_queryable' 	=> false,
                    'exclude_from_search' 	=> false,
                    'hierarchical' 			=> false,
                    'rewrite' 				=> true,
                    'query_var' 			=> true,
                    'supports' 				=> array('title','custom-fields'),
                    'show_in_nav_menus' 	=> false,
                    'show_in_menu' 	        => false,

                    //'taxonomies' => array('subscriber_tags'),
                    'menu_icon' => 'dashicons-feedback',

                )
            )
        );

    }






    public function _posttype_mail_template(){

        if ( post_type_exists( "mail_template" ))
            return;

        $singular  = __( 'Mail template', 'mail-picker' );
        $plural    = __( 'Mail templates', 'mail-picker' );


        register_post_type( "mail_template",
            apply_filters( "register_post_type_mail_template", array(
                    'labels' => array(
                        'name' 					=> $plural,
                        'singular_name' 		=> $singular,
                        'menu_name'             => $singular,
                        'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                        'add_new'               => sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'edit' 					=> __( 'Edit', 'mail-picker' ),
                        'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                        'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
                        'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                        'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
                        'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
                        'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
                    ),

                    'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
                    'public' 				=> true,
                    'show_ui' 				=> true,
                    'capability_type' 		=> 'post',
                    'map_meta_cap'          => true,
                    'publicly_queryable' 	=> true,
                    'exclude_from_search' 	=> false,
                    'hierarchical' 			=> false,
                    'rewrite' 				=> true,
                    'query_var' 			=> true,
                    'supports' 				=> array('title','editor','custom-fields'),
                    'show_in_nav_menus' 	=> false,
                    'show_in_menu' 	        => false,

                    //'taxonomies' => array('subscriber_tags'),
                    'menu_icon' => 'dashicons-buddicons-pm',

                )
            )
        );

    }



    public function _posttype_mail_campaign(){

        if ( post_type_exists( "mail_campaign" ))
            return;

        $singular  = __( 'Mail campaign', 'mail-picker' );
        $plural    = __( 'Mail campaigns', 'mail-picker' );


        register_post_type( "mail_campaign",
            apply_filters( "register_post_type_mail_campaign", array(
                    'labels' => array(
                        'name' 					=> $plural,
                        'singular_name' 		=> $singular,
                        'menu_name'             => $singular,
                        'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                        'add_new'               => sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'edit' 					=> __( 'Edit', 'mail-picker' ),
                        'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                        'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
                        'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                        'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
                        'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
                        'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
                    ),

                    'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
                    'public' 				=> true,
                    'show_ui' 				=> true,
                    'capability_type' 		=> 'post',
                    'map_meta_cap'          => true,
                    'publicly_queryable' 	=> false,
                    'exclude_from_search' 	=> false,
                    'hierarchical' 			=> false,
                    'rewrite' 				=> true,
                    'query_var' 			=> true,
                    'supports' 				=> array('title','custom-fields'),
                    'show_in_nav_menus' 	=> false,
                    'show_in_menu' 	        => false,

                    //'taxonomies' => array('subscriber_tags'),
                    'menu_icon' => 'dashicons-megaphone',

                )
            )
        );

    }





    public function _posttype_sms_campaign(){

        if ( post_type_exists( "sms_campaign" ))
            return;

        $singular  = __( 'SMS campaign', 'mail-picker' );
        $plural    = __( 'SMS campaigns', 'mail-picker' );


        register_post_type( "sms_campaign",
            apply_filters( "register_post_type_sms_campaign", array(
                    'labels' => array(
                        'name' 					=> $plural,
                        'singular_name' 		=> $singular,
                        'menu_name'             => $singular,
                        'all_items'             => sprintf( __( 'All %s', 'mail-picker' ), $plural ),
                        'add_new'               => sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'add_new_item' 			=> sprintf( __( 'Add %s', 'mail-picker' ), $singular ),
                        'edit' 					=> __( 'Edit', 'mail-picker' ),
                        'edit_item' 			=> sprintf( __( 'Edit %s', 'mail-picker' ), $singular ),
                        'new_item' 				=> sprintf( __( 'New %s', 'mail-picker' ), $singular ),
                        'view' 					=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'view_item' 			=> sprintf( __( 'View %s', 'mail-picker' ), $singular ),
                        'search_items' 			=> sprintf( __( 'Search %s', 'mail-picker' ), $plural ),
                        'not_found' 			=> sprintf( __( 'No %s found', 'mail-picker' ), $plural ),
                        'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mail-picker' ), $plural ),
                        'parent' 				=> sprintf( __( 'Parent %s', 'mail-picker' ), $singular )
                    ),

                    'description' => sprintf( __( 'This is where you can create and manage %s.', 'mail-picker' ), $plural ),
                    'public' 				=> true,
                    'show_ui' 				=> true,
                    'capability_type' 		=> 'post',
                    'map_meta_cap'          => true,
                    'publicly_queryable' 	=> false,
                    'exclude_from_search' 	=> false,
                    'hierarchical' 			=> false,
                    'rewrite' 				=> true,
                    'query_var' 			=> true,
                    'supports' 				=> array('title','custom-fields'),
                    'show_in_nav_menus' 	=> false,
                    'show_in_menu' 	        => false,

                    //'taxonomies' => array('subscriber_tags'),
                    'menu_icon' => 'dashicons-megaphone',

                )
            )
        );

    }













} 

new class_mail_picker_post_types();