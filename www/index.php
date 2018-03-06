<?php

/**
 * Created by PhpStorm.
 * User: lichenjun
 * Date: 17/8/9
 * Time: 上午1:30
 */
//echo 333;exit;
phpinfo();
class index
{
    public $a = 123;
    public function aaa(){
        for($n=1;$n<100;$n++){
            echo $n."<br>";
        }
        echo 44444444;
    }

}
$index = new index();
$index->aaa();
echo "dddddd";
exit;