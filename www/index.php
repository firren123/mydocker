<?php

/**
 * Created by PhpStorm.
 * User: lichenjun
 * Date: 17/8/9
 * Time: 上午1:30
 */
class index
{

    public function aa(){
        for($n=0;$n<100;$n++){
            echo "good <br>";
        }
    }
}

$c = new index();
$c->aa();

    phpinfo();
