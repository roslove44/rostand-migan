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
    #[Route('/dashboard/info/post/', name: 'admin_info_post', methods: ['POST'])]
    public function addInfo(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $info = new Info();

        if ($this->isCsrfTokenValid('postInfo' . $this->getUser()->getUserIdentifier(), $data['_token'])) {
            try {
                $info->setClef(strtolower($this->purify_input($data['clef'])));
                $info->setValue($this->purify_input($data['value']));
                $em->persist($info);
                $em->flush();

                return $this->postReturn(
                    serializer: $serializer,
                    responseCode: Response::HTTP_OK,
                    message: "Info enregistré avec succès."
                );
            } catch (UniqueConstraintViolationException $e) {
                return $this->postReturn(
                    serializer: $serializer,
                    responseCode: Response::HTTP_CONFLICT,
                    error: "La clé renseignée est déjà enregistrée."
                );
            } catch (\Throwable $th) {
                return $this->postReturn(
                    serializer: $serializer,
                    responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                    error: $th->getMessage()
                );
            }
        } else {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                error: "Token Invalide"
            );
        }
    }

    #[Route('/dashboard/info/get/', name: 'admin_info_get', methods: ['POST'])]
    public function getInfo(Request $request, SerializerInterface $serializer, InfoRepository $infoRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('postInfo' . $this->getUser()->getUserIdentifier(), $data['_token'])) {
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
                    error: $th->getMessage()
                );
            }
        } else {
            return $this->postReturn(
                serializer: $serializer,
                responseCode: Response::HTTP_UNAUTHORIZED,
                error: "Token Invalide"
            );
        }
    }

    private function postReturn(SerializerInterface $serializer, int $responseCode, ?string $error = null,  ?string $message = null, null|array|Info $content = null): JsonResponse
    {
        $result = [
            "message" => $message,
            "content" => $content,
            "error" => $error,
        ];
        $result = $serializer->serialize($result, 'json');
        return $this->json($result, $responseCode);
    }

    private function purify_input($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
