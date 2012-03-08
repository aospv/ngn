<?php

abstract class CtrlMapper extends CtrlCommon {

	abstract public function getMappingObject();
	
	protected function getActionObject($action) {
      return true;
	}
	
	protected function action() {
	  $o = $this->getMappingObject();
      $method = $this->actionBase;
      if (method_exists($o, $method)) {
        $refl = new ReflectionMethod($o, $method);
        $p = array();
        foreach ($refl->getParameters() as $v)
          if (!isset($this->oReq->g[$v->name]))
            throw new NgnException('=(');
          else
            $p[] = $this->oReq->g[$v->name];
        if (($r = call_user_func_array(array($o, $method), $p)) !== null) {
          if ($this->actionPrefix == 'ajax') {
            $this->ajaxOutput = $r;
          } elseif ($this->actionPrefix == 'json') {
            $this->json = $r;
          }
        }
      } else {
        throw new NoMethodException($method);
      }
	}

}
