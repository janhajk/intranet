<?php
/*
 * requires class.db.php
 */
class Csv2Sql {

	private $file		= '';
	private $sqlTable 	= '';	
	private $filecontent = '';	// string with content of csv file
	private $csv = array();		// 2d array of csv file
	
	public function __construct($file) {
		$this->sqlTable = 'pmkUBS';
		if(!pmkDoesTableExistInDB($this->sqlTable)) $this->createSQLTable();
		$this->file = $file;	
		$this->convert();
		$this->store();
		
	}
	
	private function convert() {
		$tmpFile = array();
		$this->open();									// load the csv file
		$this->csv = $this->parse($this->filecontent);	// parse csv file
		$this->header = $this->csv[0];					// First row is header
		foreach($this->header as $key=>$content) {		// gets rid of '.' and ' '
			$this->header[$key] = trim(str_replace('.','',str_replace(' ','',$content)));
		}
		unset($this->csv[0]);							// delete header row from content
		// make array with keys from header
		foreach($this->csv as $key=>$content) {
			foreach($content as $k=>$c) {
				$c = str_replace("'",'',$this->dateformat($c));
				$this->csv[$key][$this->header[$k]] = $c;
				unset($this->csv[$key][$k]);			// delete old numerical	entry
			}
		}
	}

	private function store() {
		$db = $GLOBALS['db'];
		//$db->query('DELETE from pmkUBS');
		foreach($this->csv as $line) {
			if(!$this->doesEntryExist($line) && $this->isdate($line['Bewertungsdatum'])) {
				$db->query('INSERT INTO '.$this->sqlTable.' (id) VALUES (\'\')');
				$id = mysql_insert_id();
				foreach($line as $key=>$cell) {
					$sql = "UPDATE ".$this->sqlTable." SET $key = '$cell' WHERE id='".$id."'";
					$db->query($sql);
				}
			}
		}		
	}
	
	/*
	 * Checks if entry already exists in the db
	 * Checks all cols
	 */
	private function doesEntryExist($entry) {
		$db = $GLOBALS['db'];
		$sql = '';
		foreach($entry as $key=>$e) {
			$e = trim(str_replace('\'','',$e));	// get rid of whitespaces and "'"
			if(preg_match("/([0-9']*\.[0-9]+|[0-9]+\.[0-9]*)$/",$e)) {	// if its float
				$e = (float)$e;
				$sql .= " AND $key >= ".($e-0.1)." AND $key <= ".($e+0.1);
			}
			elseif($e == NULL) {}
			elseif($key == 'Datumvon') {}
			elseif($key == 'Datumbis') {}
			else {
				$vergleich = 'LIKE';
				$sql .= " AND $key $vergleich '$e'";
			}
		}
		$sql = substr($sql,4,strlen($sql));
		$sql = "SELECT * FROM ".$this->sqlTable." WHERE ".$sql;
		$db->query($sql);
		return $db->count;
	}
	
	/*
	 * if Date, Formats the Date into right sql-date format
	 * Input can be any value, also no dates;
	 */
	private function dateformat($value) {
		if(preg_match('/[0123][0-9]\.[01][0-9]\.20[0-9][0-9]/', $value)) $value = date_german2mysql($value);
		return $value;
	}
	
	private function isdate($value) {
		if(preg_match('/20[0-9][0-9]-[01][0-9]-[0123][0-9]/', $value)) return true;
	}
	
	private function createSQLTable() {
		$db = $GLOBALS['db'];
		$sql = 'CREATE TABLE `'.$this->sqlTable.'` ('
				. ' `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, '
				. ' `Bewertungsdatum` DATE NOT NULL, '
				. ' `Bankbeziehung` VARCHAR(255) NOT NULL, '
				. ' `Portfolio` VARCHAR(255) NOT NULL, '
				. ' `Kundenprodukt` VARCHAR(255) NOT NULL, '
				. ' `IBAN` VARCHAR(255) NOT NULL, '
				. ' `Whrg` VARCHAR(3) NOT NULL, '
				. ' `Datumvon` DATE NOT NULL, '
				. ' `Datumbis` DATE NOT NULL, '
				. ' `Beschreibung` VARCHAR(255) NOT NULL, '
				. ' `Abschlussdatum` DATE NOT NULL, '
				. ' `Buchungsdatum` DATE NOT NULL, '
				. ' `Valuta` VARCHAR(255) NOT NULL, '
				. ' `Beschreibung1` VARCHAR(255) NOT NULL, '
				. ' `Beschreibung2` VARCHAR(255) NOT NULL, '
				. ' `Beschreibung3` VARCHAR(255) NOT NULL, '
				. ' `Belastung` FLOAT NOT NULL, '
				. ' `Gutschrift` FLOAT NOT NULL, '
				. ' `Saldo` FLOAT NOT NULL'
				. ' )'
				. ' ENGINE = myisam;';
		$db->query($sql);
	}
	
	/*
	 * Reads the Content of the File into a String
	 */
	private function open() {
		$fp = fopen($this->file, 'r');
		$this->filecontent = fread($fp, filesize($this->file));
		fclose($fp);
	}
	
	
	/*
	 * Create a 2D array from a CSV string
     *
     * @param mixed $data 2D array
     * @param string $delimiter Field delimiter
     * @param string $enclosure Field enclosure
     * @param string $newline Line seperator
     * @return
     */
    private function parse($data, $delimiter = ';', $enclosure = '"', $newline = "\n"){
        $pos = $last_pos = -1;
        $end = strlen($data);
        $row = 0;
        $quote_open = false;
        $trim_quote = false;

        $return = array();

        // Create a continuous loop
        for ($i = -1;; ++$i){
            ++$pos;
            // Get the positions
            $comma_pos = strpos($data, $delimiter, $pos);
            $quote_pos = strpos($data, $enclosure, $pos);
            $newline_pos = strpos($data, $newline, $pos);

            // Which one comes first?
            $pos = min(($comma_pos === false) ? $end : $comma_pos, ($quote_pos === false) ? $end : $quote_pos, ($newline_pos === false) ? $end : $newline_pos);

            // Cache it
            $char = (isset($data[$pos])) ? $data[$pos] : null;
            $done = ($pos == $end);

            // It it a special character?
            if ($done || $char == $delimiter || $char == $newline){

                // Ignore it as we're still in a quote
                if ($quote_open && !$done){
                    continue;
                }

                $length = $pos - ++$last_pos;

                // Is the last thing a quote?
                if ($trim_quote){
                    // Well then get rid of it
                    --$length;
                }

                // Get all the contents of this column
                $return[$row][] = ($length > 0) ? str_replace($enclosure . $enclosure, $enclosure, substr($data, $last_pos, $length)) : '';

                // And we're done
                if ($done){
                    break;
                }

                // Save the last position
                $last_pos = $pos;

                // Next row?
                if ($char == $newline){
                    ++$row;
                }

                $trim_quote = false;
            }
            // Our quote?
            else if ($char == $enclosure){

                // Toggle it
                if ($quote_open == false){
                    // It's an opening quote
                    $quote_open = true;
                    $trim_quote = false;

                    // Trim this opening quote?
                    if ($last_pos + 1 == $pos){
                        ++$last_pos;
                    }

                }
                else {
                    // It's a closing quote
                    $quote_open = false;

                    // Trim the last quote?
                    $trim_quote = true;
                }

            }

        }

        return $return;
    }

}
?>