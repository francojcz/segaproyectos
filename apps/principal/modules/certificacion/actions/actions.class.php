<?php

/**
 * certificacion actions.
 *
 * @package    tpmlabs
 * @subpackage certificacion
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class certificacionActions extends sfActions
{
	public function executeEliminarCertificado() {
		$computador = ComputadorPeer::retrieveByPK($this->getRequestParameter('certificado'));
		if($computador) {
			$computador->delete();
		}

		return $this->renderText('Ok');
	}
	public function executeGenerarCertificado(sfWebRequest $request) {
		$nombreComputador = $request->getParameter('nombre_computador');

		$alphabet = "1234567890abcdefghijklmnopqrstuvwxyz";
		$key = "";
		$length = 40;
		for($i=0;$i<$length;$i++) {
			$key .= $alphabet{rand(0,strlen($alphabet)-1)};
		}

		$computador = new Computador();
		$computador->setComCertificado($key);
		$computador->setComNombre($nombreComputador);
		$computador->save();

		return $this->renderText($key);
	}
	public function executeIndex(sfWebRequest $request)
	{
	}
}
