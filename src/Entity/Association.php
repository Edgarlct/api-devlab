<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AssosController;
use App\Controller\PostAssociationController;
use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: AssociationRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'path' => '/association',
            'controller' => PostAssociationController::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'media_object_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'get',
        'get_associations' => [
            'method' => 'GET',
            'path' => '/associations/me',
            'controller' => AssosController::class,
            'validation_groups' => ['Default', 'assso:me'],
        ]
    ],
    itemOperations: [
        'get',
        'put',
        'delete',
        'patch',
    ],
    normalizationContext: ['groups' => ['media_object:read']]
)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['media_object:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['media_object:read'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['media_object:read'])]
    private $logo;

    /**
     * @Vich\UploadableField(mapping="assos_image", fileNameProperty="logo")
     */
    #[Assert\NotNull(groups: ['media_object_create'])]
    private ?File $file = null;


    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'associations')]
    #[Groups(['media_object:read'])]
    private $users;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Event::class, orphanRemoval: true)]
    #[Groups(['media_object:read'])]
    private $events;

    #[ORM\OneToMany(mappedBy: 'associationOffice', targetEntity: User::class)]
    #[Groups(['media_object:read'])]
    private $officeMember;

    #[ORM\Column(type: 'text')]
    #[Groups(['media_object:read'])]
    private $description;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->officeMember = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAssociation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAssociation() === $this) {
                $event->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return Association
     */
    public function setFile(?File $file): Association
    {
        $this->file = $file;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getOfficeMember(): Collection
    {
        return $this->officeMember;
    }

    public function addOfficeMember(User $officeMember): self
    {
        if (!$this->officeMember->contains($officeMember)) {
            $this->officeMember[] = $officeMember;
            $officeMember->setAssociationOffice($this);
        }

        return $this;
    }

    public function removeOfficeMember(User $officeMember): self
    {
        if ($this->officeMember->removeElement($officeMember)) {
            // set the owning side to null (unless already changed)
            if ($officeMember->getAssociationOffice() === $this) {
                $officeMember->setAssociationOffice(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
