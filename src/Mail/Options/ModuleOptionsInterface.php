<?php
namespace Mail\Options;

interface ModuleOptionsInterface
{
    /**
     * Get template paths
     *
     * @return array
     */
    public function getTemplatePaths();

    /**
     * Set template paths
     *
     * @param  array                                $templatePaths
     * @return \Mail\Options\ModuleOptionsInterface
    */
    public function setTemplatePaths(array $templatePaths);
}
