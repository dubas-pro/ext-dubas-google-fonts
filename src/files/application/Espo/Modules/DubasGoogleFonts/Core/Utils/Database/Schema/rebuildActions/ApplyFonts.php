<?php

namespace Espo\Modules\DubasGoogleFonts\Core\Utils\Database\Schema\rebuildActions;

use TCPDF_FONTS;

class ApplyFonts extends \Espo\Core\Utils\Database\Schema\BaseRebuildActions
{
    public function afterRebuild()
    {
        $tcpdfFontsDir = 'vendor/tecnickcom/tcpdf/fonts/poppins.z';
        if (!file_exists($tcpdfFontsDir)) {
            $fonts = [
                'Oswald.ttf',
                'Raleway.ttf',
                'Lato-Bold.ttf',
                'Lato-Regular.ttf',
                'Lato-BoldItalic.ttf',
                'Lato-Italic.ttf',
            ];
            $fontsDir = 'application/Espo/Modules/DubasGoogleFonts/Resources/fonts';
            foreach ($fonts as $font) {
                $fontPath = $fontsDir . '/' . $font;
                $fontPath = realpath($fontPath);

                TCPDF_FONTS::addTTFfont($fontPath, 'TrueType', '', 32, realpath('vendor/tecnickcom/tcpdf/fonts') . '/', 3, 1, false, false);
            }
        }
    }
}