<?php

namespace Boke0\Mechanism\Mdlw;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

class AdminSessionMdlw implements MiddlewareInterface{
    public function __construct($responseFactory,$user){
        $this->user=$user;
        $this->responseFactory=$responseFactory;
    }
    public function process(ServerRequestInterface $req,RequestHandlerInterface $next): ResponseInterface{
        if(!$this->user){
            return $this->responseFactory->createResponse()
                ->withHeader("Location","/install");
        }
        $cookie=$req->getCookieParams();
        $jwt=$cookie["boke0ick-jwt"];
        if($jwt==NULL||!$this->user->session($jwt)){
            return $this->responseFactory->createResponse(403,"Forbidden")
                ->withHeader("Location","/admin/login");
        }
        return $next->handle($req);
    }
}
