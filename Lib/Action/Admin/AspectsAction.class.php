<?php
import('ORG.Util.Input');
class AspectsAction extends Action
{
    public function index()
    {
        $table = M('aspects');
        import("ORG.Util.Page");
        $count = $table->count();
        $p = new Page($count, 10);
        $page = $p->show();
        $sql = "select aspects.id, aspects.name aspectsname, category.name from aspects
                inner join category on aspects.cat_id = category.id
                limit $p->firstRow, $p->listRows";
        $aspects = $table->query($sql);
        $this->assign('aspects', $aspects);
        $this->assign('page', $page);
        $this->display();
    }

    public function delete()
    {
        $aspects = M('aspects');
        $id = Input::getVar($_GET['id']);
        $aspects->where("id = $id")->delete();
        $this->redirect('aspects/index');
    }
}