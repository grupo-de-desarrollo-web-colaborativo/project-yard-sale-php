<?php

/*> dateDMY($timestamp): Devuelve la fecha en formato Dia/Mes/Año de un timestamp
	- Parametros
		[requerido] $timestamp (int) tiempo en formato timestamp
	- Valores Devueltos
		Tipo: string
		La fecha en formato corto D/M/A
	- Ej:
		dateCalendar(1656448442) = 28/6/2022
*/
function dateDMY($timestamp)
{
	return date('j', $timestamp) . "/" . date('n', $timestamp) . "/" . date('Y', $timestamp);
}

/*> dateShort(): Devuelve la fecha corta de un timestamp en español
	- Parametros
		[requerido] $timestamp (int) tiempo en formato timestamp
	- Valores Devueltos    
		Tipo: string
		La fecha en formato corto
	- Ej:    
		dateCalendar(1656448442) = Jun/28/2022
*/
function dateShort(int $timestamp)
{
	$meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
	return $meses[date('n', $timestamp) - 1] . "/" . date('d', $timestamp) . "/" . date('Y', $timestamp);
}