<?php

namespace MWStake\MediaWiki\Component\DataStore;

interface IWriter {

	/**
	 * Create or Update given records
	 * @param RecordSet $recordSet
	 * @return RecordSet
	 */
	public function write( $recordSet );

	/**
	 * Delete given records
	 * @param RecordSet $recordSet
	 * @return RecordSet
	 */
	public function remove( $recordSet );

	/**
	 * @return Schema Column definition compatible to
	 * https://docs.sencha.com/extjs/4.2.1/#!/api/Ext.grid.Panel-cfg-columns
	 */
	public function getSchema();
}
