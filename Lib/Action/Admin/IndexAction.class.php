<?php
import('ORG.Util.Input');
class IndexAction extends Action
{
    public function _initialize()
    {
        if(!Session::is_set('manager')) {
            $this->redirect('Auth/login');
        }
    }

    public function index()
    {
        $this->display();
    }
} // end of class