<?php
namespace App\Charts;

use Balping\JsonRaw\Replacer;

trait ContainsRawJson {
	/**
	 * Array containing JS raw objects (callbacks)
	 * Needed for json_encode options
	 */
	protected $rawObjects = [];

	/**
	 * Formats the chart options.
	 *
	 * @param bool $strict
	 *
	 * @param bool $noBraces
	 * @return string
	 */
	public function formatOptions(bool $strict = false, bool $noBraces = false){
		$encoded = parent::formatOptions($strict, $noBraces);

		return Replacer::replace($encoded, $this->rawObjects);
	}
}
