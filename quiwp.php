<?php
    class dswp
    {
        public $homeurl;
        public $themeurl;
        public $pluginurl;
        public $taxdata='';
        public $taxeditdata='';
        public $posttypemeta='';
        public $taxonomy;

        public function __constructor()
        {

          add_action('wp_ajax_ds_createuser', array($this,'ds_createuser'));
          add_action('wp_ajax_nopriv_ds_createuser', array($this, 'ds_createuser'));
        }
        /********************************START CREATING NEW CUSTOM PAGE*******************************/
        public function newPage($atts)
        {
            extract($atts);
            //$atts=array("page_title", "menu_title", "capability", "menu_slug", "function" , "icon_url", "position", "createunder");
            add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
        }


        /********************************END CREATING NEW CUSTOM PAGE*******************************/



        /********************************CREATING NEW CUSTOM POST TYPE*******************************/
        public function newPosttype($atts)
        {
            extract($atts); //posttype,name,puralname,icon,notsupported, $hierarchical, $show_in_menu
            $name=strtolower($name);
            $singlelabel=ucfirst($name);
            $puralname=ucfirst($puralname);
            $icon=!empty($icon)?$icon:'';
            $show_in_menu=!empty($show_in_menu)?$show_in_menu:true;
            $support = array (
                          'title',
                          'editor',
                          'author',
                          'thumbnail',
                          'custom-fields',
                          'comments',
                          'genesis-seo',
                          'genesis-layouts',
                          'revisions',
                          // Add this to supports
                          'page-attributes',
                      );
            $array= array('title','editor','thumbnail', 'page-attributes');
            $hierarchical=!empty($hierarchical)?$hierarchical:false;
            $supports=$array;
            if (!empty($notsupported) and is_array($notsupported)) {
                $supports = array_diff($array, $notsupported);
            }


            $labels = array(
                    'name' => _x($puralname, 'post type general name'),
                    'singular_name' => _x($singlelabel, 'post type singular name'),
                    'add_new' => _x('Add  '.$singlelabel, $puralname),
                    'add_new_item' => __('Add New '.$singlelabel),
                    'edit_item' => __('Edit '.$singlelabel),
                    'new_item' => __('New '.$singlelabel),
                    'view_item' => __('View '.$singlelabel),
                    'search_items' => __('Search '.$singlelabel),
                    'not_found' =>  __('Nothing found'),
                    'not_found_in_trash' => __('Nothing found in Trash'),
                    'parent_item_colon' => ''
                );
            $capabilities = array(
                    'publish_posts' => 'publish_'.$name,
                    'edit_posts' => 'edit_'.$name,
                    'edit_published_posts'=>' edit_published_'.$name,
                    'edit_others_posts' => 'edit_others_'.$name,
                    'edit_private_posts'=>'edit_private_'.$name,
                    'delete_posts' => 'delete_'.$name,
                    'delete_published_posts' => 'delete_published_'.$name,
                    'delete_others_posts' => 'delete_others_'.$name,
                    'delete_private_posts'=>'delete_private_'.$name,
                    'read_private_posts' => 'read_private_'.$name,
                );
            $args = array(
                    'labels' => $labels,
                    'public' => true,
                    'publicly_queryable' => true,
                    'show_ui' => true,
                    'query_var' => true,
                    'has_archive' => true,
                    'map_meta_cap' => true,
                    'show_in_menu' => $show_in_menu,
                    'menu_icon' => $icon,
                    'rewrite' => true,
                    'capability_type' => 'post',
                    'hierarchical' => $hierarchical,
                    'menu_position' => null,
                    'supports' =>$supports,
                    'capabilities' => $capabilities,
                    //'menu_slug'=>$parentmenu
                );
            // if($parentmenu){

            // 	$args['show_in_menu']=false;
            // 	//$args['parent_slug']=$parentmenu;
            // 	 array(
            //               'parent_slug'   => $parent,
            //               'page_title'    => 'Special Pricing',
            //               'menu_title'    => 'Special Pricing',
            //               'capability'    => 'read',
            //               'menu_slug'     => 'edit.php?post_type=$posttype,
            //               'function'      => null,// Doesn't need a callback function.
            //         		),

            // }
            register_post_type($posttype, $args);
            //add custompost capabilities to admin by default
            $userroles=array("administrator");
            //This following code returning string offset error/warning, need to check
            if(!empty($capability_users)){
              //$userroles=array_merge($userroles, $capability_users);
            }
            // print_r($userroles);
            foreach ($userroles as $user) {
              // code...
              $role = get_role( "$user" );
              foreach ($capabilities as $key => $value) {
                $role->add_cap( "$value" );
              }
            }
            flush_rewrite_rules( false );//why this??
        }//newposttype ends

        /********************************CREATING NEW CUSTOM TAXONOMY*******************************/
        public function newTaxonomy($atts)
        {
            //$atts: posttype,taxonomy,name,puralname,
            extract($atts);
            //$name=strtolower($name);
            $singlename=ucwords($name);
            $puralname=ucfirst($puralname);
            $showmenu=!empty($showmenu)?$showmenu:true;
            $showadmincolumn=!empty($showadmincolumn)?$showadmincolumn:true;
            $showmetabox=!empty($showmetabox)?$showmetabox:true;
            $this->taxonomy=$taxonomy;
            if (!$showmetabox) {
                //add_action( 'wp_head',  );
            }

            $labels = array(
                    'name'              			=> _x($puralname, 'taxonomy general name'),
                    'singular_name'    				=> _x($puralname, 'taxonomy singular name'),
                    'search_items'     				=> __('Search '.$puralname),
                    'all_items'         			=> __('All '.$puralname),
                    'parent_item'       			=> __('Parent '.$singlename),
                    'parent_item_colon' 			=> __('Parent '.$singlename.':'),
                    'edit_item'         			=> __('Edit '.$singlename),
                    'update_item'       			=> __('Update '.$singlename),
                    'add_new_item'     				=> __('Add New '.$singlename),
                    'new_item_name'    				=> __('New '.$singlename.' Name'),
                    'menu_name'         			=> __($puralname),
                );

            $args = array(
                    'hierarchical'     		 	=> true,
                    'labels'            		=> $labels,
                    'show_ui'           		=> true,
                    'show_admin_column' 		=> $showadmincolumn,
                    'show_in_nav_menus' 		=> true,
                    'show_in_menu'          => $showmenu,
                    'query_var'        			=> true,
                    'rewrite'           		=> array( 'slug' => "$taxonomy" ),
                    //u'meta_box_cb'                => $showmetabox,
                );
            register_taxonomy($taxonomy, $posttype, $args);
        }//newtaxonomyends

        /********************************ADDING  NEW CUSTOM FIELD FOR TAXONOMY *******************************/
        public function newTaxmeta($taxonomy, $atts)
        {
            //$atts:array('taxonomy'=>'',array('name'=>'', label'=>'xyz','fieldtype'=>'text/select/textarea/email/image', 'value'=>'single/array'))
            //$args=$atts;
            $args=array();
            $postid=false;
            $extrafunction = 'extra_'.$taxonomy.'_fields';
            if (isset($_GET['tag_ID'])) {
                $t_id=$_GET['tag_ID'];
                $cat_meta = get_option($taxonomy."_$t_id");
            } else {
                $cat_meta=array();
            }


            $html='';
            $ehtml='';

            foreach ($atts as $arr) {
                extract($arr);

                //print_r($cat_meta);
                // $html.='<div class="form-field">';
                // $html.="	<label>$label</label>";
                $ehtml.='<tr class="form-field">
															<th scope="row" valign="top">'.$label.'</th>';
                $fieldtype=!empty($fieldtype)?$fieldtype:"text";
                $value=!empty($value)?$value:"";
                $value=!empty($cat_meta["$name"])?$cat_meta["$name"]:$value;

                if (isset($required)) {
                    $args['required']=" required";
                }
                if (isset($multiple)) {
                    $args['multiple']=$multiple;
                }
                $required=!empty($required)?' required':false;
                //$multiple=!empty($multiple)?' multiple':'';
                $id=!empty($id)? $id:'';

                $args['name']=$name;
                $args['id']=$id;
                $args['value']=$value;
                $args['fieldtype']=$fieldtype;
                //$args['multiple']=$multiple;

                $field=$this->ds_formfield($args);
                $html.=$field;
                $ehtml.='<td>'.$field.'</td></tr>';
                // $html.='</div>';
            }//end atts
            $this->taxdata=$html;

            $this->taxeditdata=$ehtml;


            add_action($taxonomy.'_edit_form_fields', function () {
                echo $this->taxeditdata;
            }, 10, 2);
            add_action($taxonomy.'_add_form_fields', function () {
                echo $this->taxdata;
            }, 10, 2);
            add_action('created_'.$taxonomy, array($this,'ds_savetaxmeta'), 10, 2);
            add_action('edited_'.$taxonomy, array($this,'ds_savetaxmeta'), 10, 2);
        }

        /********************************SAVING NEW CUSTOM FIELD FOR TAXONOMY*******************************/
        public function ds_savetaxmeta($term_id)
        {
            //print_r($_POST['ds_pmeta']);
            if (isset($_POST['ds_pmeta'])) {
                //print_r($_POST['ds_pmeta']);

                $taxonomy=$_POST['taxonomy'];
                $t_id = $term_id;
                //$t_id = $_POST['tag_ID'];
                $cat_meta = get_option($taxonomy."_$t_id");
                //print_r($cat_meta);
                if (empty($cat_meta)) {
                    add_option($taxonomy."_$t_id", array());
                    $cat_meta=array();
                }
                $cat_keys = array_keys($_POST['ds_pmeta']);
                foreach ($cat_keys as $key) {
                    //if (!empty($_POST['ds_pmeta'][$key]))
                    $cat_meta[$key] = $_POST['ds_pmeta'][$key];
                }
                //print_r($cat_meta);
                if (update_option($taxonomy."_$t_id", $cat_meta)) {
                    //echo"Updated";
                } else {
                    //echo"Ne nayanamma";
                }
            }
        }
        /***************************CREATING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE**************************/
        public function ds_custompostmeta($atts)
        {
            foreach ($atts as $box=>$boxdata) {
                extract($boxdata);

                if (empty($posttype)) {
                    die("Posttype not defined");
                }
                $position=isset($position)?$position:"low";
                $priority=isset($priority)?$priority:"normal";
                $this->posttypemeta=$fields;

                add_meta_box("ds_".$posttype."_block".$box, "$label", function () {
                    $html='<div class="dswp_content_block">';
                    foreach ($this->posttypemeta as $label=>$fdata) {

                        $args=array();
                        extract($fdata);
                        // if(!empty($events)) $args['events']=$events;
                        //print_r($fdata);
                        $fieldtype=!empty($fieldtype)?$fieldtype:"text";
                        $multiple=!empty($multiple)?' multiple':'';
                        // $fetchsubitems=!empty($fetchsubitems)?$fetchsubitems:false;
                        $id=!empty($id)?$id:'';

                        $postid=false;
                        if (isset($_GET['post']) and isset($_GET['action'])) {
                            $postid=$_GET['post'];
                            $args['postid']=$postid;
                            $value=get_post_meta($postid, "$name", true);
                        } else {
                            $value=!empty($value)?$value:'';
                        }


                        $args['label']=$label;
                        $args['name']=$name;
                        $args['id']=$id;
                        $args['value']=$value;
                        $args['fieldtype']=$fieldtype;


                        if (isset($fdata['required'])) {
                            $args['required']=" required";
                        }
                        if (isset($fdata['multiple'])) {
                            $args['multiple']=$multiple;
                        }
                        if (isset($fdata['options'])) {
                            $args['options']=$options;
                        }
                        if (isset($fdata['fetchsubitems'])) {
                            $args['fetchsubitems']=$fetchsubitems;
                        }

                        $html.=$this->ds_formfield($args);


                        // $html.='</div>';
                    }
                    $html.="</div>";
                    echo $html;
                }, "$posttype", "$priority", "$position");
            }

            add_action('save_post', array($this,'ds_save_custompostmeta'), 10, 3);


        }//ends custompostmeta

        function ds_save_custompostmeta($post_id, $post, $update){
          //echo "<pre>";
          //print_r($_POST);
          //print_r($_POST['ds_pmeta']);
            if (isset($_POST['ds_pmeta'])) {
                $ds_pmeta = $_POST['ds_pmeta'];
                    //echo $ds_pmeta["['temple_history']"];
                    foreach ($ds_pmeta as $k => $v) {
                        //echo $k." - - - ".$v;
                        update_post_meta($post_id, $k, $v);
                    }
            }    //die();
            //echo "</pre>";
        }


        /********************************CREATE NEW USER WITH ROLE FORM PAGE*******************************/

        public function newUserPage($usertype, $role="subscriber", $capabilities=false){
               $fields = array(
                        array(
                          'label' => 'User Name / Email',
                          'name' => 'email',
                          'fieldtype' => 'email',
                          'required' => true,
                        ),
                        array(
                          'label' => 'Password',
                          'name' => 'password',
                          'fieldtype' => 'password',
                          'required' => true,
                        ),
                        array(
                          'label' => 'First Name',
                          'name' => 'first_name',
                          'fieldtype' => 'text',
                          'required' => true,
                        ),
                        array(
                          'label' => 'Last Name',
                          'name' => 'last_name',
                          'fieldtype' => 'text',
                          'required' => true,
                        ),
                        array(
                          'label' => 'Mobile',
                          'name' => 'mobile',
                          'fieldtype' => 'text',
                          'required' => true,
                        ),
                        array(
                          'label' => 'Website',
                          'name' => 'website',
                          'fieldtype' => 'url',
                        ),
                        array(
                          'label' => 'role',
                          'name' => 'role',
                          'fieldtype' => 'hidden',
                          'value'  => "$role"
                        ),

                      );
            ob_start(); ?>
            <div id="col-container" class="wp-clearfix">

              <div id="col-left">
              <div class="col-wrap">


              <div class="form-wrap">

              <h1>Add New <?php echo $usertype;?></h1>
              <form method="post" name="ds_createuser" id="ds_createuser" class="validate" >
                <input name="action" type="hidden" value="ds_createuser">
                <?php wp_nonce_field( 'create-user_'."$usertype" ); ?>

                <?php
                  foreach ($fields  as $k => $field) {
                    // code...
                    echo $this->ds_formfield($field);
                  }
                ?>
                <p class="submit"><input type="submit" name="createuser" id="createusersub" class="button button-primary" value="Add New User"></p>

              </form>
              </div>
              </div>
              </div><!-- /col-left -->

              <div id="col-right">
              <div class="col-wrap">
                  <?php
                    $blogusers = get_users( "orderby=id&role=$role" );
                    // Array of WP_User objects.
                    foreach ( $blogusers as $user ) {
                    echo '<span>' . esc_html( $user->user_email ) . '</span>';
                    }


                  ?>



              </div>
              </div><!-- /col-right -->

          </div>
  <?php
    $html=ob_get_contents();
    ob_clean();


    return $html;
}
        /********************************END CREATING NEW USER WITH ROLE FORM PAGE*******************************/

function ds_createuser(){
  extract($_POST);
  $userdata=array(
        'first_name'=>"$first_name",
        'last_name'=>"$last_name",
        'display_name' => "$first_name $lastname",
        'user_email' => "$email",
        'user_login' => "$email",
        'user_pass' => "$password",
        'user_url' => "$url",
        'role' => "$role"
  );
  $user_id = username_exists( $email );
  $result=0;
  if ( ! $user_id && false == email_exists( $email ) ) {
      $user_id = wp_insert_user( $userdata );
      //Add meta data
      if(empty($mobile))  add_user_meta( $user_id, '_mobile', $mobile);
      if(empty($url))  add_user_meta( $user_id, '_website', $url);
      $msg = __( 'User added successfully.');

      $users = get_users( "role=$role&order=DESC&number=10" );
      $result=1;
      //$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
  } else {
      $msg = __( 'User already exists.');
      $users='';
  }
  echo json_encode(array($result,$msg,$users));
  die();
}


        /*************************SAVING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE************************/
        public function ds_formfield($args)
        {
            //print_r($args);
            $postid=false;
            extract($args);
      			$html='';

            $fetchsubitems=!empty($fetchsubitems)?$fetchsubitems:false;
            $fieldtype=!empty($fieldtype)?$fieldtype:"text";
            $value=!empty($value)?$value:"";
            $id=!empty($id)?$id:'';
            $required=isset($required)?" required":'';

            $hidfields=array("hidden", "div");

            $requiredtext="<span class=\"description hide-if-js\">\(required\)</span>";
            if(!in_array($fieldtype, $hidfields)){
              $html.='<div class="form-field">';
              $html.="	<label>$label : $required</label>";
            }
            switch ($fieldtype):
              case 'textarea':
                $html.="<textarea name=\"ds_pmeta[$name]\" id=\"$id\" $required>$value</textarea>";
              break;
            case 'select':
              $multiple=!empty($multiple)?' multiple':false;

              if ($multiple) {
                  $html.="<select name=\"ds_pmeta[$name][]\"  id=\"$id\" $required $multiple autocomplete=\"off\">";
              } else {
                  $html.="<select name=\"ds_pmeta[$name]\"  id=\"$id\" $required>";
              }
              $html.="<option value=\"\">Select Any</option>";
              foreach ($options as $k => $v) {
                  if ($multiple) {
                      $vals=explode(",", $value);
                      $selected=(in_array($k, $vals))?'selected="selected"':"";
                  } else {
                      $selected=($k==$value)?'selected="selected"':"";
                  }
                  $html.="<option value=\"$k\" $selected>$v</option>";
              }
              $html.="</select>";
              break;
            case 'rte':
              $editor_id=$name;
              $args=is_array($args)?$args:array();
              ob_start();
                wp_editor($value, $editor_id, $args);
                $html.=ob_get_contents();
              ob_clean();
              break;
            case 'image':
              $html.="<div class=\"ds_field_image\"><input type=\"url\" name=\"ds_pmeta[$name]\" id=\"".$name."_meta_image\" value=\"$value\" placeholder=\"Browse Image\" $required>
								<input type=\"button\" name=\"".$name."_meta_image_button\" id=\"".$name."_meta_image_button\" class=\"uk-button uk-button-primary uk-margin-small-top dswp_upload_button\" value=\"Upload\"> </div>";
            if (!empty($value)) {
                $html.="<img src=\"$value\" style=\"max-width:300px\"  id=\"$id\" />";
            }
            break;
            case 'div':
              $html.="<div id=\"$id\"> </div>";
            break;
            case 'taxonomy':
              $args['echo']=0;
              $args['name']="tax_input[".$args['taxonomy']."][]\" autocomplete=\"off";
              $selectedoptions=get_the_terms( $postid, $args['taxonomy'] );
              if(!empty($selectedoptions)) {
                $args['selected']=$selectedoptions[0]->term_id;
                //echo $fetchsubitems."-".$selectedoptions[0]->term_id;
                //print_r($selectedoptions);
              }
                $html.=wp_dropdown_categories( $args );

              if($fetchsubitems and !empty($selectedoptions[0]->term_id)){
                $args['child_of']=$selectedoptions[0]->term_id;
                $args['selected']=$selectedoptions[1]->term_id;
                // $args['name']=$fetchsubitems;
                $args['id']=$fetchsubitems;
                $html.="<span id=\"".$id."_subdata\">".wp_dropdown_categories($args)."</span>";
              }

              if($fetchsubitems and empty($selectedoptions[0]->term_id)){
                $html.="<span id=\"".$id."_sub\"><select id=\"$taxonomy\" name=\"".$args['name']."\"><option>Select $lable first</option></select></span>";
              }
              $html.="<script type=\"text/javascript\">";
              $html.="jQuery(document).ready(function(e) {";
              $html.="jQuery('#".$args['taxonomy']."div').remove();";
              $html.='});';
              $html.="</script>";
              break;
            case 'users':
              $args['echo']=0;
              $args['name']="ds_pmeta[$name]";
              $args['selected']=$value;
              $html.=wp_dropdown_users($args);
              break;
            case 'posttype':
              $args['echo']=0;
              $args['name']="ds_pmeta[$name]";
              $args['selected']=$value;
              $html.=wp_dropdown_pages($args);
              break;
            case 'map':
              if (isset($postid)) {
                $ds_latitude = get_post_meta($postid, "ds_latitude", true);
                $ds_longitude = get_post_meta($postid, "ds_longitude", true);
              } else {
                $ds_latitude = '';
                $ds_longitude = '';
              }
              $html.="<div class=\"uk-grid\">
											<div class=\"uk-width-1-1\">
												<div id=\"map_canvas\">

												</div>
											</div>
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Latitude:</dt>
														<dd><input type=\"text\" name=\"ds_pmeta[ds_latitude]\" id=\"ds_latitude\"  value=\"$ds_latitude\"  /></dd>
											  </dl>
											</div>
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Longitude:</dt>
													 <dd><input type=\"text\" name=\"ds_pmeta[ds_longitude]\"  id=\"ds_longitude\" value=\"$ds_longitude\"  /></dd>
											  </dl>
											</div>";
              $html.="<script type=\"text/javascript\">";
              $html.="jQuery(document).ready(function(e) {";

              if (!empty($ds_latitude) and !empty($ds_longitude)):
                $html.="mapLoad( $ds_latitude, $ds_longitude );"; else:
                $html.="mapLoad();";
              endif;

              $html.='});';
              $html.="</script>";
              break;
            case 'password':
              $html.="<input type=\"$fieldtype\" name=\"ds_pmeta[$name]\" value=\"$value\"  id=\"$id\" $required autocomplete=\"off\" class=\"regular-text strong\">";
              $pswdname=$name."text";
              $html.="<input type=\"text\" id=\"passwdtext\" name=\"ds_pmeta[$pswdname]\" value=\"$value\"  id=\"$id\" $required autocomplete=\"off\" class=\"regular-text strong\" disabled=\"\">";
              break;
            default:
              $html.="<input type=\"$fieldtype\" name=\"ds_pmeta[$name]\" value=\"$value\"  id=\"$id\" $required>";
            endswitch;
            if(!in_array($fieldtype, $hidfields)){
            // if($fieldtype!="hidden" or $fieldtype!="div"){

              $html.='</div>';
            }
            return $html;
        }


        function full_url(){
      		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
      		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
      		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
      		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
      	}
      	//$actual_link = full_url();

      	function add_querystring_var($url, $key, $value) {
      		$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
      		$url = substr($url, 0, -1);
      		if (strpos($url, '?') === false) {
      			return ($url . '?' . $key . '=' . $value);
      		} else {
      			return ($url . '&' . $key . '=' . $value);
      		}
      	}


      	function remove_querystring_var($url, $key) {
      		$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
      		$url = substr($url, 0, -1);
      		return ($url);
      	}



      	/*888888888888888888 MYSQL FUNCTIONS  88888888888888888888*/
      	//execute sql query
      	function myquery($sql){
      	global $wpdb;
      			return $wpdb->query($sql);
      	}

      	//get resulr set
      	function myres($sql,$type='object'){
      		global $wpdb;
      		if($type=='array'){
      			return $wpdb->get_results($sql,ARRAY_A);
      		}else{
      			return $wpdb->get_results($sql);
      		}
      	}

      	//get table column
      	function mycol($sql){
      			global $wpdb;
      			return $wpdb->get_col($wpdb->prepare($sql));
      	}

      	//get table row
      	function myrow($sql, $type=false){ //ARRAY_A, ARRAY_N
      			global $wpdb;
      			if($type) return $wpdb->get_row($sql, $type);
      			else return $wpdb->get_row($sql);

      	}

      	//get table field (select field from tablename)
      	function myfield($sql){
      			global $wpdb;
      			return $wpdb->get_var($sql);
      	}

      	//get csv (select field from tablename)
      	function mycsv($sql){
      			global $wpdb;
      			$r=$this->mycol($sql);
      			return implode(",",$r);
      	}

      	//pagination
      	function pagination($pages){
      		$pgid=isset($_GET['pgid'])?$_GET['pgid']:1;
      		$first=1;
      		$next=($pgid==$pages)?1:$pgid+1;
      		$prev=($pgid==1)?$pages:$pgid-1;;
      		$last= $pages;

      		//10 page
      		$st=($pgid<=5)?1:(($pages>10)?$pgid-5:$pgid-5);
      		$end=$pages<=10?$pages:($pages>=($st+9)?$st+9:($pages-$st<$st?$pages:$pages-$st));


      		//$st=($pgid>5 and $pages>10)?(($pgid>$pages-10)?$pgid-10:$pgid-5):1;
      		//$end=($pages>10 and $pages>$pgid+5)?$pgid+5:$pages;
      		//pagination
      		$url=$this->remove_querystring_var($_SERVER['REQUEST_URI'], "pgid");
      		$pageid=(stristr($url,"?"))?"&pgid":"?pgid";


      		$nav.="<div class=\"pg_nav\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      		<tr>
      		<td width=\"16\"><a href=\"".$url."$pageid=".$first."\">&laquo;</a></td>
      		<td width=\"16\"><a href=\"".$url."$pageid=".$prev."\">&lsaquo;</a></td>
      		<td>";
      		foreach(range($st,$end) as $v){
      		$selected=($pgid==$v)?"class=\"pgselect\"":"";
      		$nav.="<a href=\"".$url."$pageid=$v\" $selected>
      		$v
      		</a>";
      		}
      		$nav.=($end<$pages)?"...":"";
      		$nav.="</td>
      		<td width=\"16\"><a href=\"".$url."$pageid=".$next."\">&rsaquo;</a></td>
      		<td width=\"16\"><a href=\"".$url."$pageid=".$last."\">&raquo;</a></td>
      		</tr>
      		</table>
      		</div>";
      		$start=($pgid-1);

      		$a=array();
      		$a['pagination']=$nav;
      		$a['startrecord']=$start;
      		return $a;
      		}




      	function mytable($sql, $args){
      		//settings
      		global $wpdb;

          $args=array(
                    "class"=>false,
                    "optionoxes"=>false,
                    "pagination"=>false,
                    "edit"=>false,
                    "delete"=>false,
                    "admincheck"=>false,
                    "border"=>false,
          );
          extract($args);

      		$r=$wpdb->get_results($sql,ARRAY_A);
      			if($pagination){

      			$nop=ceil(count($r)/$pagination);
      			if($nop>1){

      			$page=$this->pagination($nop);

      			$start=$page['startrecord']*$pagination;
      			$nav=$page['pagination'];

      			$sql.=" limit $start,$pagination";
      			$r=$wpdb->get_results($sql,ARRAY_A);
      			}
      		}


      		//echo $r=$this->myres($sql,'array');
      		//$r=$wpdb->get_results($sql,ARRAY_A);
      			//echo"<pre>";
      			//print_r($r);
      			//echo"</pre>";
      		if(count($r)>0):
      			$colcount=count($r[0]);
      			$cols=array_keys($r[0]);

      			$bdr=0; $cpad=0;
      			if($border){ $bdr=$border; 	$cpad=5; }
      			//no of fields(cols)

      			$t="<div class=\"$class\">\n";
      			if($pagination) $t.=$nav;
      			$t.="<table width=\"100%\" border=\"$bdr\" cellspacing=\"0\" cellpadding=\"$cpad\">\n";
      			//heading
      			$t.="	<tr>\n";
      				if($optionboxes) $t.="		<th></th>\n";
      				foreach($cols as $v){
      					$t.="		<th scope=\"col\">$v</th>\n";
      				}
      				if($admincheck):
      					if($edit) $t.="		<th>&nbsp;</th>\n";
      					if($delete)	$t.="		<th>&nbsp;</th>\n";
      				endif;
      			$t.="	</tr>\n";
      			//rows

      			foreach($r as $v){
      				$t.="  <tr>\n";
      				if($optionboxes) $t.="		<th><input type=\"checkbox\" name=\"djckbox[]\" value=\"".$v[id]."\" /></th>\n";
      				foreach($v as $x){
      					$t.="		<td>$x</td>\n";
      				}
      				if($admincheck):
      					if($edit) {
      					$lnk=stristr($edit,'?')?'&':'?';
      					$t.="		<td><a href=\"$edit".$lnk."id=".$v[id]."\">

      					<img src=\"".$this->pluginurl."/images/dj/16/edit.png\" alt=\"\" title=\"\" />
      					</a></td>\n";
      					}

      					if($delete)	{
      					$lnk=stristr($edit,'?')?'&':'?';
      					$t.="		<td><a href=\"$delete".$lnk."id=".$v[id]."\">
      					<img src=\"".$this->pluginurl."/images/dj/16//delete.png\" alt=\"\" title=\"\" />

      					</a></td>\n";
      					}
      				endif;

      				$t.="  </tr>\n";
      			}

      			$t.="</table>\n";
      			$t.="</div>";
      			return $t;
      		else:
      			return "<div class=\"$class\"><div class=\"norecords\">No Records Found.</div></div>";
      		endif;
      	}


        function ds_pp_admin_scripts()
        {

            // wp_register_script('ds-google-maps', '//maps.googleapis.com/maps/api/js?key=&sensor=false');
            // wp_enqueue_script('ds-google-maps');
        }
    }//class ends


?>
