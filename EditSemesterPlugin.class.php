<?php

class EditSemesterPlugin extends StudIPPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();
        $this->navigation = "/admin/locations/semester";
        if ($GLOBALS['perm']->have_perm("root") && Navigation::hasItem($this->navigation)) {
            NotificationCenter::addObserver($this, "manipulateSidebar", "SidebarWillRender");
        }
    }

    public function manipulateSidebar()
    {
        if (Navigation::getItem($this->navigation)->isActive()) {
            $actions = Sidebar::Get()->getWidget("actions");
            $actions->addLink(
                _("Semesterzeiten ändern"),
                PluginEngine::getURL($this, [], "semester/select"),
                Icon::create('settings', 'clickable'),
                ['data-dialog' => 1, 'data-confirm' => _("Semesterzeiten ändern sollten Sie nur in Ausnahmesituatione tun. Wollen Sie fortfahren?")]
            );
        }
    }
}