<?

class clsdb {

      // variables to connect to the mysql server
      private $host;
      private $user;
      private $password;
		private $dbname;

      // contains the pointer of the actual connection
      public $connectid;

      // contains result pointer returned by a query
      public $result;

      // contains the number of result-rows
      public $count;
		public $cols;

      // status of connection possible values: "connected" or "disconnected"
      public $db_status = "disconnected";

      // contains last error-message from mysql
      public $errormessage;

      // contains last error-number from mysql
      public $errornumber;

      // spaltentitel oder zahlen
		public $assoc = MYSQLI_ASSOC;

		/**
		 * private constructor - implement the singleton pattern
		 */
		public function __construct() {
			// variables to connect to the mysql server
			$this->host 		= $GLOBALS['pmkdbhost'];
			$this->user 		= $GLOBALS['pmkdbuser'];
			$this->password 	= $GLOBALS['pmkdbpassword'];
			$this->dbname 		= $GLOBALS['pmkdbdbname'];
			$this->open();
		}

        // function to open connection to database
        // returns 1 if connection is up or 0, if connection failed or if there is an open connection

        function open() {

                if($this->db_status != "connected") {
                        if(!($this->connectid = @mysqli_connect($this->host, $this->user, $this->password))) {
                                $this->db_status = "disconnected";
                                $this->errormessage = mysqli_error();
                                $this->errornumber = mysqli_errno();
                                return 0;
                        }
                        else {
                                $this->db_status = "connected";
								if (!$this->select()) echo "";
                                return 1;
                                }
                }
                else {
                        echo "error connecting to db";
                        return 0;
                }
        }

        // function to select database for future queries
        // returns 1, if database was selected correctly or 0, if selecting the database failed or if no connection is open
        function select() {
                if($this->db_status == "connected") {
                        if(!mysqli_select_db($this->connectid, $this->dbname)) {
                                $this->errormessage = mysqli_error();
                                $this->errornumber = mysqli_errno();
                                return 0;
                        }
                        else {
                                return 1;
                        }
                }
                else {
                        return 0;
                }
        }

        // function to send query to database
        // returns 1, if query was send correctly or 0, if something went wrong
        function query($query) {

                if($this->db_status == "connected") {
                        if(!$this->result = mysqli_query($this->connectid, $query)) {
                                $this->errormessage = mysqli_error();
                                $this->errornumber = mysqli_errno();
                                return 0;
                        }
                        else {
                                if(substr($query, 0, 4) == "SELE") { $this->count = mysqli_num_rows($this->result);
																	 $this->cols  = mysqli_num_fields($this->result);}
                                return 1;
                        }
                }
                else {
                        return 0;
                }
        }

        // function to get the next result of the last query
        // returns next result, if there is still a result to return or 0, if there is no more result to return
        function results() {

                if(!$this->result) {
                        $this->errormessage = "Result is empty.";
                        $this->errornumber = 99;
                        return 0;
                }
                else {
						//return mysql_error();
                        return mysqli_fetch_array($this->result, $this->assoc);
                }
        }

        // function to get last errormessage
        function db_com_get_last_error() {

                return ($this->errornumber . " : " . $this->errormessage);
        }


        // function to close connection to database
        // return 1, if connection has been closed correctly or 0, if no connection is open or if something went wrong while closing the connection
        function close() {

                if($this->db_status == "connected") {
                        if(!mysqli_close($this->connectid)) {
                                $this->errornumber = mysqli_errno();
                                $this->errormessage = mysqli_error();
                                return 0;
                        }
                        else {
                                $this->db_status = "disconnected";
                                return 1;
                        }
                }
                else {
                        return 0;
                }
        }

		function fieldname($field) {
                if(!$this->result) {
                        $this->errormessage = "Result is empty.";
                        $this->errornumber = 99;
                        return 0;
                }
                else {
                        return mysqli_field_name($this->result, $field);
                }
		}

		// Mit dieser Funktion kann man alle Spaltentitel in einem Array ausgeben lassen.
		// begonnen wird dabei per default bei 0, also mit der `id`-Spalte
		// möchte man die `id`-Spalte weglassen, dann kann man $start = 1 setzten
		function colTitles($start = 0) {
                if(!$this->result) {
                        $this->errormessage = "Result is empty.";
                        $this->errornumber = 99;
                        return 0;
                }
                else {
					for ( $i = $start; $i < $this->cols; $i++ ) {
						$names[] = mysqli_field_name( $this->result, $i );
					}
					return $names;
               }
		}

// end of class CLSdb
}
?>