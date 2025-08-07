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
    
    add_meta_box(
        'skills_quick_add',
        'Hızlı Yetenek Ekleme',
        'devlog_skills_quick_add_meta_box_callback',
        'skills',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'devlog_add_skills_meta_boxes');

/**
 * Skills Meta Box Callback
 */
function devlog_skills_meta_box_callback($post) {
    wp_nonce_field('devlog_skills_meta', 'devlog_skills_meta_nonce');
    
    $skill_name = $post->post_title;
    $category = get_post_meta($post->ID, '_devlog_skill_category', true);
    $icon = get_post_meta($post->ID, '_devlog_skill_icon', true);
    $percentage = get_post_meta($post->ID, '_devlog_skill_percentage', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="devlog_skill_name">Yetenek Adı</label></th>
            <td>
                <input type="text" id="devlog_skill_name" name="devlog_skill_name" value="<?php echo esc_attr($skill_name); ?>" class="regular-text" placeholder="Örn: Node.js, React, MySQL" required />
                <p class="description">Bu yeteneğin adını yazın (örn: PHP, JavaScript, Docker vb.)</p>
            </td>
        </tr>
        <tr>
            <th><label for="devlog_skill_category">Kategori Adı</label></th>
            <td>
                <input type="text" id="devlog_skill_category" name="devlog_skill_category" value="<?php echo esc_attr($category); ?>" class="regular-text" placeholder="Örn: Backend Technologies, Frontend Frameworks" />
                <p class="description">İstediğiniz kategori adını yazın. Aynı kategorideki skill'ler birlikte gruplandırılacak.</p>
            </td>
        </tr>
        <tr>
            <th><label for="devlog_skill_icon">FontAwesome İkon</label></th>
            <td>
                <input type="text" id="devlog_skill_icon" name="devlog_skill_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="Örn: fas fa-server, fab fa-node-js, fas fa-database" />
                <p class="description">
                    FontAwesome ikon sınıfını yazın. Örnekler:<br>
                    <code>fas fa-server</code> (Backend), 
                    <code>fas fa-database</code> (Database), 
                    <code>fab fa-node-js</code> (Node.js), 
                    <code>fas fa-cloud</code> (Cloud)<br>
                    <a href="https://fontawesome.com/icons" target="_blank">🔗 FontAwesome ikonlarını buradan bulabilirsiniz</a>
                </p>
            </td>
        </tr>
        <tr>
            <th><label for="devlog_skill_percentage">Yetenek Yüzdesi</label></th>
            <td>
                <input type="range" id="devlog_skill_percentage" name="devlog_skill_percentage" value="<?php echo esc_attr($percentage ?: 50); ?>" min="0" max="100" step="5" style="width: 300px;" />
                <output for="devlog_skill_percentage" id="percentage-output"><?php echo esc_attr($percentage ?: 50); ?>%</output>
                <p class="description">Kaydırarak yetenek seviyenizi belirleyin (0-100%)</p>
            </td>
        </tr>
    </table>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('devlog_skill_percentage');
        const output = document.getElementById('percentage-output');
        
        if (slider && output) {
            slider.addEventListener('input', function() {
                output.textContent = this.value + '%';
            });
        }
    });
    </script>
    <?php
}

/**
 * Skills Quick Add Meta Box Callback
 */
function devlog_skills_quick_add_meta_box_callback($post) {
    ?>
    <div style="padding: 10px 0;">
        <h4>Hızlı Skill Şablonları</h4>
        <p style="font-size: 12px; color: #666;">Skill formunu otomatik doldurmak için tıklayın:</p>
        
        <div style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 5px;">Backend</h5>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Node.js" data-category="Backend Technologies" data-icon="fab fa-node-js" data-percentage="85">Node.js</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="PHP" data-category="Backend Technologies" data-icon="fab fa-php" data-percentage="90">PHP</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Python" data-category="Backend Technologies" data-icon="fab fa-python" data-percentage="85">Python</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Java" data-category="Backend Technologies" data-icon="fab fa-java" data-percentage="80">Java</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="C#" data-category="Backend Technologies" data-icon="fas fa-code" data-percentage="75">.NET</button>
        </div>
        
        <div style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 5px;">Frontend</h5>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="JavaScript" data-category="Frontend Technologies" data-icon="fab fa-js-square" data-percentage="90">JavaScript</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="React" data-category="Frontend Frameworks" data-icon="fab fa-react" data-percentage="85">React</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Vue.js" data-category="Frontend Frameworks" data-icon="fab fa-vuejs" data-percentage="80">Vue.js</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="HTML5" data-category="Frontend Technologies" data-icon="fab fa-html5" data-percentage="95">HTML5</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="CSS3" data-category="Frontend Technologies" data-icon="fab fa-css3-alt" data-percentage="90">CSS3</button>
        </div>
        
        <div style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 5px;">Database</h5>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="MySQL" data-category="Databases" data-icon="fas fa-database" data-percentage="85">MySQL</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="PostgreSQL" data-category="Databases" data-icon="fas fa-database" data-percentage="80">PostgreSQL</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="MongoDB" data-category="Databases" data-icon="fas fa-leaf" data-percentage="75">MongoDB</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Redis" data-category="Databases" data-icon="fas fa-database" data-percentage="70">Redis</button>
        </div>
        
        <div style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 5px;">DevOps & Cloud</h5>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Docker" data-category="DevOps & Cloud" data-icon="fab fa-docker" data-percentage="85">Docker</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="AWS" data-category="DevOps & Cloud" data-icon="fab fa-aws" data-percentage="80">AWS</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Git" data-category="DevOps & Cloud" data-icon="fab fa-git-alt" data-percentage="90">Git</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Linux" data-category="DevOps & Cloud" data-icon="fab fa-linux" data-percentage="85">Linux</button>
        </div>
        
        <div style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 5px;">Mobil</h5>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="React Native" data-category="Mobile Development" data-icon="fab fa-react" data-percentage="75">React Native</button>
            <button type="button" class="button-secondary skill-template" style="margin: 2px; font-size: 11px;" 
                data-name="Flutter" data-category="Mobile Development" data-icon="fas fa-mobile-alt" data-percentage="70">Flutter</button>
        </div>
        
        <hr style="margin: 15px 0;">
        <button type="button" id="clear-skill-form" class="button" style="width: 100%; margin-bottom: 5px;">
            🗑️ Formu Temizle
        </button>
        <small style="color: #666; font-style: italic;">İpucu: Kendi skill'lerinizi de ekleyebilirsiniz!</small>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Skill template buttons
        $('.skill-template').on('click', function() {
            const name = $(this).data('name');
            const category = $(this).data('category');
            const icon = $(this).data('icon');
            const percentage = $(this).data('percentage');
            
            // Fill form fields
            $('#devlog_skill_name').val(name);
            $('#devlog_skill_category').val(category);
            $('#devlog_skill_icon').val(icon);
            $('#devlog_skill_percentage').val(percentage);
            
            // Update percentage output
            $('#percentage-output').text(percentage + '%');
            
            // Update title field if it's a new post
            if ($('#title').val() === '') {
                $('#title').val(name);
            }
            
            // Highlight selected button briefly
            $(this).css('background-color', '#00a32a').css('color', 'white');
            setTimeout(() => {
                $(this).css('background-color', '').css('color', '');
            }, 500);
        });
        
        // Clear form button
        $('#clear-skill-form').on('click', function() {
            $('#devlog_skill_name').val('');
            $('#devlog_skill_category').val('');
            $('#devlog_skill_icon').val('');
            $('#devlog_skill_percentage').val(50);
            $('#percentage-output').text('50%');
            $('#title').val('');
        });
    });
    </script>
    <?php
}

/**
 * Save Skills Meta Data
 */
function devlog_save_skills_meta($post_id) {
    // Prevent infinite loop
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
    
    if (!isset($_POST['devlog_skills_meta_nonce']) || !wp_verify_nonce($_POST['devlog_skills_meta_nonce'], 'devlog_skills_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Check if this is a skills post
    if (get_post_type($post_id) !== 'skills') {
        return;
    }
    
    // Remove the hook temporarily to prevent infinite loop
    remove_action('save_post', 'devlog_save_skills_meta');
    
    // Save skill name as post title
    if (isset($_POST['devlog_skill_name']) && !empty($_POST['devlog_skill_name'])) {
        $skill_name = sanitize_text_field($_POST['devlog_skill_name']);
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $skill_name
        ));
    }
    
    // Save meta fields
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
    
    // Re-add the hook
    add_action('save_post', 'devlog_save_skills_meta');
}
add_action('save_post', 'devlog_save_skills_meta');

/**
 * Create Demo Skills (for testing)
 */
function devlog_create_demo_skills() {
    if (get_option('devlog_demo_skills_created')) {
        return; // Already created
    }
    
    $demo_skills = array(
        array(
            'name' => 'PHP',
            'category' => 'backend',
            'percentage' => 95,
            'icon' => 'fab fa-php'
        ),
        array(
            'name' => 'Laravel',
            'category' => 'backend',
            'percentage' => 90,
            'icon' => 'fab fa-laravel'
        ),
        array(
            'name' => 'JavaScript',
            'category' => 'frontend',
            'percentage' => 85,
            'icon' => 'fab fa-js-square'
        ),
        array(
            'name' => 'Vue.js',
            'category' => 'frontend',
            'percentage' => 80,
            'icon' => 'fab fa-vuejs'
        ),
        array(
            'name' => 'MySQL',
            'category' => 'database',
            'percentage' => 88,
            'icon' => 'fas fa-database'
        ),
        array(
            'name' => 'MongoDB',
            'category' => 'database', 
            'percentage' => 75,
            'icon' => 'fas fa-leaf'
        ),
        array(
            'name' => 'Docker',
            'category' => 'devops',
            'percentage' => 70,
            'icon' => 'fab fa-docker'
        ),
        array(
            'name' => 'AWS',
            'category' => 'devops',
            'percentage' => 65,
            'icon' => 'fab fa-aws'
        )
    );
    
    foreach ($demo_skills as $skill_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $skill_data['name'],
            'post_type' => 'skills',
            'post_status' => 'publish',
            'post_content' => ''
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_devlog_skill_category', $skill_data['category']);
            update_post_meta($post_id, '_devlog_skill_percentage', $skill_data['percentage']);
            update_post_meta($post_id, '_devlog_skill_icon', $skill_data['icon']);
        }
    }
    
    update_option('devlog_demo_skills_created', true);
}

// Call demo skills function on theme activation (for testing)
add_action('after_switch_theme', 'devlog_create_demo_skills');

// Also call it on init for immediate testing
add_action('init', function() {
    if (!get_option('devlog_demo_skills_created')) {
        devlog_create_demo_skills();
    }
});

/**
 * Add Skills Bulk Import Menu
 */
function devlog_add_skills_bulk_menu() {
    add_submenu_page(
        'edit.php?post_type=skills',
        'Toplu Skill Ekleme',
        'Toplu Ekle',
        'manage_options',
        'skills-bulk-add',
        'devlog_skills_bulk_add_page'
    );
}
add_action('admin_menu', 'devlog_add_skills_bulk_menu');

/**
 * Skills Bulk Add Page
 */
function devlog_skills_bulk_add_page() {
    if (isset($_POST['bulk_skills_submit'])) {
        devlog_process_bulk_skills();
    }
    ?>
    <div class="wrap">
        <h1>Toplu Skill Ekleme</h1>
        <p>Bu sayfada birden fazla skill'i hızlıca ekleyebilirsiniz. Her satıra bir skill yazın.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('bulk_skills_action', 'bulk_skills_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="bulk_skills_data">Skills Listesi</label>
                    </th>
                    <td>
                        <textarea id="bulk_skills_data" name="bulk_skills_data" rows="15" cols="80" class="large-text" placeholder="Her satıra bir skill yazın. Format:
Skill Adı | Kategori | İkon | Yüzde

Örnekler:
PHP | Backend Technologies | fab fa-php | 90
JavaScript | Frontend Technologies | fab fa-js-square | 85
MySQL | Databases | fas fa-database | 80
Docker | DevOps | fab fa-docker | 75
React | Frontend Frameworks | fab fa-react | 85
Node.js | Backend Technologies | fab fa-node-js | 88"></textarea>
                        <p class="description">
                            <strong>Format:</strong> <code>Skill Adı | Kategori | İkon | Yüzde</code><br>
                            <strong>Örnek:</strong> <code>PHP | Backend Technologies | fab fa-php | 90</code><br>
                            Her satır bir skill olacak. | (pipe) karakteri ile ayırın.
                        </p>
                    </td>
                </tr>
            </table>
            
            <h3>Örnek Skill Setleri</h3>
            <div style="margin: 20px 0;">
                <button type="button" class="button" onclick="addSkillSet('backend')">Backend Set Ekle</button>
                <button type="button" class="button" onclick="addSkillSet('frontend')">Frontend Set Ekle</button>
                <button type="button" class="button" onclick="addSkillSet('database')">Database Set Ekle</button>
                <button type="button" class="button" onclick="addSkillSet('devops')">DevOps Set Ekle</button>
            </div>
            
            <?php submit_button('Skills Ekle', 'primary', 'bulk_skills_submit'); ?>
        </form>
    </div>
    
    <script>
    function addSkillSet(type) {
        const textarea = document.getElementById('bulk_skills_data');
        let skillSet = '';
        
        switch(type) {
            case 'backend':
                skillSet = `PHP | Backend Technologies | fab fa-php | 90
Python | Backend Technologies | fab fa-python | 85
Node.js | Backend Technologies | fab fa-node-js | 88
Java | Backend Technologies | fab fa-java | 82
C# | Backend Technologies | fas fa-code | 80`;
                break;
            case 'frontend':
                skillSet = `JavaScript | Frontend Technologies | fab fa-js-square | 90
React | Frontend Frameworks | fab fa-react | 85
Vue.js | Frontend Frameworks | fab fa-vuejs | 80
HTML5 | Frontend Technologies | fab fa-html5 | 95
CSS3 | Frontend Technologies | fab fa-css3-alt | 90`;
                break;
            case 'database':
                skillSet = `MySQL | Databases | fas fa-database | 85
PostgreSQL | Databases | fas fa-database | 80
MongoDB | Databases | fas fa-leaf | 75
Redis | Databases | fas fa-database | 70`;
                break;
            case 'devops':
                skillSet = `Docker | DevOps & Cloud | fab fa-docker | 85
AWS | DevOps & Cloud | fab fa-aws | 80
Git | DevOps & Cloud | fab fa-git-alt | 90
Linux | DevOps & Cloud | fab fa-linux | 85`;
                break;
        }
        
        if (textarea.value.trim() !== '') {
            textarea.value += '\n' + skillSet;
        } else {
            textarea.value = skillSet;
        }
    }
    </script>
    <?php
}

/**
 * Process Bulk Skills
 */
function devlog_process_bulk_skills() {
    if (!wp_verify_nonce($_POST['bulk_skills_nonce'], 'bulk_skills_action')) {
        wp_die('Security check failed');
    }
    
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to perform this action');
    }
    
    $bulk_data = sanitize_textarea_field($_POST['bulk_skills_data']);
    $lines = explode("\n", $bulk_data);
    $added_count = 0;
    $errors = array();
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $parts = explode('|', $line);
        if (count($parts) != 4) {
            $errors[] = "Hatalı format: " . $line;
            continue;
        }
        
        $skill_name = trim($parts[0]);
        $category = trim($parts[1]);
        $icon = trim($parts[2]);
        $percentage = intval(trim($parts[3]));
        
        if (empty($skill_name)) {
            $errors[] = "Boş skill adı: " . $line;
            continue;
        }
        
        // Create the post
        $post_data = array(
            'post_title' => $skill_name,
            'post_type' => 'skills',
            'post_status' => 'publish'
        );
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Add meta data
            update_post_meta($post_id, '_devlog_skill_category', $category);
            update_post_meta($post_id, '_devlog_skill_icon', $icon);
            update_post_meta($post_id, '_devlog_skill_percentage', $percentage);
            $added_count++;
        } else {
            $errors[] = "Skill eklenemedi: " . $skill_name;
        }
    }
    
    // Show results
    if ($added_count > 0) {
        echo '<div class="notice notice-success"><p>' . $added_count . ' skill başarıyla eklendi!</p></div>';
    }
    
    if (!empty($errors)) {
        echo '<div class="notice notice-error"><p>Hatalar:<br>' . implode('<br>', $errors) . '</p></div>';
    }
}

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
 * Get All Skills Grouped by Category (Dynamic)
 */
function devlog_get_skills_grouped() {
    // Get all skills first
    $all_skills = get_posts(array(
        'post_type' => 'skills',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    $grouped_skills = array();
    $category_icons = array(); // Track first icon found for each category
    
    foreach ($all_skills as $skill) {
        $category = get_post_meta($skill->ID, '_devlog_skill_category', true);
        $icon = get_post_meta($skill->ID, '_devlog_skill_icon', true);
        
        // Use category as key, or "other" if empty
        $category_key = !empty($category) ? sanitize_title($category) : 'other';
        $category_name = !empty($category) ? $category : 'Diğer Yetenekler';
        
        // Use first icon found for this category, or default
        if (!isset($category_icons[$category_key])) {
            $category_icons[$category_key] = !empty($icon) ? $icon : 'fas fa-star';
        }
        
        // Group skills by category
        if (!isset($grouped_skills[$category_key])) {
            $grouped_skills[$category_key] = array(
                'category_data' => array(
                    'name' => $category_name,
                    'icon' => $category_icons[$category_key]
                ),
                'skills' => array()
            );
        }
        
        $grouped_skills[$category_key]['skills'][] = $skill;
    }
    
    return $grouped_skills;
}

/**
 * Get Skills Statistics
 */
function devlog_get_skills_statistics() {
    $all_skills = get_posts(array(
        'post_type' => 'skills',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $stats = array(
        'total_skills' => 0,
        'categories' => array(),
        'average_percentage' => 0,
        'highest_skill' => null,
        'lowest_skill' => null
    );
    
    if (empty($all_skills)) {
        return $stats;
    }
    
    $total_percentage = 0;
    $highest_percentage = 0;
    $lowest_percentage = 100;
    $categories = array();
    
    foreach ($all_skills as $skill) {
        $percentage = (int) get_post_meta($skill->ID, '_devlog_skill_percentage', true);
        $category = get_post_meta($skill->ID, '_devlog_skill_category', true);
        
        $stats['total_skills']++;
        $total_percentage += $percentage;
        
        // Track categories
        if (!empty($category)) {
            if (!isset($categories[$category])) {
                $categories[$category] = 0;
            }
            $categories[$category]++;
        }
        
        // Track highest skill
        if ($percentage > $highest_percentage) {
            $highest_percentage = $percentage;
            $stats['highest_skill'] = array(
                'name' => $skill->post_title,
                'percentage' => $percentage
            );
        }
        
        // Track lowest skill  
        if ($percentage < $lowest_percentage) {
            $lowest_percentage = $percentage;
            $stats['lowest_skill'] = array(
                'name' => $skill->post_title,
                'percentage' => $percentage
            );
        }
    }
    
    $stats['categories'] = $categories;
    $stats['average_percentage'] = round($total_percentage / $stats['total_skills'], 1);
    
    return $stats;
}

/**
 * Display Skills Admin Dashboard Widget
 */
function devlog_skills_dashboard_widget() {
    $stats = devlog_get_skills_statistics();
    ?>
    <div class="devlog-skills-widget">
        <h3>📊 Skill İstatistikleri</h3>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 15px 0;">
            <div style="background: #f0f6fc; padding: 10px; border-radius: 5px; text-align: center;">
                <strong style="font-size: 24px; color: #1d4ed8;"><?php echo $stats['total_skills']; ?></strong>
                <br><small>Toplam Skill</small>
            </div>
            
            <div style="background: #f0fdf4; padding: 10px; border-radius: 5px; text-align: center;">
                <strong style="font-size: 24px; color: #16a34a;"><?php echo $stats['average_percentage']; ?>%</strong>
                <br><small>Ortalama Seviye</small>
            </div>
        </div>
        
        <?php if ($stats['highest_skill']): ?>
        <div style="background: #fefce8; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
            <strong>🏆 En Yüksek:</strong> 
            <?php echo esc_html($stats['highest_skill']['name']); ?> 
            (<?php echo $stats['highest_skill']['percentage']; ?>%)
        </div>
        <?php endif; ?>
        
        <?php if (!empty($stats['categories'])): ?>
        <div style="margin-top: 15px;">
            <h4>📋 Kategoriler:</h4>
            <?php foreach ($stats['categories'] as $category => $count): ?>
            <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #eee;">
                <span><?php echo esc_html($category); ?></span>
                <strong><?php echo $count; ?> skill</strong>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 15px; text-align: center;">
            <a href="<?php echo admin_url('edit.php?post_type=skills'); ?>" class="button button-primary">
                ⚡ Skills Yönet
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=skills&page=skills-bulk-add'); ?>" class="button button-secondary">
                ➕ Toplu Ekle
            </a>
        </div>
    </div>
    <?php
}

/**
 * Add Skills Dashboard Widget
 */
function devlog_add_skills_dashboard_widget() {
    wp_add_dashboard_widget(
        'devlog_skills_widget',
        '🎯 DevLog - Skills Overview',
        'devlog_skills_dashboard_widget'
    );
}
add_action('wp_dashboard_setup', 'devlog_add_skills_dashboard_widget');

/**
 * Add Skills Management Columns
 */
function devlog_skills_admin_columns($columns) {
    // Remove date column
    unset($columns['date']);
    
    // Add custom columns
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['skill_category'] = '📂 Kategori';
    $new_columns['skill_icon'] = '🎨 İkon';
    $new_columns['skill_percentage'] = '📊 Seviye';
    $new_columns['skill_preview'] = '👀 Önizleme';
    $new_columns['date'] = 'Tarih';
    
    return $new_columns;
}
add_filter('manage_skills_posts_columns', 'devlog_skills_admin_columns');

/**
 * Fill Skills Management Columns
 */
function devlog_skills_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'skill_category':
            $category = get_post_meta($post_id, '_devlog_skill_category', true);
            echo $category ? '<span style="background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 12px; font-size: 12px;">' . esc_html($category) . '</span>' : '<span style="color: #6b7280;">—</span>';
            break;
            
        case 'skill_icon':
            $icon = get_post_meta($post_id, '_devlog_skill_icon', true);
            if ($icon) {
                echo '<i class="' . esc_attr($icon) . '" style="font-size: 18px; color: #4f46e5;"></i>';
            } else {
                echo '<span style="color: #6b7280;">—</span>';
            }
            break;
            
        case 'skill_percentage':
            $percentage = get_post_meta($post_id, '_devlog_skill_percentage', true);
            $percentage = $percentage ?: 0;
            
            // Color based on percentage
            $color = '#ef4444'; // Red for low
            if ($percentage >= 70) $color = '#22c55e'; // Green for high
            elseif ($percentage >= 40) $color = '#f59e0b'; // Orange for medium
            
            echo '<div style="display: flex; align-items: center; gap: 8px;">';
            echo '<div style="background: #f3f4f6; border-radius: 8px; height: 8px; width: 60px; overflow: hidden;">';
            echo '<div style="background: ' . $color . '; height: 100%; width: ' . $percentage . '%;"></div>';
            echo '</div>';
            echo '<strong style="color: ' . $color . ';">' . $percentage . '%</strong>';
            echo '</div>';
            break;
            
        case 'skill_preview':
            $skill_name = get_the_title($post_id);
            $percentage = get_post_meta($post_id, '_devlog_skill_percentage', true);
            $icon = get_post_meta($post_id, '_devlog_skill_icon', true);
            
            echo '<div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; font-size: 12px;">';
            if ($icon) echo '<i class="' . esc_attr($icon) . '"></i> ';
            echo '<strong>' . esc_html($skill_name) . '</strong><br>';
            echo '<span style="color: #6b7280;">Seviye: ' . ($percentage ?: 0) . '%</span>';
            echo '</div>';
            break;
    }
}
add_action('manage_skills_posts_custom_column', 'devlog_skills_admin_columns_content', 10, 2);

/**
 * Make Skills Columns Sortable
 */
function devlog_skills_sortable_columns($columns) {
    $columns['skill_category'] = 'skill_category';
    $columns['skill_percentage'] = 'skill_percentage';
    return $columns;
}
add_filter('manage_edit-skills_sortable_columns', 'devlog_skills_sortable_columns');

/**
 * Handle Skills Column Sorting
 */
function devlog_skills_column_sorting($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ('skill_category' === $orderby) {
        $query->set('meta_key', '_devlog_skill_category');
        $query->set('orderby', 'meta_value');
    }
    
    if ('skill_percentage' === $orderby) {
        $query->set('meta_key', '_devlog_skill_percentage');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'devlog_skills_column_sorting');

/**
 * Skills Shortcode
 */
function devlog_skills_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'limit' => -1,
        'style' => 'default', // default, compact, mini
        'show_stats' => 'false',
        'columns' => 3
    ), $atts, 'devlog_skills');

    $args = array(
        'post_type' => 'skills',
        'posts_per_page' => (int) $atts['limit'],
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    if (!empty($atts['category'])) {
        $args['meta_query'] = array(
            array(
                'key' => '_devlog_skill_category',
                'value' => $atts['category'],
                'compare' => '='
            )
        );
    }

    $skills = get_posts($args);
    
    if (empty($skills)) {
        return '<p>Henüz skill eklenmemiş.</p>';
    }

    ob_start();
    
    $container_class = 'devlog-skills-shortcode style-' . esc_attr($atts['style']);
    $columns = max(1, min(6, (int) $atts['columns']));
    
    ?>
    <div class="<?php echo $container_class; ?>" style="--columns: <?php echo $columns; ?>;">
        <?php if ($atts['style'] === 'default'): ?>
            <!-- Default Style - Full Categories -->
            <div class="skills-grid">
                <?php 
                if (empty($atts['category'])) {
                    // Group by category
                    $grouped_skills = array();
                    foreach ($skills as $skill) {
                        $category = get_post_meta($skill->ID, '_devlog_skill_category', true);
                        $category = $category ?: 'Diğer';
                        
                        if (!isset($grouped_skills[$category])) {
                            $grouped_skills[$category] = array();
                        }
                        $grouped_skills[$category][] = $skill;
                    }
                    
                    foreach ($grouped_skills as $category => $category_skills):
                        $first_skill = $category_skills[0];
                        $category_icon = get_post_meta($first_skill->ID, '_devlog_skill_icon', true);
                        $category_icon = $category_icon ?: 'fas fa-star';
                ?>
                <div class="skill-category-short">
                    <div class="skill-category-header-short">
                        <i class="<?php echo esc_attr($category_icon); ?>"></i>
                        <h4><?php echo esc_html($category); ?></h4>
                    </div>
                    <div class="skill-list-short">
                        <?php foreach ($category_skills as $skill): 
                            $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                            $percentage = $percentage ?: 50;
                        ?>
                        <div class="skill-item-short">
                            <div class="skill-header-short">
                                <span class="skill-name-short"><?php echo esc_html($skill->post_title); ?></span>
                                <span class="skill-percentage-short"><?php echo $percentage; ?>%</span>
                            </div>
                            <div class="skill-bar-short">
                                <div class="skill-progress-short" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                } else {
                    // Single category
                    ?>
                    <div class="skill-list-short single-category">
                        <?php foreach ($skills as $skill): 
                            $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                            $percentage = $percentage ?: 50;
                            $icon = get_post_meta($skill->ID, '_devlog_skill_icon', true);
                        ?>
                        <div class="skill-item-short">
                            <div class="skill-header-short">
                                <?php if ($icon): ?>
                                    <i class="<?php echo esc_attr($icon); ?> skill-icon-short"></i>
                                <?php endif; ?>
                                <span class="skill-name-short"><?php echo esc_html($skill->post_title); ?></span>
                                <span class="skill-percentage-short"><?php echo $percentage; ?>%</span>
                            </div>
                            <div class="skill-bar-short">
                                <div class="skill-progress-short" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            
        <?php elseif ($atts['style'] === 'compact'): ?>
            <!-- Compact Style - Simple List -->
            <div class="skills-compact">
                <?php foreach ($skills as $skill): 
                    $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                    $percentage = $percentage ?: 50;
                    $icon = get_post_meta($skill->ID, '_devlog_skill_icon', true);
                ?>
                <div class="skill-compact-item">
                    <?php if ($icon): ?>
                        <i class="<?php echo esc_attr($icon); ?>"></i>
                    <?php endif; ?>
                    <span class="skill-compact-name"><?php echo esc_html($skill->post_title); ?></span>
                    <span class="skill-compact-percentage"><?php echo $percentage; ?>%</span>
                </div>
                <?php endforeach; ?>
            </div>
            
        <?php elseif ($atts['style'] === 'mini'): ?>
            <!-- Mini Style - Tags Only -->
            <div class="skills-mini">
                <?php foreach ($skills as $skill): 
                    $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                    $percentage = $percentage ?: 50;
                    
                    // Color based on percentage
                    $color_class = 'low';
                    if ($percentage >= 80) $color_class = 'expert';
                    elseif ($percentage >= 60) $color_class = 'advanced';
                    elseif ($percentage >= 40) $color_class = 'intermediate';
                ?>
                <span class="skill-mini-tag <?php echo $color_class; ?>" title="<?php echo esc_attr($skill->post_title . ': ' . $percentage . '%'); ?>">
                    <?php echo esc_html($skill->post_title); ?>
                </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($atts['show_stats'] === 'true'): ?>
            <!-- Skills Statistics -->
            <div class="skills-shortcode-stats">
                <?php 
                $total_skills = count($skills);
                $total_percentage = 0;
                foreach ($skills as $skill) {
                    $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                    $total_percentage += (int) $percentage;
                }
                $average = $total_skills > 0 ? round($total_percentage / $total_skills, 1) : 0;
                ?>
                <div class="stats-grid">
                    <div class="stat-item">
                        <strong><?php echo $total_skills; ?></strong>
                        <span>Skill</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $average; ?>%</strong>
                        <span>Ortalama</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <style>
    .devlog-skills-shortcode {
        margin: 20px 0;
    }
    
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(var(--columns, 3), 1fr);
        gap: 20px;
    }
    
    .skill-category-short {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e0e0e0;
    }
    
    .skill-category-header-short {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        color: #e74c3c;
    }
    
    .skill-category-header-short i {
        font-size: 1.2em;
    }
    
    .skill-category-header-short h4 {
        margin: 0;
        font-size: 1.1em;
    }
    
    .skill-item-short {
        margin-bottom: 12px;
    }
    
    .skill-header-short {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .skill-name-short {
        font-weight: 600;
        color: #333;
    }
    
    .skill-percentage-short {
        font-size: 0.9em;
        color: #e74c3c;
        font-weight: bold;
    }
    
    .skill-bar-short {
        background: #e0e0e0;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .skill-progress-short {
        background: linear-gradient(45deg, #e74c3c, #c0392b);
        height: 100%;
        border-radius: 3px;
        transition: width 2s ease;
    }
    
    .skills-compact .skill-compact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .skills-compact .skill-compact-item:last-child {
        border-bottom: none;
    }
    
    .skill-compact-name {
        flex: 1;
        font-weight: 500;
    }
    
    .skill-compact-percentage {
        color: #e74c3c;
        font-weight: bold;
        font-size: 0.9em;
    }
    
    .skills-mini {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .skill-mini-tag {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
        cursor: default;
    }
    
    .skill-mini-tag.low {
        background: #ffebee;
        color: #c62828;
    }
    
    .skill-mini-tag.intermediate {
        background: #fff3e0;
        color: #ef6c00;
    }
    
    .skill-mini-tag.advanced {
        background: #e8f5e8;
        color: #2e7d32;
    }
    
    .skill-mini-tag.expert {
        background: #e3f2fd;
        color: #1565c0;
    }
    
    .skills-shortcode-stats {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f5f5f5;
        border-radius: 8px;
    }
    
    .stat-item strong {
        display: block;
        font-size: 1.5em;
        color: #e74c3c;
        margin-bottom: 5px;
    }
    
    .stat-item span {
        color: #666;
        font-size: 0.9em;
    }
    
    @media (max-width: 768px) {
        .skills-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
    
    return ob_get_clean();
}
add_shortcode('devlog_skills', 'devlog_skills_shortcode');

/**
 * Skills Widget
 */
class DevLog_Skills_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'devlog_skills_widget',
            'DevLog Skills',
            array(
                'description' => 'Skills listesini widget olarak gösterir.'
            )
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $limit = !empty($instance['limit']) ? $instance['limit'] : 5;
        $style = !empty($instance['style']) ? $instance['style'] : 'compact';
        
        echo do_shortcode("[devlog_skills category='$category' limit='$limit' style='$style']");
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Skills';
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $limit = !empty($instance['limit']) ? $instance['limit'] : 5;
        $style = !empty($instance['style']) ? $instance['style'] : 'compact';
        
        // Get available categories
        $categories = array();
        $skills = get_posts(array('post_type' => 'skills', 'posts_per_page' => -1));
        foreach ($skills as $skill) {
            $cat = get_post_meta($skill->ID, '_devlog_skill_category', true);
            if ($cat && !in_array($cat, $categories)) {
                $categories[] = $cat;
            }
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Başlık:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">Kategori:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
                <option value="">Tüm Kategoriler</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo esc_attr($cat); ?>" <?php selected($category, $cat); ?>><?php echo esc_html($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>">Limit:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo esc_attr($limit); ?>" min="1" max="20">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>">Stil:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
                <option value="compact" <?php selected($style, 'compact'); ?>>Kompakt</option>
                <option value="mini" <?php selected($style, 'mini'); ?>>Mini (Etiketler)</option>
                <option value="default" <?php selected($style, 'default'); ?>>Varsayılan</option>
            </select>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? sanitize_text_field($new_instance['category']) : '';
        $instance['limit'] = (!empty($new_instance['limit'])) ? (int) $new_instance['limit'] : 5;
        $instance['style'] = (!empty($new_instance['style'])) ? sanitize_text_field($new_instance['style']) : 'compact';
        
        return $instance;
    }
}

/**
 * Register Skills Widget
 */
function devlog_register_skills_widget() {
    register_widget('DevLog_Skills_Widget');
}
add_action('widgets_init', 'devlog_register_skills_widget');

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
