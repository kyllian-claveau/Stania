<?php

namespace App\Controller;

use App\Business\DatabaseBusiness;
use App\Business\UpgradeBusiness;
use App\Form\Type\Upgrade\UpgradeCodeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use UpdateCodeModel;

#[Route('/upgrade')]
class UpgradeController extends AbstractController
{
    #[Route('/database', name: 'app_upgrade_database')]
    public function database(
        DatabaseBusiness $databaseBusiness,
        Request $request,
        UpgradeBusiness $upgradeBusiness
    ): Response
    {
        if ($databaseBusiness->upToDate()) {
            return $this->redirectToRoute('app_login');
        }

        $canConnectToDatabase = $databaseBusiness->canConnectToDatabase();
        $mustCreateDatabase = !$canConnectToDatabase || !$databaseBusiness->doesDatabaseExist();

        if (!$mustCreateDatabase) {
            $mustMigrate = !empty($databaseBusiness->getMissingMigrations());
        } else {
            $mustMigrate = true;
        }

        $model = new UpdateCodeModel();
        $form = $this->createForm(UpgradeCodeType::class, $model)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $canConnectToDatabase) {
            if ($model->getCode() !== $upgradeBusiness->getUpdateCode()) {
                $form->get('code')->addError(new FormError('Le code ne correspond pas à la variable d\'environement "CODE_UPDATE"'));
            } else {
                $databaseBusiness->update();
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('Pages/Update/database.html.twig', [
            'must_create_database' => $mustCreateDatabase,
            'must_migrate' => $mustMigrate,
            'database_name' => $databaseBusiness->getDatabaseName(),
            'can_connect_to_database' => $canConnectToDatabase,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/missing-update-code', name: 'app_upgrade_missing_update_code')]
    public function missingUpdateCode(DatabaseBusiness $databaseBusiness): Response
    {
        if ($databaseBusiness->upToDate()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('Pages/Update/missing-update-code.html.twig');
    }
}