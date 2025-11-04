<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Unit\Filter;

use MediaWikiUnitTestCase;
use MWStake\MediaWiki\Component\DataStore\Filter\Boolean;
use MWStake\MediaWiki\Component\DataStore\Record;

class BooleanTest extends MediaWikiUnitTestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Boolean::matches
	 */
	public function testPositive() {
		$filter = new Boolean( [
			'field' => 'field1',
			'comparison' => 'eq',
			'value' => true
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => true,
			'field2' => false
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers MWStake\MediaWiki\Component\DataStore\Filter\Boolean::matches
	 */
	public function testNegative() {
		$filter = new Boolean( [
			'field' => 'field1',
			'comparison' => 'eq',
			'value' => false
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => true,
			'field2' => false
		] ) );

		$this->assertFalse( $result );
	}

	/**
	 * @dataProvider provideMatchesValues
	 * @param bool $expectation
	 * @param string $comparison
	 * @param mixed $fieldValue
	 * @param mixed $filterValue
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Boolean::matches
	 */
	public function testMatches( $expectation, $comparison, $fieldValue, $filterValue ) {
		$filter = new Boolean( [
			Boolean::KEY_FIELD => 'field_A',
			Boolean::KEY_VALUE => $filterValue,
			Boolean::KEY_COMPARISON => $comparison
		] );

		$dataSet = new Record( (object)[
			'field_A' => $fieldValue
		] );

		if ( $expectation ) {
			$this->assertTrue( $filter->matches( $dataSet ), 'Filter should apply' );
		} else {
			$this->assertFalse( $filter->matches( $dataSet ), 'Filter should not apply' );
		}
	}

	public function provideMatchesValues() {
		return [
			[ true, Boolean::COMPARISON_EQUALS, true, true ],
			[ true, Boolean::COMPARISON_EQUALS, 1, true ],
			[ true, Boolean::COMPARISON_EQUALS, '1', true ],
			[ true, Boolean::COMPARISON_EQUALS, false, false ],
			[ true, Boolean::COMPARISON_EQUALS, 0, false ],
			[ true, Boolean::COMPARISON_EQUALS, '0', false ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, true, false ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, 1, false ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, '1', false ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, false, true ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, 0, true ],
			[ true, Boolean::COMPARISON_NOT_EQUALS, '0', true ],
			[ false, Boolean::COMPARISON_EQUALS, true, false ],
			[ false, Boolean::COMPARISON_EQUALS, 1, false ],
			[ false, Boolean::COMPARISON_EQUALS, '1', false ],
			[ false, Boolean::COMPARISON_EQUALS, false, true ],
			[ false, Boolean::COMPARISON_EQUALS, 0, true ],
			[ false, Boolean::COMPARISON_EQUALS, '0', true ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, true, true ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, 1, true ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, '1', true ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, false, false ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, 0, false ],
			[ false, Boolean::COMPARISON_NOT_EQUALS, '0', false ]
		];
	}
}
