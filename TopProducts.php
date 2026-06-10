<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace TopProducts;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\Finder\Finder;
use Thelia\Core\Install\Database;
use Thelia\Module\BaseModule;
use TopProducts\Model\TopProductQuery;

class TopProducts extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'topproducts';

    const UPDATE_PATH = __DIR__ . DS . 'Config' . DS . 'update';

    public static function configureServices(ServicesConfigurator $services): void
    {
        $services->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([
                __DIR__.'/I18n/*',
                __DIR__.'/Config/**/*.php',
                __DIR__.'/Tests/*',
                __DIR__.'/TopProducts.php',
                __DIR__.'/Model/Base/*',
                __DIR__.'/Model/Map/*',
                __DIR__.'/Smarty/*',
            ])
            ->autowire(true)
            ->autoconfigure(true);
    }

    public function postActivation(ConnectionInterface $con = null): void
    {
        $database = new Database($con);

        try {
            TopProductQuery::create()->findOne();
        } catch (\Exception $e) {
            $database->insertSql(null, [__DIR__ . '/Config/thelia.sql']);
        }
    }

    public function update($currentVersion, $newVersion, ConnectionInterface $con = null): void
    {
        $finder = (new Finder())->files()->name('#.*?\.sql#')->sortByName()->in(self::UPDATE_PATH);

        if ($finder->count() === 0) {
            return;
        }

        $database = new Database($con);

        /** @var \Symfony\Component\Finder\SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(null, [$updateSQLFile->getPathname()]);
            }
        }
    }
}
