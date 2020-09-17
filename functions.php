<?php

// tema setup
function wooshop_setup(){
add_theme_support('automatic-feed-links');
add_theme_support('html5',array('search-form','comment-form','comment-list','gallery','caption'));

//Menüleri buradan ekliyoruz.
register_nav_menu('primary',__('Ana Menü','wooshop') );
register_nav_menu('footermenu1',__('Footer Menü 1', 'wooshop'));
register_nav_menu('footermenu2',__('Footer Menü2','wooshop') );


add_theme_support('post-thumbnails');
set_post_thumbnail_size(604,270,true);

add_filter('use_defualt_gallery_style','	__return_false');

add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');


}

add_action('after_setup_theme','wooshop_setup');

//all the scripts and styles 
function wooshop_scripts_styles(){

	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/inc/bootstrap/css/bootstrap.min.css', array() );
	wp_enqueue_style( 'slider',get_template_directory_uri() . '/inc/slider/css/swiper.min.css', array() );

	wp_enqueue_style( 'fontawesome',get_template_directory_uri() . '/inc/fontawesome/css/all.min.css', array() );
	wp_enqueue_style( 'wooshop_style',get_template_directory_uri() . '/style.css', array() );
	wp_enqueue_style( 'responsive',get_template_directory_uri() . '/css/responsive.css', array() );
	


	wp_enqueue_script( 'Popper',get_template_directory_uri() . '/inc/popper.min.js', array('jquery'));
	wp_enqueue_script( 'bootstrap-js',get_template_directory_uri() . '/inc/bootstrap/js/bootstrap.min.js', array('jquery'),'2016-19-08',true);
	wp_enqueue_script( 'slider2',get_template_directory_uri() . '/inc/slider/js/swiper.min.js', array('jquery'),'2016-19-08',true);
	wp_enqueue_script( 'slider3',get_template_directory_uri() . '/inc/slider/js/swiper.thumbnails.js', array('jquery'),'2016-19-08',true);
	wp_enqueue_script( 'jsscript',get_template_directory_uri() . '/inc/scripts.js', array('jquery'),'2016-19-08',true);
	
}


add_action('wp_enqueue_scripts','wooshop_scripts_styles');






/* ACF Bileşeni*/

add_filter('acf/settings/path', 'my_acf_settings_path');
function my_acf_settings_path( $path ) {
    $path = get_stylesheet_directory() . '/inc/acf/';
    return $path;
    
}
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
 
    $dir = get_stylesheet_directory_uri() . '/inc/acf/';
    return $dir;
    
}
//add_filter('acf/settings/show_admin', '__return_false');

include_once( get_stylesheet_directory() . '/inc/acf/acf.php' );


// sepet güncelleyici
if( function_exists('acf_add_options_page') ) {	 
	
	acf_add_options_page(array(
		'page_title' 	=> 'Site Ayarları',
		'menu_title'	=> 'Site Ayarları',
		'menu_slug' 	=> 'site-ayarlari',
		'capability'	=> 'manage_options',
		'redirect'		=> false,
		'update_button'		=> __('Güncelle', 'acf'),
		'updated_message'	=> __("Ayarlar Güncellendi", 'acf'),
	));
	

	
}
function wookod_sepet( $urunler){
	ob_start();  // sayfayı yenilemeden sepeti güncel tutma
	?>
	<span class="sepet-guncel">
		<span class="sepet-urun"><?php echo WC()-> cart->cart_contents_count;?></span>
		<span class = sepet-fiyat"><?php echo WC()->cart->get_cart_total();?></span>
		
	</span>
	<?php

	$urunler['.sepet-guncel']=ob_get_clean();
	return $urunler;

}

add_filter('woocommerce_add_to_cart_fragments','wookod_sepet');


//Klasik editöre geri dönmek için ekle.
add_filter('use_block_editor_for_post','__return_false',10);
add_filter('use_block_editor_for_post_type','__return_false',10);


// Resim boyutları

function resim_boyutlari (){
	add_image_size('thumb-blog-kapak',1110,438,true);
	add_image_size('thumb-kategori-2-kapak',350,206,true);
}

add_action('after_setup_theme','resim_boyutlari'); 


//Özel Özet

function ozel_ozet(){
$excerpt = get_the_content();
$excerpt = preg_replace("([.*?])",'',$excerpt);
$excerpt = strip_shortcodes($excerpt);
$excerpt =strip_tags($excerpt);
$excerpt = substr($excerpt,0,250);
$excerpt = substr($excerpt,0,strripos($excerpt,""));
$excerpt= trim (preg_replace('/\s+/','',$excerpt));
$excerpt = $excerpt.'<div class ="clearfix"</div> <a href="'.get_the_permalink().'" class = "devamini-oku">	devamını oku <i class="fas fa-long-arrow-alt-right"></i></a>';
return $excerpt;
}


//Tab 2. başlığını kapatan filtre

add_filter('woocommerce_product_description_heading','__return_null');
add_filter('woocommerce_product_additional_information_heading','__return_null');


// Ürün detay Sayfasında Tab İsimlendirme
add_filter('woocommerce_product_tabs','tab_isimlendirme',98);
function tab_isimlendirme($tabs){

	$tabs['description']['title'] = __('Genel Bakış');


	return $tabs;
}

//İlgili ürünler ve çarpraz satış kolonları
add_filter('woocommerce_output_related_products_args','ilgili_urunler_args',20);

function ilgili_urunler_args($args){
	$args['posts_per_page'] =6 ;
	$args['columns'] = 6;
	return $args;
}


add_filter('woocommerce_upsell_display_args','capraz_satis_args',20);

function capraz_satis_args($args){
	$args['posts_per_page'] = 6;
	$args['columns'] =6;
	return $args;
}
/*
//Mağaza başlığını gizler

add_filter('woocommerce_show_page_title',' magaza_basligi_gizle');
function magaza_basligi_gizle($title){
	if(is_shop()) $title = false;
	return $title;
}

*/
// ozel magaza sidebari


function magaza_sidebar(){

register_sidebar(
array(
'name' =>__ ('Mağaza Sidebarı','wookod'),
'id'   => 'magaza-sidebar',
'description' =>__('Mağaza Sidebarı','wookod'),
'before_widget' =>'<div class="beyaz-kutu">',
'after_widget' =>  "</div>",
'before_title' =>'<h3 class="tab-baslik kalin-baslik">',
'after_title'  =>'</h3><div class = "clearfix"></div>',
)
);


register_sidebar(
	array(
	'name' =>'Blog Sidebar',
	'id ' => 'blog-sidebar',
	'description' => "Blog Sidebar",
	'before_widget' =>'<div id="%1$s" class="widget %2$s" >',
	'after_widget' => '</div>',
	'before_title' => '<h3 class= "tab-baslik kalin-baslik">',
	'after_title ' => '</h3><div class="clearfix"></div>'
)
);
}


add_action('widgets_init','magaza_sidebar');


add_filter('woocommerce_redirect_single_search_result','__return_false');

// Sepet sayfasında otomatik güncelleme

add_action('wp_footer','otomatik_sepet_guncelleme');
function otomatik_sepet_guncelleme(){
	if(is_cart()){
		?>
			<script type="text/javascript">
				jQuery('div.woocommerce').on('click','input.qty',function(){
					jQuery("[name='update_cart']").trigger("click");

				});
			</script>
			<?php
	}
}

// Ödeme ekranı düzenlemeleri

add_filter('woocommerce_checkout_fields','odeme_alan_ozellestirmeleri');
function odeme_alan_ozellestirmeleri($fields){
	unset($fields['billing']['billing_postcode']);
// 	unset($fields['order']['order_comments']);

	return $fields;
}


// Extra sipariş alanı ekleme

add_action('woocommerce_after_checkout_billing_form','ekstra_alan');

function ekstra_alan($checkout){

woocommerce_form_field('tckimlikno',array(
'type' => 'text',
'class' => array('my-field-class form-row-wide'),
'label' => __('TC Kimlik Numaranız'),
'placeholder' => __('TC Kimlik Numaranız'),
'required' => 'true'
),


$checkout->get_value('tckimlikno'));
}


add_action('woocommerce_checkout_process','ozel_alan_uyari');

function ozel_alan_uyari(){
	if(!$_POST['tckimlikno']) wc_add_notice(__('Lütfen 11 haneli TC kimlik numaranızı yazın'),'error');
}


// doldurulan alanı taşımak için

add_action('woocommerce_checkout_update_order_meta','ozel_alan_sipariste_guncelleme');

function ozel_alan_sipariste_guncelleme($order_id){
	if($_POST['tckimlikno']) update_post_meta($order_id,'_tckimlikno',sanitize_text_field($_POST['tckimlikno']));
}


add_action('woocommerce_admin_order_data_after_billing_address','ozel_alan_sipariste_gosterme',10,1);

function ozel_alan_sipariste_gosterme($order){
	echo '<p><strong>'.__('Müşteri TC kimlik numarası').':</strong>'. get_post_meta($order->id,'_tckimlikno',true) . '</p>';
}