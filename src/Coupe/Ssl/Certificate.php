<?php

namespace Coupe\Ssl;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Certificate
{

    /**
     * @param array $dn
     * @param null  $passPhrase
     *
     * @return string
     */
    public function create(array $dn, $passPhrase = null)
    {
        $config = $this->getConfig();

        $key = openssl_pkey_new($config);
        $crt = openssl_csr_new($dn, $key, $config);
        $crt = openssl_csr_sign($crt, null, $key, 365, $config);

        $x509 = null;
        $pKey = null;
        openssl_x509_export($crt, $x509);
        openssl_pkey_export($key, $pKey, $passPhrase, $config);

        return $x509 . $pKey;
    }

    /**
     * @param string $address
     * @return string
     */
    public function createForAddress($address)
    {
        return $this->create([
            "countryName"            => "UK",
            "stateOrProvinceName"    => "London",
            "localityName"           => "London",
            "organizationName"       => "Coupe HTTP Server",
            "organizationalUnitName" => "Coupe Development Team",
            "commonName"             => $address,
            "emailAddress"           => "example@example.com"
        ]);
    }

    protected function getConfig()
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return [
                'config' => PHP_BINDIR . '/extras/ssl/openssl.cnf'
            ];
        } else {
            return null;
        }
    }

} 