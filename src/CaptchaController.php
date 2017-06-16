<?php

namespace Xablau\Captcha;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class CaptchaController extends Controller
{

    private $canvas;
    private $canvasWidth;
    private $canvasHeight;
    private $length;
    private $font;
    private $fontSize;
    private $captcha;

    public function __construct()
    {
        $this->canvasWidth = 200;
        $this->canvasHeight = 70;
        $this->length = 4;
        $this->font = __DIR__ . '/fonts/Verdana.ttf';
        $this->fontSize = 35;
        $this->captcha = $this->randomString();
    }

    public function create()
    {
        $padding = $this->fontSize + 5;
        $canvasArea = $this->canvasWidth - $padding;
        $maxCharWidth = $canvasArea / $this->length;
        $left = $padding / 2;
        $characters = str_split($this->captcha);
        $this->canvas = Image::canvas($this->canvasWidth, $this->canvasHeight, $this->randomBackgroundColor());
        foreach ($characters as $char) {
            $minX = $left < $padding ? $padding : $left;
            $maxX = $left > ($this->canvasWidth - $padding) ? $this->canvasWidth - $padding : $left;
            $minY = 0 + $padding;
            $maxY = $this->canvasHeight - 10;
            $this->canvas->text($char, rand($minX, $maxX), rand($minY, $maxY), function ($font) {
                $font->file($this->font);
                $font->size($this->fontSize);
                $font->color('#' . $this->randomForegroundColor());
                $font->angle(rand(-45, 45));
            });
            $left += $maxCharWidth;
        }

        return $this;
    }

    public function pixalate($pixalate = true)
    {
        if ($pixalate) {
            $this->canvas->pixelate(2);
        }
        return $this;
    }

    public function lines($lines = true)
    {
        if ($lines) {
            for ($i = 0; $i < rand(1, 4); $i++) {
                $this->canvas->line($this->randomXPoint($this->canvasWidth), $this->randomYPoint($this->canvasHeight), $this->randomXPoint($this->canvasWidth), $this->randomYPoint($this->canvasHeight), function ($draw) {
                    $draw->color('#' . $this->randomForegroundColor());
                });
            }

        }
        return $this;
    }

    public function poligon($poligon = true)
    {
        if ($poligon) {
            $points = [];
            for ($i = 0; $i < rand(3, 8); $i++) {
                $points[] = $this->randomXPoint($this->canvasWidth);
                $points[] = $this->randomYPoint($this->canvasHeight);

            }
            $this->canvas->polygon($points, function ($draw) {
                $draw->border(1, '#' . $this->randomForegroundColor());
            });
        }
        return $this;
    }

    public function save($path = false)
    {
        if ($path) {
            $this->canvas->save($path);
        } else {
            $this->canvas->save(public_path('captcha/' . time() . '.png'));
        }
    }

    public function setWidth($canvasWidth)
    {
        $this->canvasWidth = $canvasWidth;
        return $this;
    }

    public function getWidth()
    {
        return $this->canvasWidth;
    }

    public function setHeight($canvasHeight)
    {
        $this->canvasHeight = $canvasHeight;
        return $this;
    }

    public function getHeight()
    {
        return $this->canvasHeight;
    }

    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setFont($filepath)
    {
        $this->font = $filepath;
        return $this;
    }

    public function setFontSize($size)
    {
        $this->fontSize = $size;
        return $this;
    }

    public function getString()
    {
        return $this->captcha;
    }

    private function randomXPoint($width)
    {
        return rand(0, $width);
    }

    private function randomYPoint($height)
    {
        return rand(0, $height);
    }

    private function randomBrightColor()
    {
        return str_pad(dechex(mt_rand(200, 255)), 2, '0', STR_PAD_LEFT);
    }

    private function randomDarkColor()
    {
        return str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT);
    }

    private function randomBackgroundColor()
    {
        return $this->randomBrightColor() . $this->randomBrightColor() . $this->randomBrightColor();
    }

    private function randomForegroundColor()
    {
        return $this->randomDarkColor() . $this->randomDarkColor() . $this->randomDarkColor();
    }

    private function randomString($numbers = true)
    {
        $char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $return = '';
        $characters = '';
        $characters .= $char;
        if ($numbers) $characters .= $num;
        $len = strlen($characters);
        for ($n = 1; $n <= $this->length; $n++) {
            $rand = mt_rand(1, $len);
            $return .= $characters[$rand - 1];
        }
        return $return;
    }
}
