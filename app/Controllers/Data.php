<?php

class Data {

	public $table;
	public $typeList;

	function __construct() {
		global $wpdb;
		$this->table 	= $wpdb->prefix . 'posts';
		$typeListA 		= get_option( 'ais_theme_options_api' );
		$this->typeList = $typeListA['ais_theme_options_filter_data']; /* Get data from options */
	}

	public function getSearchData( $postType = false, $limit = false, $year = false, $month = false, $day = false ) {

		/* Checking */
		$errorCheck = $this->errorChecking( $postType, $limit, $year, $month, $day );
		if ( ! empty( $errorCheck ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] = 'error';
			echo json_encode( $errorCheck );
			return;
		}

		/* Sanitizing */
		if ( ! isset( $_GET['s'] ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['keyword'] 	= 'You have to set keyword, ex : ?s=try%20keyword';
			echo json_encode( $errorCheck );
			return;	
		}
		$search 	= sanitize_text_field( $_GET['s'] );

		/* Authentication */
		$authentication = $this->authentication();
		if ( ! empty( $authentication ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['access'] 	= "You don't have role to access this page";
			echo json_encode( $errorCheck );
			return;	
		}

		$keyword 	= '%' . $search . '%';

		$data 	= DB::table( $this->table )
					->where( 'post_type', 'like', $postType )
					->where( 'post_title', 'like', $keyword )
					->where( 'post_date', '>=', date( 'Y-m-d H:i:s', strtotime( $year . '-' . $month . '-' . $day ) ) )
					->limit( $limit )
					->orderBy( 'post_date', 'DESC' )
					->get();
		echo json_encode( $data );

	}

	public function getLatestData( $postType = false, $limit = false, $year = false, $month = false, $day = false ) {

		/* Checking */
		$errorCheck = $this->errorChecking( $postType, $limit, $year, $month, $day );
		if ( ! empty( $errorCheck ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] = 'error';
			echo json_encode( $errorCheck );
			return;
		}

		/* Authentication */
		$authentication = $this->authentication();
		if ( ! empty( $authentication ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['access'] 	= "You don't have role to access this page";
			echo json_encode( $errorCheck );
			return;	
		}

		$data 	= DB::table( $this->table )
					->where( 'post_type', 'like', $postType )
					->limit( $limit )
					->orderBy( 'post_date', 'DESC' )
					->get();

		echo json_encode( $data );

	}

	public function getSingleDataByParam( $postType = false, $paramType = false, $compare = false, $param = false ) {

		/* Checking */
		// $errorCheck = $this->errorChecking2( $postType, $paramType, $compare, $param );
		// if ( ! empty( $errorCheck ) ) {
		// 	header('Content-Type: application/json');
		// 	header('Status: 404 not found');
		// 	$errorCheck['status'] = 'error';
		// 	echo json_encode( $errorCheck );
		// 	return;
		// }

		/* Authentication */
		$authentication = $this->authentication();
		if ( ! empty( $authentication ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['access'] 	= "You don't have role to access this page";
			echo json_encode( $errorCheck );
			return;	
		}

		if ( $compare == 'equal' ) {
			$compare = '=';
		}

		$data 	= DB::table( $this->table )
					->where( 'post_type', 'like', 'passenger_guide' )
					->where( 'post_name', '=', 'check-in' )
					->get();

		echo json_encode( $data );

	}

	public function getDataCategory( $postType = false ) {

		/* Checking */
		$errorCheck = $this->errorChecking3( $postType );
		if ( ! empty( $errorCheck ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] = 'error';
			echo json_encode( $errorCheck );
			return;
		}

		/* Authentication */
		$authentication = $this->authentication();
		if ( ! empty( $authentication ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['access'] 	= "You don't have role to access this page";
			echo json_encode( $errorCheck );
			return;	
		}

		$data = get_object_taxonomies( $postType, 'objects' );

		echo json_encode( $data );

	}

	public function getDataByCategory( $postType = false, $limit = false, $categoryTaxonomy = false, $categoryType = false, $category = false ) {

		/* Checking */
		$errorCheck = $this->errorChecking4( $postType, $limit, $categoryTaxonomy, $categoryType, $category );
		if ( ! empty( $errorCheck ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] = 'error';
			echo json_encode( $errorCheck );
			return;
		}

		/* Authentication */
		$authentication = $this->authentication();
		if ( ! empty( $authentication ) ) {
			header('Content-Type: application/json');
			header('Status: 404 not found');
			$errorCheck['status'] 	= 'error';
			$errorCheck['access'] 	= "You don't have role to access this page";
			echo json_encode( $errorCheck );
			return;	
		}

		$data = get_posts(
		    array(
		        'posts_per_page' => $limit,
		        'post_type' => $postType,
		        'tax_query' => array(
		            array(
		                'taxonomy' 	=> $categoryTaxonomy,
		                'field' 	=> $categoryType,
		                'terms'	 	=> $category,
		            )
		        )
		    )
		);

		echo json_encode( $data );

	}

	private function errorChecking( $postType = false, $limit = false, $year = false, $month = false, $day = false ) {
		
		$errorCheck = null;

		if ( ! isset( $_GET['appID'] ) || ! isset( $_GET['appSecret'] ) || empty( $_GET['appID'] ) || empty( $_GET['appSecret'] ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'You have to set your app ID and Secret first, contact your administrator'
			);
		}

		if ( ! in_array( $postType, $this->typeList ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'Post type not found'
			);
		}
		if ( ! intval( $limit ) ) {
			$errorCheck['limit']	= array(
				'message' 	=> 'Limit must be number',
			);
		}
		if ( ! checkdate( $month, $day, $year ) ) {
			$errorCheck['date']	= array(
				'message' 	=> 'You have to use valid date, ex : 2015/08/01',
			);
		}
		
		return $errorCheck;
	
	}

	private function errorChecking2( $postType = false, $paramType = false, $compare = false, $param = false ) {
		
		$errorCheck = null;

		if ( ! isset( $_GET['appID'] ) || ! isset( $_GET['appSecret'] ) || empty( $_GET['appID'] ) || empty( $_GET['appSecret'] ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'You have to set your app ID and Secret first, contact your administrator'
			);
		}

		if ( ! in_array( $postType, $this->typeList ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'Post type not found'
			);
		}
		if ( empty( $paramType ) || empty( $param ) || empty( $compare ) ) {
			$errorCheck['param']	= array(
				'message' 	=> 'You have to specified your paramater field',
			);
		}
		
		return $errorCheck;
	
	}

	private function errorChecking3( $postType = false ) {
		
		$errorCheck = null;

		if ( ! isset( $_GET['appID'] ) || ! isset( $_GET['appSecret'] ) || empty( $_GET['appID'] ) || empty( $_GET['appSecret'] ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'You have to set your app ID and Secret first, contact your administrator'
			);
		}

		if ( ! in_array( $postType, $this->typeList ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'Post type not found'
			);
		}
		
		return $errorCheck;
	
	}

	private function errorChecking4( $postType = false, $limit = false, $categoryTaxonomy = false, $categoryType = false, $category = false ) {
		
		$errorCheck = null;

		if ( ! isset( $_GET['appID'] ) || ! isset( $_GET['appSecret'] ) || empty( $_GET['appID'] ) || empty( $_GET['appSecret'] ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'You have to set your app ID and Secret first, contact your administrator'
			);
		}

		if ( ! in_array( $postType, $this->typeList ) ) {
			$errorCheck['post_type'] 	= array(
				'message'	=> 'Post type not found'
			);
		}
		if ( ! intval( $limit ) ) {
			$errorCheck['limit']	= array(
				'message' 	=> 'Limit must be number',
			);
		}
		if ( empty ( $categoryTaxonomy ) || empty( $categoryType ) || empty( $category ) ) {
			$errorCheck['date']	= array(
				'message' 	=> 'You have to fix your category param',
			);
		}
		
		return $errorCheck;
	
	}

	private function authentication() {

		$args = array(
			'role'         => 'developer',
			'meta_key'     => 'ais_developer_app_id',
			'meta_value'   => $_GET['appID'],
		);

		$users = get_users( $args );
		if ( empty( $users ) ) {
			$errorCheck['access'] 	= "Wrong app ID";
			return $errorCheck;	
		}

		$appSecret = get_user_meta( $users[0]->data->ID, 'ais_developer_app_secret', true ); 
		if ( $appSecret != $_GET['appSecret'] ) {
			$errorCheck['access'] 	= "Wrong app secret";
			return $errorCheck;	
		}

	}

}