<?php

namespace App\Controller;

use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PositionController extends AbstractController
{
    /**
     * @var UtilsService
     */
    private UtilsService $utilsService;

    /**
     * PositionController constructor.
     * @param UtilsService
     */
    public function __construct(UtilsService $utilsService)
    {
        $this->utilsService = $utilsService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $positions = $this->utilsService->getPositions();
        return $this->json($positions);
    }
}