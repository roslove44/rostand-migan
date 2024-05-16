<?php

namespace App\Controller\Admin;

use App\Entity\Info;
use App\Repository\InfoRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class InfoController extends AbstractController
{
    #[Route('/api/info/post/', name: 'admin_info_post', methods: ['POST'])]
    public function addInfo(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('info' . $this->getUser()->getUserIdentifier(), $data['_token'])) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                message: "Token Invalide",
                error: true
            );
        }
        $info = new Info();
        try {
            $info->setClef(strtolower($this->purify_input($data['clef'])));
            $info->setValue($this->purify_input($data['value']));
            $em->persist($info);
            $em->flush();

            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_OK,
                message: "Info enregistré avec succès.",
                content: $info
            );
        } catch (UniqueConstraintViolationException $e) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_CONFLICT,
                message: "La clé renseignée est déjà enregistrée.",
                error: true
            );
        } catch (\Throwable $th) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $th->getMessage(),
                error: true
            );
        }
    }

    #[Route('/api/info/get/', name: 'admin_info_get', methods: ['GET'])]
    public function getInfo(Request $request, SerializerInterface $serializer, InfoRepository $infoRepository): JsonResponse
    {

        try {
            $infos = $infoRepository->findAll();

            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_OK,
                message: "Ensemble des infos",
                content: $infos
            );
        } catch (\Throwable $th) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $th->getMessage(),
                error: true
            );
        }
    }

    #[Route('/api/info/delete/{clef}', name: 'admin_info_delete', methods: ['DELETE'])]
    public function deleteInfo($clef, InfoRepository $infoRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('info' . $this->getUser()->getUserIdentifier(), $data['_token'])) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                message: "Token Invalide",
                error: true
            );
        }

        $clef = $this->purify_input($clef);
        $info = $infoRepository->findOneBy(['clef' => $clef]);
        if (!$info) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                message: "Aucune clé ne correspond à votre demande",
                error: true
            );
        }
        try {
            $em->remove($info);
            $em->flush();

            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_OK,
                message: "Info supprimé avec succès."
            );
        } catch (\Throwable $th) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $th->getMessage(),
                error: true
            );
        }
    }

    #[Route('/api/info/update/{clef}', name: 'admin_info_update', methods: ['UPDATE'])]
    public function updateInfo($clef, InfoRepository $infoRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('info' . $this->getUser()->getUserIdentifier(), $data['_token'])) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                message: "Token Invalide",
                error: true
            );
        }

        $clef = $this->purify_input($clef);
        $info = $infoRepository->findOneBy(['clef' => $clef]);
        if (!$info) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_NOT_FOUND,
                message: "Aucune clé ne correspond à votre demande",
                error: true
            );
        }
        try {
            $info->setClef($this->purify_input($data['clef']));
            $info->setValue($this->purify_input($data['value']));
            $em->persist($info);
            $em->flush();

            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_OK,
                message: "Info modifié avec succès.",
                content: $info
            );
        } catch (\Throwable $th) {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $th->getMessage(),
                error: true
            );
        }
    }

    private function postReturn(SerializerInterface $serializer, int $responseCode, bool $error = false,  ?string $message = null, null|array|Info $content = null): JsonResponse
    {
        return $this->json(
            [
                "message" => $message,
                "content" => $content,
                "error" => $error,
                "status" => $responseCode
            ]
        );
    }

    private function purify_input(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
