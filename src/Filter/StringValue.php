<?php

namespace MWStake\MediaWiki\Component\DataStore\Filter;

use MWStake\MediaWiki\Component\DataStore\Filter;

/**
 * Class name "String" is reserved
 */
class StringValue extends Filter {
	public const COMPARISON_STARTS_WITH = 'sw';
	public const COMPARISON_ENDS_WITH = 'ew';
	public const COMPARISON_NOT_CONTAINS = 'nct';

	/**
	 * Performs string filtering based on given filter of type string on a
	 * dataset
	 * @param \MWStake\MediaWiki\Component\DataStore\Record $dataSet
	 * @return bool
	 */
	protected function doesMatch( $dataSet ) {
		$fieldValues = $dataSet->get( $this->getField() );
		if ( !is_array( $fieldValues ) ) {
			$fieldValues = [ $fieldValues ];
		}
		foreach ( $fieldValues as $fieldValue ) {
			if ( !is_scalar( $fieldValue ) ) {
				continue;
			}
			$res = $this->compareStrings(
				$this->getComparison(),
				(string)$fieldValue,
				$this->getValue()
			);
			if ( $res ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Case-insensitive optional string comparison function
	 *
	 * @param string $sOp - comparison option
	 * @param string $sHaystack
	 * @param string $sNeedle
	 * @return boolean
	 */
	private function compareStrings( string $sOp, string $sHaystack, string $sNeedle): bool {
		$sHaystack = mb_strtolower( $sHaystack );
		$sNeedle = mb_strtolower( $sNeedle );

		switch ( $sOp ) {
			case self::COMPARISON_STARTS_WITH:
				return str_starts_with( $sHaystack, $sNeedle );
			case self::COMPARISON_ENDS_WITH:
				return str_ends_with( $sHaystack, $sNeedle );
			case self::COMPARISON_CONTAINS:
			case self::COMPARISON_LIKE:
				return str_contains( $sHaystack, $sNeedle );
			case self::COMPARISON_NOT_CONTAINS:
				return !str_contains( $sHaystack, $sNeedle );
			case self::COMPARISON_EQUALS:
				return $sHaystack === $sNeedle;
			case self::COMPARISON_NOT_EQUALS:
				return $sHaystack !== $sNeedle;
			default:
				return false;	
		}
	}
}
