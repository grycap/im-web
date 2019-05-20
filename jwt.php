<?php

class JWT
{
	/**
	 * Decode a string with URL-safe Base64.
	 *
	 * @param string $input A Base64 encoded string
	 *
	 * @return string A decoded string
	 */
	public static function urlsafeB64Decode($input)
	{
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}
		return base64_decode(strtr($input, '-_', '+/'));
	}

	/**
	 * Helper method to create a JSON error.
	 *
	 * @param int $errno An error number from json_last_error()
	 *
	 * @return void
	 */
	private static function handleJsonError($errno)
	{
		$messages = array(
				JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
				JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
				JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
				JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
				JSON_ERROR_UTF8 => 'Malformed UTF-8 characters' //PHP >= 5.3.3
		);
		throw new Exception(
				isset($messages[$errno])
				? $messages[$errno]
				: 'Unknown JSON error: ' . $errno
				);
	}

	/**
	 * Decode a JSON string into a PHP object.
	 *
	 * @param string $input JSON string
	 *
	 * @return object Object representation of JSON string
	 *
	 * @throws DomainException Provided string was invalid JSON
	 */
	public static function jsonDecode($input)
	{
		if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
			/** In PHP >=5.4.0, json_decode() accepts an options parameter, that allows you
			 * to specify that large ints (like Steam Transaction IDs) should be treated as
			 * strings, rather than the PHP default behaviour of converting them to floats.
			 */
			$obj = json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
		} else {
			/** Not all servers will support that, however, so for older versions we must
			 * manually detect large ints in the JSON string and quote them (thus converting
			 *them to strings) before decoding, hence the preg_replace() call.
			 */
			$max_int_length = strlen((string) PHP_INT_MAX) - 1;
			$json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
			$obj = json_decode($json_without_bigints);
		}
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			static::handleJsonError($errno);
		} elseif ($obj === null && $input !== 'null') {
			throw new Exception('Null result with non-null input');
		}
		return $obj;
	}

    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string        $jwt            The JWT
     *
     * @return object The JWT's payload as a PHP object
     *
     * @throws Exception in case of error
     *
     * @uses jsonDecode
     * @uses urlsafeB64Decode
     */
    public static function decode($jwt)
    {
        $timestamp = time();
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
        	throw new Exception('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = static::jsonDecode(static::urlsafeB64Decode($headb64)))) {
        	throw new Exception('Invalid header encoding');
        }
        if (null === $payload = static::jsonDecode(static::urlsafeB64Decode($bodyb64))) {
        	throw new Exception('Invalid claims encoding');
        }
        if (false === ($sig = static::urlsafeB64Decode($cryptob64))) {
        	throw new Exception('Invalid signature encoding');
        }

        return $payload;
    }
}
?>