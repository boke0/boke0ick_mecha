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
                $head=base64_encode(
                    json_encode(
                        [
                            "alg"=>"HS256",
                            "typ"=>"JWT"
                        ]
                    )
                );
                $body=base64_encode(
                    json_encode(
                        [
                            "userId"=>$user["id"],
                            "iat"=>time()
                        ]
                    )
                );
                $sig=hash("sha256",$head.".".$body.Cfg::get("jwt_secret"));
                Cookie::set("boke0ick-jwt","{$head}.{$body}.$sig");
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
}
