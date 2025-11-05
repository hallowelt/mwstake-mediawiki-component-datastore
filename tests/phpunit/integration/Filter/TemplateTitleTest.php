<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Integration\Filter;

use MediaWikiIntegrationTestCase;
use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Record;

class TemplateTitleTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\TemplateTitle::matches
	 */
	public function testPositive() {
		$filter = new Filter\TemplateTitle( [
			'field' => 'field1',
			'comparison' => 'eq',
			'value' => 'Help'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Template:Help',
			'field2' => 'User:WikiSysop'
		] ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filter\TemplateTitle::matches
	 */
	public function testNegative() {
		$filter = new Filter\TemplateTitle( [
			'field' => 'field1',
			'comparison' => 'eq',
			'value' => 'Help'
		] );

		$result = $filter->matches( new Record( (object)[
			'field1' => 'Vorlage:Hilfe',
			'field2' => 'User:WikiSysop'
		] ) );

		$this->assertFalse( $result );
	}
}
