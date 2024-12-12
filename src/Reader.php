<?php

namespace MWStake\MediaWiki\Component\DataStore;

use MediaWiki\MediaWikiServices;

abstract class Reader implements IReader {

	/**
	 *
	 * @var \IContextSource
	 */
	protected $context = null;

	/**
	 *
	 * @var \Config
	 */
	protected $config = null;

	/**
	 *
	 * @param \IContextSource|null $context
	 * @param \Config|null $config
	 */
	public function __construct( \IContextSource $context = null, \Config $config = null ) {
		$this->context = $context;
		if ( $this->context === null ) {
			$this->context = \RequestContext::getMain();
		}

		$this->config = $config;
		if ( $this->config === null ) {
			$this->config = MediaWikiServices::getInstance()->getMainConfig();
		}
	}

	/**
	 *
	 * @return \User
	 */
	protected function getUser() {
		return $this->context->getUser();
	}

	/**
	 *
	 * @return \Title
	 */
	protected function getTitle() {
		return $this->context->getTitle();
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return ResultSet
	 */
	public function read( $params ) {
		$primaryDataProvider = $this->makePrimaryDataProvider( $params );
		$dataSets = $primaryDataProvider->makeData( $params );

		$filterer = $this->makeFilterer( $params );
		$dataSets = $filterer->filter( $dataSets );
		$total = count( $dataSets );

		$sorter = $this->makeSorter( $params );
		$dataSets = $sorter->sort(
			$dataSets,
			$this->getSchema()->getUnsortableFields()
		);

		$trimmer = $this->makeTrimmer( $params );
		$dataSets = $trimmer->trim( $dataSets );

		$secondaryDataProvider = $this->makeSecondaryDataProvider();
		if ( $secondaryDataProvider instanceof ISecondaryDataProvider ) {
			$dataSets = $secondaryDataProvider->extend( $dataSets );
		}

		if ( $primaryDataProvider instanceof IBucketProvider ) {
			return new BucketedResultSet( $dataSets, $total, $primaryDataProvider->getBuckets() );
		}
		return new ResultSet( $dataSets, $total );
	}

	/**
	 * @param ReaderParams $params
	 * @return IPrimaryDataProvider
	 */
	abstract protected function makePrimaryDataProvider( $params );

	/**
	 * @param ReaderParams $params
	 * @return Filterer
	 */
	protected function makeFilterer( $params ) {
		return new Filterer( $params->getFilter() );
	}

	/**
	 * @param ReaderParams $params
	 * @return Sorter
	 */
	protected function makeSorter( $params ) {
		return new Sorter( $params->getSort() );
	}

	/**
	 * @param ReaderParams $params
	 * @return ITrimmer
	 */
	protected function makeTrimmer( $params ) {
		return new LimitOffsetTrimmer(
				$params->getLimit(),
				$params->getStart()
		);
	}

	/**
	 * @return ISecondaryDataProvider|null to skip
	 */
	abstract protected function makeSecondaryDataProvider();
}
