<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\InspectorLocationEnum;
use App\Repository\InspectorRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: InspectorRepository::class)]
class Inspector
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    private string $name;

    #[ORM\Column(length: 255, nullable: false, enumType:InspectorLocationEnum::class)]
    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    private InspectorLocationEnum $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location->value;
    }

    public function setLocation(InspectorLocationEnum $location): self
    {
        $this->location = $location;

        return $this;
    }
}
