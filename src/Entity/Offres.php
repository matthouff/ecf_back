<?php

namespace App\Entity;

use App\Repository\OffresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;



/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:offres"}},
 *     collectionOperations={
 *          "get_all"={
 *              "method":"GET",
 *              "path":"/offres"
 *          },
 *          "name_search"={
 *              "method":"GET",
 *              "path":"/offres/search"
 *          }
 *     },
 *     itemOperations={
 *          "get_offre"={
 *              "method":"GET",
 *              "path":"/offre/{id}"
 *          }
 *     }
 * )
 *
 * @ApiFilter(
 *     SearchFilter::class, properties={
 *          "title"="partial",
 *          "type_contrat"="exact"
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass=OffresRepository::class)
 */
class Offres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"read:offres"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"read:offres"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups ({"read:offres"})
     */
    private $type_contrat;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"read:offres"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"read:offres"})
     */
    private $profil_desc;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"read:offres"})
     */
    private $profil_comp;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"read:offres"})
     */
    private $poste_desc;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"read:offres"})
     */
    private $poste_mission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $website_offre;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups ({"read:offres"})
     */
    private $createdat;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="offres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"read:offres"})
     */
    private $societe;

    /**
     * @ORM\OneToMany(targetEntity=Candidat::class, mappedBy="offres", orphanRemoval=true)
     */
    private $candidat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->candidat = new ArrayCollection();
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

    public function getTypeContrat(): ?string
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(string $type_contrat): self
    {
        $this->type_contrat = $type_contrat;

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

    public function getProfilDesc(): ?string
    {
        return $this->profil_desc;
    }

    public function setProfilDesc(string $profil_desc): self
    {
        $this->profil_desc = $profil_desc;

        return $this;
    }

    public function getProfilComp(): ?string
    {
        return $this->profil_comp;
    }

    public function setProfilComp(?string $profil_comp): self
    {
        $this->profil_comp = $profil_comp;

        return $this;
    }

    public function getPosteDesc(): ?string
    {
        return $this->poste_desc;
    }

    public function setPosteDesc(string $poste_desc): self
    {
        $this->poste_desc = $poste_desc;

        return $this;
    }

    public function getPosteMission(): ?string
    {
        return $this->poste_mission;
    }

    public function setPosteMission(?string $poste_mission): self
    {
        $this->poste_mission = $poste_mission;

        return $this;
    }

    public function getWebsiteOffre(): ?string
    {
        return $this->website_offre;
    }

    public function setWebsiteOffre(?string $website_offre): self
    {
        $this->website_offre = $website_offre;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeImmutable
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeImmutable $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Collection<int, Candidat>
     */
    public function getCandidat(): Collection
    {
        return $this->candidat;
    }

    public function addCandidat(Candidat $candidat): self
    {
        if (!$this->candidat->contains($candidat)) {
            $this->candidat[] = $candidat;
            $candidat->setOffres($this);
        }

        return $this;
    }

    public function removeCandidat(Candidat $candidat): self
    {
        if ($this->candidat->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getOffres() === $this) {
                $candidat->setOffres(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
