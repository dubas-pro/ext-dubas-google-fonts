<?php

use Espo\Core\Container;
use Espo\Core\DataManager;
use Exception;

class AfterUninstall
{
    private Container $container;

    public function run(Container $container): void
    {
        $this->container = $container;
    }

    protected function clearCache(): void
    {
        try {
            /** @var DataManager $dataManager */
            $dataManager = $this->container->get('dataManager');
            $dataManager->clearCache();
        } catch (Exception $e) {
        }
    }
}
