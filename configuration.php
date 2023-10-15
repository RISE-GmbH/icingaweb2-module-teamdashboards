<?php

/** @var \Icinga\Application\Modules\Module $this */

use Icinga\Application\Icinga;
use Icinga\Authentication\Auth;
use Icinga\Data\Filter\FilterMatch;
use Icinga\Module\Teamdashboards\MappingIniRepository;

$section = $this->menuSection(N_('Teamdashboards'), [
    'url' => 'navigation/dashboard?name=teamdashboards',
    'icon' => 'dashboard',
    'priority' => 15
]);

$this->providePermission('teamdashboards/mapping', $this->translate('allow access to mapping'));

$section->add(N_('Mapping'))
    ->setUrl('teamdashboards/mapping')
    ->setPermission('teamdashboards/mapping')
    ->setPriority(30);

$this->provideConfigTab('config/mapping', array(
    'title' => $this->translate('Configuration'),
    'label' => $this->translate('Configuration'),
    'url' => 'mapping'
));


$auth = Auth::getInstance();
if ($auth->isAuthenticated() && !Icinga::app()->isCli()) {

    $mappings = (new MappingIniRepository())->select()->fetchAll();

    foreach ($mappings as $mapping) {

        $permission = 'teamdashboards/mapping/' . $mapping->name;
        $this->providePermission($permission, $this->translate('allow access to mapping') . " " . $mapping->name);

        if($mapping->enabled ==1){
            if($auth->hasPermission($permission)){
                $displayname = $mapping->name;
                if(isset($mapping->alias) && !empty($mapping->alias)){
                    $displayname = $mapping->alias;
                }

                $section->add($displayname, array(
                    'url' => 'teamdashboards/team-dashboard',
                    'urlParameters' => array('user' => $mapping->name),
                    'priority' => $mapping->priority,
                ));
            }
        }


    }
}



?>
