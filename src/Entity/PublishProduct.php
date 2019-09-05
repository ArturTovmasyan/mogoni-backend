<?php

namespace App\Entity;

use App\Entity\Traits\TimeAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublishProductRepository")
 * @ORM\Table(name="publish_product", indexes={@ORM\Index(name="search_repo_name_idx", columns={"repo_name"})})
 * @UniqueEntity(
 *     fields={"authorName", "repoName"},
 *     errorPath="authorName",
 *     message="Product by this names is already exist."
 * )
 */
class PublishProduct
{
    use TimeAwareTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"publish"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"publish"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Serializer\Groups({"publish"})
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=150, unique=false)
     */
    private $authorName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=150, unique=false)
     */
    private $repoName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Serializer\Groups({"publish"})
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Serializer\Groups({"publish"})
     */
    private $roadmap;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Serializer\Groups({"publish"})
     */
    private $screenshots;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Serializer\Groups({"publish"})
     */
    private $installations;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Serializer\Groups({"publish"})
     */
    private $examples;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"publish"})
     */
    private $contact;

    /**
     * @var Github
     *
     * @ORM\ManyToOne(targetEntity="Github", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_github", referencedColumnName="id", nullable=false)
     * })
     * @Serializer\Groups({"publish"})
     */
    private $github;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getInstallations()
    {
        return json_decode($this->installations, true);
    }

    public function setInstallations($installations): self
    {
        $this->installations = json_encode($installations);

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getScreenshots()
    {
        return json_decode($this->screenshots, true);
    }

    public function setScreenshots($screenshots): self
    {
        $this->screenshots = json_encode($screenshots);

        return $this;
    }

    public function getRoadmap(): ?string
    {
        return $this->roadmap;
    }

    public function setRoadmap(string $roadmap): self
    {
        $this->roadmap = $roadmap;

        return $this;
    }

    public function getRepoName(): ?string
    {
        return $this->repoName;
    }

    public function setRepoName(string $repoName): self
    {
        $this->repoName = $repoName;

        return $this;
    }

    public function getGithub(): ?Github
    {
        return $this->github;
    }

    public function setGithub(?Github $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getExamples()
    {
        return json_decode($this->examples, true);
    }

    public function setExamples($examples): self
    {
        $this->examples = json_encode($examples);

        return $this;
    }
}
