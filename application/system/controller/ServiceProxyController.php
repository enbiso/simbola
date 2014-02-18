<?php

namespace application\system\controller;

/**
 * Description of ServiceProxy
 *
 * @author Faraj
 */
class ServiceProxyController extends \simbola\core\application\AppController {
    
    function actionCall() {
        if ($this->issetGet(array('module', 'service', 'action')) && $this->issetPost(array('redirect'))) {
            $redirectAddr = $this->post('redirect');            
            $output = $this->invoke($this->get('module'), $this->get('service'), $this->get('action'), $this->post('params'));
            \simbola\Simbola::app()->session->set('sproxy_' . $redirectAddr, $output);                                                                        
            $this->redirect($redirectAddr);
        } else {
            throw new \Exception("Invalid Service Requested.");
        }
    }
    
    public function actionDocs() {
        if($this->issetGet("docs")){
            $service = $this->get('docs');
            $service_class = \simbola\Simbola::app()->getServiceClassName($service);
            if(class_exists($service_class)){
                $data['service'] = $service;
                foreach (get_class_vars($service_class) as $prop_name => $value) {
                    if(\simbola\base\lib\SString::Contains($prop_name,'schema_')){
                        $name = str_replace("schema_", "", $prop_name);
                        $data['actions'][$name]['req']['auth'] = array('username','skey');
                        $data['actions'][$name]['req']['service'] = $service;
                        $data['actions'][$name]['req']['action'] = $name;
                        $data['actions'][$name]['req']['params'] = $value['req']['params'];
                        $data['actions'][$name]['res']   = $value['res'];
                        $data['actions'][$name]['err']   = $value['err'];
                    }
                }
                $this->json($data);
            }else{
                $this->json(array("error"=>"Service not found."));
            }
        }else{
            $this->json(array('USAGE' => 'enter URL as /service_doc[docs:<service_name>], ie, service_name \'member,etc...\''));
        }
    }
    
}

?>
