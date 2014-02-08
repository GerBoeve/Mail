<?php
namespace Mail\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Turn off strict options mode
     *
     * @var bool
     */
    protected $__strictMode__ = false;
    
    /**
     * @var array
     */
    protected $templatePaths = [];
    
    /**
     * Get template paths
     * 
     * @return array
     */
    public function getTemplatePaths()
    {
        return $this->templatePaths;
    }
    
    /**
     * Set template paths
     * 
     * @param array $templatePaths
     * @return \Mail\Options\ModuleOptionsInterface
     */
    public function setTempatePaths(array $templatePaths = [])
    {
        $this->templatePaths = $templatePaths;
        return $this;
    }
}