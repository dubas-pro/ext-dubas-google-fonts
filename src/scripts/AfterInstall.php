<?php
/**
 * This file is part of the Dubas Google Fonts - EspoCRM extension.
 *
 * dubas s.c. - contact@dubas.pro
 * Copyright (C) 2021-2025 Arkadiy Asuratov, Emil Dubielecki
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

use Espo\Core\Container;
use Espo\Core\DataManager;
use Exception;

class AfterInstall
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
