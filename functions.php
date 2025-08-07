<?php
/**
 * DevLog Developer Blog Theme Functions
 *
 * @package DevLog
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function devlog_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('custom-background');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style();

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'devlog'),
        'footer' => __('Footer Menu', 'devlog'),
    ));

    // Set content width
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'devlog_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function devlog_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('devlog-fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('devlog-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    wp_enqueue_style('devlog-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue scripts
    wp_enqueue_script('devlog-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('devlog-main', 'devlog_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('devlog_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'devlog_enqueue_scripts');

/**
 * Register Widget Areas
 */
function devlog_widgets_init() {
    register_sidebar(array(
        'name'          => __('Blog Sidebar', 'devlog'),
        'id'            => 'blog-sidebar',
        'description'   => __('Add widgets for blog pages', 'devlog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'devlog'),
        'id'            => 'footer-widgets',
        'description'   => __('Add widgets for footer area', 'devlog'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'devlog_widgets_init');

/**
 * Custom Logo Setup
 */
function devlog_custom_logo_setup() {
    $defaults = array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'devlog_custom_logo_setup');

/**
 * Get Custom Logo or Site Title
 */
function devlog_get_logo() {
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        return get_custom_logo();
    } else {
        return '<a href="' . esc_url(home_url('/')) . '" class="nav__logo">' . get_bloginfo('name') . '</a>';
    }
}

/**
 * Excerpt Length
 */
function devlog_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'devlog_excerpt_length');

/**
 * Excerpt More
 */
function devlog_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'devlog_excerpt_more');

/**
 * Get Post Reading Time
 */
function devlog_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    
    return $reading_time . ' dk okuma';
}

/**
 * Get Post Categories as Tags
 */
function devlog_get_post_categories($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    $output = '';
    
    if (!empty($categories)) {
        foreach ($categories as $category) {
            $output .= '<span class="blog__tag">' . esc_html($category->name) . '</span>';
        }
    }
    
    return $output;
}

/**
 * Custom Post Meta
 */
function devlog_post_meta() {
    echo '<div class="blog__meta">';
    echo '<span class="blog__date">' . get_the_date('j F Y') . '</span>';
    echo '<span class="blog__read-time">' . devlog_reading_time() . '</span>';
    echo '</div>';
}

/**
 * Navigation for Single Posts
 */
function devlog_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ($prev_post || $next_post) {
        echo '<nav class="post-navigation">';
        
        if ($prev_post) {
            echo '<div class="nav-previous">';
            echo '<a href="' . get_permalink($prev_post) . '">';
            echo '<span class="nav-label">Önceki Yazı</span>';
            echo '<span class="nav-title">' . get_the_title($prev_post) . '</span>';
            echo '</a>';
            echo '</div>';
        }
        
        if ($next_post) {
            echo '<div class="nav-next">';
            echo '<a href="' . get_permalink($next_post) . '">';
            echo '<span class="nav-label">Sonraki Yazı</span>';
            echo '<span class="nav-title">' . get_the_title($next_post) . '</span>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</nav>';
    }
}

/**
 * Comments Template
 */
function devlog_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-body">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar($comment, 60); ?>
                    <b class="fn"><?php echo get_comment_author_link(); ?></b>
                </div>
                <div class="comment-metadata">
                    <time datetime="<?php comment_time('c'); ?>">
                        <?php printf('%1$s %2$s', get_comment_date(), get_comment_time()); ?>
                    </time>
                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
            </div>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
        </div>
    <?php
}

/**
 * Pagination
 */
function devlog_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) return;
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    
    if ($paged >= 1) $links[] = $paged;
    if ($paged >= 3) $links[] = $paged - 1;
    if ($paged >= 2) $links[] = $paged - 1;
    if ($paged < $max) $links[] = $paged + 1;
    if ($paged + 1 < $max) $links[] = $paged + 1;
    if ($paged + 2 < $max) $links[] = $paged + 2;
    
    echo '<nav class="pagination">';
    
    if ($paged > 1) {
        echo '<a href="' . get_pagenum_link($paged - 1) . '" class="pagination-prev">Önceki</a>';
    }
    
    echo '<ul class="pagination-list">';
    
    if (!in_array(1, $links)) {
        if ($paged == 1) {
            echo '<li class="current">1</li>';
        } else {
            echo '<li><a href="' . get_pagenum_link(1) . '">1</a></li>';
        }
        
        if (!in_array(2, $links)) echo '<li class="dots">…</li>';
    }
    
    sort($links);
    foreach ((array) $links as $link) {
        if ($paged == $link) {
            echo '<li class="current">' . $link . '</li>';
        } else {
            echo '<li><a href="' . get_pagenum_link($link) . '">' . $link . '</a></li>';
        }
    }
    
    if (!in_array($max, $links)) {
        if (!in_array($max - 1, $links)) echo '<li class="dots">…</li>';
        echo '<li><a href="' . get_pagenum_link($max) . '">' . $max . '</a></li>';
    }
    
    echo '</ul>';
    
    if ($paged < $max) {
        echo '<a href="' . get_pagenum_link($paged + 1) . '" class="pagination-next">Sonraki</a>';
    }
    
    echo '</nav>';
}

/**
 * Breadcrumbs
 */
function devlog_breadcrumbs() {
    if (is_home() || is_front_page()) return;
    
    echo '<nav class="breadcrumbs">';
    echo '<a href="' . home_url() . '">Ana Sayfa</a>';
    
    if (is_category() || is_single()) {
        echo ' <span class="separator">></span> ';
        the_category(' <span class="separator">></span> ');
        
        if (is_single()) {
            echo ' <span class="separator">></span> ';
            the_title();
        }
    } elseif (is_page()) {
        echo ' <span class="separator">></span> ';
        the_title();
    } elseif (is_search()) {
        echo ' <span class="separator">></span> Arama: "' . get_search_query() . '"';
    } elseif (is_404()) {
        echo ' <span class="separator">></span> 404 - Sayfa Bulunamadı';
    }
    
    echo '</nav>';
}

/**
 * Contact Form Handler
 */
function devlog_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['devlog_contact_nonce'], 'devlog_contact_form')) {
        wp_die('Güvenlik doğrulaması başarısız!');
    }
    
    // Sanitize form data
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_redirect(home_url('/?contact=error'));
        exit;
    }
    
    if (!is_email($email)) {
        wp_redirect(home_url('/?contact=error'));
        exit;
    }
    
    // Prepare email
    $to = get_option('admin_email');
    $email_subject = 'İletişim Formu: ' . $subject;
    $email_message = "Ad: $name\n";
    $email_message .= "E-posta: $email\n\n";
    $email_message .= "Mesaj:\n$message";
    
    $headers = array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'Content-Type: text/plain; charset=UTF-8'
    );
    
    // Send email
    if (wp_mail($to, $email_subject, $email_message, $headers)) {
        wp_redirect(home_url('/?contact=success'));
    } else {
        wp_redirect(home_url('/?contact=error'));
    }
    
    exit;
}
add_action('wp_ajax_devlog_contact', 'devlog_handle_contact_form');
add_action('wp_ajax_nopriv_devlog_contact', 'devlog_handle_contact_form');

/**
 * Customizer
 */
function devlog_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('devlog_hero', array(
        'title' => __('Hero Section', 'devlog'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('hero_title', array(
        'default' => 'Backend Developer',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'devlog'),
        'section' => 'devlog_hero',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_description', array(
        'default' => 'Ölçeklenebilir web uygulamaları ve API\'ler geliştiren deneyimli bir backend developer\'ım.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_description', array(
        'label' => __('Hero Description', 'devlog'),
        'section' => 'devlog_hero',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('hero_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label' => __('Hero Image', 'devlog'),
        'section' => 'devlog_hero',
    )));
    
    // About Section
    $wp_customize->add_section('devlog_about', array(
        'title' => __('About Section', 'devlog'),
        'priority' => 31,
    ));
    
    $wp_customize->add_setting('about_description', array(
        'default' => '5+ yıllık deneyime sahip bir backend developer olarak...',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('about_description', array(
        'label' => __('About Description', 'devlog'),
        'section' => 'devlog_about',
        'type' => 'textarea',
    ));
    
    // Experience Section Settings
    $wp_customize->add_section('devlog_experience', array(
        'title' => __('Experience Section', 'devlog'),
        'priority' => 35,
    ));

    $wp_customize->add_setting('experience_subtitle', array(
        'default' => 'Profesyonel Geçmiş',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('experience_subtitle', array(
        'label' => __('Experience Subtitle', 'devlog'),
        'section' => 'devlog_experience',
        'type' => 'text'
    ));

    $wp_customize->add_setting('experience_title', array(
        'default' => 'İş Tecrübelerim',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('experience_title', array(
        'label' => __('Experience Title', 'devlog'),
        'section' => 'devlog_experience',
        'type' => 'text'
    ));

    // Skills Section Settings
    $wp_customize->add_section('devlog_skills', array(
        'title' => __('Skills Section', 'devlog'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('skills_subtitle', array(
        'default' => 'Teknik Bilgiler',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('skills_subtitle', array(
        'label' => __('Skills Subtitle', 'devlog'),
        'section' => 'devlog_skills',
        'type' => 'text'
    ));

    $wp_customize->add_setting('skills_title', array(
        'default' => 'Yeteneklerim',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('skills_title', array(
        'label' => __('Skills Title', 'devlog'),
        'section' => 'devlog_skills',
        'type' => 'text'
    ));
    
    $wp_customize->add_section('devlog_contact', array(
        'title' => __('Contact Information', 'devlog'),
        'priority' => 32,
    ));
    
    $wp_customize->add_setting('contact_email', array(
        'default' => 'developer@devlog.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('contact_email', array(
        'label' => __('Contact Email', 'devlog'),
        'section' => 'devlog_contact',
        'type' => 'email',
    ));
    
    $wp_customize->add_setting('social_github', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('social_github', array(
        'label' => __('GitHub URL', 'devlog'),
        'section' => 'devlog_contact',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('social_linkedin', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('social_linkedin', array(
        'label' => __('LinkedIn URL', 'devlog'),
        'section' => 'devlog_contact',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('social_twitter', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('social_twitter', array(
        'label' => __('Twitter URL', 'devlog'),
        'section' => 'devlog_contact',
        'type' => 'url',
    ));
}
add_action('customize_register', 'devlog_customize_register');

/**
 * Body Classes
 */
function devlog_body_classes($classes) {
    // Add dark theme class by default
    $classes[] = 'dark-theme';
    
    // Add page-specific classes
    if (is_home() || is_front_page()) {
        $classes[] = 'home-page';
    }
    
    if (is_single()) {
        $classes[] = 'single-post';
    }
    
    if (is_page()) {
        $classes[] = 'single-page';
    }
    
    return $classes;
}
add_filter('body_class', 'devlog_body_classes');

/**
 * Add Meta Tags to Head
 */
function devlog_meta_tags() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
    
    if (is_single() || is_page()) {
        global $post;
        if ($post) {
            $excerpt = wp_trim_words(get_the_excerpt(), 25, '...');
            echo '<meta name="description" content="' . esc_attr($excerpt) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'devlog_meta_tags');

/**
 * Remove Unnecessary WordPress Features
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Security: Remove WordPress Version
 */
function devlog_remove_version() {
    return '';
}
add_filter('the_generator', 'devlog_remove_version');

/**
 * Optimize WordPress
 */
function devlog_optimize_wordpress() {
    // Remove emoji support
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Remove query strings from static resources
    function devlog_remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('style_loader_src', 'devlog_remove_query_strings', 10, 1);
    add_filter('script_loader_src', 'devlog_remove_query_strings', 10, 1);
}
add_action('init', 'devlog_optimize_wordpress');

/**
 * Theme Activation Hook
 */
function devlog_theme_activation() {
    // Create necessary pages
    $pages = array(
        'Blog' => 'blog',
        'Hakkımda' => 'about',
        'İletişim' => 'contact'
    );
    
    foreach ($pages as $title => $slug) {
        $existing_page = get_page_by_path($slug);
        
        if (!$existing_page) {
            $page_data = array(
                'post_title' => $title,
                'post_name' => $slug,
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            );
            
            wp_insert_post($page_data);
        }
    }
    
    // Set blog page as posts page
    $blog_page = get_page_by_path('blog');
    if ($blog_page) {
        update_option('page_for_posts', $blog_page->ID);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'devlog_theme_activation');

/**
 * Register Custom Post Types
 */
function devlog_register_post_types() {
    // Experience Post Type
    register_post_type('experience', array(
        'labels' => array(
            'name' => 'İş Tecrübeleri',
            'singular_name' => 'İş Tecrübesi',
            'add_new' => 'Yeni Tecrübe Ekle',
            'add_new_item' => 'Yeni İş Tecrübesi Ekle',
            'edit_item' => 'İş Tecrübesini Düzenle',
            'new_item' => 'Yeni İş Tecrübesi',
            'view_item' => 'İş Tecrübesini Görüntüle',
            'search_items' => 'İş Tecrübelerinde Ara',
            'not_found' => 'İş tecrübesi bulunamadı',
            'not_found_in_trash' => 'Çöp kutusunda iş tecrübesi bulunamadı'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'custom-fields', 'page-attributes'),
        'has_archive' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'show_in_rest' => true
    ));

    // Skills Post Type
    register_post_type('skills', array(
        'labels' => array(
            'name' => 'Yetenekler',
            'singular_name' => 'Yetenek',
            'add_new' => 'Yeni Yetenek Ekle',
            'add_new_item' => 'Yeni Yetenek Ekle',
            'edit_item' => 'Yeteneği Düzenle',
            'new_item' => 'Yeni Yetenek',
            'view_item' => 'Yeteneği Görüntüle',
            'search_items' => 'Yeteneklerde Ara',
            'not_found' => 'Yetenek bulunamadı',
            'not_found_in_trash' => 'Çöp kutusunda yetenek bulunamadı'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array('title', 'custom-fields', 'page-attributes'),
        'has_archive' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'show_in_rest' => true
    ));
}
add_action('init', 'devlog_register_post_types');

/**
 * Add Meta Boxes for Experience
 */
function devlog_add_experience_meta_boxes() {
    add_meta_box(
        'experience_details',
        'İş Tecrübesi Detayları',
        'devlog_experience_meta_box_callback',
        'experience',
        'normal',
        'high'
    );
    
    add_meta_box(
        'experience_projects',
        'Projeler',
        'devlog_experience_projects_meta_box_callback',
        'experience',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'devlog_add_experience_meta_boxes');

/**
 * Experience Meta Box Callback
 */
function devlog_experience_meta_box_callback($post) {
    wp_nonce_field('devlog_experience_meta', 'devlog_experience_meta_nonce');
    
    $company = get_post_meta($post->ID, '_devlog_company', true);
    $position = get_post_meta($post->ID, '_devlog_position', true);
    $period = get_post_meta($post->ID, '_devlog_period', true);
    $description = get_post_meta($post->ID, '_devlog_description', true);
    $technologies = get_post_meta($post->ID, '_devlog_technologies', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="devlog_company">Şirket Adı</label></th>
            <td><input type="text" id="devlog_company" name="devlog_company" value="<?php echo esc_attr($company); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="devlog_position">Pozisyon</label></th>
            <td><input type="text" id="devlog_position" name="devlog_position" value="<?php echo esc_attr($position); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="devlog_period">Çalışma Dönemi</label></th>
            <td><input type="text" id="devlog_period" name="devlog_period" value="<?php echo esc_attr($period); ?>" class="regular-text" placeholder="Örn: 2021 - Devam Ediyor" /></td>
        </tr>
        <tr>
            <th><label for="devlog_description">İş Açıklaması</label></th>
            <td><textarea id="devlog_description" name="devlog_description" rows="3" class="large-text"><?php echo esc_textarea($description); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="devlog_technologies">Kullanılan Teknolojiler</label></th>
            <td>
                <input type="text" id="devlog_technologies" name="devlog_technologies" value="<?php echo esc_attr($technologies); ?>" class="regular-text" />
                <p class="description">Teknolojileri virgülle ayırarak yazın. Örn: Node.js, Express.js, MongoDB, Redis</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Experience Projects Meta Box Callback
 */
function devlog_experience_projects_meta_box_callback($post) {
    $projects = get_post_meta($post->ID, '_devlog_projects', true);
    if (!is_array($projects)) {
        $projects = array();
    }
    
    ?>
    <div id="devlog-projects-container">
        <div id="devlog-projects-list">
            <?php 
            if (!empty($projects)) {
                foreach ($projects as $index => $project) {
                    ?>
                    <div class="devlog-project-item" data-index="<?php echo $index; ?>">
                        <h4>Proje <?php echo ($index + 1); ?> 
                            <button type="button" class="remove-project" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-left: 10px;">Sil</button>
                        </h4>
                        <table class="form-table">
                            <tr>
                                <th><label>Proje Adı</label></th>
                                <td><input type="text" name="devlog_projects[<?php echo $index; ?>][title]" value="<?php echo esc_attr($project['title'] ?? ''); ?>" class="regular-text" /></td>
                            </tr>
                            <tr>
                                <th><label>Proje Açıklaması</label></th>
                                <td><textarea name="devlog_projects[<?php echo $index; ?>][description]" rows="3" class="large-text"><?php echo esc_textarea($project['description'] ?? ''); ?></textarea></td>
                            </tr>
                            <tr>
                                <th><label>Proje Teknolojileri</label></th>
                                <td>
                                    <input type="text" name="devlog_projects[<?php echo $index; ?>][technologies]" value="<?php echo esc_attr($project['technologies'] ?? ''); ?>" class="regular-text" />
                                    <p class="description">Bu proje için özel teknolojiler (virgülle ayırın)</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label>Proje URL'si</label></th>
                                <td><input type="url" name="devlog_projects[<?php echo $index; ?>][url]" value="<?php echo esc_attr($project['url'] ?? ''); ?>" class="regular-text" placeholder="https://example.com" /></td>
                            </tr>
                        </table>
                        <hr style="margin: 20px 0;">
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <button type="button" id="add-project" style="background: #007cba; color: white; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; margin-top: 10px;">
            + Yeni Proje Ekle
        </button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let projectIndex = <?php echo count($projects); ?>;
        
        $('#add-project').on('click', function() {
            const projectHtml = `
                <div class="devlog-project-item" data-index="${projectIndex}">
                    <h4>Proje ${projectIndex + 1} 
                        <button type="button" class="remove-project" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-left: 10px;">Sil</button>
                    </h4>
                    <table class="form-table">
                        <tr>
                            <th><label>Proje Adı</label></th>
                            <td><input type="text" name="devlog_projects[${projectIndex}][title]" value="" class="regular-text" /></td>
                        </tr>
                        <tr>
                            <th><label>Proje Açıklaması</label></th>
                            <td><textarea name="devlog_projects[${projectIndex}][description]" rows="3" class="large-text"></textarea></td>
                        </tr>
                        <tr>
                            <th><label>Proje Teknolojileri</label></th>
                            <td>
                                <input type="text" name="devlog_projects[${projectIndex}][technologies]" value="" class="regular-text" />
                                <p class="description">Bu proje için özel teknolojiler (virgülle ayırın)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Proje URL'si</label></th>
                            <td><input type="url" name="devlog_projects[${projectIndex}][url]" value="" class="regular-text" placeholder="https://example.com" /></td>
                        </tr>
                    </table>
                    <hr style="margin: 20px 0;">
                </div>
            `;
            
            $('#devlog-projects-list').append(projectHtml);
            projectIndex++;
        });
        
        $(document).on('click', '.remove-project', function() {
            if (confirm('Bu projeyi silmek istediğinizden emin misiniz?')) {
                $(this).closest('.devlog-project-item').remove();
                updateProjectNumbers();
            }
        });
        
        function updateProjectNumbers() {
            $('.devlog-project-item').each(function(index) {
                $(this).find('h4').first().contents().first()[0].textContent = 'Proje ' + (index + 1) + ' ';
            });
        }
    });
    </script>
    <?php
}

/**
 * Save Experience Meta Data
 */
function devlog_save_experience_meta($post_id) {
    if (!isset($_POST['devlog_experience_meta_nonce']) || !wp_verify_nonce($_POST['devlog_experience_meta_nonce'], 'devlog_experience_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save basic experience fields
    $fields = array(
        'devlog_company' => '_devlog_company',
        'devlog_position' => '_devlog_position',
        'devlog_period' => '_devlog_period',
        'devlog_description' => '_devlog_description',
        'devlog_technologies' => '_devlog_technologies'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save projects
    if (isset($_POST['devlog_projects']) && is_array($_POST['devlog_projects'])) {
        $projects = array();
        
        foreach ($_POST['devlog_projects'] as $project_data) {
            if (!empty($project_data['title'])) { // Only save if title is not empty
                $projects[] = array(
                    'title' => sanitize_text_field($project_data['title']),
                    'description' => sanitize_textarea_field($project_data['description']),
                    'technologies' => sanitize_text_field($project_data['technologies']),
                    'url' => esc_url_raw($project_data['url'])
                );
            }
        }
        
        update_post_meta($post_id, '_devlog_projects', $projects);
    } else {
        // If no projects submitted, clear the field
        delete_post_meta($post_id, '_devlog_projects');
    }
}
add_action('save_post', 'devlog_save_experience_meta');

/**
 * Add Meta Boxes for Skills
 */
function devlog_add_skills_meta_boxes() {
    add_meta_box(
        'skills_details',
        'Yetenek Detayları',
        'devlog_skills_meta_box_callback',
        'skills',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'devlog_add_skills_meta_boxes');

/**
 * Skills Meta Box Callback
 */
function devlog_skills_meta_box_callback($post) {
    wp_nonce_field('devlog_skills_meta', 'devlog_skills_meta_nonce');
    
    $category = get_post_meta($post->ID, '_devlog_skill_category', true);
    $icon = get_post_meta($post->ID, '_devlog_skill_icon', true);
    $percentage = get_post_meta($post->ID, '_devlog_skill_percentage', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="devlog_skill_category">Kategori</label></th>
            <td>
                <select id="devlog_skill_category" name="devlog_skill_category" class="regular-text">
                    <option value="">Kategori Seçin</option>
                    <option value="backend" <?php selected($category, 'backend'); ?>>Backend Technologies</option>
                    <option value="database" <?php selected($category, 'database'); ?>>Databases</option>
                    <option value="devops" <?php selected($category, 'devops'); ?>>DevOps & Cloud</option>
                    <option value="tools" <?php selected($category, 'tools'); ?>>Tools & Frameworks</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="devlog_skill_icon">FontAwesome İkon</label></th>
            <td>
                <input type="text" id="devlog_skill_icon" name="devlog_skill_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="Örn: fas fa-server" />
                <p class="description">FontAwesome ikon sınıfını yazın. <a href="https://fontawesome.com/icons" target="_blank">İkonları buradan bulabilirsiniz</a></p>
            </td>
        </tr>
        <tr>
            <th><label for="devlog_skill_percentage">Yetenek Yüzdesi</label></th>
            <td>
                <input type="number" id="devlog_skill_percentage" name="devlog_skill_percentage" value="<?php echo esc_attr($percentage); ?>" min="0" max="100" />
                <span>%</span>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Skills Meta Data
 */
function devlog_save_skills_meta($post_id) {
    if (!isset($_POST['devlog_skills_meta_nonce']) || !wp_verify_nonce($_POST['devlog_skills_meta_nonce'], 'devlog_skills_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'devlog_skill_category' => '_devlog_skill_category',
        'devlog_skill_icon' => '_devlog_skill_icon',
        'devlog_skill_percentage' => '_devlog_skill_percentage'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'devlog_save_skills_meta');

/**
 * Get Experiences for Display
 */
function devlog_get_experiences() {
    $experiences = get_posts(array(
        'post_type' => 'experience',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    return $experiences;
}

/**
 * Get Skills for Display
 */
function devlog_get_skills_by_category($category = '') {
    $args = array(
        'post_type' => 'skills',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    
    if (!empty($category)) {
        $args['meta_query'] = array(
            array(
                'key' => '_devlog_skill_category',
                'value' => $category,
                'compare' => '='
            )
        );
    }
    
    return get_posts($args);
}

/**
 * Custom Navigation Walker
 */
class DevLog_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    // Start Level - Outputs the list container
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    // End Level - Closes the list container
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    // Start Element - Outputs the list item and link
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav__item';
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = $args->before ?? '';
        $item_output .= '<a class="nav__link"' . $attributes . '>';
        $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // End Element - Closes the list item
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
