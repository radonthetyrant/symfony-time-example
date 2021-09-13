<?php

namespace App\TimeTracking;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=TimeLog::class, mappedBy="project")
     */
    private $timeLogs;

    public function __construct()
    {
        $this->timeLogs = new ArrayCollection();
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

    /**
     * @return Collection|TimeLog[]
     */
    public function getTimeLogs(): Collection
    {
        return $this->timeLogs;
    }

    public function addTimeLog(TimeLog $timeLog): self
    {
        if (!$this->timeLogs->contains($timeLog)) {
            $this->timeLogs[] = $timeLog;
            $timeLog->setProject($this);
        }

        return $this;
    }

    public function removeTimeLog(TimeLog $timeLog): self
    {
        if ($this->timeLogs->removeElement($timeLog)) {
            // set the owning side to null (unless already changed)
            if ($timeLog->getProject() === $this) {
                $timeLog->setProject(null);
            }
        }

        return $this;
    }
}
