<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\Filterer;
use MWStake\MediaWiki\Component\DataStore\Record;
use PHPUnit\Framework\TestCase;

class FiltererTest extends TestCase {

	/** @var array */
	protected $testDataSets = [
		[
			'field1' => 1,
			'field2' => '20170101000000',
			'field3' => '1 item',
			'field4' => [ 1, 2, 3 ],
			'field5' => [
				[ 'a' => 1 ],
				[ 'a' => 2 ],
				[ 'a' => 3 ]
			]
		],
		[
			'field1' => 3,
			'field2' => '20170101000002',
			'field3' => '10 items',
			'field4' => [ 2, 3, 4 ],
			'field5' => [
				[ 'a' => 1 ],
				[ 'a' => 2 ],
				[ 'a' => 3 ]
			]
		],
		[
			'field1' => 4,
			'field2' => '20170101000001',
			'field3' => 'an item',
			'field4' => [ 4, 5, 6 ],
			'field5' => [
				[ 'a' => 1 ],
				[ 'a' => 2 ],
				[ 'a' => 3 ]
			]
		],
		[
			'field1' => 2,
			'field2' => '20170101000003',
			'field3' => 'An eloquent item',
			'field4' => [ 3, 1, 2 ],
			'field5' => [
				[ 'a' => 1 ],
				[ 'a' => 2 ],
				[ 'a' => 3 ]
			]
		]
	];

	/**
	 *
	 * @param array $filters
	 * @param array $expectedCount
	 * @dataProvider provideFilterData
	 * @covers \MWStake\MediaWiki\Component\DataStore\Filterer::filter
	 */
	public function testFilter( $filters, $expectedCount ) {
		$filterer = new Filterer( Filter::newCollectionFromArray( $filters ) );
		$dataSets = $this->makeDataSets();
		$filteredDataSets = $filterer->filter( $dataSets );

		$this->assertCount( $expectedCount, $filteredDataSets );
	}

	public function provideFilterData() {
		return [
			'numeric' => [
				[ [
					'type' => 'numeric',
					'field' => 'field1',
					'value' => 2,
					'comparison' => 'gt'
				] ],
				2
			],
			'list' => [
				[ [
					'type' => 'list',
					'field' => 'field4',
					'value' => [ 2 ],
					'comparison' => 'ct'
				] ],
				3
			],
			'string and datetime' => [
				[ [
					'type' => 'string',
					'field' => 'field3',
					'value' => 'item',
					'comparison' => 'ew'
				],
				[
					'type' => 'date',
					'field' => 'field2',
					'value' => '20170101000000',
					'comparison' => 'gt'
				] ],
				2
			],
			'string' => [
				[ [
					'type' => 'string',
					'field' => 'field3',
					'value' => 'an',
					'comparison' => 'ct'
				] ],
				2
			],
		];
	}

	protected function makeDataSets() {
		$dataSets = [];
		foreach ( $this->testDataSets as $dataSet ) {
			$dataSets[] = new Record( (object)$dataSet );
		}

		return $dataSets;
	}

}
