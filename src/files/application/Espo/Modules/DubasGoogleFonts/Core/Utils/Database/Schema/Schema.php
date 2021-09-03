<?php

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