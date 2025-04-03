<?php

namespace MWStake\MediaWiki\Component\DataStore;

use stdClass;

class Filterer {

	/**
	 *
	 * @var Filter[]
	 */
	protected $filters = null;

	/**
	 *
	 * @param Filter $filters
	 */
	public function __construct( $filters ) {
		$this->filters = $filters;
	}

	/**
	 *
	 * @param \MWStake\MediaWiki\Component\DataStore\Record[] $data
	 * @return \MWStake\MediaWiki\Component\DataStore\Record[]
	 */
	public function filter( $data ) {
		$filteredData = array_filter( $data, function ( $aDataSet ) {
				return $this->matchFilter( $aDataSet );
		}
		);

		return array_values( $filteredData );
	}

	/**
	 *
	 * @param stdClass $dataSet
	 * @return bool
	 */
	protected function matchFilter( $dataSet ) {
		foreach ( $this->filters as $filter ) {
			// If just one of these filters does not apply, the dataset needs
			// to be removed
			if ( !$filter->matches( $dataSet ) ) {
				return false;
			}
		}

		return true;
	}
}
