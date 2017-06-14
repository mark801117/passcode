<?php
namespace PassCode;

/**
 * Description of PassCode
 * 
 * @author Cloud
 */
class PassCode 
{
    protected $imgWidth;
    protected $imgHeight;
    protected $red;
    protected $green;
    protected $blue;
    /**
     * 驗證碼產生器 (將產生結果存在SESSION['passCode'])
     * @param type $imgWidth 圖片寬
     * @param type $imgHeight 圖片高
     * @param type $red 
     * @param type $green
     * @param type $blue
     */
    public function __construct($imgWidth = 120, $imgHeight = 34, $red = 200, $green = 255, $blue = 255)
    {
        $this->imgWidth = $imgWidth;
        $this->imgHeight = $imgHeight;
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }
    /**
     * generate random code by given length
     * @param type $length
     */
    private function generateCode($length = 4)
    {
        $code="";
        for($i=0; $i<$length; $i++ ) {
            if($i%2==0) {
                $code.=chr(rand(49, 57));
            } else {
                        $ascii=rand(97, 122);
                    if ($ascii==108) {
                        $ascii++;
                    }
                $code.=chr($ascii);
            }
        }
        return $code;
    }
    /**
     * noise background 
     * @param type $im
     * @return type
     */
    private function noiseBackground($im)
    {
        for($i=0;$i<60;$i++) {
            $noiseChar="#";
            if($i%2==0) {
                $noiseChar="*";
            }
            $noiseColor=imagecolorallocate($im,rand(200,250),rand(200,250),rand(200,250));
            imagestring($im, 1, rand(1, $this->imgWidth), rand(1, $this->imgHeight), $noiseChar, $noiseColor);
        }
        return $im;
    }
    /**
     * create image
     * @return type
     */
    private function generateImage()
    {
        $im=imagecreate($this->imgWidth, $this->imgHeight);
        $bgColor=imagecolorallocate($im, $this->red, $this->green, $this->blue);
        return $im;
    }
    /**
     * put code into image
     * @param type $pCode
     * @param type $im
     * @return type
     */
    private function putCodeInImage($pCode, $im)
    {
        for($i=0;$i<strlen($pCode);$i++) {
            $fontSize=5;
            $fontX=$this->imgWidth/strlen($pCode)*$i+10;
            $fontY=rand(1, $this->imgHeight/2);
            $fontColor=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
            imagestring($im, $fontSize, $fontX, $fontY, $pCode[$i], $fontColor);
        }
        return $im;
    }
    /**
     * generate a passcode image HTML page
     * @param type $noiseBackground 
     * @param type $length
     */
    public function generate($noiseBackground = true, $length = 4)
    {
        $imgWidth=$this->imgWidth;
        $imgHeight=$this->imgHeight;
        $r=$this->red;
        $g=$this->green;
        $b=$this->blue;
        $pCode=$this->generateCode($length);
        $_SESSION['passCode']=$pCode;
        $im=$this->generateImage();
        if ($noiseBackground) {
            $this->noiseBackground($im);
        }
        $im=$this->putCodeInImage($pCode, $im);
        header("Content-type: image/jpeg");
        imagejpeg($im, NULL, 100);
        imagedestroy( $im );
    }
}
