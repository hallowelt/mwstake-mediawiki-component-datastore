<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Unit;

use MediaWikiUnitTestCase;
use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Filter\StringValue;
use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use MWStake\MediaWiki\Component\DataStore\Sort;

class ReaderParamsTest extends MediaWikiUnitTestCase {
	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\ReaderParams::__construct
	 */
	public function testInitFromArray() {
		$params = new ReaderParams( [
			'query' => 'Some query',
			'limit' => 50,
			'start' => 100,
			'sort' => [
				[ 'property' => 'prop_a', 'direction' => 'asc' ],
				[ 'property' => 'prop_b', 'direction' => 'desc' ]
			],
			'filter' => [
				[ 'type' => 'string',
					'comparison' => 'ct',
					'value' => 'test',
					'field' => 'prop_a'
				],
				[
					'type' => 'numeric',
					'comparison' => 'gt',
					'value' => 99,
					'field' => 'prop_b'
				]
			]
		] );

		$this->assertInstanceOf( ReaderParams::class, $params );

		// TODO: Split test
		$this->assertEquals( 'Some query', $params->getQuery() );
		$this->assertEquals( 100, $params->getStart() );
		$this->assertEquals( 50, $params->getLimit() );

		$sort = $params->getSort();
		$this->assertCount( 2, $sort );
		$firstSort = $sort[0];
		$this->assertInstanceOf(
			Sort::class, $firstSort
		);

		$this->assertEquals(
			Sort::ASCENDING,
			$firstSort->getDirection()
		);

		$filter = $params->getFilter();
		$this->assertCount( 2, $filter );

		$firstFilter = $filter[0];
		$this->assertInstanceOf(
			Filter::class, $firstFilter
		);

		$this->assertEquals(
			StringValue::COMPARISON_CONTAINS,
			$firstFilter->getComparison()
		);

		$filedNames = [];
		foreach ( $filter as $filterObject ) {
			$filedNames[] = $filterObject->getField();
		}

		$this->assertContains( 'prop_a', $filedNames );
	}
}
