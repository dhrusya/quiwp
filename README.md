# quiwp
QuiWP: Quick Wordpress Plugins is a php class with collection of functions and controls used to make wordpress plugins quick and easy.

Build Custom post types, custom taxonomies, Custom post type fields in a moment.

Currently supported form fields:

1) Text
2) Email
3) URL
4) Image with upload
5) Select box
6) Multi select box
7) Google Map
8) Users list
9) Page list
10) Taxonomy list box

Coming soon,

1) date
2) datetime
3) time
4) Color select with transperancy

### Prerequisites

First create a plugin in wordpress plugin directory with the name you wanted and with a php file say here index.php with default code as follows and save,

```
<?php
   /*
   Plugin Name: myplugin
   Plugin URI: http://dhrusya.com
   description: This is our first plugin

   Version: 0.1
   Author: Gopal Chaladi
   License: GPL2
   */
```

### Installing QUIWP

Create a folder named inc or includes and place quiwp.php in it (here I am using foldername 'inc').
And create assets folder and place quiwp.css and quiwp.js in it.
Now add it to your plugin,

```
//including quiwp
require_once ('includes/quiwp.php');
//creating quiwp object
$dswp=new dswp();
```
```
//Add js and css files to admin
add_action('admin_print_scripts', 'quiwp_admin_scripts');
function quiwp_admin_scripts()
{
    wp_enqueue_style('quiwpcss', plugins_url('assets/quiwp.css', __FILE__));
    wp_register_script('quiwpjs', plugins_url('assets/quiwp.js', __FILE__));
    wp_enqueue_script('quiwpjs');
    // for ajax and other variables
    wp_localize_script( 'quiwpjs', 'ds_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'homeurl' => home_url(),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending data, please wait...')
    ));
   
    wp_enqueue_media();

}
```
Now you are ready to use QUIWP.

### Creating custom posttypes and taxonomies
Following function will create a custom post type 'project' and two taxonomies associated with it.
```
//creating custom post_type... create here 'project'
add_action('init','ds_project'); 
function ds_project(){
  global $dswp;
  //posttype,name,puralname,icon,notsupported(editor supported fields), $hierarchical(true/false), $show_in_menu(true/false)
	$atts=array('posttype'=>'project','name'=>'Project spec','puralname'=>'Project specs','icon'=>'');//attributes
	$dswp->newPosttype($atts);
  // creating taxonomy
  $dswp->newTaxonomy(array('posttype' => 'project', 'name' => 'Domain', 'puralname' => 'Domain', 'taxonomy' => 'domain'));
  $dswp->newTaxonomy(array('posttype' => 'project', 'name' => 'Technology', 'puralname' => 'Technologies', 'taxonomy' => 'technology'));
}

```

### Creating custom fields to custom field types
Following function will create a custom metabox with fields in it with metabox position defaults to 'low' and priority to 'normal'.

Fieldstypes available are,
* text
* email
* number
* password
* textarea
* select
* rte (Rich Text Editor)
* image
* div (Just to display content)
* taxonomy (custom taxonomy dropdown box)
* users (users with specified role in dropdown)
* posttype (custom posts in dropdown)
* map (Map control with dradale location icon that saves longitude and latitude. Add google map js before using it)


```
//creating custom fields
add_action('add_meta_boxes', 'ds_project_postmeta');
    function ds_project_postmeta(){
  		global $dswp;
        $atts=array(
                array(
                        'posttype'=>'project',
                        'label'=>'Project fields',
                        //'position'=>'low',
                        //'priority'=>'normal',
                        'fields'=>array(
										              array(
                                            'label'=>'Priority',
                                            'name'=>'project-priority',
                                            'fieldtype'=>'select',
                                            'options'=>array('Urgent'=>'Urgent', 'High'=>'High', 'Medium'=>'Medium', 'Low'=>'Low')
                                  ),

                                  array(
                                            'label'=>'Remuneration',
                                            'name'=>'project-remuneration',
                                            'fieldtype'=>'text',
                                  ),
                                  array(
                                            'label'=>'Platform',
                                            'name'=>'project-platform',
                                            'fieldtype'=>'select',
                                            'options'=>array('Android'=>'Android', 'iOS'=>'iOS', 'Web'=>'Web'),
                                            "multiple"=>"multiple" //enables multiple selection
                                  )
                        )
                    )
            );

        $dswp->ds_custompostmeta($atts);
    }

```

### Saving Custom post data
A sample function to save custom post data. 
```
//To save custom fields data
add_action('save_post', "ds_save_custompostmeta", 10,3);
function ds_save_custompostmeta($post_id, $post, $update){
      if(isset($_POST['ds_pmeta'])){
				$ds_pmeta=$_POST['ds_pmeta'];
				foreach($ds_pmeta as $k=>$v){
					update_post_meta($post_id, $k, $v);
				}
			}
}
```

