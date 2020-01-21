<?php

namespace Boke0\Mechanism\Mdlw;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Boke0\Mechanism\Cookie;

class CsrfMdlw implements MiddlewareInterface{
    public function __construct($responseFactory){
        $this->responseFactory=$responseFactory;
    }
    public function process(ServerRequestInterface $req,RequestHandlerInterface $next): ResponseInterface{
        if($req->getServerParams("REQUEST_METHOD")=="POST" && $req->getCookieParams()["csrftoken"]!=$req->getParsedBody()["csrftoken"]){
            $res=$this->responseFactory->createResponse("403","Forbidden");
            $body=$res->getBody();
            $body->write("不正アクセスを検知しました");
            return $res
                ->withHeader("Content-Type","application/json")
                ->withBody($body);
        }else{
            $token=hash("sha256",uniqid().mt_rand());
            Cookie::set(
                "csrftoken",
                $token,
                3600*24
            );
            $req->withCookieParams($req->getCookieParams()+["csrftoken"=>$token]);
        }
        return $next->handle($req);
    }
}
