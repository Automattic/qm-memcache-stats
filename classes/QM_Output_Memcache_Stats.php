<?php

/**
 * Class QM_Output_Memcache
 */
class QM_Output_Memcache_Stats extends QM_Output_Html {

	/**
	 * @param QM_Collector $collector
	 */
	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );

		add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 101 );
		add_filter( 'qm/output/title', array( $this, 'admin_title' ), 101 );
		add_filter( 'qm/output/menu_class', array( $this, 'admin_class' ) );
	}

	/**
	 * Echoes the Query Manager compatible output
	 * @return void
	 */
	public function output() {
		global $wp_object_cache;
		echo '<div class="qm" id="' . esc_attr( $this->collector->id() ) . '">';
		echo '<table cellspacing="0">';
		echo '<thead>';
		echo '<tr>';
		foreach ( $wp_object_cache->stats as $stat => $n ) {
			echo '<th scope="col">' . sprintf( esc_html__( 'Memcache %s', 'query-monitor' ), $stat ) . '</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		echo '<tr>';
		foreach ( $wp_object_cache->stats as $stat => $n ) {
			echo '<td>' . intval( $n ) . '</td>';
		}
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		foreach ( $wp_object_cache->group_ops as $group => $ops ) {
			echo '<table>';
			echo '<thead>';
			echo '<tr>';
			echo '<th>' . sprintf( esc_html__( 'Memcache %s commands', 'query-monitor' ), $group ) . '</th>';
			echo '<th>' . esc_html__( 'Called', 'query-monitor' ) . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			$ops_count = array_count_values( $ops );
			$ops       = array_unique( $ops );
			foreach ( $ops as $op ) {
				echo '<tr>';
				echo '<td>';
				echo wp_kses_post( $wp_object_cache->colorize_debug_line( $op ) );
				echo '</td>';
				echo '<td>' . intval( $ops_count[ $op ] ) . 'x</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
		echo '</div>';
	}

	/**
	 * Adds QM Memcache stats to admin panel
	 *
	 * @param array $title Array of QM admin panel titles
	 *
	 * @return array
	 */
	public function admin_title( array $title ) {
		global $wp_object_cache;
		$title[] = sprintf(
			esc_html__( 'Cache %d/%d', 'query-monitor' ),
			intval( $wp_object_cache->stats['get'] ),
			intval( $wp_object_cache->stats['add'] )
		);

		return $title;
	}

	/**
	 * Add Memcache class
	 *
	 * @param array $classes Array of QM classes
	 *
	 * @return array
	 */
	public function admin_class( array $class ) {
		$class[] = 'qm-memcache-stats';

		return $class;
	}

	/**
	 * Adds Memcache stats item to Query Monitor Menu
	 *
	 * @param array $menu Array of QM admin menu items
	 *
	 * @return array
	 */
	public function admin_menu( array $menu ) {
		$menu[] = $this->menu( array(
			'id'    => 'qm-memcache-stats',
			'href'  => '#qm-memcache-stats',
			'title' => esc_html__( 'Memcache stats', 'query-monitor' )
		) );

		return $menu;
	}

}