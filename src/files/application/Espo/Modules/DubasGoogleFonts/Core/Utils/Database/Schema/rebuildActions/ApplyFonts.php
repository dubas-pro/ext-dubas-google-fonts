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
                'Lato-Bold.ttf',
                'Lato-Regular.ttf',
                'Lato-BoldItalic.ttf',
                'Lato-Italic.ttf',
                'Glory-Bold.ttf',
                'Glory-Regular.ttf',
                'Glory-BoldItalic.ttf',
                'Glory-Italic.ttf',
                'Montserrat-Bold.ttf',
                'Montserrat-Regular.ttf',
                'Montserrat-BoldItalic.ttf',
                'Montserrat-Italic.ttf',
                'Nunito-Bold.ttf',
                'Nunito-Regular.ttf',
                'Nunito-BoldItalic.ttf',
                'Nunito-Italic.ttf',
                'OpenSans-Bold.ttf',
                'OpenSans-Regular.ttf',
                'OpenSans-BoldItalic.ttf',
                'OpenSans-Italic.ttf',
                'Oswald-Bold.ttf',
                'Oswald-Regular.ttf',
                'Poppins-Bold.ttf',
                'Poppins-Regular.ttf',
                'Poppins-BoldItalic.ttf',
                'Poppins-Italic.ttf',
                'Quicksand-Bold.ttf',
                'Quicksand-Regular.ttf',
                'Raleway-Bold.ttf',
                'Raleway-Regular.ttf',
                'Raleway-BoldItalic.ttf',
                'Raleway-Italic.ttf',
                'Roboto-Bold.ttf',
                'Roboto-Regular.ttf',
                'Roboto-BoldItalic.ttf',
                'Roboto-Italic.ttf',
                'SourceSansPro-Bold.ttf',
                'SourceSansPro-Regular.ttf',
                'SourceSansPro-BoldItalic.ttf',
                'SourceSansPro-Italic.ttf',
                'Ubuntu-Bold.ttf',
                'Ubuntu-Regular.ttf',
                'Ubuntu-BoldItalic.ttf',
                'Ubuntu-Italic.ttf',
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