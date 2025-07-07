<?php

namespace App\Services;

use phpseclib3\Crypt\EC\Curves\secp256k1;
use phpseclib3\Math\BigInteger;

class BitcoinService
{
    private function hash160(string $data): string
    {
        return hash('ripemd160', hash('sha256', $data, true), true);
    }

    private function base58check_encode(string $prefix, string $payload): string
    {
        $full = $prefix . $payload;
        $checksum = substr(hash('sha256', hash('sha256', $full, true), true), 0, 4);
        $binary = $full . $checksum;

        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $num = gmp_init(bin2hex($binary), 16);
        $encoded = '';
        while (gmp_cmp($num, 0) > 0) {
            list($num, $rem) = gmp_div_qr($num, 58);
            $encoded = $alphabet[gmp_intval($rem)] . $encoded;
        }

        foreach (str_split($binary) as $char) {
            if ($char === "\x00") {
                $encoded = '1' . $encoded;
            } else {
                break;
            }
        }

        return $encoded;
    }

    public function createWallet(): array
    {
        $curve = new secp256k1();
        $privateKey = $curve->createKey();
        $d = $privateKey->getD();
        $privateKeyHex = str_pad($d->toHex(), 64, '0', STR_PAD_LEFT);

        $publicKey = $privateKey->getPublicKey();
        $point = $publicKey->getCoordinates();
        $x = str_pad($point['x']->toHex(), 64, '0', STR_PAD_LEFT);
        $y = $point['y'];
        $prefix = $y->mod(new BigInteger(2))->toString() === '0' ? '02' : '03';
        $publicKeyCompressed = hex2bin($prefix . $x);

        $hash160 = $this->hash160($publicKeyCompressed);
        $address = $this->base58check_encode("\x00", $hash160);

        return [
            'private_key_hex' => $privateKeyHex,
            'public_key_compressed_hex' => strtoupper(bin2hex($publicKeyCompressed)),
            'address' => $address,
        ];
    }
}
