<?php

namespace Xablau\Captcha;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CaptchaController extends Controller
{

    public function __construct()
    {
    }

    public function create()
    {
        $canvasHeigth = 70;
        $canvasWidth = 200;


        $img = Image::canvas(32, 32, '#ff0000');

        $img->save(public_path('captcha/'.time().'.png'));

        dd(1);
    }
}
