<?php

declare(strict_types=1);

namespace oat\geogebra\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Exception\IrreversibleMigration;
use oat\geogebra\scripts\install\RegisterPciGeogebraIMS;
use oat\qtiItemPci\model\PciModel;
use oat\tao\scripts\tools\migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202305230813533559_geogebra extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Fix loading issue with the Geogebra PCI';
    }

    public function up(Schema $schema): void
    {
        $registry = (new PciModel())->getRegistry();

        // Caution, we remove all existing versions of the PCI, this is necessary only because the previous versions are broken
        if ($registry->has('GGBPCI')) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $registry->removeAllVersions('GGBPCI');
        }

        $this->addReport(
            $this->propagate(
                new RegisterPciGeogebraIMS()
            )(
                ['1.0.2']
            )
        );

    }

    public function down(Schema $schema): void
    {
        throw new IrreversibleMigration(
            'In order to undo this migration, please revert the client-side changes and run ' . RegisterPciGeogebraIMS::class
        );

    }
}
