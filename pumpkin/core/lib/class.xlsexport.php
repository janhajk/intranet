<?php
/***************************************************************************************
* Software: class.xlsexport                                                		   	   *
* Version:  0.1                                                                		   *			
* Date:     2008-12-05                                                         		   *
* Author:   Jan Sch�r 		                                                   		   *
* License:  Freeware                                                           		   *
*                                                                              		   *
* You may use and modify this software as you wish.                            		   *
***************************************************************************************/
if (!class_exists('Cxls')) {
	define('Cxls_VERSION', '0.1');

	class Cxls {
		//Private properties
		private $data; // die Daten f�r das Excel-Sheet
		private $filename = 'export';
		private $headerRows = array();

		/*******************************************************************************
		*                                                                              *
		*                               Public methods                                 *
		*                                                                              *
		*******************************************************************************/
		public function __construct($filename, $arrParam) {
			$this->filename = $filename;
			$this->data = $arrParam;
		}
		public function setHeaderRow($arrParam) {
				$this->headerRows = $arrParam;
		}
		
		/*
		 * Gibt das XLS aus
		 * "\t" -> f�r neue Zelle
		 * "\n" -> f�r neue Zeile
		 */
		public function output(){
			$data = '';
			$total = '';
			foreach($this->data as $key=>$row) {	// Zeile f�r Zeile
				$line = '';
				$k = 0;	// Z�hler (wird zur Zeit nicht gebraucht)
				foreach($this->headerRows as $key=>$col) {
					$value = $row[$key];
					$arr_value = '';
					if ((!isset($value)) OR ($value == "")) {$value = "\t";}
					// Als Zelle formatieren
					elseif((isset($value)) && ($value != "")) {
						$value = $value;	// wegen den Umlauten
						$value = str_replace('"', '""', $value);
						$value = '"'.$value.'"'."\t";
					}
					$line .= $value;	// Zelle zu Row hinzuf�gen
					$k++;
				}	// n�chste Zelle
				$line .= "\t";	// Letzte Zelle schliessen	 
				$data .= trim($line)."\n";	// neue Zeile
			}	// n�chste Zeile
			$data = str_replace("\r","",$data);
			$this->xlsHeader(strlen($data));
			print $this->makeHeaderRow().utf8_decode($data);
			exit;	// gibt die ExcelDatei aus, keine weiteren aktionen m�glich
		}
		
		/*
		 * Schreibt den XLS Header der Datei
		 */
		private function xlsHeader($filelength) {
			header("Content-type: application/octet-stream; charset=utf-8");
			header("Content-Disposition: attachment;filename=".$this->filename.".xls");
			header("Pragma: no-cache");
			header("Content-Length: $filelength");
			header("Expires: 0");
		}
		
		/*
		 * Erstellt die Kopfzeile der Tabelle
		 */
		private function makeHeaderRow() {
			$line = '';
			foreach($this->headerRows as $key=>$value) {                                                      
				if ((!isset($value)) OR ($value == "")) {$value = "\t";} 
				else {
					$value = ($value);	// wegen den Umlauten
					$value = str_replace('"', '""', $value);
					$value = '"' . $value . '"' . "\t";
				}
				$line .= $value;
			}
			return(trim($line)."\n");
		}

	} //End of Class
} // End if Class not exists