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

include '../site/bootstrap.php';

use Espo\Core\Application;
use Espo\Core\Authentication\LoginFactory;
use Espo\Core\InjectableFactory;
use Espo\Core\Utils\Crypt;
use Espo\Core\Utils\PasswordHash;
use Espo\ORM\EntityManager;

$app = new Application();
$app->setupSystemUser();

$entityManager = $app->getContainer()->getByClass(EntityManager::class);
$passwordHash = $app->getContainer()->getByClass(InjectableFactory::class)->create(PasswordHash::class);
$authLoginFactory = $app->getContainer()->getByClass(InjectableFactory::class)->create(LoginFactory::class);
$crypt = $app->getContainer()->getByClass(InjectableFactory::class)->create(Crypt::class);

if (!file_exists('../tests/fixtures/init.php')) {
    echo 'File with test data not found' . PHP_EOL;
    exit(0);
}

$data = include '../tests/fixtures/init.php';
$data = $data['entities'];

foreach ($data as $entityType => $collection) {
    foreach ($collection as $entityData) {
        $toAppend = $entityData['__APPEND__'] ?? null;
        $entityId = $entityData['id'] ?? null;

        if (!is_string($entityId)) {
            throw new RuntimeException('Entity ID must be a string');
        }

        if ($toAppend === true) {
            $entity = $entityManager->getRepository($entityType)->getById($entityId);
        } else {
            $entityManager->getQueryExecutor()->execute(
                $entityManager
                    ->getQueryBuilder()
                    ->delete()
                    ->from($entityType)
                    ->where([
                        'id' => $entityId,
                    ])
                    ->build(),
            );

            $entity = $entityManager->getRepository($entityType)->getNew();
        }

        $saveOptions = [];

        foreach ($entityData as $field => $value) {
            if ($field === '__SAVE_OPTIONS__') {
                $saveOptions = $value;

                continue;
            }

            if ('createdById' === $field) {
                $saveOptions[$field] = $value;

                continue;
            }

            if ('password' === $field) {
                $currentPassword = $entity->get($field) ?? null;

                if (
                    is_string($currentPassword) &&
                    $passwordHash->verify($value, $currentPassword)
                ) {
                    // Skip if the password is the same
                    continue;
                }

                $value = $passwordHash->hash($value);
            }

            if (in_array($entityType, ['EmailAccount', 'InboundEmail'], true)) {
                if ('password' === $field) {
                    $value = $crypt->encrypt($value);
                }

                if ('smtpPassword' === $field) {
                    $value = $crypt->encrypt($value);
                }
            }

            $entity->set($field, $value);
        }

        $entityManager->saveEntity($entity, $saveOptions);
    }
}
