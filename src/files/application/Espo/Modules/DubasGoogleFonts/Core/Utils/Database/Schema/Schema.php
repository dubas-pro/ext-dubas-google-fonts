<?php

/*
 * This file is part of the Dubas Google Fonts - EspoCRM extension.
 *
 * DUBAS S.C. - contact@dubas.pro
 * Copyright (C) 2021 Arkadiy Asuratov, Emil Dubielecki
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

namespace Espo\Modules\DubasGoogleFonts\Core\Utils\Database\Schema;

use Espo\Core\Di;

class Schema extends \Espo\Core\Utils\Database\Schema\Schema implements
    Di\MetadataAware,
    Di\ConfigAware,
    Di\EntityManagerAware
{
    use Di\MetadataSetter;

    use Di\ConfigSetter;

    use Di\EntityManagerSetter;

    protected function initRebuildActions($currentSchema = null, $metadataSchema = null)
    {
        parent::initRebuildActions($currentSchema, $metadataSchema);

        $rebuildActionClasses = [
            'afterRebuild' => [
                new \Espo\Modules\DubasGoogleFonts\Core\Utils\Database\Schema\rebuildActions\ApplyFonts($this->metadata, $this->config, $this->entityManager),
            ],
        ];

        $this->rebuildActionClasses = array_merge_recursive($rebuildActionClasses, $this->rebuildActionClasses);
    }
}
