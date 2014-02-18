<?php

namespace application\system\library\capcha;

/**
 * Description of SCapcha
 *
 * @author FARFLK
 */
class SCapcha {

    private $image = null;
    private $config = array();
    private $code;

    public function __construct($config = array()) {
        if (!function_exists('gd_info')) {
            throw new \Exception('Required GD library is missing');
        }
        $this->initConfig($config);
        $this->generate();
    }

    private function initConfig($config) {
        $bgPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . "backgrounds" . DIRECTORY_SEPARATOR;
        $fontPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR;

        $this->config = array(
            'code' => '',
            'min_length' => 5,
            'max_length' => 5,
            'backgrounds' => array(
                $bgPath . '45-degree-fabric.png',
                $bgPath . 'cloth-alike.png',
                $bgPath . 'grey-sandbag.png',
                $bgPath . 'kinda-jean.png',
                $bgPath . 'polyester-lite.png',
                $bgPath . 'stitched-wool.png',
                $bgPath . 'white-carbon.png',
                $bgPath . 'white-wave.png'
            ),
            'fonts' => array(
                $fontPath . 'times_new_yorker.ttf'
            ),
            'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
            'min_font_size' => 28,
            'max_font_size' => 28,
            'color' => '#666',
            'angle_min' => 0,
            'angle_max' => 10,
            'shadow' => true,
            'shadow_color' => '#fff',
            'shadow_offset_x' => -1,
            'shadow_offset_y' => 1
        );

        if (is_array($config)) {
            foreach ($config as $key => $value)
                $this->config[$key] = $value;
        }

        if ($this->config['min_length'] < 1)
            $this->config['min_length'] = 1;
        if ($this->config['angle_min'] < 0)
            $this->config['angle_min'] = 0;
        if ($this->config['angle_max'] > 10)
            $this->config['angle_max'] = 10;
        if ($this->config['angle_max'] < $this->config['angle_min'])
            $this->config['angle_max'] = $this->config['angle_min'];
        if ($this->config['min_font_size'] < 10)
            $this->config['min_font_size'] = 10;
        if ($this->config['max_font_size'] < $this->config['min_font_size'])
            $this->config['max_font_size'] = $this->config['min_font_size'];
    }

    public function generate() {
        srand(microtime() * 100);
        if (empty($this->config['code'])) {
            $this->config['code'] = '';
            $length = rand($this->config['min_length'], $this->config['max_length']);
            while (strlen($this->config['code']) < $length) {
                $this->config['code'] .= substr($this->config['characters'], rand() % (strlen($this->config['characters'])), 1);
            }
        }
        $this->code = $this->config['code'];
        
// Use milliseconds instead of seconds
        srand(microtime() * 100);

// Pick random background, get info, and start captcha
        $background = $this->config['backgrounds'][rand(0, count($this->config['backgrounds']) - 1)];
        list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);

        $captcha = imagecreatefrompng($background);

        $color = $this->hex2rgb($this->config['color']);
        $color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);

// Determine text angle
        $angle = rand($this->config['angle_min'], $this->config['angle_max']) * (rand(0, 1) == 1 ? -1 : 1);

// Select font randomly
        $font = $this->config['fonts'][rand(0, count($this->config['fonts']) - 1)];

// Verify font file exists
        if (!file_exists($font))
            throw new \Exception('Font file not found: ' . $font);

//Set the font size.
        $font_size = rand($this->config['min_font_size'], $this->config['max_font_size']);
        $text_box_size = imagettfbbox($font_size, $angle, $font, $this->config['code']);

// Determine text position
        $box_width = abs($text_box_size[6] - $text_box_size[2]);
        $box_height = abs($text_box_size[5] - $text_box_size[1]);
        $text_pos_x_min = 0;
        $text_pos_x_max = ($bg_width) - ($box_width);
        $text_pos_x = rand($text_pos_x_min, $text_pos_x_max);
        $text_pos_y_min = $box_height;
        $text_pos_y_max = ($bg_height) - ($box_height / 2);
        $text_pos_y = rand($text_pos_y_min, $text_pos_y_max);

// Draw shadow
        if ($this->config['shadow']) {
            $shadow_color = $this->hex2rgb($this->config['shadow_color']);
            $shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
            imagettftext($captcha, $font_size, $angle, $text_pos_x + $this->config['shadow_offset_x'], $text_pos_y + $this->config['shadow_offset_y'], $shadow_color, $font, $this->config['code']);
        }

// Draw text
        imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $this->config['code']);
        ob_start();
        imagepng($captcha);
        $this->image = ob_get_contents();
        ob_end_clean();
    }
    
    private function hex2rgb($hex_str, $return_string = false, $separator = ',') {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
        $rgb_array = array();
        if (strlen($hex_str) == 6) {
            $color_val = hexdec($hex_str);
            $rgb_array['r'] = 0xFF & ($color_val >> 0x10);
            $rgb_array['g'] = 0xFF & ($color_val >> 0x8);
            $rgb_array['b'] = 0xFF & $color_val;
        } elseif (strlen($hex_str) == 3) {
            $rgb_array['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $rgb_array['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $rgb_array['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            return false;
        }
        return $return_string ? implode($separator, $rgb_array) : $rgb_array;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCode() {
        return $this->code;
    }
    
    public function getImageB64() {
        return base64_encode($this->image);
    }

}