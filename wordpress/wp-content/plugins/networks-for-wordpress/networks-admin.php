<?php

define( 'WP_NETWORKS_PER_PAGE_DEFAULT', 10 );

class wp_Networks_Admin
{
	
	var $admin_page;
	var $admin_screen;
	var $admin_settings_page;
	
	var $slug = 'networks';
	var $listPage;
	var $sitesPage;
	
	function wp_Networks_Admin() {

		/** load localization files if present */
		if( file_exists( dirname( __FILE__ ) . '/' . dirname(plugin_basename(__FILE__)) . '-' . get_locale() . '.mo' ) ) {
			load_plugin_textdomain( 'njsl-networks', false, dirname(plugin_basename(__FILE__)) );
		} else if ( file_exists( dirname( __FILE__ ) . '/' . get_locale() . '.mo' ) ) {
			_doing_it_wrong( 'load_textdomain', 'Please rename your translation files to use the ' . dirname(plugin_basename(__FILE__)) . '-' . get_locale() . '.mo' . ' format', '1.0.9' );
			load_textdomain( 'njsl-networks', dirname( __FILE__ ) . '/' . get_locale() . '.mo' );
		}

		add_action( 'network_admin_menu', array(&$this, 'networks_admin_menu') );
		add_filter( 'manage_sites_action_links' , array(&$this,'add_blogs_link'), 10, 3 );

		/** Compatibility with older releases */
		add_action( 'admin_menu', array(&$this, 'networks_admin_menu') );
		if( ! has_action('manage_sites_action_links') ) {
			add_action( 'wpmublogsaction', array(&$this,'assign_blogs_link') );
		}
			
		wp_register_script( 'njsl_networks_admin_js', plugins_url('/_inc/admin.js', __FILE__), array('jquery') );
		wp_register_style( 'njsl_networks_admin_css', plugins_url('/_inc/admin.css', __FILE__) );
		
		add_filter( 'set-screen-option', array( &$this, 'save_network_options' ), 10, 3 );
		
		# Add all known sitemeta descriptions to New Network page
		add_filter( 'manage_sitemeta_descriptions', array( &$this, 'add_sitemeta_descriptions' ) );
	}

	function assign_blogs_link( $blog_id = null ) {
		global $blog;
		if($blog_id == null) {
			$blog_id = $blog['blog_id'];
		}
		echo '<a href="' . $this->listPage . '&amp;action=move&amp;blog_id=' . $blog_id . '" class="edit">' . __('Move Site','njsl-networks') . '</a>';
	}
	
	function add_blogs_link( $actions, $blog_id, $blog_name ) {
		$actions['move'] = '<a href="' . $this->listPage . '&amp;action=move&amp;blog_id=' . $blog_id . '" class="edit">' . __('Move','njsl-networks') . '</a>';
		return $actions;
	}

	function networks_admin_menu()
	{
		if(function_exists('is_network_admin')) {
			/** WP >= 3.1 */
			$this->admin_page = add_submenu_page( 'sites.php', __('Networks','njsl-networks'), __('Networks','njsl-networks'), 'manage_network_options', $this->slug, array(&$this, 'sites_page') );

			$this->listPage = 'sites.php?page=' . $this->slug;
			$this->sitesPage = 'sites.php';

		} else {
			/** WP < 3.1	*/
			$this->admin_page = add_submenu_page( 'ms-admin.php', __('Networks','njsl-networks'), __('Networks','njsl-networks'), 'manage_options', $this->slug, array(&$this, 'sites_page') );
			$this->listPage = 'ms-admin.php?page=' . $this->slug;
			$this->sitesPage = 'ms-sites.php';
		}
		
		/** Help for WP < 3.3 */
		add_contextual_help($this->admin_page, $this->networks_help());
		
		add_action( 'load-' . $this->admin_page, array(&$this,'networks_help_screen') );
		add_action( 'load-' . $this->admin_page, array(&$this,'networks_options_screen') );
		add_action( 'admin_print_scripts-' . $this->admin_page, array(&$this,'add_admin_scripts') );
		add_action( 'admin_print_styles-' . $this->admin_page, array(&$this,'add_admin_styles') );
	}
	
	function add_admin_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'njsl_networks_admin_css' );
	}
	
	function add_admin_scripts() {
		wp_enqueue_script( 'wp-pointer', false, array('jquery') );
		wp_enqueue_script( 'njsl_networks_admin_js', false, array('jquery') );
		wp_localize_script( 'njsl_networks_admin_js', 'strings', $this->localize_admin_js() );
	}
	
	function localize_admin_js() {

		$widget_text = '<h3>' . esc_js( __( 'Questions about Networks?') ) . '</h3>';
		$widget_text .= '<p>' . esc_js( __( 'Check the Help pages before you get started!' ) ). '</p>';

		// Get dismissed pointers
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

		// Pointer has been dismissed
		if ( in_array( 'networks-help', $dismissed ) ) {
			$widget_text = '';
		}

		return array(
			'checkingString'	=> __('Checking...','njsl-networks'),
			'pointerText'		=> $widget_text
		);
	}

	/** ====== config_page ====== */
	function sites_page() {

		require_once (dirname(__FILE__) . '/networks-compatibility.php');
		
		global $wpdb;
		global $options_to_copy;
		global $current_screen;

		if ( !is_super_admin() ) {
		    wp_die( '<p>' . __('You do not have permission to access this page.') . '</p>' );
		}

		if( isset( $_POST['update'] ) && isset( $_GET['id'] ) ) {
			$this->edit_site_page();
		}

		if( isset( $_POST['delete'] ) && isset( $_GET['id'] ) ) {
			$this->delete_site_page();
		}
		
		if( isset( $_POST['delete_multiple'] ) && isset( $_POST['deleted_sites'] ) ) {
			$this->delete_multiple_site_page();
		}

		if( isset( $_POST['add'] ) && isset( $_POST['domain'] ) && isset( $_POST['path'] ) ) {
			$this->add_site_page();
		}

		if( isset( $_POST['move'] ) && isset( $_GET['blog_id'] ) ) {
			$this->move_blog_page();
		}
		
		if( isset( $_POST['reassign'] ) && isset( $_GET['id'] ) ) {
			$this->reassign_blog_page();
		}

		if ( isset( $_GET['updated'] ) ) {
		    ?><div id="message" class="updated fade"><p><?php _e('Options saved.','njsl-networks') ?></p></div><?php
		} else if(isset($_GET['added'])) {
			?><div id="message" class="updated fade"><p><?php _e('Network created.','njsl-networks'); ?></p></div><?php
		} else if(isset($_GET['deleted'])) {
			?><div id="message" class="updated fade"><p><?php _e('Network(s) deleted.','njsl-networks'); ?></p></div><?php
		}

		switch( $_GET[ 'action' ] ) {
			case 'move':
				$this->move_blog_page();
				break;
			case 'assignblogs':
				$this->reassign_blog_page();
				break;
			case 'deletesite':
				$this->delete_site_page();
				break;
			case 'editsite':
				$this->edit_site_page();
				break;
			case 'delete_multisites':
				$this->delete_multiple_site_page();
				break;
			case 'verifynetwork':
				$this->verify_network_page();
				break;
		    default:
				
				/** strip off query tags */
				$queryStr = add_query_arg( array(
					'action'	=> false,
					'id'		=> false,
					'updated'	=> false,
					'deleted'	=> false,
					'search'	=> false,
					'start'		=> false,
					's'			=> false
				) );
				
				$countSearchConditions = '';
				if(isset($_GET['s'])) {
					if(isset($_GET['search']) && $_GET['search'] == __('Search Networks','njsl-networks')) {
						$countSearchConditions = ', ' . $wpdb->sitemeta . ' WHERE ' . $wpdb->site . '.domain LIKE ' . "'%" . $wpdb->escape($_GET['s']) . "%'";
						$countSearchConditions .= 'OR ( ' . $wpdb->sitemeta . '.site_id = ' . $wpdb->site . '.id AND ' . $wpdb->sitemeta . '.meta_value LIKE ' . "'%" . $wpdb->escape($_GET['s']) . "%')";
					}
				}
	
				$total = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->site . $countSearchConditions);
				
				$searchConditions = '';				
				if(isset($_GET['s'])) {
					if(isset($_GET['search']) && $_GET['search'] == __('Search Networks','njsl-networks')) {
						$searchConditions = ' WHERE ' . 'wp_site.domain LIKE ' . "'%" . $wpdb->escape($_GET['s']) . "%'";
						$searchConditions .= ' OR ' . 'sm1.meta_value LIKE ' . "'%" . $wpdb->escape($_GET['s']) . "%'";
						$searchConditions .= ' OR ' . 'sm2.meta_value LIKE ' . "'%" . $wpdb->escape($_GET['s']) . "%'";
					}
				}

	
				if( isset( $_GET[ 'start' ] ) == false ) {
					$start = 1;
				} else {
					$start = intval( $_GET[ 'start' ] );
				}
				if( isset( $_GET[ 'num' ] ) == false ) {
					$num = get_user_option( 'networks_per_page' );
					if( ! $num ) {
						$num = WP_NETWORKS_PER_PAGE_DEFAULT;
					}
				} else {
					$num = intval( $_GET[ 'num' ] );
				}
					
	//			$networks_query = "SELECT {$wpdb->site}.*, COUNT({$wpdb->blogs}.blog_id) as blogs, {$wpdb->blogs}.path as blog_path 
	//				FROM {$wpdb->site} LEFT JOIN {$wpdb->blogs} ON {$wpdb->blogs}.site_id = {$wpdb->site}.id $searchConditions GROUP BY {$wpdb->site}.id" ; 
	
				$networks_query = "
						SELECT {$wpdb->site}.*,
						sm1.meta_value as site_name,
						sm2.meta_value as site_admins,
						COUNT({$wpdb->blogs}.blog_id) as blogs,
						{$wpdb->blogs}.path as blog_path
					FROM
						{$wpdb->site}
					LEFT JOIN
						{$wpdb->blogs}
					ON
						{$wpdb->blogs}.site_id = {$wpdb->site}.id
					LEFT JOIN
						{$wpdb->sitemeta} as sm1
					ON
						sm1.meta_key = 'site_name' AND
						sm1.site_id = {$wpdb->site}.id
					LEFT JOIN
						{$wpdb->sitemeta} as sm2
					ON
						sm2.meta_key = 'site_admins' AND
						sm2.site_id = {$wpdb->site}.id
					$searchConditions
					GROUP BY {$wpdb->site}.id";

				if( isset( $_GET[ 'sortby' ] ) == false ) {
					$_GET[ 'sortby' ] = 'ID';
				}
	
			switch($_GET['sortby']) {
				case 'Domain':
					$networks_query .= ' ORDER BY ' . $wpdb->site . '.domain ';
					break;
				case 'Path':
					$networks_query .= ' ORDER BY ' . $wpdb->site . '.path ';
					break;
				case 'Network Name':
					$networks_query .= ' ORDER BY site_name ';
					break;
				case 'Blogs':
					$networks_query .= ' ORDER BY blogs ';
					break;
				case 'ID':
				default:
					$networks_query .= ' ORDER BY ' . $wpdb->site . '.id ';
					break;
			}
	
			if( $_GET[ 'order' ] == 'DESC' ) {
				$networks_query .= 'DESC';
			} else {
				$networks_query .= 'ASC';
			}
	
			$networks_query .= ' LIMIT ' . (((int)$start - 1 ) * $num ) . ', ' . intval( $num );
			
			$network_list = $wpdb->get_results( $networks_query, ARRAY_A );
			if( count( $network_list ) < $num ) {
				$next = false;
			} else {
				$next = true;
			}

?>
<div class="wrap">
	<div class="icon32" id="icon-ms-admin"><br /></div>
	<h2><?php _e ('Networks','njsl-networks') ?> <a href="<?php echo add_query_arg( array('page' => 'networks') ); ?>#form-add-network" class="button add-new-h2"><?php _e('Add New') ?></a></h2>
	<form name="searchform" action="<?php echo add_query_arg( array('page' => 'networks') ); ?>" method="get">
		<p class="search-box"> 
			<label class="screen-reader-text" for="network-search-input"><?php _e('Search Networks','njsl-networks'); ?>:</label>
			<input type="text" name="s" id="network-search-input" />
			<input type="hidden" name="page" value="networks" />
			<input type="submit" name="search" id="search" class="button" value="<?php _e('Search Networks','njsl-networks'); ?>" />
		</p>
	</form>
	<?php
	
	/** 
	 * Define the columns to display, the syntax is 'internal name' => 'display name' 
	 */
	$networks_columns = array(
	  'id'      => __( 'ID' ),
	  'site'	=> __( 'Network Name' ),
	  'domain'	=> __( 'Domain' ),
	  'path'	=> __( 'Path' ),
	  'sites'	=> __( 'Sites' ),
	  'admins'	=> __( 'Super Admins', 'njsl-networks' )
	);
	$networks_columns = apply_filters('manage_sites_columns', $networks_columns);
	$networks_columns = apply_filters('manage_networks_columns', $networks_columns);
	
	/**
	 * Define sortable columns
	 */
	$sortable_networks_columns = array(
	  'id',
	  'site',
	  'domain',
	  'path',
	  'sites'
	);
	$sortable_networks_columns = apply_filters('manage_sortable_networks_columns', $sortable_networks_columns);
	
	/** 
	 * Pagination 
	 */
	$network_nav = paginate_links( array(
		'base' => add_query_arg( 'start', '%#%' ),
		'format' => '',
		'total' => ceil($total / $num),
		'current' => $start
	));
	
	
	?>
	<form name='formlist' action='<?php echo add_query_arg( array('action' => 'delete_multisites') ); ?>' method='POST'>
		<div class="tablenav">
			<?php if ( $network_nav ) echo "<div class='tablenav-pages'>$network_nav</div>"; ?>	
			<div class="alignleft">
				<input type="submit" class="button-secondary delete" name="allsite_delete" value="<?php _e('Delete'); ?>" />
				<?php if(isset($_GET['s'])) { ?>
					<p><?php _e('Filter','njsl-networks'); ?>: <a href="<?php echo $this->listPage ?>" title="<?php _e('Remove this filter','njsl-networks') ?>"><?php echo $wpdb->escape($_GET['s']) ?></a></p>
				<?php } ?>
			</div>
		</div>
		<br class="clear" />
		<table width="100%" cellpadding="3" cellspacing="3" class="widefat"> 
			<thead>
		        <tr>
					<th class="manage-column column-cb check-column" id="cb" scope="col">
						<input type="checkbox" />
					</th>
		<?php foreach($networks_columns as $col_name => $column_display_name) { ?>
		        <th scope="col">
		        	<?php if(in_array( $col_name, $sortable_networks_columns ) ) : ?>
		        	<a href="<?php echo $this->listPage ?>&sortby=<?php echo urlencode( $column_display_name ) ?>&<?php if( $_GET[ 'sortby' ] == $column_display_name ) { if( $_GET[ 'order' ] == 'DESC' ) { echo "order=ASC&" ; } else { echo "order=DESC&"; } } ?>start=<?php echo $start ?>"><?php echo $column_display_name; ?></a>
		        	<?php else: ?>
		        	<?php echo $column_display_name; ?>
		        	<?php endif; ?>
		        </th>
		<?php } ?>
		
		        </tr>
			</thead>
		<?php
		
		if ($network_list) {
			foreach ($network_list as $blog) { 
				$network = $blog;
				$class = ('alternate' == $class) ? '' : 'alternate';
				echo '<tr class="' . $class . '">';
				if( is_subdomain_install() ) { 
					$blogname = str_replace( '.' . $current_site->domain, '', $blog[ 'domain' ] ); 
				} else { 
					$blogname = $blog[ 'path' ]; 
				}
		
				foreach($networks_columns as $column_name=>$column_display_name) {
				
				    switch($column_name) {
				
					    case 'id':
							?>
				            <th scope="row" class="check-column">
				            	<input type='checkbox' id='<?php echo $blog[ 'id' ] ?>' name='allsites[]' value='<?php echo $blog[ 'id' ] ?>'<?php if($blog['id'] == 1) echo 'disabled'; ?>>
				            </th>
				            <th scope="row" valign="top">
				            	<label for='<?php echo $blog[ 'id' ] ?>'><?php echo $blog[ 'id' ] ?></label>
				            </th>
				            <?php
				            break;
						case 'domain':
							?>
							<td valign='top'>
								<label for='<?php echo $blog[ 'id' ] ?>'><?php echo $blog['domain'] ?></label>
							</td>
							<?php
							break;
						case 'site':
							?>
							<td valign='top'>
								<label for='<?php echo $blog[ 'id' ] ?>'><?php echo $blog['site_name'] ?></label>
								<?php
								
								$actions = array(
									'assign_sites'	=> '<span class="edit"><a href="'. add_query_arg( array( 'action' => 'assignblogs', 'id' => $blog['id'] ), $queryStr ) . '" title="' . __('Assign sites to this network','njsl-networks') . '">' . __('Assign Sites','njsl-networks') . '</a></span>',
									'edit'			=> '<span class="edit"><a class="edit_network_link" href="'. add_query_arg( array( 'action' => 'editsite', 'id' => $blog['id'] ), $queryStr ) . '" title="' . __('Edit this network','njsl-networks') . '">' . __('Edit','njsl-networks') . '</a></span>',
									'verify'		=> '<span class="edit"><a class="verify_network_link" href="'. add_query_arg( array( 'action' => 'verifynetwork', 'id' => $blog['id'] ), $queryStr ) . '" title="' . __('Check this network for configuration errors','njsl-networks') . '">' . __('Verify','njsl-networks') . '</a></span>',
									'delete'		=> '<span class="delete"><a href="'. add_query_arg( array( 'action' => 'deletesite', 'id' => $blog['id'] ), $queryStr ) . '" title="' . __('Delete this network','njsl-networks') . '">' . __('Delete','njsl-networks') . '</a></span>'
								);
								
								/** Disable deleting site ID 1 */
								if( $blog[ 'id' ] == 1 ) {
									array_pop($actions);
								}
								
								?>
								<?php if ( count( $actions ) != 0 ) : ?>
								<div class="row-actions">
									<?php echo implode( ' | ', $actions ); ?>
								</div>
								<?php endif; ?>
							</td>
							<?php
							break;
						case 'path':
							?>
							<td valign='top'><label for='<?php echo $blog[ 'id' ] ?>'><?php echo $blog['path'] ?></label></td>
							<?php
							break;
						case 'sites':
							?>
							<td valign='top'><a href="http://<?php echo $blog['domain'] . $blog['blog_path'];?>wp-admin/<?php echo (strpos($this->listPage,'site') !== false) ? 'network/' . $this->sitesPage : $this->sitesPage ?>" title="<?php _e('Sites on this network','njsl-networks'); ?>"><?php echo $blog['blogs'] ?></a></td>
							<?php
							break;
						case 'admins':
							$admins = maybe_unserialize( $blog['site_admins']);
							?>
							<td valign='top'>
							<?php foreach($admins as $admin) : ?>
								<?php $this_admin = get_user_by( 'login', $admin ); ?>
								<a href="<?php echo esc_url( network_admin_url( add_query_arg( 'wp_http_referer', urlencode( stripslashes( $_SERVER['REQUEST_URI'] ) ), 'user-edit.php?user_id=' . $this_admin->ID ) ) ) ?>"><?php echo $this_admin->user_login ?></a><br />
							<?php endforeach; ?>
							</td>
							<?php
						default:
							?>
							<td valign='top'><?php do_action('manage_sites_custom_column', $column_name, $blog['id']); ?></td>
							<?php
							break;
					}
				}
			?>
				</tr>
			<?php
			}
		} else {
		?>
			<tr style=''>
				<td colspan="8"><?php _e('No matching networks were found.','njsl-networks') ?></td>
			</tr>
		<?php
		} // end if ($blogs)
		?>
		</table>
	</form>

	<h3><a name="form-add-network"></a><?php _e('Create a Network','njsl-networks'); ?></h3>
	<form method="POST" action="<?php echo add_query_arg( array('action' => 'addsite') ); ?>">
		<table class="form-table">
			<tr>
				<td>
					<table class="form-table">
						<tr><th scope="row"><label for="newName"><?php _e('Network Name','njsl-networks'); ?>:</label></th><td><input type="text" name="name" id="newName" placeholder="<?php _e('My new network', 'njsl-networks' ); ?>" title="<?php _e('A friendly name for your new Network','njsl-networks'); ?>" /></td></tr>
						<?php if( is_subdomain_install() ) : ?>
						<tr>
							<th scope="row"><?php _e( 'Network URL', 'njsl-networks' ) ?>:</th>
							<td>
								<table>
									<tr>
										<th scope="row" class="network-uri-th"><label for="newDom"><?php _e('Domain','njsl-networks'); ?>:</label></th>
										<th scope="row" class="network-uri-th"><label for="newPath"><?php _e('Path','njsl-networks'); ?>:</label></th>
									</tr>
									<tr>
										<td> http://<input type="text" name="domain" id="newDom" title="<?php _e('The domain for your new Network','njsl-networks'); ?>" /></td>
										<td><code>/</code><input type="hidden" name="path" id="newPath" value="/" /></td>
									</tr>
								</table>
							</td>
						</tr>
						<?php else : ?>
						<tr>
							<th scope="row"><?php _e( 'Network URL', 'njsl-networks' ) ?>:</th>
							<td>
								<table>
									<tr>
										<th scope="row" class="network-uri-th"><label for="newDom"><?php _e('Domain','njsl-networks'); ?>:</label></th>
										<th scope="row" class="network-uri-th"><label for="newPath"><?php _e('Path','njsl-networks'); ?>:</label></th>
									</tr>
									<tr>
										<td> http://<input type="text" name="domain" id="newDom" placeholder="host.example.com" title="<?php _e('The domain for your new Network','njsl-networks'); ?>" /></td>
										<td><input type="text" name="path" id="newPath" placeholder="/" title="<?php _e('If you are unsure, put in /'); ?>" /></td>
									</tr>
								</table>
							</td>
						</tr>
						<?php endif ; ?>
						<tr>
							<th scope="row"><label for="create_root_site"><?php _e('Create a Root Site','njsl-networks') ?>:</label></th>
							<td><input type="checkbox" name="createRootSite" id="create_root_site" checked />
							<p><?php _e('By default, a site will be created at the root of the new network.<br /> If you don\'t know what this means, leave it checked.','njsl-networks'); ?></p>
							</td>
						</tr>
						<tr><th scope="row"><label for="newBlog"><?php _e('Root Site Name','njsl-networks'); ?>:</label></th><td><input type="text" name="newBlog" id="newBlog" placeholder="<?php _e('My new site name', 'njsl-networks' ); ?>" title="<?php _e('The name for the new Network\'s root site','njsl-networks'); ?>" /></td></tr>
					</table>
				</td>
				<td style="vertical-align: top" id="new-network-preview">
					<h4>New Network Preview</h4>
					<ul>
						<li>Domain: <span id="domain_preview" /></li>
						<li>Site URL: <span id="siteurl_preview" /></li>
					</ul>
					<input type="button" name="verify_domain" id="verify_domain" value="<?php _e('Check Network Settings','njsl-networks'); ?>" />
					<div id="verifying" />
				</td>
			</tr>
		</table>
		<div class="metabox-holder meta-box-sortables" id="advanced_site_options">
		<div class="postbox if-js-closed">
			<div title="Click to toggle" class="handlediv"><br/></div>
			<h3><span><?php _e('Advanced Network Options','njsl-networks'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
				<tr>
					<th scope="row"><label for="cloneSite"><?php _e('Copy Network Options From','njsl-networks'); ?>:</label></th>
					<?php	$site_list = $wpdb->get_results( 'SELECT id, domain, ' . $wpdb->sitemeta . '.meta_value as site_name FROM ' . $wpdb->site . ' LEFT JOIN ' . $wpdb->sitemeta . ' ON ' . $wpdb->sitemeta . '.meta_key = "site_name" AND ' . $wpdb->sitemeta . '.site_id=' . $wpdb->site . '.id' , ARRAY_A );	?>
					<td colspan="2"><select name="cloneSite" id="cloneSite"><option value="0"><?php _e('Do Not Copy','njsl-networks'); ?></option><?php foreach($site_list as $site) { echo '<option value="' . $site['id'] . '"' . ($site['id'] == 1 ? ' selected' : '' ) . '>' . $site['site_name'] . ' (' . $site['domain'] . ')' . '</option>'; } ?></select></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label><?php _e('Options to Copy','njsl-networks'); ?>:</label></th>
					<td>
					</td>
					<td valign="top">
						<p><?php _e('Options added by plugins may not exist on all networks.','njsl-networks'); ?></p>
					</td>
				</tr>
				<tr>
					<td></td>
					<?php
						$all_site_options = $wpdb->get_results('SELECT DISTINCT meta_key FROM ' . $wpdb->sitemeta);
						
						$known_sitemeta_options = array();
						$known_sitemeta_options = apply_filters( 'manage_sitemeta_descriptions' , $known_sitemeta_options );
						
						$options_to_copy = apply_filters( 'manage_site_clone_options' , $options_to_copy);
						
					?>
					<td colspan="2">
						<table class="widefat">
							<thead>
								<tr>
									<th scope="col" class="check-column"></th>
									<th scope="col"><?php _e( 'Meta Key', 'njsl-networks' ); ?></th>
									<th scope="col"><?php _e( 'Description' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($all_site_options as $count => $option) { ?>
								<tr class="<?php echo $class = ('alternate' == $class) ? '' : 'alternate'; ?>">
									<th scope="row" class="check-column"><input type="checkbox" id="option_<?php echo $count; ?>" name="options_to_clone[<?php echo $option->meta_key; ?>]"<?php echo (in_array($option->meta_key,$options_to_copy) ? ' checked' : '' ); ?> /></th>
									<td><label for="option_<?php echo $count; ?>" title="<?php echo $option->meta_key ?>"><?php echo ( strlen($option->meta_key) > 35 ? substr($option->meta_key, 0, 35) . '...' : $option->meta_key ); ?></label></td>
									<td><label for="option_<?php echo $count; ?>"><?php echo (array_key_exists($option->meta_key,$known_sitemeta_options) ? __($known_sitemeta_options[$option->meta_key]) : '' ); ?></label></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</td>
				</table>
			</div>
		</div>
		</div>
		<?php submit_button(__('Add Network','njsl-networks'),'primary','add'); ?>
	</form>
</div>
<script type="text/javascript">
jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
jQuery('.postbox').children('h3').click(function() {
	if (jQuery(this.parentNode).hasClass('closed')) {
		jQuery(this.parentNode).removeClass('closed');
	} else {
		jQuery(this.parentNode).addClass('closed');
	}
});
</script>
<?php
			break;
		} // end switch( $action )
	}
	
	function move_blog_page() {

		global $wpdb;

		if(isset($_POST['move']) && isset($_GET['blog_id'])) {

			if(isset($_POST['from']) && isset($_POST['to'])) {
				move_blog($_GET['blog_id'],$_POST['to']);
				$_GET['updated'] = 'yes';
				$_GET['action'] = 'saved';
			}
			
		} else {
		
			if(!isset($_GET['blog_id'])) {
				die(__('You must select a site to move.','njsl-networks'));
			}
			$query = "SELECT * FROM {$wpdb->blogs} WHERE blog_id=" . (int)$_GET['blog_id'];
			$blog = $wpdb->get_row($query);
			if(!$blog) {
				wp_die(__('Site not found in blogs table.','njsl-networks'));
			}

			$optionTable = $wpdb->get_blog_prefix( $blog->blog_id ) . 'options';

			$details = $wpdb->get_row("SELECT * FROM {$optionTable} WHERE option_name='blogname'");
			if(!$details) {
				wp_die(__('Blog options table not found.','njsl-networks'));
			}

			$sites = $wpdb->get_results("SELECT *, {$wpdb->sitemeta}.meta_value as site_name FROM {$wpdb->site} LEFT JOIN {$wpdb->sitemeta} ON {$wpdb->sitemeta}.site_id = {$wpdb->site}.id AND {$wpdb->sitemeta}.meta_key = 'site_name' GROUP BY {$wpdb->sitemeta}.site_id");
			foreach($sites as $key => $site) {
				if($site->id == $blog->site_id) {
					$mySite = $sites[$key];
				}
			}
			?>
			<h2><?php echo __('Moving','njsl-networks') . ' ' . stripslashes($details->option_value); ?></h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<table class="widefat">
					<thead>
						<tr>
							<th scope="col"><?php _e('From','njsl-networks'); ?>:</th>
							<th scope="col"><label for="to"><?php _e('To','njsl-networks'); ?>:</label></th>
						</tr>
					</thead>
					<tr>
						<td><?php echo $mySite->site_name . ' (' . $mySite->domain . ')'; ?></td>
						<td>
							<select name="to" id="to">
								<option value="0"><?php _e('Select a Network','njsl-networks'); ?></option>
								<?php
								foreach($sites as $site) {
									if($site->id != $mySite->id) {
										echo '<option value="' . $site->id . '">' . $site->site_name . ' (' . $site->domain . ')' . '</option>' . "\n";
									}
								}
								?>
							</select>
						</td>
					</tr>
				</table>
				<br />
				<?php if(has_action('add_move_blog_option')) { ?>
				<table class="widefat">
					<thead>
						<tr scope="col"><th colspan="2"><?php _e('Options','njsl-networks'); ?>:</th></tr>
					</thead>
					<?php do_action('add_move_blog_option',$blog->blog_id); ?>
				</table>
				<br />
				<?php } ?>
				<div>
					<input type="hidden" name="from" value="<?php echo $blog->site_id; ?>" />
					<input class="button" type="submit" name="move" value="<?php _e('Move Site','njsl-networks'); ?>" />
					<a class="button" href="<?php echo $this->sitesPage ?>"><?php _e('Cancel','njsl-networks'); ?></a>
				</div>
			</form>
			<?php
		}
	}

	function reassign_blog_page() {
		
		global $wpdb;
		
		if(isset($_POST['reassign']) && isset($_GET['id'])) {
			if(isset($_POST['jsEnabled'])) {
				/** Javascript enabled for client - check the 'to' box */
				if(!isset($_POST['to'])) {
					wp_die(__('No sites selected.','njsl-networks'));
				}
				$blogs = $_POST['to'];
			} else {
				/** Javascript disabled for client - check the 'from' box */
				if(!isset($_POST['from'])) {
					wp_die(__('No sites selected.','njsl-networks'));
				}
				$blogs = $_POST['from'];
			}

			$currentBlogs = $wpdb->get_results("SELECT * FROM {$wpdb->blogs} WHERE site_id=" . (int)$_GET['id']);

			foreach($blogs as $blog) {
				move_blog($blog,(int)$_GET['id']);
			}

			/* true sync - move any unlisted blogs to 'zero' site */
			if(ENABLE_HOLDING_SITE) {
				foreach($currentBlogs as $currentBlog) {
					if(!in_array($currentBlog->blog_id,$blogs)) {
						move_blog($currentBlog->blog_id,0);
					}
				}
			}

			$_GET['updated'] = 'yes';
			$_GET['action'] = 'saved';

		} else {
			
			// get site by id
			$query = "SELECT *, {$wpdb->sitemeta}.meta_value as site_name FROM {$wpdb->site} " . " LEFT JOIN {$wpdb->sitemeta} ON {$wpdb->sitemeta}.meta_key='site_name' AND {$wpdb->sitemeta}.site_id = {$wpdb->site}.id WHERE id=" . (int)$_GET['id'];
			$site = $wpdb->get_row($query);
			if(!$site) {
				wp_die(__('Invalid network ID selected','njsl-networks'));
			}
			$blogs = $wpdb->get_results("SELECT * FROM {$wpdb->blogs}");
			if(!$blogs) {
				wp_die(__('Blogs table is inaccessible.','njsl-networks'));
			}
			foreach($blogs as $key => $blog) {
				$tableName = $wpdb->get_blog_prefix( $blog->blog_id ) . 'options';
				
				$blog_name = $wpdb->get_row("SELECT * FROM $tableName WHERE option_name='blogname'");
				if($wpdb->last_error != '') {
					wp_die(printf(__('Could not locate options table for a site. (Tried: %s).','njsl-networks'), $tableName ));
				}
				if(!$blog_name) {
					$blogs[$key]->name = __('Unknown site name','njsl-networks');
				} else {
					$blogs[$key]->name = stripslashes($blog_name->option_value);
				}
			}
			?>
			<div class="wrap">
				<div class="icon32" id="icon-ms-admin"><br></div>
				<h2><?php _e('Assign Sites to','njsl-networks'); ?>: <?php echo $site->site_name . ' (' . $site->domain . $site->path . ')' ?></h2>
				<noscript>
					<div id="message" class="updated hide-if-js"><p><?php printf( __('Select the sites you want to assign to this network from the column at left, and click "%s."','njsl-networks'),__('Update Assignments','njsl-networks')); ?></p></div>
				</noscript>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="site-assign-form">
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e('Available','njsl-networks'); ?></th>
							<th style="width: 2em;"></th>
							<th><?php _e('Assigned','njsl-networks'); ?></th>
						</tr>
					</thead>
					<tr>
						<td>
							<select name="from[]" id="from" multiple style="height: auto; width: 98%">
							<?php
								foreach($blogs as $blog) {
									if($blog->site_id != $site->id) echo '<option value="' . $blog->blog_id . '">' . $blog->name  . ' (' . $blog->domain . ')</option>';
								}
							?>
							</select>
						</td>
						<td>
							<input type="button" name="unassign" id="unassign" value="<<" /><br />
							<input type="button" name="assign" id="assign" value=">>" />
						</td>
						<td valign="top">
							<?php if(!ENABLE_HOLDING_SITE) { ?><ul style="margin: 0; padding: 0; list-style-type: none;">
								<?php foreach($blogs as $blog) { 
									if ($blog->site_id == $site->id) { ?>
									<li><?php echo $blog->name . ' (' . $blog->domain . ')'; ?></li>
								<?php } } ?>
							</ul><?php } ?>
							<select name="to[]" id="to" multiple style="height: auto; width: 98%">
							<?php
							if(ENABLE_HOLDING_SITE) {
								foreach($blogs as $blog) {
									if($blog->site_id == $site->id) echo '<option value="' . $blog->blog_id . '">' . $blog->name . ' (' . $blog->domain . ')</option>';
								}
							}
							?>
							</select>
						</td>
					</tr>
				</table>
				<br class="clear" />
					<?php if(has_action('add_move_blog_option')) { ?>
					<table class="widefat">
						<thead>
							<tr scope="col"><th colspan="2"><?php _e('Options','njsl-networks'); ?>:</th></tr>
						</thead>
						<?php do_action('add_move_blog_option',$blog->blog_id); ?>
					</table>
					<br />
					<?php } ?>
				<input type="submit" name="reassign" value="<?php _e('Update Assignments','njsl-networks'); ?>" class="button" />
				<a href="<?php echo $this->listPage ?>"><?php _e('Cancel'); ?></a>
				</form>
				<script type="text/javascript">
					
					/** add field to signal javascript is enabled */
					jQuery(document.createElement('input'))
						.attr( 'type', 'hidden' )
						.attr( 'name', 'jsEnabled' )
						.attr( 'value', 'true' )
						.appendTo( '#site-assign-form' );
					
					/** Handle clicks to add/remove sites to/from selected list */
					jQuery( 'input[name=assign]' ).click( function() {		move( 'from', 'to' );	});
					jQuery( 'input[name=unassign]' ).click( function() {	move( 'to', 'from' );	});
					
					/** Select all sites in "selected" box when submitting */
					jQuery( '#site-assign-form' ).submit( function() {
						jQuery( '#to' ).children( 'option' ).attr( 'selected', true );
					});
		
					function move( from, to ) {
						jQuery( '#' + from ).children( 'option:selected' ).each( function() {
							jQuery( '#' + to ).append( jQuery( this ).clone() );
							jQuery( this ).remove();
						});
					}
					
				</script>
			</div>
			<?php
			
		}
	}

	function add_site_page() {
		
		global $wpdb, $options_to_copy;
		
		if(isset($_POST['add']) && isset($_POST['domain']) && isset($_POST['path'])) {

			/** grab custom options to clone if set */
			if(isset($_POST['options_to_clone']) && is_array($_POST['options_to_clone'])) {
				$options_to_clone = array_keys($_POST['options_to_clone']);
			} else {
				$options_to_clone = $options_to_copy;
			}

			if(isset($_POST['createRootSite'])) {
				$result = add_site(
					$_POST['domain'],
					$_POST['path'], 
					(isset($_POST['newBlog']) ? $_POST['newBlog'] : __('New Network Created','njsl-networks') ) ,
					(isset($_POST['cloneSite']) ? $_POST['cloneSite'] : NULL ), 
					$options_to_clone 
				);
			} else {
				$result = add_site(
					$_POST['domain'],
					$_POST['path'], 
					false,
					(isset($_POST['cloneSite']) ? $_POST['cloneSite'] : NULL ), 
					$options_to_clone 
				);
			}
			if($result) {
				if(isset($_POST['name'])) {
					switch_to_site($result);
					add_site_option('site_name',$_POST['name']);
					restore_current_site();
				}

				$_GET['added'] = 'yes';
				$_GET['action'] = 'saved';
			}

		} else {
			
			// integrated with main page
			
		}
	}

	function edit_site_page() {
		
		global $wpdb;
		
		if( isset( $_POST['update'] ) && isset( $_GET['id'] ) ) {
			
			$site = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->site} WHERE id=%d", $_GET['id'] ) );
			if(!$site) {
				wp_die(__('Invalid network ID selected','njsl-networks'));
			}
			update_site((int)$_GET['id'],$_POST['domain'],$_POST['path']);
			$_GET['updated'] = 'true';
			$_GET['action'] = 'saved';

		} else {
			
			$site = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->site} WHERE id=%d", $_GET['id'] ) );

			if( ! $site ) {
				wp_die(__('Invalid network ID selected','njsl-networks'));
			}
			
			/* strip off the action tag */
			$queryStr = add_query_arg( array( 'action' => false ) );
			
			?>
			<div class="wrap">
				<div class="icon32" id="icon-ms-admin"><br></div>
				<h2><?php _e('Edit Network','njsl-networks'); ?>: <a href="http://<?php echo $site->domain . $site->path ?>"><?php echo $site->domain . $site->path ?></a></h2>
				<form method="post" action="<?php echo $queryStr; ?>">
					<table class="form-table">
						<tr class="form-field"><th scope="row"><label for="domain"><?php _e('Domain','njsl-networks'); ?></label></th><td> http://<input type="text" id="domain" name="domain" value="<?php echo $site->domain; ?>"></td></tr>
						<tr class="form-field"><th scope="row"><label for="path"><?php _e('Path','njsl-networks'); ?></label></th><td><input type="text" id="path" name="path" value="<?php echo $site->path; ?>" /></td></tr>
					</table>
					<?php if(has_action('add_edit_site_option')) { ?>
					<h3>Options:</h3>
					<table class="form-table">
						<?php do_action('add_edit_site_option'); ?>
					</table>
					<?php } ?>
					<p>
						<input type="hidden" name="siteId" value="<?php echo $site->id; ?>" />
						<input class="button" type="submit" name="update" value="<?php _e('Update Network','njsl-networks'); ?>" />
						<a href="<?php echo $this->listPage ?>"><?php _e('Cancel','njsl-networks'); ?></a>
					</p>
				</form>
			</div>
			<?php
		}	
	}

	function delete_site_page() {
		
		global $wpdb;
		
		if(isset($_POST['delete']) && isset($_GET['id'])) {
			
			if($_GET['id'] == 1) {
				wp_die( __( 'Deleting the main network is disabled.', 'njsl-networks' ) );
			}
			
			$result = delete_site((int)$_GET['id'],(isset($_POST['override'])));
			if(is_a($result,'WP_Error')) {
				wp_die($result->get_error_message());
			}
			$_GET['deleted'] = 'yes';
			$_GET['action'] = 'saved';
			
		} else {
			
			// get site by id
			$query = "SELECT * FROM {$wpdb->site} WHERE id=" . (int)$_GET['id'];
			$site = $wpdb->get_row($query);
			if(!$site) {
				wp_die(__('Invalid network ID selected','njsl-networks'));
			}

			$query = "SELECT * FROM {$wpdb->blogs} WHERE site_id=" . (int)$_GET['id'];
			$blogs = $wpdb->get_results($query);

			/* strip off the action tag */
			$queryStr = add_query_arg( array( 'action' => false ) );
			
			?>
			<form method="POST" action="<?php echo $queryStr; ?>">
				<div>
					<h2><?php _e('Delete Network','njsl-networks'); ?>: <a href="http://<?php echo $site->domain . $site->path ?>"><?php echo $site->domain . $site->path ?></a></h2>
<?php if($blogs) {
	if(RESCUE_ORPHANED_BLOGS && ENABLE_HOLDING_SITE) {
 ?>
					<div id="message" class="error">
						<p><?php _e('There are sites associated with this network. ','njsl-networks');  _e('Deleting it will move those sites to the holding network.','njsl-networks'); ?></p>
						<p><label for="override"><?php _e('If you still want to delete this network, check the following box','njsl-networks'); ?>:</label> <input type="checkbox" name="override" id="override" /></p>
					</div>
<?php } else { ?>
					<div id="message" class="error">
						<p><?php _e('There are sites associated with this network. ','njsl-networks'); _e('Deleting it will delete those sites as well.','njsl-networks'); ?></p>
						<p><label for="override"><?php _e('If you still want to delete this network, check the following box','njsl-networks'); ?>:</label> <input type="checkbox" name="override" id="override" /></p>
					</div>
<?php	} ?>
<?php } ?>
					<p><?php _e('Are you sure you want to delete this network?','njsl-networks'); ?></p>
					<input type="submit" name="delete" value="<?php _e('Delete Network','njsl-networks'); ?>" class="button" /> 
					<a href="<?php echo $this->listPage ?>"><?php _e('Cancel','njsl-networks'); ?></a>
				</div>
			</form>
			<?php
			
		}
	}
	
	function delete_multiple_site_page() {
		
		global $wpdb;
				
		if(isset($_POST['delete_multiple']) && isset($_POST['deleted_sites'])) {
			foreach($_POST['deleted_sites'] as $deleted_site) {
				$result = delete_site((int)$deleted_site,(isset($_POST['override'])));
				if(is_a($result,'WP_Error')) {
					wp_die($result->get_error_message());
				}
			}
			$_GET['deleted'] = 'yes';
			$_GET['action'] = 'saved';
		} else {
			
			/** ensure a list of sites was sent */
			if(!isset($_POST['allsites'])) {
				wp_die(__('You have not selected any networks to delete.'));
			}
			
			/** Ensure that all sites are integers */
			$allsites = array_map(create_function('$val','return (int)$val;'),$_POST['allsites']);
			
			/** ensure each site is valid */
			foreach($allsites as $site) {
				if(!site_exists((int)$site)) {
					wp_die(__('You have selected an invalid network.','njsl-networks'));
				}
			}
			/** remove primary site from list */
			if(in_array(1,$allsites)) {
				$sites = array();
				foreach($allsites as $site) {
					if($site != 1) $sites[] = $site;
				}
				$allsites = $sites;
			}
			
			$query = "SELECT * FROM {$wpdb->site} WHERE id IN (" . implode(',',$allsites) . ')';
			$site = $wpdb->get_results($query);
			if(!$site) {
				wp_die(__('You have selected an invalid network or networks.','njsl-networks'));
			}
			
			$query = "SELECT * FROM {$wpdb->blogs} WHERE site_id IN (" . implode(',',$allsites) . ')';
			$blogs = $wpdb->get_results($query);
			
			?>
			<form method="POST" action="<?php echo add_query_arg( array( 'action' => false, 'id' => false ) ) ?>"><div>
			<h2><?php _e('Delete Multiple Networks','njsl-networks'); ?></h2>
			<?php
			
			if($blogs) {
				if(RESCUE_ORPHANED_BLOGS && ENABLE_HOLDING_SITE) {
					?>
					
			<div id="message" class="error">
				<h3><?php _e('You have selected the following networks for deletion','njsl-networks'); ?>:</h3>
				<ul>
				<?php foreach($site as $deleted_site) { ?>
					<li><input type="hidden" name="deleted_sites[]" value="<?php echo $deleted_site->id; ?>" /><?php echo $deleted_site->domain . $deleted_site->path ?></li>
				<?php } ?>
				</ul>
				<p><?php _e('There are sites assigned to one or more of these networks.','njsl-networks');  _e('Deleting them will move these sites to the holding network.','njsl-networks'); ?></p>
				<p><label for="override"><?php _e('If you still want to delete these networks, check the following box','njsl-networks'); ?>:</label> <input type="checkbox" name="override" id="override" /></p>
			</div>
					
					<?php
				} else {
					?>
					
			<div id="message" class="error">
				<h3><?php _e('You have selected the following networks for deletion','njsl-networks'); ?>:</h3>
				<ul>
				<?php foreach($site as $deleted_site) { ?>
					<li><input type="hidden" name="deleted_sites[]" value="<?php echo $deleted_site->id; ?>" /><?php echo $deleted_site->domain . $deleted_site->path ?></li>
				<?php } ?>
				</ul>
				<p><?php _e('There are sites associated with one or more of these networks.','njsl-networks');  _e('Deleting them will delete those sites as well.','njsl-networks'); ?></p>
				<p><label for="override"><?php _e('If you still want to delete these networks, check the following box','njsl-networks'); ?>:</label> <input type="checkbox" name="override" id="override" /></p>
			</div>
					
					<?php
				}
			} else {

				?>
					
			<div id="message">
				<h3><?php _e('You have selected the following networks for deletion','njsl-networks'); ?>:</h3>
				<ul>
				<?php foreach($site as $deleted_site) { ?>
					<li><input type="hidden" name="deleted_sites[]" value="<?php echo $deleted_site->id; ?>" /><?php echo $deleted_site->domain . $deleted_site->path ?></li>
				<?php } ?>
				</ul>
			</div>
					
				<?php
				
			}
			?>
				<p><?php _e('Are you sure you want to delete these networks?','njsl-networks'); ?></p>
				<input type="submit" name="delete_multiple" value="<?php _e('Delete Networks','njsl-networks'); ?>" class="button" /> <input type="submit" name="cancel" value="<?php _e('Cancel','njsl-networks'); ?>" class="button" />
			</div></form>
			<?php
		}
	}
	
	function verify_network_page() {
		global $wpdb;
		global $url_dependent_blog_options;
		
		$site_id = (int)$_GET['id'];
		?>
		<h2><?php printf(__('Verifying Network: %s','njsl-networks'),'') ?></h2>
		<p>
			<?php _e('This page will perform some basic diagnostics on your Network to uncover common configuration errors. 
			This testing is by no means exhaustive, and it is still possible to break your Network and pass all these tests.','njsl-networks'); ?>
		</p>
		<h3><?php printf(__('Checking <code>%s</code> table for selected Network by ID','njsl-networks'),$wpdb->site) ?>:</h3>
		<?php
		$site = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->site . ' WHERE id=%d LIMIT 1',$site_id));
		if($site) {
			$domain = $site->domain;
			$path = $site->path;
			echo '<p class="network_success">' . __('Passed.','njsl-networks') . '</p>';
		} else {
			wp_die(sprintf(__('The selected network was not found in the <code>%s</code> table. Testing cannot continue.','njsl_networks'),$wpdb->site));
		}
		?>
		
		<h3><?php printf(__('Checking <code>%s</code> table for a unique combination of domain and path','njsl-networks'),$wpdb->site) ?>:</h3>
		<?php
		$site = $wpdb->get_row($wpdb->prepare('SELECT COUNT(*) as rows FROM ' . $wpdb->site . ' WHERE domain=%s AND path=%s',$domain,$path));
		echo ($site->rows == 1) ? '<p class="network_success">' . __('Passed.','njsl-networks') . '</p>' : '<p class="network_error">' . __('Failed.','njsl-networks') . '</p>';
		?>
		
		<h3><?php _e('Checking for super admin(s)','njsl-networks') ?>:</h3>
		<?php
		$site_admins = $wpdb->get_var($wpdb->prepare('SELECT meta_value as site_admin FROM ' . $wpdb->sitemeta . ' WHERE site_id=%d AND meta_key=%s',$site_id,'site_admins'));
		if($site_admins) {
			$admins = @unserialize($site_admins);
			if(count($admins) > 0) {
				echo '<p class="network_success">' . __('Passed.','njsl-networks') . ' ' . sprintf(_n('Found %d super admin','Found %d super admins',count($admins), 'njsl-networks'),count($admins)) . '</p>';
				echo '<ul>';
				foreach($admins as $admin) {
					echo '<li>' . $admin . '</li>';
				}
				echo '</ul>';
			} else {
				echo '<p class="network_error">' . sprintf(__('Super admins meta value could not be decoded. Check your <code>%s</code> table for corruption.','njsl-networks'),$wpdb->sitemeta) . '</p>';
			}
		} else {
			echo '<p class="network_warning">' . sprintf(__('Super admins meta key was not found. Check your <code>%s</code> table for the proper keys.','njsl-networks'),$wpdb->sitemeta);
			echo '<br />' . __('Without a super admins key, only username "admin" will be treated as a super admin.','njsl-networks') . '</p>';
		}
		?>
		
		<h3><?php _e('Checking DNS for this network','njsl-networks') ?>:</h3>
		<?php
		$network_addr = gethostbyname($domain);
		$current_addr = gethostbyname($_SERVER['HTTP_HOST']);
		
		if(!is_numeric(substr($network_addr,0,strpos($network_addr,'.')))) { ?>
			<p class="network_error"><?php _e('Domain could not be resolved.','njsl-networks') ?></p>
		<?php } else if($network_addr == $current_addr) { ?>
			<p class="network_success"><?php _e('Passed.','njsl-networks') ?></p>
		<?php } else { ?>
			<div class="network_warning">
				<p><?php echo __('DNS did not match.','njsl-networks') . __('This is not a fatal error, but you should verify DNS manually from your users\' perspective.','njsl-networks'); ?></p>
				<ul>
					<li><?php printf(__('Current network resolved to: <code>%s</code>','njsl-networks'),$current_addr) ?></li>
					<li><?php printf(__('Selected network resolved to: <code>%s</code>','njsl-networks'),$network_addr) ?></li>
				</ul>
			</div>
		<?php } ?>
		
		<h3><?php _e('Checking hosted sites for correct Network-related values','njsl-networks') ?>:</h3>
		<?php
		$site_errors = 0;
		$hosted_blogs = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->blogs . ' WHERE site_id=%d',$site_id));
		if($hosted_blogs && count($hosted_blogs) > 0) {
			foreach($hosted_blogs as $hosted_blog) {
				if( is_subdomain_install() ) {
					if(strpos($hosted_blog->domain,$domain) === false) {
						$site_errors++;
						echo '<p class="network_error">' . sprintf(__('Site %d (%s) has an invalid <code>domain</code> setting.','njsl-networks'), $hosted_blog->blog_id, $hosted_blog->domain . $hosted_blog->path) . '</p>';
					}
					if($hosted_blog->path != '/') {
						$site_errors++;
						echo '<p class="network_error">' . sprintf(__('Site %d (%s)  has an invalid <code>path</code> setting.','njsl-networks'), $hosted_blog->blog_id, $hosted_blog->domain . $hosted_blog->path) . '</p>';
					}
				} else {
					if($hosted_blog->domain != $domain) {
						$site_errors++;
						echo '<p class="network_error">' . sprintf(__('Site %d (%s)  has an invalid <code>domain</code> setting.','njsl-networks'), $hosted_blog->blog_id, $hosted_blog->domain . $hosted_blog->path) . '</p>';
					}
					if(strpos($hosted_blog->path, $path) === false) {
						$site_errors++;
						echo '<p class="network_error">' . sprintf(__('Site %d (%s)  has an invalid <code>path</code> setting.','njsl-networks'), $hosted_blog->blog_id, $hosted_blog->domain . $hosted_blog->path) . '</p>';
					}
				}
			}
		} else {
			$site_errors++;
			echo '<p class="network_warning">' . __('No sites were found on this network. Skipping this check.','njsl-networks') . '</p>';
		}
		if($site_errors == 0) {
			echo '<p class="network_success">' . __('Passed.','njsl-networks') . '</p>';
		}
		?>
		<h3><?php _e('Checking hosted sites for correct Network-related meta values','njsl-networks') ?>:</h3>
		<?php
		$site_errors = 0;
		if($hosted_blogs && count($hosted_blogs) > 0) {
			foreach($hosted_blogs as $hosted_blog) {
				$blog_meta = $wpdb->get_results('SELECT option_name, option_value FROM ' . $wpdb->get_blog_prefix( $hosted_blog->blog_id ) . 'options' . ' WHERE option_name IN ("' . implode('", "',$url_dependent_blog_options) . '")');
				foreach($blog_meta as $meta) {
					if( strpos( trailingslashit( $meta->option_value ), $domain . $path ) === false) {
						$site_errors++;
						echo '<p class="network_error">' . sprintf(__('Site %d (%s) has an invalid meta value in <code>%s</code>. This may prevent access to this site or disable some features.','njsl-networks'),$hosted_blog->blog_id, $hosted_blog->domain . $hosted_blog->path, $meta->option_name) . '</p>';
					}
				}
			}
		} else {
			$site_errors++;
			echo '<p class="network_warning">' . __('No sites were found on this network. Skipping this check.','njsl-networks') . '</p>';
		}
		if($site_errors == 0) {
			echo '<p class="network_success">' . __('Passed.','njsl-networks') . '</p>';
		}
		?>
		<?php if( is_subdomain_install() ) : ?>
		<h3><?php _e('Checking DNS for hosted site subdomains','njsl-networks') ?>:</h3>
		<?php
		
		if($hosted_blogs && count($hosted_blogs) > 0) {
			foreach($hosted_blogs as $hosted_blog) {
				$site_addr = gethostbyname($hosted_blog->domain);
				if(!is_numeric(substr($site_addr,0,strpos($site_addr,'.')))) { ?>
					<p class="network_error"><?php printf(__('Site %d\'s domain (%s) could not be resolved.','njsl-networks'),$hosted_blog->blog_id, $hosted_blog->domain) ?></p>
				<?php } else {
					if($site_addr != $network_addr) {
						?>
						<div class="network_warning">
							<p>
								<?php printf(__('Site %d\'s <code>domain</code> does not match its network\'s.'), $hosted_blog->blog_id) ?>
								<?php _e('This is not a fatal error, but you should verify DNS manually from your users\' perspective.','njsl-networks'); ?>	
							</p>
							<ul>
								<li><?php printf(__('Site %d resolved to: <code>%s</code>','njsl-networks'),$hosted_blog->blog_id, $site_addr) ?></li>
								<li><?php printf(__('Selected network resolved to: <code>%s</code>','njsl-networks'),$network_addr) ?></li>
							</ul>
						</div>
						<?php
					}
					if($site_addr != $current_addr) {
						?>
						<div class="network_warning">
							<p>
								<?php printf(__('Site %d\'s <code>domain</code> does not match the current network\'s.','njsl-networks'), $hosted_blog->blog_id) ?>
								<?php _e('This is not a fatal error, but you should verify DNS manually from your users\' perspective.','njsl-networks'); ?>
							</p>
							<ul>
								<li><?php printf(__('Site %d resolved to: <code>%s</code>','njsl-networks'),$hosted_blog->blog_id, $site_addr) ?></li>
								<li><?php printf(__('Current network resolved to: <code>%s</code>','njsl-networks'),$current_addr) ?></li>
							</ul>
						</div>
						<?php
					}
				}
			}
		} else {
			echo '<p class="network_warning">' . __('No sites were found on this network. Skipping this check.','njsl-networks') . '</p>';
		}
		?>
		<?php endif; ?>
		<?php
	}
	
	function filter_networks_help($contextual_help, $screen_id, $screen) {
		if($screen_id == $this->admin_page || $screen_id == $this->admin_page . '-network') {
			return $this->networks_help();
		}
		return $contextual_help;
	}

	function networks_help() {
		return $this->networks_help_intro() . $this->networks_help_create() . $this->networks_help_verify();
	}
	
	function networks_help_intro() {
		$contextual_help = 
		'<p>' . __('The table below shows all the Networks running on this installation of WordPress.','njsl-networks') . '</p>' .
		'<h4>' . __('What is a Network?','njsl-networks') . '</h4>' .
		'<p>' . __('A Network is a group of sites with common admins, plugins, and policies.','njsl-networks') . '</p>' .
		'<p>' . __('With Networks for WordPress, you can create as many distinct Networks as you need. All your Networks will share a common codebase and set of users.') . '</p>' . 
		'<p>' . __('The most common use of Networks is running distinct groups of sites on multiple domains from a single install.','njsl-networks') . '</p>' . 
		'<p>' . __('If what you want to do is run a single site on a different domain, or to have a site accessible at more than one domain, you should look into domain mapping instead.','njsl-networks') . '</p>'
		;
		return $contextual_help;
	}
	
	function networks_help_create() {
		$contextual_help = 
		'<h4>' . __('Adding a Network','njsl-networks') . '</h4>' . 
		'<ol>' .
		'<li>' . __('Enter the network\'s basic information in the <a href="#form-add-network">form below</a>.','njsl-networks') . '</li>' .
		'<li>' . sprintf(__('Use the test link on the right to verify the new address before creating the network. You should see the "%s" error.','njsl-networks'),__('No site defined on this host','njsl-networks')) . '</li>' .
		'<li>' . __('If you don\'t see the right message, use the "Check Network Settings" button on the right to see possible issues with your configuration.','njsl-networks') . '</li>' .
		'<li>' . __('Click "Add Network" when you\'re ready to proceed.','njsl-networks') . '</li>' . 
		'</ol>'
		;
		return $contextual_help;
	}

	function networks_help_verify() {
		$contextual_help = 
		'<h4>' . __('Troubleshooting your Networks','njsl-networks') . '</h4>' . 
		'<p>' . __('If you encounter problems with one of your networks, use the "Verify" link below each network name to perform automated testing.','njsl-networks') . '</p>' .
		'<p>' . __('Running multiple networks requires care in your server configuration. For best results, ensure all the domains you are planning to use for networks share the same settings.','njsl-networks') . '</p>'
		;
		return $contextual_help;
	}
	
	function networks_help_more_info() {
		$contextual_help = 
		'<p><strong>' . __('More Information','njsl-networks') . ':</strong></p>' .

		'<p><a href="http://codex.wordpress.org/Create_A_Network" target="_blank">' . __('WordPress Codex - Create a Network','njsl-networks') . '</a></p>' .
		'<p><a href="http://www.jerseyconnect.net/development/networks-for-wordpress/" target="_blank">' . __('Networks for WordPress Home Page and FAQ','njsl-networks') . '</a></p>'
		;
		return $contextual_help;
	}
	
	function networks_help_screen() {
		if(class_exists('WP_Screen')) {
			$this->admin_screen = WP_Screen::get($this->admin_page . '-network');
			$this->admin_screen->add_help_tab(
				array(
					'title'    => __('Networks Overview','njsl-networks'),
					'id'       => 'help',
					'content'  => $this->networks_help_intro()
				)
			);
			
			$this->admin_screen->add_help_tab(
				array(
					'title'    => __('Adding a Network','njsl-networks'),
					'id'       => 'add_network',
					'content'  => $this->networks_help_create()
				)
			);
			
			$this->admin_screen->add_help_tab(
				array(
					'title'    => __('Troubleshooting','njsl-networks'),
					'id'       => 'verify',
					'content'  => $this->networks_help_verify()
				)
			);
			
			$this->admin_screen->set_help_sidebar(
				$this->networks_help_more_info()
			);
		}		
	}
	
	function networks_options_screen() {
		if( class_exists( 'WP_Screen' ) ) {
			$this->admin_screen = WP_Screen::get( $this->admin_page . '-network');
			$this->admin_screen->add_option( 
				'per_page', 
				array(
					'label' => __( 'Networks per page', 'njsl-networks' ),
					'default' => 10,
					'option' => 'networks_per_page'
				) 
			);
		}
	}
	
	function save_network_options( $value, $option_name, $original_value ) {
		if( $option_name != 'networks_per_page' )	return $value;
		if( 1 < (int)$original_value && (int)$original_value <= 100 )	return $original_value;
		return false;
	}
	
	/**
	 * Add descriptions for all sitemeta values 
	 */
	function add_sitemeta_descriptions( $known_options ) {
		
		$my_known_options = array(
			'active_sitewide_plugins'	=> __( 'List of active Network-enabled plugins', 'njsl-networks' ),
			'add_new_users'				=> __( 'Allow site administrators to add new users to their site via the "Users &rarr; Add New" page.' ),
			'admin_email'				=> __( 'Network administrator email', 'njsl-networks' ),
			'admin_user_id'				=> __( 'Deprecated - Admin user ID', 'njsl-networks' ),
			'allowed_themes'			=> __( 'Deprecated - Old list of allowed themes', 'njsl-networks' ),
			'allowedthemes'				=> __( 'List of allowed themes', 'njsl-networks' ),
			'banned_email_domains'		=> __( 'If you want to ban domains from site registrations. One domain per line.' ),
			'blog_upload_space'			=> __( 'Maximum size (in MB) for file uploads', 'njsl-networks' ),
			'fileupload_maxk'			=> __( 'Max upload file size' ),
			'first_comment'				=> __( 'The first comment on a new site.' ),
			'first_comment_author'		=> __( 'The author of the first comment on a new site.' ),
			'first_comment_url'			=> __( 'The URL for the first comment on a new site.' ),
			'first_page'				=> __( 'The first page on a new site.' ),
			'first_post'				=> __( 'The first post on a new site.' ),
			'illegal_names'				=> __( 'Users are not allowed to register these sites. Separate names by spaces.' ),
			'limited_email_domains'		=> __( 'If you want to limit site registrations to certain domains. One domain per line.' ),
			'registration'				=> __( 'Allow new registrations' ),
			'registrationnotification'	=> __( 'Send the network admin an email notification every time someone registers a site or user account.' ),
			'site_admins'				=> __( 'List of site administrator usernames', 'njsl-networks' ),
			'site_name'					=> __( 'A friendly name for the network', 'njsl-networks' ),
			'siteurl'					=> __( 'URI for the root of this network &mdash; do not clone this', 'njsl-network' ),
			'upload_filetypes'			=> __( 'Upload file types' ),
			'welcome_email'				=> __( 'The welcome email sent to new site owners.' ),
			'welcome_user_email'		=> __( 'The welcome email sent to new users.' )
		);
		
		return array_merge( $known_options, $my_known_options ) ;
		
	}
	
}

?>
