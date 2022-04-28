<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PostEventController;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'path' => '/events',
            'controller' => PostEventController::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'events_object_create'],
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
    ],
    itemOperations: ['get', 'put', 'delete', 'patch'],
)]class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['media_object:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['media_object:read'])]
    private $name;

    #[ORM\Column(type: 'float')]
    #[Groups(['media_object:read'])]
    private $price;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['media_object:read'])]
    private $date;

    #[ORM\Column(type: 'integer')]
    #[Groups(['media_object:read'])]
    private $placeNumber;

    #[ORM\Column(type: 'integer')]
    #[Groups(['media_object:read'])]
    private $placeRemaining;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
    private $tickets;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $association;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['media_object:read'])]
    private $path;

    /**
     * @Vich\UploadableField(mapping="events_image", fileNameProperty="path")
     */
    #[Assert\NotNull(groups: ['events_object_create'])]
    private ?File $file = null;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $namePlace;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->placeRemaining = $this->placeNumber;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPlaceNumber(): ?int
    {
        return $this->placeNumber;
    }

    public function setPlaceNumber(int $placeNumber): self
    {
        $this->placeNumber = $placeNumber;

        return $this;
    }

    public function getPlaceRemaining(): ?int
    {
        return $this->placeRemaining;
    }

    public function setPlaceRemaining(int $placeRemaining): self
    {
        $this->placeRemaining = $placeRemaining;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

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
     * @return Event
     */
    public function setFile(?File $file): Event
    {
        $this->file = $file;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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

    public function getNamePlace(): ?string
    {
        return $this->namePlace;
    }

    public function setNamePlace(string $namePlace): self
    {
        $this->namePlace = $namePlace;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
