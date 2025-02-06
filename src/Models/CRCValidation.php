<?php

class CRCValidation
{
	public bool $isValid;

	public function __construct(bool $valid)
	{
		$this->isValid = $valid;
	}
}
