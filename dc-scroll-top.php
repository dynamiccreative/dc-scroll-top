<?php
/**
 * Plugin Name: DC Scroll Top
 * Plugin URI: https://github.com/dynamiccreative/dc-scroll-top
  * Update URI: https://github.com/dynamiccreative/dc-scroll-top
 * Description: Ajoute un bouton scroll to top personnalisable.
 * Version: 2.0.0
 * Author: Team Dynamic Creative
 * Author URI: http://www.dynamic-creative.com
 * GitHub Plugin URI: https://github.com/dynamiccreative/dc-scroll-top
 * Primary Branch: main
 * Text Domain: dc-scroll-top
 * Domain Path: /languages
 * Tested up to: 6.8
 * Requires at least: 6.7
 */

// Securite : empecher l'acces direct
if (!defined('ABSPATH')) {
    exit;
}

// Constantes du plugin
define('DST_VERSION', '2.0.0');
define('DST_FILE', __FILE__);
define('DST_DIR_PATH', plugin_dir_path(DST_FILE));
define('DST_DIR_URL', plugin_dir_url(DST_FILE));

/**
 * Classe principale du plugin DC Scroll Top
 */
class DC_Scroll_Top {

    /**
     * Options par defaut
     */
    private $default_options = [
        'active'           => '1',
        'responsive_width' => '650',
        'color'            => '#000000',
        'pos_bottom'       => '10',
        'pos_right'        => '10',
        'size'             => '40',
        'animation'        => 'fade',
        'style'            => '1',
        'scroll_distance'  => '300',
        'scroll_speed'     => '300',
    ];

    private $config = [
        'slug'          => 'dc-scroll-top/dc-scroll-top.php',
        'repo'          => 'dc-scroll-top',
        'access_token'  => '',
        'icon_url'      => 'https://raw.githubusercontent.com/dynamiccreative/dc-scroll-top/main/assets/img/icon-256x256.png',
        'banner_url'      => 'https://raw.githubusercontent.com/dynamiccreative/dc-scroll-top/main/assets/img/banner-1544x500.png'
    ];

    /**
     * Styles SVG disponibles
     */
    private $svg_styles = [
        1 => 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M369.2,311.1c-7.7,7.7-20.2,7.7-28,0l-85.1-85.1l-85.1,85.1c-8.3,7.1-20.8,6.1-28-2.2c-6.4-7.4-6.4-18.4,0-25.8l99.1-99.1c7.7-7.7,20.2-7.7,28,0	l99.1,99.1C377.1,291,377,303.5,369.2,311.1z',
        2 => 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M360.4,214.1c-7.1,7.2-18.7,7.2-25.9,0.1	c0,0-0.1-0.1-0.1-0.1l-60.2-60.3v248.5c0,10.1-8.2,18.3-18.3,18.3c-10.1,0-18.3-8.2-18.3-18.3V153.8l-60.2,60.3	c-7.1,7.2-18.7,7.2-25.9,0.1c0,0-0.1-0.1-0.1-0.1c-7.2-7.1-7.2-18.7-0.1-25.9c0,0,0.1-0.1,0.1-0.1L243,96.7	c7.1-7.2,18.7-7.2,25.9-0.1c0,0,0.1,0.1,0.1,0.1l91.4,91.4c7.2,7.1,7.2,18.7,0.1,25.9C360.5,214.1,360.4,214.1,360.4,214.1z',
        3 => 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M348.2,319.1l-91.4-91.4 l-91.4,91.4l-25.8-25.8l117.2-117.2L374,293.3L348.2,319.1z',
        4 => 'M256,512C114.6,512,0,397.4,0,256S114.6,0,256,0s256,114.6,256,256S397.4,512,256,512z M256,32C132.3,32,32,132.3,32,256 s100.3,224,224,224s224-100.3,224-224S379.7,32,256,32z M384,336c-4.2,0.1-8.3-1.7-11.2-4.8L256,214.7L139.2,331.2	c-6.2,6.2-16.2,6.2-22.4,0c-6.2-6.2-6.2-16.2,0-22.4l128-128c5.8-6.2,15.5-6.5,21.7-0.7c0.2,0.2,0.5,0.5,0.7,0.7l128,128	c6.2,5.8,6.5,15.5,0.7,21.7c-0.2,0.2-0.5,0.5-0.7,0.7C392.3,334.3,388.2,336.1,384,336L384,336z',
        5 => 'M256,0C114.8,0,0,114.8,0,256s114.8,256,256,256s256-114.8,256-256S397.2,0,256,0z M256,480	C132.5,480,32,379.5,32,256S132.5,32,256,32s224,100.5,224,224S379.5,480,256,480z M347.3,228.7c6.3,6.3,6.3,16.4,0,22.6	c-3.1,3.1-7.2,4.7-11.3,4.7s-8.2-1.6-11.3-4.7L272,198.6V352c0,8.8-7.2,16-16,16s-16-7.2-16-16V198.6l-52.7,52.7	c-6.3,6.3-16.4,6.3-22.6,0s-6.3-16.4,0-22.6l80-80c6.3-6.3,16.4-6.3,22.6,0L347.3,228.7z',
        6 => 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M256,475.4 c-121.2,0-219.4-98.2-219.4-219.4S134.8,36.6,256,36.6S475.4,134.8,475.4,256S377.2,475.4,256,475.4z M256,184.5l117.2,117.2 l-25.8,25.8L256,236.1l-91.4,91.4l-25.8-25.8L256,184.5z'
    ];

    /**
     * Constructeur
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialisation du plugin
     */
    public function init() {
        $this->update_plugin();

        // Hooks pour le frontend (seulement si actif)
        if ($this->get_option('active') && apply_filters('dc_scroll_top_show', true)) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
            add_action('wp_head', [$this, 'output_custom_css']);
        }

        // Hooks pour l'admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // Filtres
        add_filter('plugin_action_links_' . plugin_basename(DST_FILE), [$this, 'add_settings_link']);
        add_filter('plugin_row_meta', [$this,'add_row_meta'], 10, 4);
        add_filter('admin_footer_text', [$this, 'admin_footer_text']);
    }

    /*
     * Update
     */
    public function update_plugin() {
        require_once DST_DIR_PATH . 'includes/GitHubUpdater.php';
        $gitHubUpdater = new DstGitHubUpdater(DST_FILE);
        $gitHubUpdater->setPluginIcon($this->config['icon_url']);
        $gitHubUpdater->setPluginBannerSmall($this->config['banner_url']);
        $gitHubUpdater->setPluginBannerLarge($this->config['banner_url']);
        $gitHubUpdater->add();
    }

    /*
     * Rajoute un picto dans la liste des plugins
     */
    public function add_row_meta($links, $file, $plugin_data, $status) {
        if ($this->config['slug'] === $file) {
            $links[] = '<a href="'.esc_attr($plugin_data['PluginURI']).'" class="" target="_blank"><img src="' . $this->config['icon_url'] . '" alt="Icon" style="width:16px;height:16px;vertical-align:middle;"/></a>';
        }
        return $links;
    }

    /**
     * Charge les assets du frontend
     */
    public function enqueue_frontend_assets() {
        $options = $this->get_options();

        wp_enqueue_style(
            'dc-scroll-top',
            DST_DIR_URL . 'assets/css/dcscrolltop.css',
            [],
            DST_VERSION
        );

        wp_enqueue_script(
            'dc-scroll-top',
            DST_DIR_URL . 'assets/js/scrolltop.js',
            [],
            DST_VERSION,
            true
        );

        wp_localize_script('dc-scroll-top', 'DC_STT_Front', [
            'animation'      => $options['animation'],
            'scrollDistance'  => (int) $options['scroll_distance'],
            'scrollSpeed'    => (int) $options['scroll_speed'],
            'scrollTitle'    => __('Retour en haut', 'dc-scroll-top'),
        ]);
    }

    /**
     * Genere le CSS personnalise
     */
    public function output_custom_css() {
        $options = $this->get_options();
        $svg_path = $this->get_svg_style($options['style']);
        $encoded_color = str_replace('#', '%23', $options['color']);

        echo "<!-- DC Scroll Top CSS -->\n";
        echo "<style type='text/css'>\n";
        echo ".scrollup-slide #scrollUp { bottom: -" . ($options['pos_bottom'] + $options['size']) . "px; }\n";
        echo ".scrollup-slide.scrollup #scrollUp { bottom: {$options['pos_bottom']}px; opacity: 1; visibility: visible; }\n";
        echo "#scrollUp {\n";
        echo "  bottom: {$options['pos_bottom']}px;\n";
        echo "  right: {$options['pos_right']}px;\n";
        echo "  height: {$options['size']}px;\n";
        echo "  width: {$options['size']}px;\n";
        echo "  background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path fill=\"{$encoded_color}\" d=\"{$svg_path}\"/></svg>') no-repeat;\n";
        echo "  background-size: contain;\n";
        echo "  visibility: hidden;\n";
        echo "  opacity: 0;\n";
        echo "  transition: all 350ms ease;\n";
        echo "  overflow: hidden;\n";
        echo "}\n";
        echo "#scrollUp:hover { opacity: 0.8; }\n";
        echo "#scrollUp:focus { outline: 2px solid {$options['color']}; outline-offset: 2px; }\n";
        echo ".scrollup #scrollUp { opacity: 1; visibility: visible; }\n";
        echo "@media screen and (max-width: {$options['responsive_width']}px) {\n";
        echo "  #scrollUp { display: none !important; }\n";
        echo "}\n";
        echo "</style>\n";
        echo "<!-- End DC Scroll Top CSS -->\n";
    }

    /**
     * Ajoute le menu d'administration
     */
    public function add_admin_menu() {
        if (is_plugin_active('dc-support-technique/dc-support-technique.php')) {
            add_submenu_page(
                'dc-settings',
                __('DC Scroll Top Options', 'dc-scroll-top'),
                __('Scroll Top', 'dc-scroll-top'),
                'manage_options',
                'dcscrolltop-options',
                [$this, 'admin_page']
            );
        } else {
            add_options_page(
                __('DC Scroll Top Options', 'dc-scroll-top'),
                __('DC Scroll Top', 'dc-scroll-top'),
                'manage_options',
                'dcscrolltop-options',
                [$this, 'admin_page']
            );
        }
    }

    /**
     * Page d'administration
     */
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // Traitement du formulaire
        $saved = false;
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'dcscrolltop_save')) {
            $this->save_options($_POST);
            $saved = true;
        }

        $options = $this->get_options();
        $is_active = (bool) $options['active'];
        ?>
        <div class="dc-stt-wrap">

            <!-- ══ Header ══════════════════════════════════════════════════ -->
            <div class="dc-stt-header">
                <div class="dc-stt-header-left">
                    <div class="dc-stt-header-icon">⬆️</div>
                    <div>
                        <h1>Scroll Top</h1>
                        <p class="dc-stt-header-sub"><?php _e('Bouton scroll to top personnalisable', 'dc-scroll-top'); ?></p>
                    </div>
                </div>
                <span class="dc-stt-header-badge">v<?php echo DST_VERSION; ?></span>
            </div>

            <div class="dc-stt-body">

                <?php if ($saved) : ?>
                    <div class="dc-stt-notice-success">✅ <?php _e('Configuration enregistree.', 'dc-scroll-top'); ?></div>
                <?php endif; ?>

                <form method="post" action="">
                    <?php wp_nonce_field('dcscrolltop_save'); ?>

                    <div class="dc-stt-layout">
                        <div class="dc-stt-main">

                            <!-- ══ Card Activation ═════════════════════════ -->
                            <div class="dc-stt-card">
                                <div class="dc-stt-card-header">
                                    <h2><span>⚡</span> <?php _e('Module', 'dc-scroll-top'); ?></h2>
                                    <label class="dc-stt-toggle-inline" title="<?php esc_attr_e('Activer / desactiver le bouton', 'dc-scroll-top'); ?>">
                                        <span class="dc-stt-toggle-label-txt" id="dc-stt-toggle-text"><?php echo $is_active ? 'Active' : 'Desactive'; ?></span>
                                        <div class="dc-stt-toggle">
                                            <input type="hidden" name="active" value="0">
                                            <input type="checkbox" name="active" value="1" id="dc-stt-active" <?php checked($is_active); ?>>
                                            <span class="dc-stt-slider"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- ══ Card Responsive ═════════════════════════ -->
                            <div class="dc-stt-card">
                                <div class="dc-stt-card-header">
                                    <h2><span>📱</span> <?php _e('Responsive', 'dc-scroll-top'); ?></h2>
                                </div>
                                <div class="dc-stt-card-body">
                                    <table class="dc-stt-settings-table">
                                        <tr>
                                            <th><?php _e('Breakpoint Mobile', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="responsive_width" value="<?php echo esc_attr($options['responsive_width']); ?>" min="1" max="2000" class="dc-stt-input" />
                                                <span class="dc-stt-unit">px</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- ══ Card Apparence ══════════════════════════ -->
                            <div class="dc-stt-card">
                                <div class="dc-stt-card-header">
                                    <h2><span>🎨</span> <?php _e('Apparence', 'dc-scroll-top'); ?></h2>
                                </div>
                                <div class="dc-stt-card-body">
                                    <table class="dc-stt-settings-table">
                                        <tr>
                                            <th><?php _e('Couleur', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="text" name="color" value="<?php echo esc_attr($options['color']); ?>" class="color-field" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Position bas', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="pos_bottom" value="<?php echo esc_attr($options['pos_bottom']); ?>" min="0" max="100" class="dc-stt-input" />
                                                <span class="dc-stt-unit">px</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Position droite', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="pos_right" value="<?php echo esc_attr($options['pos_right']); ?>" min="0" max="100" class="dc-stt-input" />
                                                <span class="dc-stt-unit">px</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Taille', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="size" value="<?php echo esc_attr($options['size']); ?>" min="20" max="100" class="dc-stt-input" />
                                                <span class="dc-stt-unit">px</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Icone', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <div class="dc-stt-radio-svg">
                                                    <?php foreach ($this->svg_styles as $style_id => $svg_path): ?>
                                                        <label>
                                                            <input type="radio" name="style" value="<?php echo $style_id; ?>" <?php checked($options['style'], $style_id); ?> />
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                                <path d="<?php echo esc_attr($svg_path); ?>" />
                                                            </svg>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- ══ Card Comportement ═══════════════════════ -->
                            <div class="dc-stt-card">
                                <div class="dc-stt-card-header">
                                    <h2><span>⚙️</span> <?php _e('Comportement', 'dc-scroll-top'); ?></h2>
                                </div>
                                <div class="dc-stt-card-body">
                                    <table class="dc-stt-settings-table">
                                        <tr>
                                            <th><?php _e('Animation', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <select name="animation" class="dc-stt-input">
                                                    <option value="fade" <?php selected($options['animation'], 'fade'); ?>>Fade</option>
                                                    <option value="slide" <?php selected($options['animation'], 'slide'); ?>>Slide</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Distance d\'apparition', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="scroll_distance" value="<?php echo esc_attr($options['scroll_distance']); ?>" min="50" max="2000" class="dc-stt-input" />
                                                <span class="dc-stt-unit">px</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Vitesse de remontee', 'dc-scroll-top'); ?></th>
                                            <td>
                                                <input type="number" name="scroll_speed" value="<?php echo esc_attr($options['scroll_speed']); ?>" min="100" max="3000" step="100" class="dc-stt-input" />
                                                <span class="dc-stt-unit">ms</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <!-- ══ Sidebar ═════════════════════════════════════ -->
                        <div class="dc-stt-aside">

                            <!-- Preview -->
                            <div class="dc-stt-preview-card">
                                <div class="dc-stt-card-header">
                                    <h2><span>👁️</span> <?php _e('Apercu', 'dc-scroll-top'); ?></h2>
                                </div>
                                <div class="dc-stt-preview-zone" id="dc-stt-preview-zone">
                                    <div class="dc-stt-preview-btn" id="dc-stt-preview-btn"></div>
                                </div>
                            </div>

                            <!-- A propos -->
                            <div class="dc-stt-sidebar">
                                <h3><?php _e('A propos', 'dc-scroll-top'); ?></h3>
                                <p><?php _e('Agence Web creee en 1999. Conception de sites Internet, Mobile, developpement et bien d\'autres...', 'dc-scroll-top'); ?></p>
                                <a href="https://www.dynamic-creative.com" target="_blank">
                                    <img src="<?php echo DST_DIR_URL; ?>assets/img/logo.png" alt="dynamic-creative.com" width="200" height="44" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ══ Save bar ════════════════════════════════════════ -->
                    <div class="dc-stt-save-bar">
                        <button type="submit" name="submit" value="1" class="dc-stt-btn-primary">💾 <?php _e('Enregistrer', 'dc-scroll-top'); ?></button>
                    </div>

                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Charge les assets de l'admin
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'dcscrolltop-options') !== false) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style(
                'dc-scroll-top-admin',
                DST_DIR_URL . 'assets/css/admin.css',
                [],
                DST_VERSION
            );
            wp_enqueue_script(
                'dc-scroll-top-admin',
                DST_DIR_URL . 'assets/js/admin.js',
                ['jquery', 'wp-color-picker'],
                DST_VERSION,
                true
            );
            wp_localize_script('dc-scroll-top-admin', 'DC_STT', [
                'svg_styles' => $this->svg_styles,
            ]);
        }
    }

    /**
     * Ajoute le lien de parametres
     */
    public function add_settings_link($links) {
        $url = is_plugin_active('dc-support-technique/dc-support-technique.php')
            ? 'admin.php?page=dcscrolltop-options'
            : 'options-general.php?page=dcscrolltop-options';

        $settings_link = sprintf('<a href="%s">%s</a>', $url, __('Settings', 'dc-scroll-top'));
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Texte du footer admin
     */
    public function admin_footer_text($footer_text) {
        $screen = get_current_screen();
        if ($screen && strpos($screen->id, 'dcscrolltop-options') !== false) {
            return sprintf(
                __('Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'dc-scroll-top'),
                '<strong>DC Scroll Top</strong>',
                '<a href="https://github.com/dynamiccreative" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
            );
        }
        return $footer_text;
    }

    /**
     * Recupere une option
     */
    private function get_option($key) {
        return get_option("dst_{$key}", $this->default_options[$key] ?? '');
    }

    /**
     * Recupere toutes les options
     */
    private function get_options() {
        $options = [];
        foreach ($this->default_options as $key => $default) {
            $options[$key] = $this->get_option($key);
        }
        return $options;
    }

    /**
     * Sauvegarde les options
     */
    private function save_options($post_data) {
        $allowed_keys = array_keys($this->default_options);

        foreach ($allowed_keys as $key) {
            if (isset($post_data[$key])) {
                $value = sanitize_text_field($post_data[$key]);

                // Validation specifique
                switch ($key) {
                    case 'active':
                        $value = $value ? '1' : '0';
                        break;
                    case 'color':
                        $value = sanitize_hex_color($value) ?: $this->default_options[$key];
                        break;
                    case 'responsive_width':
                    case 'pos_bottom':
                    case 'pos_right':
                    case 'size':
                    case 'scroll_distance':
                    case 'scroll_speed':
                        $value = absint($value) ?: $this->default_options[$key];
                        break;
                    case 'animation':
                        $value = in_array($value, ['fade', 'slide']) ? $value : $this->default_options[$key];
                        break;
                    case 'style':
                        $value = array_key_exists($value, $this->svg_styles) ? $value : $this->default_options[$key];
                        break;
                }

                update_option("dst_{$key}", $value);
            }
        }
    }

    /**
     * Recupere le chemin SVG pour un style donne
     */
    private function get_svg_style($style_id) {
        return isset($this->svg_styles[$style_id]) ? $this->svg_styles[$style_id] : $this->svg_styles[1];
    }
}

// Initialisation du plugin
new DC_Scroll_Top();
