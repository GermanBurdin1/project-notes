<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthService
{
	private EntityManagerInterface $entityManager;
	private UserPasswordHasherInterface $passwordHasher;
	private JWTTokenManagerInterface $jwtManager;

	public function __construct(
		EntityManagerInterface $entityManager,
		UserPasswordHasherInterface $passwordHasher,
		JWTTokenManagerInterface $jwtManager
	) {
		$this->entityManager = $entityManager;
		$this->passwordHasher = $passwordHasher;
		$this->jwtManager = $jwtManager;
	}

	public function login(string $email, string $password): string
	{
		$user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

		if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
			throw new \Exception('Invalid credentials');
		}

		return $this->jwtManager->create($user);
	}

	public function register(string $email, string $nickname, string $password): User
	{
		$user = new User();
		$user->setEmail($email);
		$user->setNickname($nickname);
		$user->setPassword($this->passwordHasher->hashPassword($user, $password));

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return $user;
	}
}
