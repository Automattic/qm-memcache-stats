<?php

/**
 * Class QM_Collector_Memcache
 */
class QM_Collector_Memcache_Stats extends QM_Collector {

	public $id = 'memcache-stats';

	/**
	 * @return string
	 */
	public function name() {
		return esc_html__( 'Memcache Stats', 'query-monitor' );
	}

	/**
	 * @return void
	 */
	public function process() {
	}

}