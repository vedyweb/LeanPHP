<?php
namespace LeanPress\Core\Middleware;
use LeanPress\Core\Http\Request;
use LeanPress\Core\Http\Response;
use LeanPress\Model\AuthModel;

class JwtAuth {

    private $userAuthModel;

    public function __construct() {
        $this->userAuthModel = new AuthModel();
    }

    public function getAuthenticate(Request $request, Response $response): bool
    {

        /*
        * 1. adım olarak, login olurken hem token hem de expry date oluşturuluyır zaten.
        * YAPILACAK - NOT: Kullanıcı oluşturulunca token'ı da dönmek gerek şu an yok.
        * 2. Artık, herhangi bir middleware servis çağrılırken login servisinin döndüğü,
        * token'ı store on tabletteki gibi falan header ekleyip gelmek.
        */
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            throw new \Exception('Authorization header is missing');
        }

        // Bearer kısmını çıkarıyor: Bearer eyJhbGciOiJIUzI1....
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $valid_token = $this->userAuthModel->validateTokenAndExpiry($token);

        if (!is_array($valid_token) || !isset($valid_token['token']) || $token !== $valid_token['token']) {
            throw new \Exception('Invalid token');
        }
        return true;
    }

}