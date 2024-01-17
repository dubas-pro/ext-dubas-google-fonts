<?php
/**
 * This file is part of the Dubas Google Fonts - EspoCRM extension.
 *
 * dubas s.c. - contact@dubas.pro
 * Copyright (C) 2021-2024 Arkadiy Asuratov, Emil Dubielecki
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use Exception;
use Espo\Core\Container;
use Espo\Core\DataManager;
use Espo\Core\Utils\Config;

class BeforeInstall
{
    private Container $container;

    public function run(Container $container): void
    {
        $this->container = $container;

        /** @var Config $config */
        $config = $container->get('config');

        $pdfEngine = $config->get('pdfEngine');

        if ($pdfEngine !== 'Tcpdf') {
            throw new Exception('This extension requires TCPDF extension to be installed and enabled. https://github.com/yurikuzn/ext-tcpdf/releases/latest');
        }
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
