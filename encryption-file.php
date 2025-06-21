<?php
class SecureEncryptor {
    private $key;
    private $cipher = 'AES-256-CBC';

    public function __construct($key) {
        // Derive a 256-bit key using SHA-256
        $this->key = hash('sha256', $key, true);
    }

    public function encrypt($plaintext) {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        // Return encrypted string with IV prepended (base64-encoded)
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($ciphertextBase64) {
        $ciphertext = base64_decode($ciphertextBase64);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        
        $iv = substr($ciphertext, 0, $ivLength);
        $encrypted = substr($ciphertext, $ivLength);

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}

$secretKey = "MyStrongPassword123!";
$encryptor = new SecureEncryptor($secretKey);

$originalText = "This is a secret message.";
$encrypted = $encryptor->encrypt($originalText);
$decrypted = $encryptor->decrypt($encrypted);

echo "Original: " . $originalText . "\n";
echo "Encrypted: " . $encrypted . "\n";
echo "Decrypted: " . $decrypted . "\n";


>?

