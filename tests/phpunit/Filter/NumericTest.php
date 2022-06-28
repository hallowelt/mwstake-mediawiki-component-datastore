<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Filter;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;

class NumericTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Numeric::matches
	 */
	public function testPositive() {
		$filter = new Filter\Numeric( [
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
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Numeric::matches
	 */
	public function testNegative() {
		$filter = new Filter\Numeric( [
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
