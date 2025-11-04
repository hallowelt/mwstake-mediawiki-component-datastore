<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Integration\Filter;

use MediaWikiIntegrationTestCase;
use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;

class TitleTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Title::matches
	 */
	public function testPositive() {
		$filter = new Filter\Title( [
			'field' => 'field2',
			'comparison' => 'eq',
			'value' => 'User:WikiSysop'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Template:Help',
			'field2' => 'User:WikiSysop'
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\Title::matches
	 */
	public function testNegative() {
		$filter = new Filter\Title( [
			'field' => 'field1',
			'comparison' => 'eq',
			'value' => 'Hilfe'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Vorlage:Hilfe',
			'field2' => 'User:WikiSysop'
		] ) );

		$this->assertFalse( $result );
	}
}
