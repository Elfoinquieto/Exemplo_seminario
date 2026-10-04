<?php
 
namespace App\Services;
 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
 
class Operations
{
    public static function decryptId($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return redirect('/');
        }
    }

     public static function encryptId($value)
    {
        return Crypt::encrypt($value);
    }
}
