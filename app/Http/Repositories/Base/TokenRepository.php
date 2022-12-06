<?php
namespace App\Http\Repositories\Base;

class TokenRepository {
    protected $key;
    protected $algo;

    public function __construct($key, $algo = 'sha256') {
        $this->key = $key;
        $this->algo = $algo;
    }

    public function create($plaintext){
        return hash_hmac($this->algo, $plaintext, $this->key, false);
    }

    public function verify($plaintext, $expected){
        $hashed = hash_hmac($this->algo, $plaintext, $this->key, false);
        return hash_equals($expected, $hashed);
    }
}