<?php
if (!defined('ABSPATH')) exit;

class WDA_Plugin {
    private static $instance;
    private $option_key = 'wda_content';

    public static function instance() {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('wda_assignment', [$this, 'render_shortcode']);
        register_activation_hook(dirname(__DIR__) . '/wordpress-developer-assignment.php', [$this, 'activate']);
    }

    public function activate() {
        if (!get_option($this->option_key)) {
            add_option($this->option_key, [
                'eyebrow' => 'Digital experience studio',
                'title' => 'Build a digital presence that feels unmistakably yours.',
                'description' => 'A clean, responsive WordPress experience built with custom PHP, HTML, CSS and JavaScript — without a page builder.',
                'primary_text' => 'Explore the work',
                'primary_url' => '#work',
                'secondary_text' => 'Get in touch',
                'secondary_url' => '#contact',
                'stats' => [
                    ['number' => '12+', 'label' => 'Projects delivered'],
                    ['number' => '98%', 'label' => 'Client satisfaction'],
                    ['number' => '24/7', 'label' => 'Digital availability'],
                ],
                'cards' => [
                    ['tag' => '01', 'title' => 'Strategy', 'text' => 'Clear goals, focused messaging and a content structure that is easy to manage.'],
                    ['tag' => '02', 'title' => 'Design', 'text' => 'Responsive visual systems with careful spacing, typography and hierarchy.'],
                    ['tag' => '03', 'title' => 'Development', 'text' => 'Semantic WordPress code, reusable components and maintainable assets.'],
                ],
                'footer_text' => 'Custom WordPress implementation for the developer assignment.'
            ]);
        }

        if (!get_page_by_path('developer-assignment')) {
            wp_insert_post([
                'post_title' => 'Developer Assignment',
                'post_name' => 'developer-assignment',
                'post_content' => '[wda_assignment]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
        }
    }

    public function enqueue_assets() {
        wp_enqueue_style('wda-style', WDA_URL . 'assets/css/style.css', [], WDA_VERSION);
        wp_enqueue_script('wda-script', WDA_URL . 'assets/js/script.js', [], WDA_VERSION, true);
    }

    private function content() {
        $defaults = get_option($this->option_key, []);
        return wp_parse_args($defaults, [
            'eyebrow'=>'Digital experience studio','title'=>'Build a digital presence that feels unmistakably yours.',
            'description'=>'A clean, responsive WordPress experience built with custom PHP, HTML, CSS and JavaScript — without a page builder.',
            'primary_text'=>'Explore the work','primary_url'=>'#work','secondary_text'=>'Get in touch','secondary_url'=>'#contact',
            'stats'=>[],'cards'=>[],'footer_text'=>''
        ]);
    }

    public function render_shortcode() {
        $c = $this->content();
        ob_start(); ?>
        <div class="wda-site">
            <header class="wda-header">
                <a class="wda-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home">W<span>/</span>A</a>
                <button class="wda-menu" type="button" aria-expanded="false" aria-controls="wda-nav">Menu</button>
                <nav id="wda-nav" class="wda-nav" aria-label="Primary navigation">
                    <a href="#about">About</a><a href="#work">Services</a><a href="#contact">Contact</a>
                </nav>
            </header>

            <main>
                <section class="wda-hero" id="about">
                    <div class="wda-hero-copy">
                        <p class="wda-eyebrow"><?php echo esc_html($c['eyebrow']); ?></p>
                        <h1><?php echo esc_html($c['title']); ?></h1>
                        <p class="wda-lead"><?php echo esc_html($c['description']); ?></p>
                        <div class="wda-actions">
                            <a class="wda-btn wda-btn-primary" href="<?php echo esc_url($c['primary_url']); ?>"><?php echo esc_html($c['primary_text']); ?><span>↗</span></a>
                            <a class="wda-text-link" href="<?php echo esc_url($c['secondary_url']); ?>"><?php echo esc_html($c['secondary_text']); ?> <span>→</span></a>
                        </div>
                    </div>
                    <div class="wda-hero-art" aria-hidden="true"><div class="wda-orbit orbit-one"></div><div class="wda-orbit orbit-two"></div><div class="wda-orbit orbit-three"></div><div class="wda-core">WP</div></div>
                </section>

                <section class="wda-stats" aria-label="Highlights">
                    <?php foreach ($c['stats'] as $stat): ?>
                        <div class="wda-stat"><strong><?php echo esc_html($stat['number'] ?? ''); ?></strong><span><?php echo esc_html($stat['label'] ?? ''); ?></span></div>
                    <?php endforeach; ?>
                </section>

                <section class="wda-work" id="work">
                    <div class="wda-section-head"><p class="wda-eyebrow">What we do</p><h2>Built around clarity, performance and maintainability.</h2></div>
                    <div class="wda-grid">
                    <?php foreach ($c['cards'] as $card): ?>
                        <article class="wda-card"><span class="wda-card-number"><?php echo esc_html($card['tag'] ?? ''); ?></span><h3><?php echo esc_html($card['title'] ?? ''); ?></h3><p><?php echo esc_html($card['text'] ?? ''); ?></p><span class="wda-arrow">↗</span></article>
                    <?php endforeach; ?>
                    </div>
                </section>

                <section class="wda-cta" id="contact">
                    <p class="wda-eyebrow">Start a conversation</p><h2>Ready to turn the idea into a working website?</h2><a class="wda-btn wda-btn-light" href="mailto:hello@example.com">hello@example.com <span>↗</span></a>
                </section>
            </main>

            <footer class="wda-footer"><span><?php echo esc_html($c['footer_text']); ?></span><span><?php echo esc_html(date('Y')); ?> ©</span></footer>
        </div>
        <?php return ob_get_clean();
    }
}
