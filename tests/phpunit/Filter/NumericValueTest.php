<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Filter;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;
use PHPUnit\Framework\TestCase;

class NumericValueTest extends TestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\NumericValue::matches
	 */
	public function testPositive() {
		$filter = new Filter\NumericValue( [
			'field' => 'field1',
			'comparison' => 'gt',
			'value' => 5
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 7,
			'field2' => 3
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\NumericValue::matches
	 */
	public function testNegative() {
		$filter = new Filter\NumericValue( [
			'field' => 'field1',
			'comparison' => 'gt',
			'value' => 5
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 3,
			'field2' => 7
		] ) );

		$this->assertFalse( $result );
	}
}
