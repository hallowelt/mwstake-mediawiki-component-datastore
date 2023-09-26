<?php

namespace MWStake\MediaWiki\Component\DataStore;

use MWStake\MediaWiki\Component\DataStore\Filter\NumericValue;
use MWStake\MediaWiki\Component\DataStore\Filter\StringValue;
use Wikimedia\Rdbms\IDatabase;

abstract class PrimaryDatabaseDataProvider implements IPrimaryDataProvider {

	/**
	 *
	 * @var Record[]
	 */
	protected $data = [];

	/**
	 *
	 * @var IDatabase
	 */
	protected $db = null;

	/**
	 *
	 * @var Schema
	 */
	protected $schema = null;

	/**
	 *
	 * @param IDatabase $db
	 * @param Schema $schema
	 */
	public function __construct( IDatabase $db, Schema $schema ) {
		$this->db = $db;
		$this->schema = $schema;
	}

	/**
	 * string[]
	 */
	abstract protected function getTableNames();

	/**
	 *
	 * @return string|string[]
	 */
	protected function getFields() {
		return '*';
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return array
	 */
	protected function getJoinConds( ReaderParams $params ) {
		return [];
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return Record[]
	 */
	public function makeData( $params ) {
		$this->data = [];

		$res = $this->db->select(
			$this->getTableNames(),
			$this->getFields(),
			$this->makePreFilterConds( $params ),
			__METHOD__,
			$this->makePreOptionConds( $params ),
			$this->getJoinConds( $params )
		);
		foreach ( $res as $row ) {
			$this->appendRowToData( $row );
		}

		return $this->data;
	}

	/**
	 *
	 * @return array
	 */
	protected function getDefaultConds() {
		return [];
	}

	/**
	 *
	 * @return array
	 */
	protected function getDefaultOptions() {
		return [];
	}

	/**
	 * @param Filter $filter
	 * @return bool
	 */
	protected function skipPreFilter( Filter $filter ) {
		return false;
	}

	/**
	 *
	 * @param array &$conds
	 * @param Filter $filter
	 */
	protected function appendPreFilterCond( &$conds, Filter $filter ) {
		$apply = true;
		switch ( $filter->getComparison() ) {
			case Filter::COMPARISON_EQUALS:
				$conds[$filter->getField()] = $filter->getValue();
				break;
			case Filter::COMPARISON_NOT_EQUALS:
				$conds[] = "{$filter->getValue()} != {$filter->getField()}";
				break;
			case StringValue::COMPARISON_CONTAINS:
				$conds[] = "{$filter->getField()} " . $this->db->buildLike(
					$this->db->anyString(),
					$filter->getValue(),
					$this->db->anyString()
				);
				break;
			case StringValue::COMPARISON_NOT_CONTAINS:
				$conds[] = "{$filter->getField()} NOT " . $this->db->buildLike(
					$this->db->anyString(),
					$filter->getValue(),
					$this->db->anyString()
				);
				break;
			case StringValue::COMPARISON_STARTS_WITH:
				$conds[] = "{$filter->getField()} " . $this->db->buildLike(
					$filter->getValue(),
					$this->db->anyString()
				);
				break;
			case StringValue::COMPARISON_ENDS_WITH:
				$conds[] = "{$filter->getField()} " . $this->db->buildLike(
					$this->db->anyString(),
					$filter->getValue()
				);
				break;
			case NumericValue::COMPARISON_GREATER_THAN:
				$conds[] = "{$filter->getField()} > {$filter->getValue()}";
				break;
			case NumericValue::COMPARISON_LOWER_THAN:
				$conds[] = "{$filter->getField()} < {$filter->getValue()}";
				break;
			default:
				$apply = false;
		}
		if ( $apply ) {
			$filter->setApplied();
		}
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return array
	 */
	protected function makePreFilterConds( ReaderParams $params ) {
		$conds = $this->getDefaultConds();
		$fields = array_values( $this->schema->getFilterableFields() );
		$filterFinder = new FilterFinder( $params->getFilter() );
		foreach ( $fields as $fieldName ) {
			$filters = $filterFinder->findAllFiltersByField( $fieldName );
			foreach ( $filters as $filter ) {
				if ( !$filter instanceof Filter ) {
					continue;
				}
				if ( $this->skipPreFilter( $filter ) ) {
					continue;
				}

				$this->appendPreFilterCond( $conds, $filter );
			}
		}
		return $conds;
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return array
	 */
	protected function makePreOptionConds( ReaderParams $params ) {
		$conds = $this->getDefaultOptions();

		$fields = array_values( $this->schema->getSortableFields() );

		foreach ( $params->getSort() as $sort ) {
			if ( !in_array( $sort->getProperty(), $fields ) ) {
				continue;
			}
			if ( !isset( $conds['ORDER BY'] ) ) {
				$conds['ORDER BY'] = "";
			} else {
				$conds['ORDER BY'] .= ",";
			}
			$conds['ORDER BY'] .= "{$sort->getProperty()} {$sort->getDirection()}";
		}
		return $conds;
	}

	/**
	 *
	 * @param \stdClass $row
	 */
	abstract protected function appendRowToData( \stdClass $row );
}
