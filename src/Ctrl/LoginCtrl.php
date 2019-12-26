<?php

namespace Boke0\Mechanism\Ctrl;

class LoginCtrl extends Ctrl{
    public function handle($req,$args){
        $userMdl=$this->container->get("user");
        if($userMdl->session($req->getCookieParams()["boke0ick-jwt"])){
            return $this->createResponse()
                        ->withHeader("Location","/admin");
        }
        if($req->getServerParams()["REQUEST_METHOD"]=="POST"){
            try{
                $this->csrfTokenCheck();
                $post=$req->getParsedBody();
                $user=$userMdl->login($post["email"],$post["password"]);
                return $this->createResponse()
                            ->withHeader("Location","/admin");
            }catch(\Exception $e){
                $message=$e->getMessage();
            }
        }else{
            Session::set(
                "csrftoken",
                hash("sha256",uniqid().mt_rand())
            );
            $this->csrfTokenSet();
        }
        return $this->twig("login.html",[
            "message"=>$message,
            "post"=>$post
        ]);   
    }
}
