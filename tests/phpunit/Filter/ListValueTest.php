<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Filter;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;

class ListValueTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\ListValue::matches
	 */
	public function testPositive() {
		$filter = new Filter\ListValue( [
			'field' => 'field1',
			'comparison' => 'ct',
			'value' => [ 'Hello' ]
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => [ 'Hello', 'World' ],
			'field2' => false
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\ListValue::matches
	 */
	public function testNegative() {
		$filter = new Filter\ListValue( [
			'field' => 'field1',
			'comparison' => 'ct',
			'value' => [ 'Hello' ]
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => [ 'Hallo', 'Welt' ],
			'field2' => false
		] ) );

		$this->assertFalse( $result );
	}
}
