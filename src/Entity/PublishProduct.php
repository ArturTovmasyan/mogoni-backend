<?php

namespace App\Entity;

use App\Entity\Traits\TimeAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $authorName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $repoName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $roadmap;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $screenshot;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $installation;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $example;

    /**
     * @var Github
     *
     * @ORM\ManyToOne(targetEntity="Github", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_github", referencedColumnName="id", nullable=false)
     * })
     */
    private $github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact;

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

    public function getInstallation()
    {
        return json_decode($this->installation, true);
    }

    public function setInstallation($installation): self
    {
        $this->installation = json_encode($installation);

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

    public function getScreenshot()
    {
        return json_decode($this->screenshot, true);
    }

    public function setScreenshot($screenshot): self
    {
        $this->screenshot = json_encode($screenshot);

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

    public function getExample()
    {
        return json_decode($this->example, true);
    }

    public function setExample($example): self
    {
        $this->example = json_encode($example);

        return $this;
    }
}
