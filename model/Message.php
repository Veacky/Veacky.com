<?php


class message
{
    public function setFlash($message,$type ='danger'){
        $_SESSION['flash'] = array(
            'message' => $message,
            'type' => $type
        );
    }
    public function flash(){
        if(isset($_SESSION['flash'])){
            if($_SESSION['flash']['type'] == 'danger'){
                $animate = " animated shake";
            } else {
                $animate =' animated bounce';
            }
            echo "<div class=\"alert alert-".$_SESSION['flash']['type'].$animate."\" role=\"alert\">".$_SESSION['flash']['message']."</div>";
            unset($_SESSION['flash']);

        }
    }

}