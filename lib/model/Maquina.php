<?php


/**
 * Skeleton subclass for representing a row from the 'maquina' table.
 *
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 12/13/10 23:16:12
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Maquina extends BaseMaquina {
	public function calcularNumeroHorasActivasDelDia($dia, $mes, $año) {
		return ($this->calcularNumeroMinutosActivosDelDia($dia, $mes, $año)/60);
	}
	public function calcularNumeroMinutosActivosDelDia($dia, $mes, $año) {
		$minutosActivos = 0;
		if($this->getMaqEliminado()==0) {
			$minutosActivos = 24*60;
		}
		else {
			//			$mesEntero = intval($mes);
			//			$mesEliminacion = intval($this->getMaqFechaActualizacion('m'));
			$dateTimeDelDia = new DateTime($año.'-'.$mes.'-'.$dia);
			$timestampDelDia = $dateTimeDelDia->getTimestamp();
			$dateTimeEliminacion = new DateTime($this->getMaqFechaActualizacion('Y-m-d'));
			$timestampEliminacion = $dateTimeEliminacion->getTimestamp();
			if($timestampDelDia<$timestampEliminacion) {
				$minutosActivos = 24*60;
			}
			else if($timestampDelDia==$timestampEliminacion) {
				$horaEliminacion = intval($this->getMaqFechaActualizacion('H'));
				$minutoEliminacion = intval($this->getMaqFechaActualizacion('i'));
				$segundoEliminacion = intval($this->getMaqFechaActualizacion('s'));
				$minutosActivos = RegistroUsoMaquinaPeer::calcularMinutos(1, $horaEliminacion, $minutoEliminacion, $segundoEliminacion);
			}
			else {
				$minutosActivos = 0;
			}
		}
		return $minutosActivos;
	}
	public function calcularNumeroHorasActivasDelAño($año) {
		$sumatoria = 0;

		for($i=1;$i<=12;$i++) {
			$sumatoria += $this->calcularNumeroMinutosActivosDelMes($i, $año);
		}

		return ($sumatoria/60);
	}
	public function calcularNumeroHorasActivasDelMes($mes, $año) {
		return ($this->calcularNumeroMinutosActivosDelMes($mes, $año)/60);
	}
	public function calcularNumeroMinutosActivosDelMes($mes, $año) {
		$minutosActivos = 0;
		if($this->getMaqEliminado()==0) {
			$minutosActivos = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes($mes, $año)*24*60;
		}
		else {
			$mesEntero = intval($mes);
			$mesEliminacion = intval($this->getMaqFechaActualizacion('m'));
			if($mesEntero<$mesEliminacion) {
				$minutosActivos = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes($mes, $año)*24*60;
			}
			else if($mesEntero==$mesEliminacion) {
				$diaEliminacion = intval($this->getMaqFechaActualizacion('d'));
				$horaEliminacion = intval($this->getMaqFechaActualizacion('H'));
				$minutoEliminacion = intval($this->getMaqFechaActualizacion('i'));
				$segundoEliminacion = intval($this->getMaqFechaActualizacion('s'));
				$minutosActivos = RegistroUsoMaquinaPeer::calcularMinutos($diaEliminacion, $horaEliminacion, $minutoEliminacion, $segundoEliminacion);
			}
			else {
				$minutosActivos = 0;
			}
		}
		return $minutosActivos;
	}
	public function getNombreCompleto() {
		return $this->getMaqNombre().'-'.$this->getMaqCodigoInventario();
	}
} // Maquina
