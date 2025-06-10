<?php defined('ABSPATH') || die('You do not have access to this page!');

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class jkdtheme
{
	
	/**
	 * @var \jkdevkit
	 */
	public static $instance;
	
	/**
	 * @var string
	 */
	public string $slug;
	
	/**
	 * @var string
	 */
	public string $url;
	
	/**
	 * @var string
	 */
	public string $path;
	
	/**
	 * @var string
	 */
	public string $name;
	
	/**
	 * @var string
	 */
	public string $author;
	
	/**
	 * @var string
	 */
	public string $id;
	
	/**
	 * @var string
	 */
	public string $version;
	
	/**
	 * @var string
	 */
	public string $assets_url;
	
	/**
	 * @param array $args
	 *
	 * @throws \Exception
	 */
	public function __construct(array $args = [])
	{
		
		/* get current theme */
		$my_theme = wp_get_theme();
		/* get theme name */
		$name = empty($my_theme->parent()['Name']) ? $my_theme['Name'] : $my_theme->parent()['Name'];
		/* slug (lower case version of the name) */
		$slug = strtolower($name);
		
		/* input attributes */
		$this->setId($args['id']);
		$this->setAuthor($args['author']);
		
		/* additional attributes */
		$this->setName($name);
		$this->setSlug($slug . '-wp');
		$this->setVersion(wp_get_theme()['Version']);
		$this->setAssetsUrl(esc_url('https://hub.jkdevstudio.com/demo/items/' . $slug . '/'));
		$this->setUrl(trailingslashit(get_template_directory_uri()));
		$this->setPath(get_template_directory());
		
		// Check if JKD Framework exists
		if (class_exists('jkdevkit')) self::$instance = new jkdevkit([
			'type' => 'theme',
			'dev' => false,
			'slug' => $slug . '-wp',
			'id' => $this->id,
			'version' => $this->version,
			'name' => $this->name,
			'author' => $this->author,
			'plugin_path' => WP_PLUGIN_DIR . '/jkd-wp-' . $slug . '-core/',
			'theme_path' => trailingslashit($this->path),
		]);
		
		// Initialize theme
		$this->init_theme();
		
	}
	
	/**
	 * @param string $id
	 *
	 * @return void
	 */
	public function setId(string $id): void
	{
		
		$this->id = $id;
		
	}
	
	/**
	 * @param string $author
	 *
	 * @return void
	 */
	public function setAuthor(string $author): void
	{
		
		$this->author = $author;
		
	}
	
	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name): void
	{
		
		$this->name = $name;
		
	}
	
	/**
	 * @param string $slug
	 */
	public function setSlug(string $slug): void
	{
		
		$this->slug = $slug;
		
	}
	
	/**
	 * @param string $version
	 *
	 * @return void
	 */
	public function setVersion(string $version): void
	{
		
		$this->version = $version;
		
	}
	
	/**
	 * @param string $assets_url
	 *
	 * @return void
	 */
	public function setAssetsUrl(string $assets_url): void
	{
		
		$this->assets_url = $assets_url;
		
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl(string $url): void
	{
		
		$this->url = $url;
		
	}
	
	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function setPath(string $path): void
	{
		
		$this->path = $path;
		
	}
	
	/**
	 * @return \jkdevkit
	 */
	public static function getInstance()
	{
		
		return self::$instance;
		
	}
	
	/**
	 * @return void
	 * @throws \Exception
	 */
	protected function init_theme(): void
	{
		
		/* init textdomain */
		add_action('init', [$this, 'i18n']);
		
		/* init woo (integration */
		add_action('init', [$this, 'woo']);
		
		/* init sidebar area */
		add_action('widgets_init', [$this, 'init_sidebar_area']);
		
		/* init navigation area */
		add_action('after_setup_theme', [$this, 'init_navigation_area']);
		
		/* theme setup values */
		add_action('after_setup_theme', [$this, 'theme_setup']);
		
		/* enqueue theme styles */
		add_action('wp_enqueue_scripts', [$this, 'enqueue_styles_theme']);
		
		/* enqueue theme scripts */
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts_theme']);
		
		/* add meta to <head> */
		add_action('wp_head', function () {
			
			$this->add_head();
			
		});
		
		/* Add admin menu page for theme info */
		add_action('admin_menu', function () {
			
			if ($this->getInstance()) $this->add_admin_pg();
			
		}, 0);
		
		$this->apply_base_filters();
		
		if (class_exists('TGM_Plugin_Activation')) add_action('tgmpa_register', [$this, 'register_required_plugins']);
		
		if (class_exists('jkdevkit') && self::getInstance() && self::getInstance()->isActivated()):
			
			/* check if exists ocdi */
			if (class_exists('OCDI_Plugin')):
				
				/* disable ocdi branding */
				add_filter('pt-ocdi/disable_pt_branding', '__return_true');
				
				/* ocdi after import */
				add_action('pt-ocdi/after_import', [$this, 'ocdi_after_import_setup']);
				
				/* ocdi import files */
				add_filter('pt-ocdi/import_files', [$this, 'ocdi_import_files']);
				
				/* ocdi register plugins */
				add_filter('ocdi/register_plugins', [$this, 'ocdi_register_plugins']);
			
			endif;
			
			/* used to set the initial fonts before the core plugin is installed & activated */
			if (empty(get_option('jkd_fonts_check'))) $this->fonts_setup();
			if (class_exists('jkdcore') && empty(get_option('jkd_kirki'))) $this->update_kirki_options();
			
			if (!$this->getInstance()->isDev()):
				add_action('init', [$this, 'cron_setup']);
				if (class_exists('YahnisElsts\PluginUpdateChecker\v5\PucFactory')) $this->init_upd();
			endif;
		
		endif;
		
	}
	
	/**
	 * @return void
	 */
	public function apply_base_filters(): void
	{
		
		add_filter('the_content', function ($content) {
			
			return $this->format_chat_content($content);
			
		}, 99);
		add_filter('document_title_parts', function ($title) {
			
			return $this->custom_title_parts($title);
			
		});
		add_filter('nav_menu_css_class', function ($classes, $item, $args) {
			
			return $this->custom_menu_class($classes, $item, $args);
			
		}, 10, 3);
		add_filter('get_comment_date', function ($comment_date, $format, $comment) {
			
			return $this->get_comment_date($comment_date, $format, $comment);
			
		}, 10, 3);
		add_filter('the_password_form', function () {
			
			return $this->get_password_form();
			
		});
		
		if (!class_exists('jkdcore')) add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
			
			return $this->menu_parse($item_output, $item, $depth, $args);
			
		}, 10, 4);
		
	}
	
	/**
	 * @return void
	 */
	public function add_head(): void
	{
		
		/* viewport */
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
		
		/* profile */
		echo '<link rel="profile" href="https://gmpg.org/xfn/11" />';
		
		/* check is singular && pings open */
		if (is_singular() && pings_open()) echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">';
		
	}
	
	/**
	 * @return void
	 * @throws \Exception
	 */
	public function add_admin_pg(): void
	{
		
		/* added dashboard page */
		add_menu_page($this->name . esc_html__(' Dashboard', 'fabula'),
			$this->name . esc_html__(' Dashboard', 'fabula'),
			'edit_theme_options', $this->slug,
			function (): void {
				
				$this->shell();
				
			}, 'data:image/svg+xml;base64,' . $this->getInstance()->getJkdLogo(), 49);
		
	}
	
	/**
	 * @return void
	 * @throws \Exception
	 */
	public function shell(): void
	{
		
		/* setup jkdevkit shell */
		$this->getInstance()->shell([
			'logo_svg' => get_template_directory_uri() . '/assets/img/logo.svg',
			'tabs' => [],
		]);
		
	}
	
	/**
	 * Format content for singular posts of post format 'chat'.
	 *
	 * @param string $content The post content.
	 *
	 * @return string The formatted content.
	 */
	public function format_chat_content(string $content): string
	{
		
		// Check if the current post is a singular 'post' with the 'chat' format
		if (is_singular('post') && get_post_format() === 'chat'):
			
			// Extract all words followed by a colon using regex
			preg_match_all('#(\S+):#', $content, $matches);
			
			// Ensure each match is unique
			$matches = array_map('array_unique', $matches);
			
			// Wrap <b> tags around each unique matched word
			foreach ($matches[0] as $word) :
				if ($word !== '') $content = str_replace($word, '<b>' . $word . '</b>', $content);
			endforeach;
			
			// Remove any remaining non-breaking spaces
			$content = str_replace('&nbsp;', '', $content);
		
		endif;
		
		return $content;
		
	}
	
	/**
	 * Customize title parts by removing 'site' from the title array.
	 *
	 * @param array $title The title parts array.
	 *
	 * @return array The modified title array.
	 */
	public function custom_title_parts(array $title): array
	{
		
		// Remove 'site' part from the title if it exists
		if (isset($title['site'])) unset($title['site']);
		
		return $title;
		
	}
	
	/**
	 * Add a custom class to the first menu item related to categories.
	 *
	 * @param array  $classes CSS classes for the current menu item.
	 * @param object $item    The current menu item.
	 * @param array  $args    Additional arguments.
	 *
	 * @return array Modified list of classes for the menu item.
	 */
	public function custom_menu_class(array $classes, $item, $args): array
	{
		
		// Static variable to track if we've processed the first category item
		static $first_category_item = true;
		
		// Add custom class to the first category item only
		if ($first_category_item && 'category' === $item->object) :
			$classes[] = 'category-first';
			$first_category_item = false; // Set flag to false after first occurrence
		endif;
		
		return $classes;
		
	}
	
	/**
	 * Customize comment date display with relative time difference.
	 *
	 * @param string|int $comment_date Formatted date string or Unix timestamp.
	 * @param string     $format       PHP date format.
	 * @param WP_Comment $comment      The comment object.
	 *
	 * @return string Modified comment date string.
	 */
	public function get_comment_date(string|int $comment_date, string $format, WP_Comment $comment): string
	{
		
		$comment_time = get_comment_time('U', false, $comment);
		$current_time = current_time('timestamp');
		
		// Display time difference if both timestamps are valid
		if ($comment_time && $current_time) :
			$time_difference = human_time_diff($comment_time, $current_time);
			
			return sprintf(__('%s ago', 'fabula'), $time_difference);
		endif;
		
		return $comment_date;
		
	}
	
	/**
	 * Customize the password-protected post form.
	 *
	 * @return string HTML output for the password form.
	 */
	public function get_password_form(): string
	{
		
		global $post;
		$label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
		
		// Construct the custom form HTML with styling classes
		$output = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post" class="post-password-form m-0 p-20 p-15-sm br-md bg-col d-f d-col">';
		$output .= '<p>' . __('This content is password protected. To view it, please enter the password below:', 'fabula') . '</p>';
		$output .= '<label for="' . $label . '">' . __('Password:', 'fabula') . '</label>';
		$output .= '<div class="input-wrp pos-rel d-f">';
		$output .= '<input name="post_password" placeholder="' . esc_attr__('Enter post password', 'fabula') . '" class="w-100" id="' . $label . '" type="password" size="20" />';
		$output .= '<input type="submit" class="pos-abs z-ui" name="Submit" value="' . esc_attr__("Submit", 'fabula') . '" />';
		$output .= '</div></form>';
		
		return $output;
		
	}
	
	/**
	 * @param $item_output
	 * @param $item
	 * @param $depth
	 * @param $args
	 *
	 * @return array|mixed|string|string[]
	 */
	public function menu_parse($item_output, $item, $depth, $args): mixed
	{
		
		/* check if current theme location is primary_menu */
		if ($args->theme_location == 'primary_menu') :
			
			/* check if is menu item has children */
			if (in_array('menu-item-has-children', $item->classes)
				|| in_array('page_item_has_children', $item->classes)) $item_output = str_replace($args->link_after . '</a>', $args->link_after . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-chevron-down arrow-down"><path d="m6 9 6 6 6-6"/></svg></a>', $item_output);
			
			/* add classes to <a> */
			$item_output = str_replace('<a', '<a class=" d-f jc-sb ai-c"', $item_output);
		
		endif;
		
		/* return modified item */
		
		return $item_output;
		
	}
	
	/**
	 * Output the appropriate title based on different page types.
	 *
	 * This function determines the appropriate title to display based on the type of page being
	 * viewed.
	 *
	 * @return void
	 */
	public static function the_title(): void
	{
		
		if (is_search()) :
			
			echo esc_html__('Search Results', 'fabula');
		
		elseif (is_404()) :
			
			echo esc_html__('Page not Found', 'fabula');
		
		elseif (is_home()) :
			
			echo esc_html__('The Blog', 'fabula');
		
		elseif (class_exists('WooCommerce') && is_shop()) :
			
			echo esc_html__('Shop', 'fabula');
		
		elseif (is_archive()) :
			
			echo get_the_archive_title();
		
		else :
			
			echo wp_get_document_title();
		
		endif;
		
	}
	
	/**
	 * @param $comment
	 * @param $args
	 * @param $depth
	 *
	 * @return void
	 */
	public static function custom_comment_template($comment, $args, $depth): void
	{
		
		$tag = ('div' === $args['style']) ? 'div' : 'li';
		
		$comment_id = get_comment_ID();
		
		?>
		
		<<?php echo esc_attr($tag); ?> data-comment-id="<?php echo esc_attr($comment_id); ?>" id="comment-<?php echo esc_attr($comment_id); ?>" <?php comment_class('d-f d-col'); ?>>
		
		<div class="comment-in d-f ai-s w-100 pos-rel">
			
			<div class="comment-avatar-wrp">
				<?php echo get_avatar($comment, 64); ?>
			</div>
			
			<div class="comment-meta-wrp">
				
				<div class="name-wrp d-f d-col-sm ai-s-sm ai-c mt-txt lnh-1 mb-12">
					
					<div class="comment-author vcard h-col m-fw">
						<?php echo get_comment_author_link(); ?>
					</div>
					
					<div class="comment-date ml-10 ml-0-sm mt-3-sm">
						<?php echo esc_attr(get_comment_date()); ?>
					</div>
				
				</div>
				
				<div class="comment-txt entry-content">
					
					<?php comment_text(); ?>
				
				</div>
			
			</div>
		
		</div>
		
		<?php
	}
	
	/**
	 * @return void
	 */
	public function enqueue_styles_theme(): void
	{
		
		/* get rtl global var from the customizer */
		$rtl = class_exists('jkdevkit') && jkdevkit\kirki::get_setting('gen-rtl-tg');
		
		/* assets suffixes array */
		$suffix = [];
		
		/* if rtl setting is true */
		if ($rtl) $suffix[] = '.rtl';
		
		/* implode all suffixes */
		$suffix = implode('', $suffix);
		
		/* check if woocommerce styles exists */
		if (!wp_style_is('jkd-theme-woo')) wp_enqueue_style('jkd-theme-woo', get_template_directory_uri() . '/assets/css/woo' . $suffix . '.min.css');
		
		/* check if main styles exists */
		if (!wp_style_is('jkd-theme-main')) wp_enqueue_style('jkd-theme-main', get_template_directory_uri() . '/assets/css/main' . $suffix . '.min.css');
		
	}
	
	/**
	 * @return void
	 */
	public function enqueue_scripts_theme(): void
	{
		
		/* enqueue comment-reply script */
		if (is_singular() && comments_open() && get_option('thread_comments')) wp_enqueue_script('comment-reply');
		
		/* check if main scripts exists and core plugin is not activated */
		if (!wp_script_is('jkd-theme-main') && !class_exists('jkdcore')):
			
			/* enqueue main scripts */
			wp_enqueue_script('jkd-theme-main', get_template_directory_uri() . '/assets/js/main.min.js', ['jquery', 'imagesloaded'],
				$this->version);
			
			/* localize jkd_ajax object */
			wp_localize_script('jkd-theme-main', 'jkd_ajax', [
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
			]);
		
		endif;
		
	}
	
	/**
	 * @return void
	 */
	public function init_navigation_area(): void
	{
		
		register_nav_menus([
			'primary_menu' => esc_html__('Navigation Menu', 'fabula'),
			'copyright_menu' => esc_html__('Copyright Menu', 'fabula'),
		]);
		
	}
	
	/**
	 * @return void
	 */
	public function init_sidebar_area(): void
	{
		
		/* register blog sidebar */
		register_sidebar([
			'name' => esc_html__('Blog sidebar', 'fabula'),
			'id' => 'blog-sidebar',
			'description' => esc_html__('Blog sidebar', 'fabula'),
			'before_widget' => '<div class="sb-wd-wrp"><div id="%1$s" class="sidebar-widget %2$s" >',
			'after_widget' => '</div></div>',
			'before_title' => '<h6 class="widget-title">',
			'after_title' => '</h6>',
		]);
		
	}
	
	/**
	 * @return void
	 */
	public function woo(): void
	{
		
		if (class_exists('WooCommerce')):
			
			add_filter('woocommerce_cart_item_remove_link', function ($remove_link, $cart_item_key) {
				
				global $woocommerce;
				$product_id = $woocommerce->cart->get_cart_item($cart_item_key)['product_id'];
				$_product = wc_get_product($product_id);
				$product_name = $_product->get_name();
				
				$custom_icon_html = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
				
				return sprintf(
					'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
					esc_url(wc_get_cart_remove_url($cart_item_key)),
					esc_attr(sprintf(__('Remove %s from cart', 'fabula'), wp_strip_all_tags($product_name))),
					esc_attr($product_id),
					esc_attr($_product->get_sku()),
					$custom_icon_html
				);
				
			}, 10, 2);
			add_filter('woocommerce_cart_item_thumbnail', function ($thumbnail, $cart_item, $cart_item_key) {
				
				$product = $cart_item['data'];
				$thumbnail_id = $product->get_image_id();
				
				if ($thumbnail_id) $thumbnail = wp_get_attachment_image($thumbnail_id);
				
				return $thumbnail;
				
			}, 10, 3);
			
			// Wrap checkout form elements for styling.
			add_action('woocommerce_checkout_before_order_review_heading', function () {
				
				echo '<div class="col2-set" id="order_review_wrp">';
				
			});
			add_action('woocommerce_checkout_after_order_review', function () {
				
				echo '</div>';
				
			});
			
			add_filter('woocommerce_checkout_fields', function ($fields) {
				
				// Add placeholder to the apartment field
				$fields['billing']['billing_address_2']['placeholder'] = __('Apartment, suite, unit, etc. (optional)', 'fabula');
				
				// Ensure the label is displayed for billing address 2 (apartment field)
				$fields['billing']['billing_address_2']['label'] = __('Apartment, suite, unit, etc.', 'fabula');
				
				// Add or adjust other placeholders and labels for fields if needed
				$fields['billing']['billing_first_name']['placeholder'] = __('First Name', 'fabula');
				$fields['billing']['billing_last_name']['placeholder'] = __('Last Name', 'fabula');
				$fields['billing']['billing_company']['placeholder'] = __('Company Name (optional)', 'fabula');
				$fields['billing']['billing_city']['placeholder'] = __('City', 'fabula');
				$fields['billing']['billing_postcode']['placeholder'] = __('ZIP Code', 'fabula');
				$fields['billing']['billing_email']['placeholder'] = __('Email Address', 'fabula');
				$fields['billing']['billing_phone']['placeholder'] = __('Phone Number', 'fabula');
				
				return $fields;
				
			});
			
			// Remove WooCommerce breadcrumb
			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
			
			add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60);
			
			add_action('woocommerce_before_shop_loop', function () {
				
				echo '<div class="shop-header-wrp d-f ai-c mb-30 mb-15-sm jc-sb d-col-sm ai-s-sm jc-fs-sm">';
				
			}, 15);
			add_action('woocommerce_before_shop_loop', function () {
				
				echo '</div>';
				
			}, 35);
			
			add_action('woocommerce_before_shop_loop_item_title', function () {
				
				echo '<div class="p-hdr">';
				
			}, 5);
			add_action('woocommerce_before_shop_loop_item_title', function () {
				
				echo '</div>';
				
			}, 15);
			
			add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
				
				return [
					'width' => 768,
					'height' => 768,
					'crop' => 1
				];
				
			});
			
			add_action('woocommerce_before_single_product_summary', function () {
				
				echo '<div class="product-wrp d-f d-col-md">';
				
			}, 5);
			add_action('woocommerce_after_single_product_summary', function () {
				
				echo '</div>';
				
			}, 5);
			
			add_action('woocommerce_after_single_product_summary', function () {
				
				$related_products = wc_get_related_products(get_the_ID());
				
				if (!empty($related_products)) echo '<div class="rel-wrp">';
				
			}, 15);
			add_action('woocommerce_after_single_product_summary', function () {
				
				$related_products = wc_get_related_products(get_the_ID());
				
				if (!empty($related_products)) echo '</div>';
				
			}, 25);
			
			add_filter('woocommerce_pagination_args', function ($args) {
				
				$args['prev_text'] = '<div class="pag-arrow prev-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg></div>';
				$args['next_text'] = '<div class="pag-arrow next-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></div>';
				
				return $args;
			});
		
		endif;
		
	}
	
	/* ------------- INSTALLATION & GENERAL ------------- */
	
	/**
	 * @return array
	 */
	private function get_plugins_list(): array
	{
		
		$core_plugin_url = get_option('jkd_core_plugin_url');
		
		$plugins = [
			['name' => 'JKDEVKIT', 'slug' => 'jkdevkit', 'source' => get_template_directory_uri() . '/plugins/jkdevkit.zip'],
			['name' => 'Advanced Custom Fields PRO', 'slug' => 'advanced-custom-fields-pro', 'source' => get_template_directory_uri() . '/plugins/advanced-custom-fields-pro.zip'],
			['name' => 'Paid Memberships Pro', 'slug' => 'paid-memberships-pro', 'required' => false, 'source' => get_template_directory_uri() . '/plugins/paid-memberships-pro.zip'],
			['name' => 'Kirki', 'slug' => 'kirki'],
			['name' => 'Elementor', 'slug' => 'elementor'],
			['name' => 'One Click Demo Import', 'slug' => 'one-click-demo-import', 'required' => false],
			['name' => 'MailChimp for WordPress', 'slug' => 'mailchimp-for-wp', 'required' => false],
			['name' => 'Contact Form 7', 'slug' => 'contact-form-7', 'required' => false],
			['name' => 'WooCommerce', 'slug' => 'woocommerce', 'required' => false],
			['name' => 'Smash Balloon Instagram Feed', 'slug' => 'instagram-feed', 'required' => false],
			['name' => 'AddToAny Share Buttons', 'slug' => 'add-to-any', 'required' => false],
			['name' => 'Co-Authors Plus', 'slug' => 'co-authors-plus', 'required' => false],
			['name' => 'Easy Table of Contents', 'slug' => 'easy-table-of-contents', 'required' => false],
			['name' => 'Boxzilla', 'slug' => 'boxzilla', 'required' => false],
			['name' => 'wpDiscuz', 'slug' => 'wpdiscuz', 'required' => false],
			['name' => 'Disqus for WordPress', 'slug' => 'disqus-comment-system', 'required' => false],
			['name' => 'CookieYes | GDPR Cookie Consent', 'slug' => 'cookie-law-info', 'required' => false],
			['name' => 'Classic Widgets', 'slug' => 'classic-widgets', 'required' => false],
		];
		
		if (!empty($core_plugin_url)) $plugins[] = [
			'name' => 'JKD Theme Core Plugin',
			'slug' => 'jkd-wp-fabula-core',
			'source' => $core_plugin_url,
			'required' => true,
			'force_activation' => false,
			'force_deactivation' => false
		];
		
		return $plugins;
		
	}
	
	/**
	 * Updates the core plugin URL.
	 *
	 * @return void
	 */
	public function core_plugin_url_update(): void
	{
		
		$data = array();
		$data['url'] = get_template_directory_uri() . '/plugins/jkd-wp-fabula-core.zip';
		
		if (!empty($data['url'])):
			
			update_option('jkd_core_plugin_url', $data['url']);
		
		else :
			
			update_option('jkd_core_plugin_url', null);
		
		endif;
		
	}
	
	/**
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public function custom_cron_intervals($schedules): mixed
	{
		
		$schedules['core_plugin_url_update'] = [
			'interval' => DAY_IN_SECONDS,
			'display' => esc_html__('Every 24 Hours', 'fabula'),
		];
		
		return $schedules;
		
	}
	
	/**
	 * @return void
	 */
	public function cron_setup(): void
	{
		
		$core_plugin_url = get_option('jkd_core_plugin_url');
		
		add_filter('cron_schedules', [$this, 'custom_cron_intervals']);
		
		if (!wp_next_scheduled('core_plugin_url_update')) wp_schedule_event(time(), 'core_plugin_url_update', 'core_plugin_url_update');
		
		add_action('core_plugin_url_update', [$this, 'core_plugin_url_update']);
		
		if (empty(get_option('jkd_core_plugin_url')) && get_option('jkd_core_plugin_url') !== null
			|| !empty($_GET['page']) && $_GET['page'] === 'tgmpa-install-plugins') $this->core_plugin_url_update();
		
		if (empty($core_plugin_url)) add_action('admin_notices', function () {
			
			$notice = '<div class="notice notice-error"><p>';
			$notice .= esc_html__('Unfortunately, your installation couldn\'t retrieve the correct URL for the core theme plugin. This could be a result of incorrect activation or third-party interference. Please contact support for further instructions.', 'fabula');
			$notice .= '</p></div>';
			echo wp_kses_post($notice);
			
		});
		
	}
	
	/**
	 * @return void
	 */
	public function theme_setup(): void
	{
		
		/* add editor styles */
		add_editor_style();
		
		/* add automatic-feed-links support */
		add_theme_support('automatic-feed-links');
		
		/* add post-thumbnails support */
		add_theme_support('post-thumbnails');
		
		/* add html5 support */
		add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);
		
		/* add customize-selective-refresh-widgets support */
		add_theme_support('customize-selective-refresh-widgets');
		
		/* add post-formats support */
		add_theme_support('post-formats', ['video', 'gallery', 'quote', 'audio']);
		
		/* add title-tag support */
		add_theme_support("title-tag");
		
		/* add editor-styles support */
		add_theme_support('editor-styles');
		
		/* check if WooCommerce exists */
		if (class_exists('WooCommerce')):
			
			/* add woocommerce support */
			add_theme_support('woocommerce');
			add_theme_support('wc-product-gallery-zoom');
			add_theme_support('wc-product-gallery-lightbox');
			add_theme_support('wc-product-gallery-slider');
		
		endif;
		
		// Enqueue editor styles
		add_editor_style(get_template_directory_uri() . '/assets/css/block.min.css');
		
		/* check if content width exists */
		if (!isset($content_width)) $content_width = 1200;
		
	}
	
	/**
	 * @return void
	 */
	public function i18n(): void
	{
		
		/* register theme text domain */
		load_theme_textdomain('fabula', get_template_directory() . '/lang');
		
	}
	
	/**
	 * Register required plugins for the theme.
	 *
	 * @return void
	 */
	public function register_required_plugins(): void
	{
		
		$default_plugins = $this->get_plugins_list();
		
		$plugins = array_map(function ($plugin) {
			
			return array_merge([
				'required' => true,
				'force_activation' => false,
				'force_deactivation' => false,
			], $plugin);
			
		}, $default_plugins);
		
		$config = [
			'id' => 'fabula',
			'menu' => 'tgmpa-install-plugins',
			'parent_slug' => 'themes.php',
			'capability' => 'edit_theme_options',
			'has_notices' => true,
			'dismissable' => true,
			'is_automatic' => false,
		];
		
		tgmpa($plugins, $config);
		
	}
	
	/**
	 * @return array[]
	 */
	public function ocdi_import_files(): array
	{
		
		/* get current theme */
		$my_theme = wp_get_theme();
		
		/* get theme name */
		$name = empty($my_theme->parent()['Name']) ? $my_theme['Name'] : $my_theme->parent()['Name'];
		
		return [
			[
				'import_file_name' => esc_html($name),
				'import_file_url' => $this->assets_url . 'demo.xml',
				'categories' => ['Main Demo'],
				'import_widget_file_url' => $this->assets_url . 'widgets.json',
				'import_preview_image_url' => $this->assets_url . 'demo_preview.png',
				'import_notice' => esc_html__('The demo import time depends on your server’s configuration. If you experience any delays or errors, first check the', 'fabula') . ' ' .
					'<a href="https://docs.jkdevstudio.com/knowledge-base/demo-import-troubleshooting/" target="_blank">' . esc_html__('documentation', 'fabula') . '</a>' . ' ' .
					esc_html__('for common solutions. If the issue persists, feel free to', 'fabula') . ' ' .
					'<a href="https://jkdevstudio.ticksy.com/" target="_blank">' . esc_html__('contact support', 'fabula') . '</a>' . '.' . '<br> ' .
					esc_html__('Additionally, please install this plugin:', 'fabula') . ' ' .
					'<a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">' . esc_html__('Regenerate Thumbnails', 'fabula') . '</a>' . ' ' .
					esc_html__('and run it in Tools > Regenerate Thumbnails. This will then create the smaller versions of images that were skipped during the import.', 'fabula'),
				'preview_url' => 'https://fabula-theme.jkdevstudio.com/',
			],
		];
		
	}
	
	/**
	 * @param $plugins
	 *
	 * @return array
	 */
	public function ocdi_register_plugins($plugins): array
	{
		
		$theme_plugins = array_map(function ($plugin) {
			
			return [
				'name' => $plugin['name'],
				'slug' => $plugin['slug'],
				'required' => $plugin['required'] ?? true,
				'source' => $plugin['source'] ?? '',
				'preselected' => $plugin['preselected'] ?? false,
			];
		}, $this->get_plugins_list());
		
		return array_merge($plugins, $theme_plugins);
		
	}
	
	/**
	 * Performs setup after import, setting up pages, menus, options, and permalinks.
	 *
	 * @param $selected_import
	 *
	 * @return void
	 */
	public function ocdi_after_import_setup($selected_import): void
	{
		
		global $wp_rewrite;
		
		// Disable WordPress's rewrite rules caching
		update_option("rewrite_rules", false);
		
		// Setup essential pages
		$this->setup_pages();
		
		// Update plugin options
		$this->update_plugin_options();
		
		// Setup menus
		$this->assign_menus();
		
		// Configure permalink structure
		$this->configure_permalinks($wp_rewrite);
		
	}
	
	/**
	 * Sets up front page, posts page, and wishlist page if applicable.
	 */
	private function setup_pages(): void
	{
		
		$front_page_id = $this->find_or_fallback_page(['home-1', 'home-2']);
		$blog_page_id = $this->find_page('blog');
		
		update_option('show_on_front', 'page');
		update_option('page_on_front', $front_page_id);
		update_option('page_for_posts', $blog_page_id);
		
		$this->maybe_setup_wishlist();
		
	}
	
	/**
	 * Finds a page by path, falling back to secondary options if needed.
	 */
	private function find_or_fallback_page(array $paths): int
	{
		
		foreach ($paths as $path) :
			
			$page = get_page_by_path($path);
			
			if (!empty($page)) return $page->ID;
		
		endforeach;
		
		return 0;
		
	}
	
	/**
	 * Finds a page by its path.
	 */
	private function find_page(string $path): int
	{
		
		$page = get_page_by_path($path);
		
		return $page ? $page->ID : 0;
		
	}
	
	/**
	 * Setup wishlist page if the relevant function exists.
	 */
	private function maybe_setup_wishlist(): void
	{
		
		if (function_exists('tinv_update_option')) :
			
			$wishlist_page_id = $this->find_page('wishlist');
			
			tinv_update_option('page', 'wishlist', $wishlist_page_id);
		
		endif;
		
	}
	
	/**
	 * Updates options for all third-party plugins
	 */
	private function update_plugin_options(): void
	{
		
		/* demo font setup */
		$this->getInstance()->getFontsManager()->add_google_font('Poppins');
		
		$this->update_kirki_options();
		$this->update_addtoany_options();
		$this->update_eztoc_options();
		if (defined('PMPRO_VERSION')) $this->maybe_update_pmpro_options();
		if (defined('ELEMENTOR_VERSION')) $this->maybe_update_elementor_settings();
		
	}
	
	/**
	 * Updates PMPRO options
	 *
	 * @return void
	 */
	public function maybe_update_pmpro_options(): void
	{
		
		update_option('pmpro_style_variation', 'variation_1');
		update_option('pmpro_filterqueries', false);
		
	}
	
	/**
	 * Updates Kirki options from a remote JSON file.
	 *
	 * @return \WP_Error|null
	 */
	public function update_kirki_options(): ?WP_Error
	{
		
		$remote_json_url = $this->assets_url . 'kirki_options.json';
		
		return $this->update_options_from_json($remote_json_url, 'jkd_kirki', '{SITE_URL}', site_url());
		
	}
	
	/**
	 * Fetches remote JSON and updates the specified option.
	 *
	 * @param string $json_url      URL to fetch the JSON data.
	 * @param string $option_name   Name of the WordPress option to update.
	 * @param string $replace_key   Optional. Key to replace in the JSON body.
	 * @param string $replace_value Value to replace in the JSON body.
	 *
	 * @return void|\WP_Error
	 */
	public static function update_options_from_json(string $json_url, string $option_name, string $replace_key = '', string $replace_value = '')
	{
		
		$response = wp_remote_get($json_url, [
			'headers' => ['Accept' => 'application/json']
		]);
		
		if (is_wp_error($response)) return new WP_Error('remote_request_failed', 'Failed to fetch remote JSON data', ['status' => 500]);
		
		$json_body = wp_remote_retrieve_body($response);
		if (!empty($replace_key)) $json_body = str_replace($replace_key, $replace_value, $json_body);
		
		$plugin_options = json_decode($json_body, true);
		if (empty($plugin_options)) return new WP_Error('json_parse_failed', 'Failed to parse JSON data', ['status' => 500]);
		
		update_option($option_name, $plugin_options);
		
	}
	
	/**
	 * Updates AddToAny options from a remote JSON file.
	 *
	 * @return void
	 */
	public function update_addtoany_options(): void
	{
		
		$json_url = $this->assets_url . 'addtoany.json';
		$this->update_options_from_json($json_url, 'addtoany_options');
		
	}
	
	/**
	 * Updates Easy Table of Contents options from a remote JSON file.
	 *
	 * @return void
	 */
	public function update_eztoc_options(): void
	{
		
		$json_url = $this->assets_url . 'eztoc.json';
		$this->update_options_from_json($json_url, 'ez-toc-settings');
		
	}
	
	/**
	 * Update Elementor settings if applicable.
	 */
	private function maybe_update_elementor_settings(): void
	{
		
		update_option('elementor_disable_color_schemes', 'yes');
		update_option('elementor_disable_typography_schemes', 'yes');
		$this->setup_elementor_kit();
		
	}
	
	/**
	 * Setup Elementor kit settings.
	 */
	private function setup_elementor_kit(): void
	{
		
		$kit = get_option('elementor_active_kit');
		if (empty($kit)) return;
		
		$options = get_post_meta($kit, '_elementor_page_settings', true) ?: [];
		$default_width = 1280;
		
		$options['container_width'] = $options['container_width'] ?? ['unit' => 'px', 'sizes' => []];
		$options['container_width']['size'] = $default_width;
		
		$options['system_colors'] = [
			[
				'_id' => 'primary',
				'title' => 'Primary',
				'color' => '#FF642D',
			],
			[
				'_id' => 'secondary',
				'title' => 'Secondary',
				'color' => '#000000',
			],
			[
				'_id' => 'text',
				'title' => 'Text',
				'color' => '#000000',
			],
			[
				'_id' => 'accent',
				'title' => 'Accent',
				'color' => '#000000',
			],
		];
		
		$options['custom_colors'] = [
			[
				'_id' => 'cbf0e76',
				'title' => 'Border Color',
				'color' => '#98989866',
			],
		];
		
		update_post_meta($kit, '_elementor_page_settings', $options);
		
	}
	
	/**
	 * Assigns navigation and footer menus.
	 */
	private function assign_menus(): void
	{
		
		$navigation_menu = get_term_by('name', 'Main Menu', 'nav_menu');
		$copyright_menu = get_term_by('name', 'Copyright Menu', 'nav_menu');
		
		set_theme_mod('nav_menu_locations', [
			'primary_menu' => $navigation_menu->term_id,
			'copyright_menu' => $copyright_menu->term_id,
		]);
		
	}
	
	/**
	 * Configures and flushes the permalink structure.
	 */
	private function configure_permalinks($wp_rewrite): void
	{
		
		$wp_rewrite->set_permalink_structure('/%postname%/');
		$wp_rewrite->flush_rules(true);
		
	}
	
	/**
	 * @return void
	 * @throws \Exception
	 */
	private function fonts_setup(): void
	{
		
		$fonts = jkdtheme::getInstance()->getFontsManager()->get_fonts_library_list();
		
		if (empty($fonts)):
			
			$this->getInstance()->getFontsManager()->add_google_font('Poppins');
			
			$this->getInstance()->getControlsManager()->set_option('fonts-load', [
				'fabula-heading-font' => 'gf-poppins',
				'fabula-text-font' => 'gf-poppins',
			]);
		
		endif;
		
		update_option('jkd_fonts_check', true);
		
	}
	
	/**
	 * Initializes the update process.
	 *
	 * @return void
	 */
	public function init_upd(): void
	{
		
		global $wp;
		
		$data = jkdtheme::getInstance()->getActivationData();
		
		// Exit early if no data is available
		if (empty($data)) return;
		
		$hostname = esc_url(home_url($wp->request));
		$code = $data['license']['purchase_code'] ?? '';
		$domain = $data['activation']['domain'] ?? '';
		
		// Construct the URL for the update checker
		$queryArgs = [
			'type' => 'theme',
			'id' => urlencode(jkdtheme::getInstance()->getId()),
			'purchase_code' => urlencode($code),
			'hostname' => urlencode($hostname),
			'domain' => urlencode($domain),
		];
		
		$url = 'https://hub.jkdevstudio.com/jkd-json/update-manager/v1/config/?' . http_build_query($queryArgs);
		
		// Build the update checker with the constructed URL
		PucFactory::buildUpdateChecker(
			$url,
			get_template_directory(),
			$this->slug
		);
		
	}
	
}