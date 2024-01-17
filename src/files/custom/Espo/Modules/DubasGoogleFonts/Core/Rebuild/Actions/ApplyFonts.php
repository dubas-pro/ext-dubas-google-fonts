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

namespace Espo\Modules\DubasGoogleFonts\Core\Rebuild\Actions;

use Espo\Core\Rebuild\RebuildAction;
use Espo\Core\Utils\Metadata;
use Espo\Core\Utils\Resource\PathProvider;
use TCPDF_FONTS;

class ApplyFonts implements RebuildAction
{
    private string $dirPath = 'fonts';

    private string $tcpdfFontsDir = 'vendor/tecnickcom/tcpdf/fonts';

    /**
     * @var string[]
     */
    private array $fontStyleList = [
        'regular', 'bold', 'italic', 'bolditalic',
    ];

    /**
     * @var array<string,string>
     */
    private array $fontStylesMap = [
        'regular' => '',
        'bold' => 'b',
        'italic' => 'i',
        'bolditalic' => 'bi',
    ];

    public function __construct(
        private Metadata $metadata,
        private PathProvider $pathProvider
    ) {
    }

    public function process(): void
    {
        $fontPathList = $this->getFontPathList();

        $fontFaceList = $this->metadata
            ->get(['app', 'pdfEngines', 'Tcpdf', 'fontFaceList']);

        foreach ($fontFaceList as $fontFace) {
            foreach ($this->fontStyleList as $fontStyle) {
                $fontName = $fontFace . '-' . $fontStyle;
                $fontPath = $fontPathList[$fontName] ?? null;

                if (!$fontPath) {
                    continue;
                }

                $targetFontPath = $this->tcpdfFontsDir . '/' . $fontFace . $this->fontStylesMap[$fontStyle] . '.php';
                if (file_exists($targetFontPath)) {
                    continue;
                }

                TCPDF_FONTS::addTTFfont(
                    realpath($fontPath),
                    'TrueType',
                    '',
                    32,
                    realpath($this->tcpdfFontsDir) . '/',
                    3,
                    1,
                    false,
                    false
                );
            }
        }
    }

    /**
     * @return array<string,string>
     */
    private function getFontPathList(): array
    {
        $dirList = [];
        foreach ($this->metadata->getModuleList() as $moduleName) {
            $dirList[] = $this->pathProvider->getModule($moduleName) . $this->dirPath;
        }
        $dirList[] = $this->pathProvider->getCustom() . $this->dirPath;

        $fileList = [];
        foreach ($dirList as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $fontList = scandir($dir) ?: [];
            foreach ($fontList as $file) {
                if (substr($file, -4) === '.ttf') {
                    $name = mb_strtolower(substr($file, 0, -4));

                    $fileList[$name] = $dir . '/' . $file;
                }
            }
        }

        return $fileList;
    }
}
