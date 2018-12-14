<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimeAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GithubRepository")
 * @ORM\Table(name="github", indexes={@ORM\Index(name="search_idx", columns={"url"})})
 */
class Github
{
    use TimeAwareTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Subtitle cannot be longer than {{ limit }} characters"
     * )
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Url cannot be longer than {{ limit }} characters"
     * )
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Url cannot be longer than {{ limit }} characters"
     * )
     */
    private $ownerName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Owner avatar url cannot be longer than {{ limit }} characters"
     * )
     */
    private $ownerAvatarUrl;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Owner github url cannot be longer than {{ limit }} characters"
     * )
     */
    private $ownerGithubUrl;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     *
     */
    private $starsCount = 0;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Language cannot be longer than {{ limit }} characters"
     * )
     */
    private $mainLanguage;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $openIssueCount = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $closedIssuesCount = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastCommitDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $commitsCount = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $allCommitCount = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $readme;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $license;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): self
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getOwnerAvatarUrl(): ?string
    {
        return $this->ownerAvatarUrl;
    }

    public function setOwnerAvatarUrl(string $ownerAvatarUrl): self
    {
        $this->ownerAvatarUrl = $ownerAvatarUrl;

        return $this;
    }

    public function getOwnerGithubUrl(): ?string
    {
        return $this->ownerGithubUrl;
    }

    public function setOwnerGithubUrl(string $ownerGithubUrl): self
    {
        $this->ownerGithubUrl = $ownerGithubUrl;

        return $this;
    }

    public function getMainLanguage(): ?string
    {
        return $this->mainLanguage;
    }

    public function setMainLanguage(string $mainLanguage): self
    {
        $this->mainLanguage = $mainLanguage;

        return $this;
    }

    public function getOpenIssueCount(): ?int
    {
        return $this->openIssueCount;
    }

    public function setOpenIssueCount(int $openIssueCount): self
    {
        $this->openIssueCount = $openIssueCount;

        return $this;
    }

    public function getClosedIssuesCount(): ?int
    {
        return $this->closedIssuesCount;
    }

    public function setClosedIssuesCount(int $closedIssuesCount): self
    {
        $this->closedIssuesCount = $closedIssuesCount;

        return $this;
    }

    public function getLastCommitDate(): ?\DateTimeInterface
    {
        return $this->lastCommitDate;
    }

    public function setLastCommitDate(\DateTimeInterface $lastCommitDate): self
    {
        $this->lastCommitDate = $lastCommitDate;

        return $this;
    }

    public function getCommitsCount(): ?int
    {
        return $this->commitsCount;
    }

    public function setCommitsCount(int $commitsCount): self
    {
        $this->commitsCount = $commitsCount;

        return $this;
    }

    public function getAllCommitCount(): ?int
    {
        return $this->allCommitCount;
    }

    public function setAllCommitCount(int $allCommitCount): self
    {
        $this->allCommitCount = $allCommitCount;

        return $this;
    }

    public function getStarsCount(): ?int
    {
        return $this->starsCount;
    }

    public function setStarsCount(int $starsCount): self
    {
        $this->starsCount = $starsCount;

        return $this;
    }

    public function getReadme()
    {
        return $this->readme;
    }

    public function setReadme($readme): self
    {
        $this->readme = json_encode($readme);

        return $this;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license): self
    {
        $this->license = json_encode($license);

        return $this;
    }
}
