<?php
  $sTemplate = <<<EOD
        <div class="item">
            <div class="title">
                <b>Лучшая работа…</b>
            </div>
            <div class="img">
                <a rel="prettyPhoto[pp_gal]" href="/images/current/portfolio/{img1}">
                    <img src="/images/current/portfolio/{img1}" />
                </a>
            </div>
        </div>
EOD;
   
  function add_watermark($img, $text, $font, $r = 128, $g = 128, $b = 128, $alpha = 100)
  {
    //получаем ширину и высоту исходного изображения
    $width = imagesx($img);
    $height = imagesy($img);
    //угол поворота текста
    $angle = -rad2deg(atan2((-$height),($width))); 
 
    //добавляем пробелы к строке
    $text = ' '.$text.' ';
 
    $c = imagecolorallocatealpha($img, $r, $g, $b, $alpha);
    $size = (($width+$height)/2)*2/strlen($text);
    $box  = imagettfbbox ( $size, $angle, $font, $text );
    $x = $width/2 - abs($box[4] - $box[0])/2;
    $y = $height/2 + abs($box[5] - $box[1])/2;
 
    //записываем строку на изображение
    imagettftext($img,$size ,$angle, $x, $y, $c, $font, $text);
    return $img;
  }
   
 
   
  $sResult = '';
  $i  = 0;
  $aFiles = glob('in/*');
  foreach($aFiles as $sFile)
  {
    $i++;
/* 
    if($i > 1)
    {
      break;
    }
*/
    $img = imagecreatefromjpeg($sFile);
    $image = add_watermark($img, 'Oddler.ru', 'Metroplex Shadow.ttf');
  
      //выводим изображение
      //header('content-type: image/jpeg'); 
      //imageJPEG($image);
 
//      imageJPEG($image, str_replace('in/', 'out/', $sFile));
  
    imagedestroy($image);
     
    $sResult .= str_replace('{img1}', basename($sFile), $sTemplate);
  }
   
  echo $sResult;