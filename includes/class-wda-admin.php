<?php
if (!defined('ABSPATH')) exit;

class WDA_Admin {
    private static $instance;
    private $key = 'wda_content';
    public static function instance() { if (!self::$instance) self::$instance = new self(); return self::$instance; }
    private function __construct() { add_action('admin_menu', [$this,'menu']); add_action('admin_init', [$this,'register']); }

    public function menu() { add_options_page('Assignment Website','Assignment Website','manage_options','wda-settings',[$this,'page']); }

    public function register() {
        register_setting('wda_group',$this->key,['sanitize_callback'=>[$this,'sanitize']]);
        add_settings_section('wda_main','Homepage content',function(){ echo '<p>Update the live page without editing code. The page uses the <code>[wda_assignment]</code> shortcode.</p>'; },'wda-settings');
        foreach (['eyebrow'=>'Eyebrow','title'=>'Hero title','description'=>'Hero description','primary_text'=>'Primary button text','primary_url'=>'Primary button URL','secondary_text'=>'Secondary link text','secondary_url'=>'Secondary link URL','footer_text'=>'Footer text'] as $field=>$label) {
            add_settings_field($field,$label,[$this,'field'],'wda-settings','wda_main',['field'=>$field]);
        }
        add_settings_field('stats','Stats',[$this,'stats_field'],'wda-settings','wda_main');
        add_settings_field('cards','Service cards',[$this,'cards_field'],'wda-settings','wda_main');
    }

    private function value($field,$default='') { $c=get_option($this->key,[]); return $c[$field] ?? $default; }
    public function field($args) { $f=$args['field']; printf('<input class="regular-text" type="text" name="%s[%s]" value="%s">',$this->key,esc_attr($f),esc_attr($this->value($f))); }
    public function stats_field() { $stats=$this->value('stats',[]); echo '<div class="wda-repeater">'; for($i=0;$i<3;$i++){ $s=$stats[$i]??[]; printf('<p><input name="%1$s[stats][%2$d][number]" placeholder="Number" value="%3$s"> <input class="regular-text" name="%1$s[stats][%2$d][label]" placeholder="Label" value="%4$s"></p>',$this->key,$i,esc_attr($s['number']??''),esc_attr($s['label']??'')); } echo '</div>'; }
    public function cards_field() { $cards=$this->value('cards',[]); echo '<div>'; for($i=0;$i<3;$i++){ $x=$cards[$i]??[]; printf('<fieldset style="border:1px solid #ddd;padding:12px;margin:8px 0"><input name="%1$s[cards][%2$d][tag]" placeholder="01" value="%3$s"> <input class="regular-text" name="%1$s[cards][%2$d][title]" placeholder="Title" value="%4$s"><br><textarea name="%1$s[cards][%2$d][text]" rows="2" cols="60" placeholder="Description">%5$s</textarea></fieldset>',$this->key,$i,esc_attr($x['tag']??''),esc_attr($x['title']??''),esc_textarea($x['text']??'')); } echo '</div>'; }
    public function sanitize($input) {
        $out=[]; foreach(['eyebrow','title','description','primary_text','primary_url','secondary_text','secondary_url','footer_text'] as $f) $out[$f]=isset($input[$f]) ? sanitize_text_field($input[$f]) : '';
        $out['stats']=[]; foreach(($input['stats']??[]) as $s) $out['stats'][]=['number'=>sanitize_text_field($s['number']??''),'label'=>sanitize_text_field($s['label']??'')];
        $out['cards']=[]; foreach(($input['cards']??[]) as $x) $out['cards'][]=['tag'=>sanitize_text_field($x['tag']??''),'title'=>sanitize_text_field($x['title']??''),'text'=>sanitize_textarea_field($x['text']??'')];
        return $out;
    }
    public function page() { if(!current_user_can('manage_options')) return; ?><div class="wrap"><h1>Assignment Website</h1><form method="post" action="options.php"><?php settings_fields('wda_group'); do_settings_sections('wda-settings'); submit_button('Save changes'); ?></form><hr><p><strong>Live page:</strong> <a href="<?php echo esc_url(home_url('/developer-assignment/')); ?>" target="_blank">Open Developer Assignment ↗</a></p></div><?php }
}
