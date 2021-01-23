<?php

namespace App\Controller;

use App\Form\FileUploadType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @Route("/admin/upload", name="upload")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function upload(Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(FileUploadType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if ($file) {
                $fileUploader->upload($file);
                $this->redirectToRoute('download');
            }
        }

        return $this->render('fileManagement/upload.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/files", name="files")
     */
    public function list()
    {
        $finder = new Finder();
        $finder->files()->in('../public/uploads');

        $contents = [];
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $creationTime = new \DateTime(date('d-m-Y H:i', $file->getMTime()));
            $fileName = $file->getFilename();
            $fileExtention = $file->getExtension();
            $contents[] = [$fileName, $fileExtention, $creationTime ,$absoluteFilePath];
        }




       return $this->render('fileManagement/download.html.twig', [
            'files' => $contents
        ]);
    }

    /**
     * @Route("/download{filePath}", name="download", requirements={"filePath"=".+"})
     */
    public function download(string $filePath)
    {
        $file = new File($filePath);
        return $this->file($file, $file->getFilename());
    }
}
