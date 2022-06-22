<?php

namespace MWStake\MediaWiki\Component\DataStore;

abstract class DatabaseReader extends Reader {

	/**
	 *
	 * @var \Wikimedia\Rdbms\IDatabase
	 */
	protected $db = null;

	/**
	 *
	 * @param \Wikimedia\Rdbms\LoadBalancer $loadBalancer
	 * @param \IContextSource|null $context
	 * @param \Config|null $config
	 */
	public function __construct(
		$loadBalancer, \IContextSource $context = null, \Config $config = null
	) {
		parent::__construct( $context, $config );
		$this->db = $loadBalancer->getConnection( DB_REPLICA );
	}
}
