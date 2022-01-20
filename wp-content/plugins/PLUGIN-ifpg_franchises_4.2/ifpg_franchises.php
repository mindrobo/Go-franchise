<?php
/*
Plugin Name: IFPG Display Franchises Plugin
Plugin URI: http://www.franchisebrokerwebsites.com
Description: Display your franchises & categories in Wordpress Widget and on a page/post
Version: 4.2
Author: IFPG, Inc.
Author URI: http://www.ifpg.org
License: GPL2
*/

class wp_my_plugin extends WP_Widget {
    private $site_url = 'https://www.franchisebrokerwebsites.com';//'http://localhost/broker-site';

    // constructor

    function __construct() {
        $widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here', 'wp_widget_plugin'));
		//$control_ops = array('width' => 400, 'height' => 300);
		parent::__construct(false, $name = __('IFPG Franchises Menu', 'wp_widget_plugin'), $widget_ops, $control_ops );
    }



    // widget form creation

    function form($instance) {



	// Check values

	if( $instance) {

	     $title = esc_attr($instance['title']);

	     $broker_id = esc_attr($instance['broker_id']);

	     $broker_secret = esc_attr($instance['broker_secret']);

	     $page_url = esc_attr($instance['page_url']);

	     $select = esc_attr($instance['select']);

	} else {

	     $title = '';

	     $username = '';

	     $password = '';

	     $page_url = '';

	     $select = '';

	}

    ?>



    <p>

    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

    </p>



    <p>

    <label for="<?php echo $this->get_field_id('broker_id'); ?>"><?php _e('Broker ID:', 'wp_widget_plugin'); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id('broker_id'); ?>" name="<?php echo $this->get_field_name('broker_id'); ?>" type="text" value="<?php echo $broker_id; ?>" />

    </p>



    <p>

    <label for="<?php echo $this->get_field_id('broker_secret'); ?>"><?php _e('Broker Secret:', 'wp_widget_plugin'); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id('broker_secret'); ?>" name="<?php echo $this->get_field_name('broker_secret'); ?>" type="password" value="<?php echo $broker_secret; ?>" />

    </p>

    

    <p>

    <label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _e('Franchises Page URL:', 'wp_widget_plugin'); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" type="text" value="<?php echo $page_url; ?>" />

    </p>

    

    <p>

    <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Menu Type', 'wp_widget_plugin'); ?></label>

    <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">

    <?php

    $options = array('List', 'Dropdown');

    foreach ($options as $option) {

    echo '  <option value="' . $option . '" id="' . $option . '"', $select == $option ? ' selected="selected"' : '', '>', $option, '</option>';

    }

    ?>

    </select>

    </p>

    <?php

    }



    // update widget

    function update($new_instance, $old_instance) {
		//$instance = $old_instance;
		$instance                   = array();
		// Fields
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['broker_id'] 		= strip_tags($new_instance['broker_id']);
		$instance['broker_secret'] 	= strip_tags($new_instance['broker_secret']);
		$instance['page_url'] 		= strip_tags($new_instance['page_url']);
		$instance['select'] 		= strip_tags($new_instance['select']);

		return $instance;
    }



    // display widget

    function widget($args, $instance) {
		extract( $args );
		// these are the widget options
		$title 	= apply_filters('widget_title', $instance['title']);
		$json 	= wp_remote_get("$this->site_url/industry/wp_feed/".$instance['broker_id']."/".sha1($instance['broker_secret']));
		
		// Get tweets into an array.

		$franchise_categories = json_decode($json['body'], true);



		$text = $instance['text'];

		$textarea = $instance['textarea'];

		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';



       // Check if title is set

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

       //var_dump($instance);

       if ( $instance['select'] == 'List' ) {

	    echo '<ul class="franchises">';

	    



	    foreach($franchise_categories as $item){			

		 echo '<li>

	    <a href="'.get_site_url().'/'.$instance['page_url'].'?industry='.$item["industry_url_name"].'">'.$item["industry_name"].'</a>



	    </li>';

	    }

	    echo '</ul>';

       } else {

	   echo '<select class="franchises" id="franchise_categories_select">';

	    echo '<option value="">Select Category</option>';

	    foreach($franchise_categories as $item){

		$selected = '';

		if($_GET["industry"] && $_GET["industry"] == $item["industry_url_name"]) {

		    $selected = ' selected="selected"';

		}

		echo '<option value="'.get_site_url().'/'.$instance['page_url'].'?industry='.$item["industry_url_name"].'"'.$selected.'>'.$item["industry_name"].'</option>';

	    }

	    echo '</select>';

	    echo "

	    <script>

    jQuery(function(){

      // bind change event to select

      jQuery('#franchise_categories_select').on('change', function () {

          var url = jQuery(this).val(); // get selected value

          if (url) { // require a URL

              window.location = url; // redirect

          }

          return false;

      });

    });

</script>";

       }

       /*

       // Check if text is set

       if( $text ) {

	  echo '<p class="wp_widget_plugin_text">'.$text.'</p>';

       }

       // Check if textarea is set

       if( $textarea ) {

	 echo '<p class="wp_widget_plugin_textarea">'.$textarea.'</p>';

       }



	// Get $select value

	if ( $select == 'lorem' ) {

		echo 'Lorem option is Selected';

		} else if ( $select == 'ipsum' ) {

		echo 'ipsum option is Selected';

		} else {

		echo 'dolorem option is Selected';

	}*/



       echo '</div>';

       echo $after_widget;

    }

}



function list_franchises($username = '', $password = '', $industry_url = '', $page_url = 'franchises', $num_franchises = false) {



    global $ifpg_franchises_options;

	

    $transName = $username.'list-franchises-'.$industry_url; // Name of value in database.

    $cacheTime = 10; // Time in minutes between updates.

    $per_page = 9;

    $site_url = 'https://www.franchisebrokerwebsites.com'; //'http://localhost/broker-site';//

    //var_dump("$site_url/company/wp_feed/$username/".sha1($password)."/".$industry_url);
//die();

    if(false === ($twitterData = get_transient($transName) ) ){

	$json = wp_remote_get("$site_url/company/wp_feed/$username/".sha1($password)."/".$industry_url);
	// Get tweets into an array.

	$twitterData = json_decode($json['body'], true);

	// Save our new transient.

	set_transient($transName, $twitterData, 60 * $cacheTime);	

    } 

    $total_franchises = count($twitterData);

	if ($username == '') {

		echo '<li>';

		echo 'Broker not configured';

		echo '</li>';

	} else {

		

		if(empty($twitterData) || isset($twitterData['error'])){

		    echo '<a href="'.$site_url.'" title="ACME Franchise Brokers">ACME Franchise Brokers</a>';

		} else {

		    $i=1;

		    echo    '<style>

				.franchise_mp {

				    float: left;  

				    margin: 0 3% 0 0; 

				    padding: 0 0 20px;

				    width: 30%; 

				    min-width: 160px;

				    height: 280px;

				    }

				 .franchise_mp h5 {   

				    margin: 10px 0; 

				    float: left;

				    }

				 .franchise_mp p {   

				    line-height: 130%;

				    float: left;

				    }

				.ifpg_featured_franchises_widget .franchise_mp {

				    width: 100%; 

				    height: auto;

				   }

				.ifpg_featured_franchises_widget .franchise_mp h5 {

				    width: 100%;

				    text-align: center;

				    }

				.btn {

				    margin-top: 10px;

				    }

				@media only screen and (max-width : 979px) {

				    .franchise_mp {

					width: 45%;

					margin: 0 5% 0 0;

					}

				    .ifpg_featured_franchises_widget .franchise_mp {

					width: 80%;

					margin: 0 10%;

					height: auto;

					}

				}   

				@media only screen and (max-width: 480px) { 

				    .franchise_mp, .ifpg_featured_franchises_widget .franchise_mp {

					width: 80%;

					margin: 0 10%;

					height: auto;

					}

					

				}

			    </style>';	

			foreach($twitterData as $item){

			    if($_GET['fran_page'] && ($_GET['fran_page']-1)*$per_page >= $i) {

				$i++;

				continue;

			    }

			    

					//var_dump("$site_url/company/wp_feed/$username/".sha1($password)."/".$industry_url);

					$msg = $item['text'];

					$permalink = 'http://twitter.com/#!/'. $username .'/status/'. $item['id_str'];

					if($encode_utf8) $msg = utf8_encode($msg);

					$link = get_site_url().'/'.$page_url.'?company='.$item["company_url_name"].'" id="'.$item["company_id"];

					$style = '';

					

					

					 echo '<div class="franchise_mp">

<a href="'.$link.'" style=" width: 100%; text-align: center; float: left; "><img src="'.$site_url.'/logos/'.$item["mlogo"].'" alt="'.$item["company_name"].'" width="144" height="65"></a>



<h5 style="">'.$item["company_name"].'</h5>';

					 if ($num_franchises === false) {

echo '<p>'.$item["description"].'<br />

    <a href="'.$link.'" title="'.$item["company_name"].'" class="btn request_info_btn btn-primary" style="float: left;" role="button">Request Info</a></p>';

					 }

    

echo '</div>';



			          if ($hyperlinks) { 	$msg = hyperlinks($msg); }

			          if ($twitter_users)  { $msg = twitter_users($msg); }

			          								

			          //echo $msg;

              

			        if($update) {				

			          $time = strtotime($item['created_at']);

			          

			          if ( ( abs( time() - $time) ) < 86400 )

			            $h_time = sprintf( __('%s ago'), human_time_diff( $time ) );

			          else

			            $h_time = date(__('Y/m/d'), $time);

			

			          //echo sprintf( __('%s', 'twitter-for-wordpress'),' <span class="twitter-timestamp"><abbr title="' . date(__('Y/m/d H:i:s'), $time) . '">' . $h_time . '</abbr></span>' );

			         }          

                  

					//echo '</li>';

				

					$i++;

					if ($num_franchises && $i > $num_franchises ) {

					    break;

					} else if($_GET['fran_page'] && $i > $_GET['fran_page']*$per_page || !$_GET['fran_page'] && $i > $per_page) {

					    break;

					}

			}



			if($num_franchises === false && $total_franchises > $per_page) {

			    $pages = ceil($total_franchises/$per_page);

			    echo '<div class="paging">';

			    for($p=1; $p <= $pages; $p++) {

				if($_GET['fran_page']*1 == 0 && $p == 1 || $_GET['fran_page']*1 == $p) {

				    echo '&nbsp;<strong>'.$p.'</strong>&nbsp;';

				} else {

				    echo '<a href="'. get_site_url().'/'.$page_url.'?industry='.$industry_url.'&fran_page='.$p.'">'.$p.'</a>&nbsp;';

				}

				    

			    }

			    echo '</div>';

			}

		}

	}

	

	//echo '</ul>';

	

}



function list_company($username = '', $password = '', $company_name = '') {

    global $ifpg_franchises_options;

	

    $transName = $username.'list-company-'.$company_name; // Name of value in database.

    $cacheTime = 60; // Time in minutes between updates.

    $site_url = 'https://www.franchisebrokerwebsites.com';//'http://localhost/broker-site'

    

    if(false === ($json = get_transient($transName) ) ){

	// Get the tweets from Twitter.

	$json = wp_remote_get("$site_url/company/wp_feed/$username/".sha1($password)."/company/".$company_name);



	// Save our new transient.	

	set_transient($transName, $json, 60 * $cacheTime);

    } 

    // Get tweets into an array.

    $company = json_decode($json['body'], true);

    ///var_dump($json, "$site_url/company/wp_feed/$username/".sha1($password)."/company/".$company_name);		

	//echo '<ul class="twitter">';

	

	if ($username == '') {

		echo '<li>';

		echo 'Broker not configured';

		echo '</li>';

	} else {

		//var_dump($company);

		if(empty($company) || isset($company['error'])){

			echo '<a href="'.$site_url.'" title="ACME Franchise Brokers">ACME Franchise Brokers</a>';

		} else {

		    echo    '<style>

				.franchise_highlights h3, .franchise_highlights p {

				    line-height: 35px;

				    margin: 0;

				    padding: 0 25px;

				    border-bottom: 1px solid #fff;

				    font-size: 18px;

				}

				.franchise_highlights h3 {

				    background: #8F969E;

				    color: #fff;

				}

				.franchise_highlights p {

				    background: #EEEEEE;

				    color: #666666;

				    font-size: 15px;

				}

				.free_info {

				    color: #A80000;

				    font-size: 14px;

				    font-weight: bold;

				    text-align: center;

				    margin-top: 30px;

				}

				.freeinfo_form_item {

				    float: left;

				    padding: 5px 0;

				    width: 100%;

				}

				#requestInfo label {

				    display: block;

				    float: left;

				    width: 30%;

				}

				select {

				    border: 1px solid #ccc;

				    border-radius: 3px;

				    font-family: inherit;

				    padding: 6px;

				    padding: 0.428571429rem;

				    font: 12.6667px Arial;

				}

				p.warning {

				    background-color: #ffcaca;

				    border: 1px solid #eb8d8d;

				    color: #da3838;

				    padding: 5px 10px;

				}

			    </style>';

		    

		    echo    '<div class="fran_profile_head">

				<img src="'.$site_url.'/logos/'.$company["logo"].'" alt="'.$company["company_name"].'" width="320" />

				

				

				<div class="clear"></div>

			    </div>';

		    echo    '<h1>'.$company["company_name"].'</h1>

			    <div class="franchise_profile">'.$company["profile"].'</div>';

		    echo    '<div class="franchise_highlights_heading">

				<h1><img src="'.$site_url.'/logos/'.$company["mlogo"].'" alt="'.$company["company_name"].'" style="float: left; margin-right: 20px;" />'.

				    $company["company_name"].'<br />Investment Information</h1>

			    </div>';

		    echo    '<div class="franchise_highlights">

				<h3>Total Investment</h3>

				<p><a href="'.$_SERVER['REQUEST_URI'].'#requestinfo">Click here to request more information</a></p>

				<h3>Cash Investment</h3>

				<p><a href="'.$_SERVER['REQUEST_URI'].'#requestinfo">Click here to request more information</a></p>

				<h3>Business Type</h3>

				<p>'.$company["business_type"].'</p>';
//$'.$company["total_capital"].($company["company_id"]==1713?'*':'').

//				($company["total_capital_max"]?' - $'.$company["total_capital_max"]:'').'

				//$'.$company["liquid_capital"].($company["company_id"]==1710?' minimum':'').'

		    if($company["date_started"]) {

			echo    '<h3>In Business Since</h3>

			<p>'.$company["date_started"].'</p>';

		    }

		    if($company["financing"]) {

			echo    '<h3>Financing</h3>

			<p>'.$company["financing"].'</p>';

		    }

		    if($company["training_support"]) {

			echo    '<h3>Training &amp; Support</h3>

			<p>'.$company["training_support"].'</p>';

		    }

		    echo    '</div>';

		    

		    if(isset($_GET['thank_you'])) {

			echo '<h3 class="free_info">Thank you for requesting information about '.$company["company_name"].'!</h3>

			    <p>We will contact you shortly to provide information about this opportunity.</p>';

		    } else {

			echo '<h3 class="free_info">Receive FREE information <br>SUBMIT the Request Info Form below.</h3>';

			echo '<div class="free_info_form_new img-rounded">

	<a name="requestinfo"></a>

	<form name="requestInfo" id="requestInfo" method="post" action="'.$site_url.'/company/post_multiple_leads">

	    <input type="hidden" name="broker_franchise_id" value="'.$username.'">

	    <input type="hidden" name="return_url" value="'.get_site_url().$_SERVER['REQUEST_URI'].'">

	    <input type="hidden" name="company_ids[]" value="'.$company["company_id"].'">

	    <p id="summary" class="warning" style="display: none;">&nbsp;</p>

	    <div class="column first">

		<div class="freeinfo_form_item two">

		    <label><span>*</span> First/Last Name</label>

		    <input type="text" name="fname" id="fname" value="" placeholder="First Name">

		    <input type="text" name="lname" id="lname" value="" placeholder="Last Name">

		    <div class="clear"></div>

		</div>

		<div class="freeinfo_form_item">

		    <label><span>*</span> Email Address</label>

		    <input type="text" name="email" id="email" value="" placeholder="Email Address">

		</div>

		<div class="freeinfo_form_item">

		    <label><span>*</span> Phone</label>

		    <input type="text" name="phone" id="phone" value="" placeholder="Phone">

		</div>       

	    </div>

	    <div class="column">

		<div class="freeinfo_form_item">

		    <label><span>*</span> Street Address</label>

		    <input type="text" name="address" id="address" value="" placeholder="Street Address">

		</div>

		<div class="freeinfo_form_item">

		    <label><span>*</span> Zip</label>

		    <input type="text" name="zip" id="zip" value="" placeholder="Zip">

		</div>

		<div class="freeinfo_form_item hidden_zip">

		    <label><span>*</span> City</label>

		    <input type="text" name="city" id="city" value="" placeholder="City">

		</div>

		<div class="freeinfo_form_item hidden_zip">

		    <label><span>*</span> State</label>

		    <select name="zip_state" id="zip_state">

			<option value="">Select a State</option>

		    <option value="AK">Alaska</option>

		    <option value="AL">Alabama</option>

		    <option value="AS">American Samoa</option>

		    <option value="AZ">Arizona</option>

		    <option value="AR">Arkansas</option>

		    <option value="CA">California</option>

		    <option value="CO">Colorado</option>

		    <option value="CT">Connecticut</option>

		    <option value="DE">Delaware</option>

		    <option value="DC">District of Columbia</option>

		    <option value="FM">Federated States of Micronesia</option>

		    <option value="FL">Florida</option>

		    <option value="GA">Georgia</option>

		    <option value="GU">Guam</option>

		    <option value="HI">Hawaii</option>

		    <option value="ID">Idaho</option>

		    <option value="IL">Illinois</option>

		    <option value="IN">Indiana</option>

		    <option value="IA">Iowa</option>

		    <option value="KS">Kansas</option>

		    <option value="KY">Kentucky</option>

		    <option value="LA">Louisiana</option>

		    <option value="ME">Maine</option>

		    <option value="MH">Marshall Islands</option>

		    <option value="MD">Maryland</option>

		    <option value="MA">Massachusetts</option>

		    <option value="MI">Michigan</option>

		    <option value="MN">Minnesota</option>

		    <option value="MS">Mississippi</option>

		    <option value="MO">Missouri</option>

		    <option value="MT">Montana</option>

		    <option value="NE">Nebraska</option>

		    <option value="NV">Nevada</option>

		    <option value="NH">New Hampshire</option>

		    <option value="NJ">New Jersey</option>

		    <option value="NM">New Mexico</option>

		    <option value="NY">New York</option>

		    <option value="NC">North Carolina</option>

		    <option value="ND">North Dakota</option>

		    <option value="MP">Northern Mariana Islands</option>

		    <option value="OH">Ohio</option>

		    <option value="OK">Oklahoma</option>

		    <option value="OR">Oregon</option>

		    <option value="PW">Palau</option>

		    <option value="PA">Pennsylvania</option>

		    <option value="PR">Puerto Rico</option>

		    <option value="RI">Rhode Island</option>

		    <option value="SC">South Carolina</option>

		    <option value="SD">South Dakota</option>

		    <option value="TN">Tennessee</option>

		    <option value="TX">Texas</option>

		    <option value="UT">Utah</option>

		    <option value="VT">Vermont</option>

		    <option value="VI">Virgin Islands</option>

		    <option value="VA">Virginia</option>

		    <option value="WA">Washington</option>

		    <option value="WV">West Virginia</option>

		    <option value="WI">Wisconsin</option>

		    <option value="WY">Wyoming</option>

		    <option value="No">Not USA or Canada</option>

		    <option value="AB">Canada: Alberta</option>

		    <option value="BC">Canada: British Columbia</option>

		    <option value="MB">Canada: Manitoba</option>

		    <option value="NB">Canada: New Brunswick</option>

		    <option value="NF">Canada: Newfoundland</option>

		    <option value="NT">Canada: Northwest Territories</option>

		    <option value="NS">Canada: Nova Scotia</option>

		    <option value="NU">Canada: Nunavut</option>

		    <option value="ON">Canada: Ontario</option>

		    <option value="PE">Canada: Prince Edward Island</option>

		    <option value="PQ">Canada: Quebec</option>

		    <option value="SK">Canada: Saskatchewan</option>

		    <option value="YK">Canada: Yukon</option>

		    </select>	

		</div>

	    </div>

	    <div class="column">			            

		<div class="freeinfo_form_item">

		    <label><span>*</span> Liquid Capital</label>

		    <select name="capital" id="capital">

			<option value="">Select Liquid Capital</option>

			<option value="1">Less than $25,000</option>

			<option value="14">$25,000 - $50,000</option>

			<option value="11">$50,000 - $100,000</option>

			<option value="4">$100,000 - $250,000</option>

			<option value="17">More than $250,000</option>

		    </select>

		</div>

		<div class="freeinfo_form_item">

		    <label><span>*</span> Timeframe</label>

		    <select name="timeframe" id="timeframe">

			<option value="">Select Timeframe</option>

			<option value="1-3 Months">1-3 Months</option>

			<option value="3-6 Months">3-6 Months</option>

			<option value="6-12 Months">6-12 Months</option>

		    </select>

		</div>

		<div class="freeinfo_form_item">

		    <label>Comments</label>

		    <textarea name="comments" id="comments" cols="30" rows="4" placeholder="Comments"></textarea>



		</div>

		<div class="freeinfo_form_item">

		    <label>&nbsp;</label>

		    <input type="submit" class="submit_btn btn btn-large" value="Submit" />

		</div>



	    </div>

	    <div id="franchise_selector">

		    </div>

	</form>

    </div>';





			echo '

			    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>

			    <script>

			    if (!jQuery.validator) {

					// $.validator is defined

					console.log("Validator not defined.");

			    }

			    jQuery(document).ready(function(){

				jQuery("#summary").hide();

				jQuery("#requestInfo").validate({

				    errorLabelContainer:"#summary",

				    showErrors: function(errorMap, errorList) {

					    if(errorList.length)

					    {

						    jQuery("#summary").html(errorList[0]["message"]);

						    jQuery("#summary").show();

					    }



				    },

				    rules: {

					    fname: "required",

					    lname: "required",

					    email: {

						    required: true,

						    email: true

					    },

					    phone: "required",

					    address: "required",

					    zip: {

						    required: true,

						    minlength: 4

					    },

					    capital: "required",

					    timeframe: "required",

				    //	state: "required",							

				    //	agree: "required"

				    },

				    messages: {

					    fname: "Please enter your firstname.",

					    lname: "Please enter your lastname.",

					    email: "Please enter a valid email address.",

					    address: "Please enter your address.",

					    zip: {

						    required: "Please enter your ZIP code.",

						    minlength: "The ZIP code must consist of at least 4 characters."

					    },

					    capital: "Please select a liquid capital amount.",

					    timeframe: "Please select a timeframe.",

					    //agree: "Please accept our FH <br>Business Buyer User Agreement."

				    }

				});

			    });

			    </script>';



			/*,

				    submitHandler: function(form) {

					jQuery.ajax({

					    type: "POST",

					    url: "'.$site_url.'/company/post_multiple_leads",

					    data: jQuery( this ).serialize(),

					    success: function() {

						jQuery("#summary").hide();

						jQuery("#requestInfo").html("<h3>Thank you for requesting information about '.$company["company_name"].'!</h3>")

						.append("<p>We will contact you shortly to provide information about this opportunity.</p>");

					    }

					});

					return false;

				    }*/

		    }

		}

	}



		          

	

}

class IFPG_Featured extends WP_Widget {
    // constructor
    function IFPG_Featured() {
        $widget_ops = array('classname' => 'ifpg_featured_franchises_widget', 'description' => __('Displays Featured Franchises in the Sidebar', 'IFPG_Featured'));
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false, $name = __('IFPG Featured Franchises Sidebar', 'IFPG_Featured'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );   
        $title = apply_filters('widget_title', $instance['title'] );
		$instance_my_plugin = new wp_my_plugin();

		$options = array_pop($instance_my_plugin->get_settings());

        echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';

        if ( $title )
            echo $before_title . $title . $after_title;

		list_franchises($options['broker_id'], $options['broker_secret'], 'golden_companies', $options['page_url'], $instance['num_franchises']);  
        echo '</div>';
		echo $after_widget;
    }



    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

		$instance['title'] 			= strip_tags( $new_instance['title'] );
        $instance['num_franchises'] = strip_tags( $new_instance['num_franchises'] );

        return $instance;
    }

    function form( $instance ) {



        $defaults = array( 'num_franchises' => __('5', 'src'));

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->

	<p>

            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>

            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width: 100%;" />

        </p>

        <p>

            <label for="<?php echo $this->get_field_id( 'num_franchises' ); ?>"><?php _e('Number of franchises:', 'hybrid'); ?></label>

            <input id="<?php echo $this->get_field_id( 'num_franchises' ); ?>" name="<?php echo $this->get_field_name( 'num_franchises' ); ?>" value="<?php echo $instance['num_franchises']; ?>" style="width: 10%;" />

        </p>        



    <?php

    }

}



function ifpg_franchises($args, $number = 1) {

    //add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));

/*    global $ifpg_franchises_options;



    //extract($args);

    // Each widget can store its own options. We keep strings here.

    include_once(ABSPATH . WPINC . '/rss.php');

    $options = get_option('widget_twitter');

    $w=get_option('wp_my_plugin-2"');*/

    

    $instance = new wp_my_plugin();

    $options = array_pop($instance->get_settings());





    if($industry_url = $_GET["industry"]) {    

	list_franchises($options['broker_id'], $options['broker_secret'], $industry_url, $options['page_url']);

    } else if($company_name = $_GET["company"]) {    

	list_company($options['broker_id'], $options['broker_secret'], $company_name);

    } else {

	list_franchises($options['broker_id'], $options['broker_secret'], 'featured', $options['page_url']);

    }

}







function src_load_widgets() {
    register_widget( 'wp_my_plugin' );
    register_widget( 'IFPG_Featured' );
}



function ifpg_query_vars($query_vars) {

    $query_vars[] = 'company';

    return $query_vars;

}



function ifpg_rewrite_rule(){

    

    add_rewrite_rule(

        'wordpress/franchises/company/([a-zA-Z0-9_-]+)$',

        'wordpress/franchises/?company=$matches[1]',

        'top'

    );

    

}
add_action( 'init', 'ifpg_rewrite_rule' );

add_filter('query_vars', 'ifpg_query_vars');

// register widget
//add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));

add_action('widgets_init', 'src_load_widgets');

add_shortcode('ifpg_franchises', 'ifpg_franchises');

?>