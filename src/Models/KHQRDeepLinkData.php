<?php

class KHQRDeepLinkData
{
	private string $shortLink;

	public function __construct($shortLink)
	{
		$this->shortLink = $shortLink;
	}

	public function getData()
	{
		return [
			"shortLink" => $this->shortLink
		];
	}
}
