<?php
namespace PassCode;

/**
 * Description of PassCode
 * 
 * @author BYMICHAEL
 */
class PassCode 
{
    protected $imgWidth;
    protected $imgHeight;
    protected $bgColor;
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
    public function __construct($imgWidth=120, $imgHeight=34, $red=200, $green=255, $blue=255)
    {
        $this->imgWidth = $imgWidth;
        $this->imgHeight = $imgHeight;
        $this->bgColor = $bgColor;
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }
    public function generate($noiseBackground=true, $length=4)
    {
        $imgWidth=$this->imgWidth;
        $imgHeight=$this->imgHeight;
        $r=$this->red;
        $g=$this->green;
        $b=$this->blue;
        $pCode="";
        for( $i = 0; $i < $length; $i++ ) {
            if($i%2==0) {
                $pCode.=chr(rand(49,57));
            } else {
                        $ascii=rand(97,122);
                    if ($ascii==108) {
                        $ascii++;
                    }
                $pCode.=chr($ascii);
            }
        }
        $_SESSION['passCode']=$pCode;
        /* create image */
        $im=imagecreate($imgWidth,$imgHeight);
        $bgColor=imagecolorallocate($im,$r,$g,$b);
        /* noise background */
        if ($noiseBackground) {
            for($i=0;$i<60;$i++) {
                $noiseChar="#";
                if($i%2==0) {
                    $noiseChar="*";
                }
                $noiseColor=imagecolorallocate($im,rand(200,250),rand(200,250),rand(200,250));
                imagestring($im,1,rand(1,$imgWidth),rand(1,$imgHeight),$noiseChar,$noiseColor);
            }            
        }
        /*put code into image*/
        for($i=0;$i<strlen($pCode);$i++) {
            $fontSize=5;
            $fontX=$imgWidth/strlen($pCode)*$i+10;
            $fontY=rand(1,$imgHeight/2);
            $fontColor=imagecolorallocate($im,rand(0,150),rand(0,150),rand(0,150));
            imagestring($im,$fontSize,$fontX,$fontY,$pCode[$i],$fontColor);
        }
        header("Content-type: image/jpeg");
        imagejpeg($im,NULL,100);
        imagedestroy( $im );
    }
}
