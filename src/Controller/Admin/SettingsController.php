<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\Type\FilterSettingsType;
use App\Form\Type\SettingsType;
use App\Repository\SettingsRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

final class SettingsController extends AbstractController
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function settings(Request $request): Response
    {
        $settings = $this->repository->findAllAsArray();

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->updateSettings($form->getNormData());
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/setting/header", name="admin_header_settings")
     */
    public function changeHeaderImage(Request $request): Response
    {
        $settings = $this->repository->findAllAsArray();

        $form = $this->createForm(FilterSettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->updateSettings($form->getNormData());
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_header_settings');
        }

        return $this->render('admin/settings/header_settings.html.twig', [
            'header_image' => $settings['header_image'],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/setting/upload_header_image", methods={"POST"}, name="admin_setting_upload_header_image")
     */
    public function uploadHeaderImage(Request $request, FileUploader $fileUploader): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $violations = $fileUploader->validate($uploadedFile);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];

            return new JsonResponse(['status' => 'error', 'message' => $violation->getMessage()]);
        }

        $fileName = $fileUploader->upload($uploadedFile);

        $this->repository->updateSetting('header_image', $fileName);

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/admin/setting/delete_header_image", methods={"POST"}, name="admin_setting_delete_header_image")
     */
    public function delete(Request $request, FileUploader $fileUploader): Response
    {
        $fileName = $this->repository->findOneBy(['setting_name' => 'header_image']);

        if ($fileName && $this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            // Delete file from folder
            $fileUploader->remove($fileName->getSettingValue());

            // Delete from db
            $this->repository->updateSetting('header_image', '');

            $this->addFlash('success', 'message.deleted');
        }

        return $this->redirectToRoute('admin_header_settings');
    }
}