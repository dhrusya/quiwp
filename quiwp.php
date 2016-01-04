<?php
/*
* Name: QuiWP 
* Description: QuiWP: Quick Wordpress Plugins is a php class with collections of functions and controls used to make wordpress plugins quick and easy. 
* Version: 0.1
* Author: Venu Gopal Chaladi, Sushma Sreemantula
* URL: http://www.dhrusya.com
* Author Email: gopal@dhrusya.com, sushma@dhrusya.com
*/
	class dswp{
		public $taxdata='';
		public $posttypemeta='';
		public $taxonomy;

		public function __constructor(){
		

		}

/********************************START CREATING NEW CUSTOM PAGE*******************************/
	public function newPagetype($atts){
    	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}



/********************************END CREATING NEW CUSTOM PAGE*******************************/
/********************************CREATING NEW CUSTOM POST TYPE*******************************/

    	public function newPosttype($atts){
    		extract($atts); //posttype,name,puralname,icon,notsupported
    			$name=strtolower($name);
    			$singlelabel=ucfirst($name);
    			$puralname=ucfirst($puralname);
    			$icon=!empty($icon)?$icon:'';
    			$array= array('title','editor','thumbnail');
    			$supports=$array;
    			if(!empty($notsupported) and is_array($notsupported)){
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
					'menu_icon' => $icon,
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'supports' =>$supports
				  );
				register_post_type( $posttype , $args );
    	}//newposttype ends

/********************************CREATING NEW CUSTOM TAXONOMY*******************************/

    	public function newTaxonomy($atts){
    		//$atts: posttype,taxonomy,name,puralname,
    		extract($atts); 
    			//$name=strtolower($name);
    			$singlename=ucwords($name);
    			$puralname=ucfirst($puralname);
    			$showmetabox=!empty($showmetabox)?$showmetabox:true;
    			$this->taxonomy=$taxonomy;
    			if(!$showmetabox){
    				//add_action( 'wp_head',  );
    			}

				$labels = array(
					'name'              			=> _x( $puralname, 'taxonomy general name' ),
					'singular_name'    				=> _x( $puralname, 'taxonomy singular name' ),
					'search_items'     				=> __( 'Search '.$puralname ),
					'all_items'         			=> __( 'All '.$puralname ),
					'parent_item'       			=> __( 'Parent '.$singlename ),
					'parent_item_colon' 			=> __( 'Parent '.$singlename.':' ),
					'edit_item'         			=> __( 'Edit '.$singlename ),
					'update_item'       			=> __( 'Update '.$singlename ),
					'add_new_item'     			=> __( 'Add New '.$singlename ),
					'new_item_name'    			=> __( 'New '.$singlename.' Name' ),
					'menu_name'         			=> __( $puralname ),
				);
				
				$args = array(
					'hierarchical'     		 	=> true,
					'labels'            		=> $labels,
					'show_ui'           		=> true,
					'show_admin_column' 		=> false,
					'show_in_nav_menus' 		=> true,
					'query_var'        			=> true,
    				'rewrite'           		=> array( 'slug' => "$taxonomy" ),

				);

				register_taxonomy($taxonomy, $posttype, $args );

				
		
		}//newtaxonomyends

/********************************ADDING  NEW CUSTOM FIELD FOR TAXONOMY *******************************/		

		public function newTaxmeta($taxonomy, $atts){
			//$atts:array('taxonomy'=>'',array('name'=>'', label'=>'xyz','fieldtype'=>'text/select/textarea/email/image', 'value'=>'single/array'))
			$args=$atts;
			$extrafunction = 'extra_'.$taxonomy.'_fields';
			if(isset($_GET['tag_ID'])) {
				$t_id=$_GET['tag_ID']; 
				$cat_meta = get_option($taxonomy."_$t_id");
			}else{
				$cat_meta=array();
			}

			
     		$html='';

     			foreach ($args as $arr) {     				
     				extract($arr);
     				
     				//print_r($cat_meta);
     				$html.='<div class="form-field">';
     				$html.="	<label>$label</label>";
     				$fieldtype=!empty($fieldtype)?$fieldtype:"text";
     				$value=!empty($value)?$value:"";
     				$value=!empty($cat_meta["$name"])?$cat_meta["$name"]:$value;
     				switch($fieldtype):   					
     					case 'textarea':     						
     						$html.="<textarea name=\"Cat_meta['$name']\">$value</textarea>";
     					break;
     					case 'select':
     						$html.="<select name=\"Cat_meta['$name']\">";
     							foreach ($value as $k => $v) {
     								$cat_meta["$name"]=!empty($cat_meta["$name"])?$cat_meta["$name"]:'';
     								$selected=("$v"==$cat_meta["$name"])?'selected="selected"':"";
     								$html.="<option value=\"$v\" $selected>$k</option>";
     							}
     						$html.="</select>"; 
     					break;
     					case 'image':
     						$html.="";
     					break;
     					default:
     					$html.="<input type=\"$fieldtype\" name=\"Cat_meta['$name']\" value=\"$value\" >";
     				endswitch;
     				$html.='</div>';

     			}//end atts
     		$this->taxdata=$html;

			add_action ($taxonomy.'_edit_form_fields', function(){ echo $this->taxdata;}, 10, 2);
			add_action($taxonomy.'_add_form_fields',function(){ echo $this->taxdata;}, 10, 2);
			add_action('created_'.$taxonomy, array ($this,'ds_savetaxmeta'), 10, 2);
			add_action ('edited_'.$taxonomy, array ($this,'ds_savetaxmeta'), 10, 2);
			
		}

/********************************SAVING NEW CUSTOM FIELD FOR TAXONOMY*******************************/

		function ds_savetaxmeta($term_id){
			if ( isset( $_POST['Cat_meta'] ) ) {
			 	$taxonomy=$_POST['taxonomy'];
		        $t_id = $term_id;
		        //$t_id = $_POST['tag_ID'];
		        $cat_meta = get_option( "Cat_$t_id");
		        $cat_keys = array_keys($_POST['Cat_meta']);
		        foreach ($cat_keys as $key){
		            if (isset($_POST['Cat_meta'][$key])) $cat_meta[$key] = $_POST['Cat_meta'][$key];
		        }
		    	update_option( $taxonomy."_$t_id", $cat_meta );    
    		}
		}


/***************************CREATING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE**************************/

		function ds_custompostmeta($atts){
			//$atts:postype,priority,postion, label,fields,
			//fields: label,name,fieldtype,value,required,multiple			
			foreach($atts as $box=>$boxdata){
				extract($boxdata);
				if(empty($posttype)) die("Posttype not defined");
				$position=isset($position)?$position:"low";
				$priority=isset($priority)?$priority:"normal";
				$this->posttypemeta=$fields;

				add_meta_box("ds_".$posttype."_block".$box, "$label", function(){
					$html='<div class="dswp_content_block">';
					foreach($this->posttypemeta as $label=>$fdata){
						extract($fdata);
						$html.='<div class="form-field">';
	     				$html.="	<label>$label :</label>";
	     				$fieldtype=!empty($fieldtype)?$fieldtype:"text";
	     				$required=!empty($required)?' required':'';
						$id=!empty($id)?' id='.$id:'';
	     				if(isset($_GET['post']) and isset($_GET['action'])){
							$postid=$_GET['post'];
							$value=get_post_meta($postid, "$name", true );

						}else{
							$value=!empty($value)?$value:'';
						}

						switch($fieldtype):	     					
	     					case 'textarea':
	     						$html.="<textarea name=\"ds_pmeta[$name]\" $id $required>$value</textarea>";
	     					break;
	     					case 'select':
	     						$multiple=!empty($multiple)?' multiple':'';
	     						$html.="<select name=\"ds_pmeta[$name]\" $id $required $multiple>";
	     						//print_r($value);
	     						$html.="<option value=\"\">Select Any</option>";
	     							foreach ($options as $k => $v) {
	     								$selected=($k==$value)?'selected="selected"':"";
	     								$html.="<option value=\"$k\" $selected>$v</option>";
	     							}
	     						$html.="</select>"; 
	     					break;
							case 'rte':
								$editor_id=$name;
								$args=is_array($args)?$args:array();
								ob_start();
								wp_editor( $value, $editor_id, $args );
								$html.=ob_get_contents();
								ob_clean();
								
							break;
	     					case 'image':
	     						$html.="<div class=\"ds_field_image\"><input type=\"url\" name=\"ds_pmeta[$name]\" id=\"".$name."_meta_image\" value=\"$value\" placeholder=\"paste url\" $required> 
								<input type=\"button\" name=\"".$name."_meta_image_button\" id=\"".$name."_meta_image_button\" class=\"dswp_upload_button\" value=\"Upload\"> </div>";
	     					break;
	     					case 'taxonomy':
	     						$args['echo']=0;
	     						$args['name']="tax_input[".$args['taxonomy']."][]\" autocomplete=\"off";
								$selectedoptions=get_the_terms( $postid, $args['taxonomy'] );
								//$ds_location=$locations[0]->term_id;
								//$selected=in_array();
								
								$args['selected']=$selectedoptions[0]->term_id;
								
	     						//$args=if(empty($args)) die("send the arguments as '\$args'\ array");
	     						$html.=wp_dropdown_categories( $args );
								if($fetch_subitems and !empty($selectedoptions[0]->term_id)){
									$args['child_of']=$selectedoptions[0]->term_id;
									$args['selected']=$selectedoptions[1]->term_id;
									$args['name']=$fetch_subitems;
									$args['id']=$fetch_subitems;
									$html.="<span id=\"ds_subcats\">".wp_dropdown_categories( $args )."</span>";
								}
	     					break;
	     					case 'users':
	     						$args['echo']=0;
	     						$args['name']="ds_pmeta[$name]";
								$args['selected']=$value;
	     						$html.=wp_dropdown_users( $args );
	     					break;
							case 'map':
								$ds_latitude = get_post_meta($postid, "ds_latitude", true );
	 							$ds_longitude = get_post_meta($postid, "ds_longitude", true );
								$html.="<div class=\"uk-grid\">
											<div class=\"uk-width-1-1\">
												<div id=\"map_canvas\">
												
												</div>
											</div>    
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Latitude:</dt>
														<dd><input type=\"text\" name=\"ds_pmeta[ds_latitude]\" id=\"ds_latitude\"  value=\"<?php echo $ds_latitude;?>\"  /></dd>
											  </dl>
											</div>
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Longitude:</dt>
													 <dd><input type=\"text\" name=\"ds_pmeta[ds_longitude]\"  id=\"ds_longitude\" value=\"<?php echo $ds_longitude;?>\"  /></dd>
											  </dl>
											</div>";
								$html.="<script type=\"text/javascript\">";
								$html.="jQuery(document).ready(function(e) {";
               
									    if(!empty($ds_latitude) and !empty($ds_longitude)):
										$html.="mapLoad( $ds_latitude, $ds_longitude );";
										else: 
										 $html.="mapLoad();";
										endif;
											$html.= 'jQuery("#'.$area_field.'").change(function(e){
												var $mainCat=jQuery(this).val();
												jQuery("#ds_areablock").empty();
													jQuery.ajax({
														url:"/wp-admin/admin-ajax.php",
														type:"POST",
														data:"action=frontend_area_action&main_catid=" + $mainCat,
														success:function(results){
															jQuery("#ds_areablock").html(results);
														}
													});
											 });
										});';
								$html.="</script>";
								break;
	     					default:
	     					$html.="<input type=\"$fieldtype\" name=\"ds_pmeta[$name]\" value=\"$value\" $id $required>";
	     				endswitch;
	     				$html.='</div>';

					}
					$html.="</div>";
					echo $html;
				}, "$posttype", "$priority", "$position");
				
			}	
			
			//add_action('save_post', array($this, 'ds_save_custompostmeta'));
			
				
			}//ends custompostmeta

/*************************SAVING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE************************/


			
		
    }//class ends
	
