<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Cookie;

class LoginCtrl extends Ctrl{
    public function handle($req,$args){
        $userMdl=$this->container->get("user");
        $cookie=$req->getCookieParams();
        if(isset($cookie["boke0ick-jwt"])&&$userMdl->session($cookie["boke0ick-jwt"])){
            return $this->createResponse()
                      ->withHeader("Location","/admin");
        }
        if($req->getServerParams()["REQUEST_METHOD"]=="POST"){
            try{
                $post=$req->getParsedBody();
                $this->csrfTokenCheck($post["token"]);
                $user=$userMdl->login($post["screen_name"],$post["password"]);
                Cookie::set("boke0ick-jwt",$userMdl->createJWT($user),3600*24*12+time());
                return $this->createResponse()
                            ->withHeader("Location","/admin");
            }catch(\Exception $e){
                $message=$e->getMessage();
            }
        }else{
            $this->csrfTokenSet();
        }
        return $this->twig("login",[
            "message"=>$message,
            "post"=>$post
        ]);   
    }
    public function logout($req,$args){
        Cookie::delete("boke0ick-jwt");
        return $this->createResponse()
            ->withHeader("Location","/admin/login");
    }
}
