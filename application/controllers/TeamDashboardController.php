<?php
/* Originally from Icinga Web 2 | (c) 2013 Icinga Development Team | GPLv2+ */
/* icingaweb2-module-teamdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Teamdashboards\Controllers;

use Icinga\Data\Filter\FilterMatch;
use Icinga\Module\Teamdashboards\MappingIniRepository;
use Icinga\Module\Teamdashboards\TeamDashboard;
use Icinga\User;
use Icinga\Web\Controller\ActionController;
use Icinga\Web\Url;
use Icinga\Web\Widget\Dashboard;


/**
 * Handle creation, removal and displaying of dashboards, panes and dashlets
 *
 * @see Icinga\Web\Widget\Dashboard for more information about dashboards
 */
class TeamDashboardController extends ActionController
{
    /**
     * @var Dashboard;
     */
    private $dashboard;

    public function init()
    {
        $this->dashboard = new TeamDashboard();
        $this->dashboard->setUser(new User(""));

    }

    /**
     * Display the dashboard with the pane set in the 'pane' request parameter
     *
     * If no pane is submitted or the submitted one doesn't exist, the default pane is
     * displayed (normally the first one)
     */
    public function indexAction()
    {
        $requestedUser = $this->getParam("user");

        $user = (new MappingIniRepository())->select()->addFilter(new FilterMatch('enabled','=',"1"))->addFilter(new FilterMatch('name','=',$requestedUser))->fetchRow();
        $permission = 'teamdashboards/mapping/'.$requestedUser;
        if($user != false && ( $this->hasPermission($permission) || $this->hasPermission("teamdashboards/mapping"))){
            $this->dashboard->setUser(new User($user->name));
        }


        $this->dashboard->load();

        $this->createTabs();
        if (! $this->dashboard->hasPanes()) {
            $this->view->title = 'TeamDashboard';
        } else {
            $panes = array_filter(
                $this->dashboard->getPanes(),
                function ($pane) {
                    return ! $pane->getDisabled();
                }
            );
            if (empty($panes)) {
                $this->view->title = 'TeamDashboard';
                $this->getTabs()->add('teamdashboard', array(
                    'active'    => true,
                    'title'     => $this->translate('TeamDashboard'),
                    'url'       => Url::fromRequest()
                ));
            } else {
                if ($this->_getParam('pane')) {
                    $pane = $this->_getParam('pane');
                    $this->dashboard->activate($pane);
                }
                if ($this->dashboard === null) {
                    $this->view->title = 'TeamDashboard';
                } else {
                    $this->view->title = $this->dashboard->getActivePane()->getTitle() . ' :: TeamDashboard';

                    $this->view->dashboard = $this->dashboard;
                }
            }
        }
    }



    /**
     * Create tab aggregation
     */
    private function createTabs()
    {
        $this->view->tabs = $this->dashboard->getTabs();
    }
}
