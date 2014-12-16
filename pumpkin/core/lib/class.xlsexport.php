<?php
/***************************************************************************************
* Software: class.xlsexport                                                		   	   *
* Version:  0.1                                                                		   *			
* Date:     2008-12-05                                                         		   *
* Author:   Jan Schär 		                                                   		   *
* License:  Freeware                                                           		   *
*                                                                              		   *
* You may use and modify this software as you wish.                            		   *
***************************************************************************************/
if (!class_exists('Cxls')) {
	define('Cxls_VERSION', '0.1');

	class Cxls {
		//Private properties
		private $data; // die Daten für das Excel-Sheet
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
		 * "\t" -> für neue Zelle
		 * "\n" -> für neue Zeile
		 */
		public function output(){
			$data = '';
			$total = '';
			foreach($this->data as $key=>$row) {	// Zeile für Zeile
				$line = '';
				$k = 0;	// Zähler (wird zur Zeit nicht gebraucht)
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
					$line .= $value;	// Zelle zu Row hinzufügen
					$k++;
				}	// nächste Zelle
				$line .= "\t";	// Letzte Zelle schliessen	 
				$data .= trim($line)."\n";	// neue Zeile
			}	// nächste Zeile
			$data = str_replace("\r","",$data);
			$this->xlsHeader(strlen($data));
			print $this->makeHeaderRow().utf8_decode($data);
			exit;	// gibt die ExcelDatei aus, keine weiteren aktionen möglich
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