<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $email = null;

	#[ORM\Column(length: 255)]
	private ?string $nickname = null;

	#[ORM\Column(length: 255)]
	private ?string $password = null;

	/**
	 * @var Collection<int, Note>
	 */
	#[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'owner', orphanRemoval: true)]
	private Collection $createdPosts;

	public function __construct()
	{
		$this->createdPosts = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = $email;

		return $this;
	}

	public function getNickname(): ?string
	{
		return $this->nickname;
	}

	public function setNickname(string $nickname): static
	{
		$this->nickname = $nickname;

		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @return Collection<int, Note>
	 */
	public function getCreatedPosts(): Collection
	{
		return $this->createdPosts;
	}

	public function addCreatedPost(Note $createdPost): static
	{
		if (!$this->createdPosts->contains($createdPost)) {
			$this->createdPosts->add($createdPost);
			$createdPost->setOwner($this);
		}

		return $this;
	}

	public function removeCreatedPost(Note $createdPost): static
	{
		if ($this->createdPosts->removeElement($createdPost)) {
			// set the owning side to null (unless already changed)
			if ($createdPost->getOwner() === $this) {
				$createdPost->setOwner(null);
			}
		}

		return $this;
	}
}
