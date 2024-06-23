<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Service\AuthService;
use App\Service\NotesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NoteController extends AbstractController
{
	private NotesService $notesService;
	private Security $security;

	public function __construct(NotesService $notesService, Security $security)
	{
		$this->notesService = $notesService;
		$this->security = $security;
	}

	#[Route('/notes', name: 'create_note', methods: ['POST'])]
	#[IsGranted('ROLE_USER')]
	public function create(Request $request): Response
	{
		$data = json_decode($request->getContent(), true);
		$user = $this->security->getUser();
		$note = $this->notesService->create($data['title'], $data['description'], $user);
		return $this->json($note);
	}

	#[Route('/notes/{id}', name: 'delete_note', methods: ['DELETE'])]
	#[IsGranted('ROLE_USER')]
	public function delete(int $id): Response
	{
		$this->notesService->delete($id);
		return $this->json(null, Response::HTTP_NO_CONTENT);
	}

	#[Route('/notes', name: 'get_all_notes', methods: ['GET'])]
	public function getAll(): Response
	{
		$notes = $this->notesService->getAll();
		return $this->json($notes);
	}

	#[Route('/notes/{id}', name: 'get_note', methods: ['GET'])]
	public function getById(int $id): Response
	{
		$note = $this->notesService->getById($id);
		return $this->json($note);
	}

	#[Route('/notes/{id}', name: 'edit_note', methods: ['PUT'])]
	#[IsGranted('ROLE_USER')]
	public function edit(Request $request, int $id): Response
	{
		$data = json_decode($request->getContent(), true);
		$note = $this->notesService->edit($id, $data['title'], $data['description']);
		return $this->json($note);
	}
}
