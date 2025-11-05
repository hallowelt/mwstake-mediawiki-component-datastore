<?php

namespace MWStake\MediaWiki\Component\DataStore\Tests\Unit;

use MediaWikiUnitTestCase;
use MWStake\MediaWiki\Component\DataStore\LimitOffsetTrimmer;

class LimitOffsetTrimmerTest extends MediaWikiUnitTestCase {

	/** @var array */
	protected $testDataSets = [
		// Page 1
		'Zero',
		'One',
		'Two',
		'Three',
		'Four',

		// Page 2
		'Five',
		'Six',
		'Seven',
		'Eight',
		'Nine',

		// Page 3
		'Ten',
		'Eleven',
		'Twelve',
		'Thirteen'
	];

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\LimitOffsetTrimmer::trim
	 */
	public function testNormalPage() {
		$trimmer = new LimitOffsetTrimmer( 5, 5 );
		$trimmedData = $trimmer->trim( $this->testDataSets );

		$this->assertCount( 5, $trimmedData );
		$this->assertEquals( 'Five', $trimmedData[0] );
	}

	/**
	 * @covers \MWStake\MediaWiki\Component\DataStore\LimitOffsetTrimmer::trim
	 */
	public function testLastPage() {
		$trimmer = new LimitOffsetTrimmer( 5, 10 );
		$trimmedData = $trimmer->trim( $this->testDataSets );

		$this->assertCount( 4, $trimmedData );
		$this->assertEquals( 'Ten', $trimmedData[0] );
	}
}
