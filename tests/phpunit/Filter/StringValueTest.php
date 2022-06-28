<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Filter;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;

class StringValueTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\StringValue::matches
	 */
	public function testPositive() {
		$filter = new Filter\StringValue( [
			'field' => 'field1',
			'comparison' => 'ct',
			'value' => 'ello'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Hello World',
			'field2' => 'Hallo Welt'
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\StringValue::matches
	 */
	public function testNegative() {
		$filter = new Filter\StringValue( [
			'field' => 'field1',
			'comparison' => 'ct',
			'value' => 'allo'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Hello World',
			'field2' => 'Hallo Welt'
		] ) );

		$this->assertFalse( $result );
	}
}
