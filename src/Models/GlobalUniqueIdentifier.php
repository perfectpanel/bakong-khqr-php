<?php

declare(strict_types=1);

namespace KHQR\Models;

use KHQR\Exceptions\KHQRException;
use KHQR\Helpers\EMV;

class GlobalUniqueIdentifier extends TagLengthString
{
    public ?string $merchantID = null;

    public ?string $acquiringBank = null;

    public ?string $accountInformation = null;

    public BakongAccountID $bakongAccountID;

    public object $data;

    public function __construct(string $tag, $valueObject)
    {
        if ($valueObject == null) {
            throw new KHQRException(KHQRException::MERCHANT_TYPE_REQUIRED);
        }

        // Get value from props object
        $bakongAccountID = $valueObject->bakongAccountID;
        $acquiringBank = $valueObject->acquiringBank;

        $isMerchant = $valueObject->isMerchant;
        $accountInformation = $valueObject->accountInformation;

        // Creating 3 instances
        // BakongAccountID: 00
        // MerchantID: 01
        // AcquiringBankName: 02
        $bakongAccountId = new BakongAccountID(
            EMV::BAKONG_ACCOUNT_IDENTIFIER,
            $bakongAccountID
        );

        $globalUniqueIdentifier = (string) $bakongAccountId;

        if ($isMerchant) {
            $merchantId = new MerchantId(
                EMV::MERCHANT_ACCOUNT_INFORMATION_MERCHANT_ID,
                $valueObject->merchantID
            );
            $acquiringBankName = new AcquiringBank(
                EMV::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
                $acquiringBank
            );

            if ($valueObject->merchantID !== null) {
                $globalUniqueIdentifier .= $merchantId;
            }
            if ($acquiringBank !== null) {
                $globalUniqueIdentifier .= $acquiringBankName;
            }

            parent::__construct($tag, $globalUniqueIdentifier);

            $this->merchantID = $merchantId;
            $this->acquiringBank = $acquiringBankName;
            $this->data = (object) [
                'bakongAccountID' => $bakongAccountId,
                'merchantID' => $merchantId,
                'acquiringBank' => $acquiringBankName,
            ];
        } else {
            if ($accountInformation !== null) {
                $accInformation = new AccountInformation(
                    EMV::INDIVIDUAL_ACCOUNT_INFORMATION,
                    $accountInformation
                );
                $globalUniqueIdentifier .= $accInformation;
            }

            if ($acquiringBank !== null) {
                $acquiringBankName = new AcquiringBank(
                    EMV::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
                    $acquiringBank
                );
                $globalUniqueIdentifier .= $acquiringBankName;
            }

            parent::__construct($tag, $globalUniqueIdentifier);

            $this->accountInformation = $accountInformation;
            $this->data = (object) [
                'bakongAccountID' => $bakongAccountId,
                'accountInformation' => $accountInformation,
            ];
        }
        $this->bakongAccountID = $bakongAccountId;
    }
}

class BakongAccountID extends TagLengthString
{
    public function __construct(string $tag, string $bakongAccountID)
    {
        // Throw validation if there is
        // 1. No tag
        // 2. empty value of bakong account
        if ($bakongAccountID === '' || $bakongAccountID === '0') {
            throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_REQUIRED);
        }

        // Validating the bakong account is it is correct
        // name@bank_domain
        $bakongAccountDivide = explode('@', $bakongAccountID);
        // Validate on length of the bakong account
        if (strlen($bakongAccountID) > EMV::INVALID_LENGTH_BAKONG_ACCOUNT) {
            throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_LENGTH_INVALID);
        }

        // Validate on length of the bakong account
        if (count($bakongAccountDivide) < 2) {
            throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_INVALID);
        }
        parent::__construct($tag, $bakongAccountID);
    }
}

class AccountInformation extends TagLengthString
{
    public function __construct(string $tag, string $value)
    {
        if (strlen($value) > EMV::INVALID_LENGTH_ACCOUNT_INFORMATION) {
            throw new KHQRException(KHQRException::ACCOUNT_INFORMATION_LENGTH_INVALID);
        }
        parent::__construct($tag, $value);
    }
}

class MerchantId extends TagLengthString
{
    public function __construct(string $tag, string $value)
    {
        if ($value === '' || $value === '0') {
            throw new KHQRException(KHQRException::MERCHANT_ID_REQUIRED);
        }
        if (strlen($value) > EMV::INVALID_LENGTH_MERCHANT_ID) {
            throw new KHQRException(KHQRException::MERCHANT_ID_LENGTH_INVALID);
        }
        parent::__construct($tag, $value);
    }
}

class AcquiringBank extends TagLengthString
{
    public function __construct(string $tag, string $value)
    {
        if ($value === '' || $value === '0') {
            throw new KHQRException(KHQRException::ACQUIRING_BANK_REQUIRED);
        }
        if (strlen($value) > EMV::INVALID_LENGTH_ACQUIRING_BANK) {
            throw new KHQRException(KHQRException::ACQUIRING_BANK_LENGTH_INVALID);
        }
        parent::__construct($tag, $value);
    }
}
