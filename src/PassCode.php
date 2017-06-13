<?php
namespace PassCode;

/**
 * Description of PassCode
 * 
 * @author BYMICHAEL
 */
class PassCode 
{
    public function generate()
    {
        $imgWidth=120;
        $imgHeight=34;
        $pCode="";
        for( $i = 0; $i < 4; $i++ ) {
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
        $_SESSION['rb_passCode']=$pCode;
        $im=imagecreate($imgWidth,$imgHeight);
        $bgColor=imagecolorallocate($im,200,255,255);
        for($i=0;$i<60;$i++) {
            $noiseChar="#";
            if($i%2==0) {
                $noiseChar="*";
            }
            $noiseColor=imagecolorallocate($im,rand(200,250),rand(200,250),rand(200,250));
            imagestring($im,1,rand(1,$imgWidth),rand(1,$imgHeight),$noiseChar,$noiseColor);
        }
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
