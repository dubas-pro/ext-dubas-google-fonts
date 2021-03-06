<?php

/*
 * This file is part of the Dubas Google Fonts - EspoCRM extension.
 *
 * DUBAS S.C. - contact@dubas.pro
 * Copyright (C) 2022 Arkadiy Asuratov, Emil Dubielecki
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

namespace Espo\Modules\DubasGoogleFonts\Core\Utils\Database\Schema\rebuildActions;

use Espo\Core\Application;
use Espo\Core\Utils\Database\Schema\BaseRebuildActions;
use Espo\Modules\DubasGoogleFonts\Tools\Pdf\Tcpdf\ApplyFonts as ApplyFontsTool;

class ApplyFonts extends BaseRebuildActions
{
    public function afterRebuild(): void
    {
        $app = new Application();
        $app->setupSystemUser();

        (new ApplyFontsTool($app->getContainer()))
            ->rebuild();
    }
}
