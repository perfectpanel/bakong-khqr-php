# bakong-khqr-sdk

This is a complete re-implementation of the `bakong-khqr` npm module (https://www.npmjs.com/package/bakong-khqr).

## Troubleshooting

### PHP curl does not work correctly on Windows.

It may be due to the fact that your PHP configuration does not include a valid certificate file. This can be confirmed by disabling the SSL verification:

```php
// ignore the SSL certificate
curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER,false);
```

or by checking with `phpinfo()`:

```shell
curl.cainfo => no value => no value
```

If that's true, the certificate file can be downloaded from https://curl.se/ca/cacert.pem, and include in `php.ini` file:

```properties
curl.cainfo = "C:\Users\force\cacert.pem"
```

After that, restart your services or your terminal and retest.