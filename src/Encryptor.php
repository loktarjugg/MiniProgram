<?php
namespace LoktarJugg\MiniProgram;
use LoktarJugg\MiniProgram\AES;
use LoktarJugg\MiniProgram\Exceptions\DecryptException;

// Based on code by overtrue 
// see https://github.com/overtrue/wechat/blob/master/src/MiniProgram/Encryptor.php
class Encryptor {
    
    /**
     * Block size.
     *
     * @var int
     */
    protected $blockSize = 32;
    /**
     * Decrypt data.
     *
     * @param string $sessionKey
     * @param string $iv
     * @param string $encrypted
     *
     * @return array
     */
    public function decryptData(string $sessionKey, string $iv, string $encrypted): array
    {
        $decrypted = AES::decrypt(
            base64_decode($encrypted, false), base64_decode($sessionKey, false), base64_decode($iv, false)
        );

        $decrypted = json_decode($this->pkcs7Unpad($decrypted), true);

        if (!$decrypted) {
            throw new DecryptException('The given payload is invalid.');
        }

        return $decrypted;
    }

    /**
     * PKCS#7 unpad.
     *
     * @param string $text
     *
     * @return string
     */
    public function pkcs7Unpad(string $text): string
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($text, 0, (strlen($text) - $pad));
    }
}