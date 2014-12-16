<?php
	/*
	Formatiert eine W�hrungszahl und f�gt ein Plus voran, wenn positiv und gew�nscht
	*/
	function pmkCurrency($number, $precidingPlus=false) {
		// Variable f�r Ausgabe
		$n = '';
		
		// Formatierung mit Tausenderstriche und Rundung auf Null Kommastellen
		$n = number_format($number,0,",","`").'.-';
		
		// Vorangehendes "+", wenn positiver Betrag und $precidingPlus = true
		if($precidingPlus){
			$n = ($number > 0) ? '+'.$n : $n;
		}
		
		// R�ckgabe
		return $n;
	}
	
	
	/*
		Schneidet ein String nach $max Zeichen ab und f�gt "..." an
	*/
	function pmkCutString($string, $max=55) {
		// Variable f�r Ausgabe
		$n = '';
		
		// Wenn String l�nger als $max, dann wird er abgeschnitten und "..." angeh�ngt
		$n = (strlen($string)>$max) ? substr($string,0,$max-3).'...':$string;
		
		// R�ckgabe
		return $n;
	}
?>