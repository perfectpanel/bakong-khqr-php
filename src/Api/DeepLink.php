<?php

class DeepLink
{
	public static function isValidLink(string $link): bool
	{
		try {
			$url = parse_url($link);

			if ($url === false || !isset($url['path']) || $url['path'] != "/v1/generate_deeplink_by_qr") {
				return false;
			}
		} catch (Exception $error) {
			return false;
		}
		return true;
	}

	public static function callDeepLinkAPI(string $url, $data)
	{
		try {
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 45);

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curlError = curl_error($ch);

			curl_close($ch);

			if ($curlError) {
				throw new KHQRException(KHQRException::CONNECTION_TIMEOUT);
			}

			$respBody = json_decode($response, true);

			if ($httpCode !== 200 || $respBody === null) {
				throw new KHQRException(KHQRException::CONNECTION_TIMEOUT);
			}

			$error = $respBody['errorCode'] ?? null;

			if ($error == 5) {
				throw new KHQRException(KHQRException::INVALID_DEEP_LINK_SOURCE_INFO);
			} else if ($error == 4) {
				throw new KHQRException(KHQRException::INTERNAL_SERVER_ERROR);
			}

			return $respBody;
		} catch (Exception $error) {
			throw new KHQRException(KHQRException::CONNECTION_TIMEOUT);
		}
	}
}
