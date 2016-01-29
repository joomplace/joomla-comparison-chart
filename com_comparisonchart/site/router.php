<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die;

function ComparisonChartBuildRoute( &$query ) {
	$segments = array();

	if ( isset( $query[ 'view' ] ) ) {
		$segments[ ] = $query[ 'view' ];
		unset( $query[ 'view' ] );
	}
	if ( isset( $query[ 'id' ] ) ) {
		$segments[ ] = $query[ 'id' ];
		unset( $query[ 'id' ] );
	}
        if ( isset( $query[ 'show' ] ) ) {
		$segments[ ] = $query[ 'show' ];
		unset( $query[ 'show' ] );
	}
    if ( isset( $query[ 'catid' ] ) ) {
    		$segments[ ] = $query[ 'catid' ];
    		unset( $query[ 'catid' ] );
    	}

	return $segments;
}

function ComparisonChartParseRoute( $segments ) {
	$vars = array( );
	$count = count( $segments );

	if ( $count ) {
		$count--;
		$segment = array_shift( $segments );

		if ( is_numeric( $segment ) ) {
			$vars[ 'id' ] = $segment;
		}
		$vars[ 'view' ] = $segment;
	}
	if ( $count ) {
		$count--;
		$segment = array_shift( $segments );

		if ( is_numeric( $segment ) ) {
			$vars[ 'id' ] = $segment;
		}
	}
    if ( $count ) {
        $count--;
    	$segment = array_shift( $segments );
        $vars[ 'catid' ] = $segment;
    	}

	return $vars;
}
