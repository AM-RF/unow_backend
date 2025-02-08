<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\EmailService;

class EmployeeController extends AbstractController
{
    /**
     * @var EmailService $emailService
     */
    private EmailService $emailService;

    /**
     * EmployeeController constructor.
     * @param EmailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @param EmployeeRepository $employeeRepository
     * @param SerializerInterface $serializer
     * @return JsonRespons
     */
    public function index(EmployeeRepository $employeeRepository, SerializerInterface $serializer): JsonResponse
    {
        $employees = $employeeRepository->findAll();
        $data = $serializer->serialize($employees, 'json', ['groups' => 'employee:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @param Request
     * @param EntityManagerInterface
     * @return JsonResponse
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setName($data['name']);
        $employee->setLastname($data['lastname']);
        $employee->setPosition($data['position']);
        $employee->setDob(new \DateTime($data['dob']));
        $employee->setEmail($data['email']);

        $entityManager->persist($employee);
        $entityManager->flush();

        $this->emailService->sendWelcomeEmployeeEmail($data['email'], $data['name'], $data['position']);

        return new JsonResponse(['status' => 'Employee created successfuly!'], Response::HTTP_CREATED);
    }

    /**
     * @param Employee
     * @param SerializerInterface
     * @return JsonResponse
     */
    public function show(Employee $employee, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($employee, 'json', ['groups' => 'employee:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @param Request
     * @param EntityManagerInterface
     * @param Employee
     * @return JsonResponse
     */
    public function update(Request $request, EntityManagerInterface $entityManager, Employee $employee): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee->setName($data['name']);
        $employee->setLastname($data['lastname']);
        $employee->setPosition($data['position']);
        $employee->setDob(new \DateTime($data['dob']));

        $entityManager->flush();

        return new JsonResponse(['status' => 'Employee updated successfuly!'], Response::HTTP_OK);
    }

    /**
     * @param Employee
     * @param EntityManagerInterface
     * @return JsonResponse
     */
    public function delete(Employee $employee, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($employee);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Employee deleted successfuly!'], Response::HTTP_OK);
    }
}


