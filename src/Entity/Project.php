<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $urlType = null;

    #[ORM\Column(length: 255)]
    private ?string $summary = null;

    /**
     * @var Collection<int, Stack>
     */
    #[ORM\ManyToMany(targetEntity: Stack::class, mappedBy: 'project')]
    private Collection $stacks;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mobilePicture = null;

    #[ORM\Column(length: 255)]
    private ?string $desktopPicture = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->stacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlType(): ?string
    {
        return $this->urlType;
    }

    public function setUrlType(string $urlType): static
    {
        $this->urlType = $urlType;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection<int, Stack>
     */
    public function getStacks(): Collection
    {
        return $this->stacks;
    }

    public function addStack(Stack $stack): static
    {
        if (!$this->stacks->contains($stack)) {
            $this->stacks->add($stack);
            $stack->addProject($this);
        }

        return $this;
    }

    public function removeStack(Stack $stack): static
    {
        if ($this->stacks->removeElement($stack)) {
            $stack->removeProject($this);
        }

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getMobilePicture(): ?string
    {
        return $this->mobilePicture;
    }

    public function setMobilePicture(?string $mobilePicture): static
    {
        $this->mobilePicture = $mobilePicture;

        return $this;
    }

    public function getDesktopPicture(): ?string
    {
        return $this->desktopPicture;
    }

    public function setDesktopPicture(string $desktopPicture): static
    {
        $this->desktopPicture = $desktopPicture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
