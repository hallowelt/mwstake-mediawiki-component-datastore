<?php

namespace MWStake\MediaWiki\Component\DataStore;

use UnexpectedValueException;

class Sort {

	public const KEY_PROPERTY = 'property';
	public const KEY_DIRECTION = 'direction';

	public const ASCENDING = 'ASC';
	public const DESCENDING = 'DESC';

	/** @var string */
	protected $property = '';

	/** @var string */
	protected $direction = '';

	/**
	 *
	 * @param string $property
	 * @param string $direction
	 * @throws UnexpectedValueException
	 */
	public function __construct( $property, $direction = self::ASCENDING ) {
		$this->property = $property;
		$this->direction = strtoupper( $direction );

		if ( !in_array( $this->direction, [ self::ASCENDING, self::DESCENDING ] ) ) {
			throw new UnexpectedValueException(
				"'{$this->direction}' is not an allowed value for argument \$direction"
			);
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getProperty() {
		return $this->property;
	}

	/**
	 *
	 * @return string One of Sort::ASCENDING or Sort::DESCENDING
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getProperty() . ' ' . $this->getDirection();
	}

	/**
	 *
	 * @param stdClass[]|array[] $sorts
	 * @return Sort[]
	 */
	public static function newCollectionFromArray( $sorts ) {
		$sortObjects = [];
		foreach ( $sorts as $sort ) {
			if ( is_array( $sort ) ) {
				$sort = (object)$sort;
			}

			$sortObjects[] = new Sort(
				$sort->{static::KEY_PROPERTY},
				isset( $sort->{static::KEY_DIRECTION} ) ? $sort->{static::KEY_DIRECTION} : null
			);
		}
		return $sortObjects;
	}
}
