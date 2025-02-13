<?php

declare(strict_types=1);

class Constants
{
	const BAKONG_API_BASE_URL = "https://api-bakong.nbc.gov.kh"; // For production
	const BAKONG_API_SIT_BASE_URL = "https://sit-api-bakong.nbc.gov.kh"; // For testing

	const BAKONG_API_TOKEN_RENEW_URL = self::BAKONG_API_BASE_URL . "/v1/renew_token";

	const BAKONG_API_DEEPLINK_URL = self::BAKONG_API_BASE_URL . "/v1/generate_deeplink_by_qr";

	const BAKONG_API_CHECK_TRANSACTION_MD5_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_md5";
	const BAKONG_API_CHECK_TRANSACTION_MD5_LIST_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_md5_list";
	const BAKONG_API_CHECK_TRANSACTION_FULL_HASH_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_hash";
	const BAKONG_API_CHECK_TRANSACTION_FULL_HASH_LIST_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_hash_list";
	const BAKONG_API_CHECK_TRANSACTION_SHORT_HASH_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_short_hash";

	const BAKONG_API_CHECK_TRANSACTION_INSTRUCTION_REF_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_instruction_ref";
	const BAKONG_API_CHECK_TRANSACTION_EXTERNAL_REF_URL = self::BAKONG_API_BASE_URL . "/v1/check_transaction_by_external_ref";
}
