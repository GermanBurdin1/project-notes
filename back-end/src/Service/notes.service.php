<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotesService
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function create(string $title, string $description, User $owner): Note
	{
		$note = new Note();
		$note->setTitle($title);
		$note->setDescription($description);
		$note->setCreatedAt(new \DateTimeImmutable());
		$note->setUpdatedAt(new \DateTime());
		$note->setOwner($owner);

		$this->entityManager->persist($note);
		$this->entityManager->flush();

		return $note;
	}

	public function delete(int $id): void
	{
		$note = $this->entityManager->getRepository(Note::class)->find($id);
		if ($note) {
			$this->entityManager->remove($note);
			$this->entityManager->flush();
		}
	}

	public function getAll(): array
	{
		return $this->entityManager->getRepository(Note::class)->findAll();
	}

	public function getById(int $id): ?Note
	{
		return $this->entityManager->getRepository(Note::class)->find($id);
	}

	public function edit(int $id, string $title, string $description): ?Note
	{
		$note = $this->entityManager->getRepository(Note::class)->find($id);
		if ($note) {
			$note->setTitle($title);
			$note->setDescription($description);
			$note->setUpdatedAt(new \DateTime());

			$this->entityManager->flush();
		}

		return $note;
	}
}
